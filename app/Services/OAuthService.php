<?php
declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;
use RuntimeException;

final class OAuthService
{
    public static function configured(string $provider): bool
    {
        if ($provider === 'google') {
            return (string) config('oauth.google.client_id') !== ''
                && (string) config('oauth.google.client_secret') !== '';
        }
        if ($provider === 'apple') {
            return (string) config('oauth.apple.client_id') !== ''
                && (string) config('oauth.apple.team_id') !== ''
                && (string) config('oauth.apple.key_id') !== ''
                && ((string) config('oauth.apple.private_key') !== '' || (string) config('oauth.apple.private_key_path') !== '');
        }
        return false;
    }

    public static function redirectUri(string $provider): string
    {
        return absolute_url('/api/oauth/' . $provider . '/callback.php');
    }

    /**
     * @return array{state: string, nonce: string, verifier: string, challenge: string}
     */
    public static function begin(string $provider): array
    {
        if (!in_array($provider, ['google', 'apple'], true)) {
            throw new InvalidArgumentException('Unknown OAuth provider.');
        }
        $state = bin2hex(random_bytes(24));
        $nonce = bin2hex(random_bytes(16));
        $verifier = self::b64urlEncode(random_bytes(48));
        $challenge = self::b64urlEncode(hash('sha256', $verifier, true));
        $serviceId = (int) (ServiceCatalogService::selected()['id'] ?? 0);

        \App\Models\OAuthState::create($provider, $state, $nonce, $verifier, $serviceId);

        return ['state' => $state, 'nonce' => $nonce, 'verifier' => $verifier, 'challenge' => $challenge];
    }

    /**
     * OAuth state is stored server-side rather than in the session so that Apple's
     * cross-site form_post callback can be validated without the SameSite cookie.
     *
     * @return array{nonce: string, verifier: string, service_id: int}|null
     */
    public static function takeState(string $provider, string $state): ?array
    {
        return \App\Models\OAuthState::take($provider, $state);
    }

    public static function authorizeUrl(string $provider, array $pkce, string $state): string
    {
        if ($provider === 'google') {
            return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
                'client_id' => (string) config('oauth.google.client_id'),
                'redirect_uri' => self::redirectUri('google'),
                'response_type' => 'code',
                'scope' => 'openid email profile',
                'state' => $state,
                'nonce' => $pkce['nonce'],
                'code_challenge' => $pkce['challenge'],
                'code_challenge_method' => 'S256',
                'access_type' => 'online',
                'prompt' => 'select_account',
            ]);
        }

        return 'https://appleid.apple.com/auth/authorize?' . http_build_query([
            'client_id' => (string) config('oauth.apple.client_id'),
            'redirect_uri' => self::redirectUri('apple'),
            'response_type' => 'code',
            'scope' => 'name email',
            'state' => $state,
            'nonce' => hash('sha256', $pkce['nonce']),
            'response_mode' => 'form_post',
            'code_challenge' => $pkce['challenge'],
            'code_challenge_method' => 'S256',
        ]);
    }

    /**
     * @return array{ok: bool, id_token?: string, error?: string}
     */
    public static function exchangeCode(string $provider, string $code, string $verifier): array
    {
        if ($provider === 'google') {
            $response = self::http(
                'POST',
                'https://oauth2.googleapis.com/token',
                ['Content-Type: application/x-www-form-urlencoded'],
                http_build_query([
                    'code' => $code,
                    'client_id' => (string) config('oauth.google.client_id'),
                    'client_secret' => (string) config('oauth.google.client_secret'),
                    'redirect_uri' => self::redirectUri('google'),
                    'grant_type' => 'authorization_code',
                    'code_verifier' => $verifier,
                ])
            );
            if (!$response['ok'] || empty($response['json']['id_token'])) {
                return ['ok' => false, 'error' => $response['error'] !== '' ? $response['error'] : 'Google token exchange failed.'];
            }
            return ['ok' => true, 'id_token' => (string) $response['json']['id_token']];
        }

        $response = self::http(
            'POST',
            'https://appleid.apple.com/auth/token',
            ['Content-Type: application/x-www-form-urlencoded'],
            http_build_query([
                'client_id' => (string) config('oauth.apple.client_id'),
                'client_secret' => self::appleClientSecret(),
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => self::redirectUri('apple'),
                'code_verifier' => $verifier,
            ])
        );
        if (!$response['ok'] || empty($response['json']['id_token'])) {
            return ['ok' => false, 'error' => $response['error'] !== '' ? $response['error'] : 'Apple token exchange failed.'];
        }
        return ['ok' => true, 'id_token' => (string) $response['json']['id_token']];
    }

    public static function verifyIdToken(string $provider, string $idToken, string $nonce): array
    {
        if ($provider === 'google') {
            return self::verifyRs256(
                $idToken,
                'https://www.googleapis.com/oauth2/v3/certs',
                'https://accounts.google.com',
                (string) config('oauth.google.client_id'),
                $nonce
            );
        }

        return self::verifyRs256(
            $idToken,
            'https://appleid.apple.com/auth/keys',
            'https://appleid.apple.com',
            (string) config('oauth.apple.client_id'),
            hash('sha256', $nonce)
        );
    }

    private static function b64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function b64urlDecode(string $data): ?string
    {
        $data = strtr($data, '-_', '+/');
        $padding = strlen($data) % 4;
        if ($padding > 0) {
            $data .= str_repeat('=', 4 - $padding);
        }
        $decoded = base64_decode($data, true);
        return $decoded === false ? null : $decoded;
    }

    /**
     * @return array{ok: bool, status: int, body: string, json: array|null, error: string}
     */
    private static function http(string $method, string $url, array $headers = [], ?string $body = null): array
    {
        $ch = curl_init($url);
        if ($ch === false) {
            return ['ok' => false, 'status' => 0, 'body' => '', 'json' => null, 'error' => 'Could not initialise HTTP client.'];
        }
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => array_merge(['Accept: application/json'], $headers),
        ]);
        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body ?? '');
        }
        $response = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['ok' => false, 'status' => $status, 'body' => '', 'json' => null, 'error' => $error ?: 'Network error.'];
        }
        $json = json_decode((string) $response, true);
        return [
            'ok' => $status >= 200 && $status < 300,
            'status' => $status,
            'body' => (string) $response,
            'json' => is_array($json) ? $json : null,
            'error' => $status >= 200 && $status < 300 ? '' : 'Provider returned HTTP ' . $status,
        ];
    }

    private static function applePrivateKey(): ?string
    {
        $inline = (string) config('oauth.apple.private_key', '');
        if ($inline !== '') {
            return str_replace('\\n', "\n", $inline);
        }
        $path = (string) config('oauth.apple.private_key_path', '');
        if ($path !== '' && is_file($path)) {
            $key = file_get_contents($path);
            return $key === false ? null : $key;
        }
        return null;
    }

    private static function appleClientSecret(): string
    {
        static $cached = '';
        static $cachedAt = 0;
        if ($cached !== '' && time() - $cachedAt < 240) {
            return $cached;
        }

        $key = self::applePrivateKey();
        if ($key === null) {
            throw new RuntimeException('Apple private key is not configured.');
        }
        $now = time();
        $header = self::b64urlEncode(json_encode(['alg' => 'ES256', 'kid' => (string) config('oauth.apple.key_id')], JSON_UNESCAPED_SLASHES));
        $payload = self::b64urlEncode(json_encode([
            'iss' => (string) config('oauth.apple.team_id'),
            'iat' => $now,
            'exp' => $now + 300,
            'aud' => 'https://appleid.apple.com',
            'sub' => (string) config('oauth.apple.client_id'),
        ], JSON_UNESCAPED_SLASHES));

        $signingInput = $header . '.' . $payload;
        $signature = '';
        if (!openssl_sign($signingInput, $signature, $key, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Could not sign the Apple client secret.');
        }
        $raw = self::derToRawEcdsa($signature);
        if ($raw === null) {
            throw new RuntimeException('Could not convert the Apple signature.');
        }
        $cached = $signingInput . '.' . self::b64urlEncode($raw);
        $cachedAt = $now;
        return $cached;
    }

    private static function derToRawEcdsa(string $der): ?string
    {
        $offset = 0;
        $length = strlen($der);
        if ($length < 8 || ord($der[$offset]) !== 0x30) {
            return null;
        }
        $offset++;
        [$seqLen, $offset] = self::derReadLength($der, $offset);
        if ($seqLen === null || $offset + $seqLen > $length) {
            return null;
        }
        if (ord($der[$offset]) !== 0x02) {
            return null;
        }
        $offset++;
        [$rLen, $offset] = self::derReadLength($der, $offset);
        if ($rLen === null || $offset + $rLen > $length) {
            return null;
        }
        $r = substr($der, $offset, $rLen);
        $offset += $rLen;
        if (ord($der[$offset] ?? "\0") !== 0x02) {
            return null;
        }
        $offset++;
        [$sLen, $offset] = self::derReadLength($der, $offset);
        if ($sLen === null || $offset + $sLen > $length) {
            return null;
        }
        $s = substr($der, $offset, $sLen);

        $r = ltrim($r, "\0");
        $s = ltrim($s, "\0");
        if (strlen($r) > 32 || strlen($s) > 32) {
            return null;
        }
        return str_pad($r, 32, "\0", STR_PAD_LEFT) . str_pad($s, 32, "\0", STR_PAD_LEFT);
    }

    /**
     * @return array{0: int|null, 1: int}
     */
    private static function derReadLength(string $der, int $offset): array
    {
        if (!isset($der[$offset])) {
            return [null, $offset];
        }
        $first = ord($der[$offset]);
        $offset++;
        if ($first < 0x80) {
            return [$first, $offset];
        }
        $bytes = $first & 0x7f;
        if ($bytes === 0 || $bytes > 4 || $offset + $bytes > strlen($der)) {
            return [null, $offset];
        }
        $length = 0;
        for ($i = 0; $i < $bytes; $i++) {
            $length = ($length << 8) | ord($der[$offset + $i]);
        }
        return [$length, $offset + $bytes];
    }

    /**
     * @return array{header: array, payload: array, signature: string, signing_input: string}|null
     */
    private static function jwtDecode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        $headerJson = self::b64urlDecode($parts[0]);
        $payloadJson = self::b64urlDecode($parts[1]);
        $signature = self::b64urlDecode($parts[2]);
        if ($headerJson === null || $payloadJson === null || $signature === null) {
            return null;
        }
        $header = json_decode($headerJson, true);
        $payload = json_decode($payloadJson, true);
        if (!is_array($header) || !is_array($payload)) {
            return null;
        }
        return [
            'header' => $header,
            'payload' => $payload,
            'signature' => $signature,
            'signing_input' => $parts[0] . '.' . $parts[1],
        ];
    }

    private static function jwksFetch(string $url): ?array
    {
        $cacheFile = sys_get_temp_dir() . '/sa-jwks-' . hash('sha256', $url) . '.json';
        if (is_file($cacheFile) && time() - (int) filemtime($cacheFile) < 3600) {
            $cached = json_decode((string) file_get_contents($cacheFile), true);
            if (is_array($cached) && isset($cached['keys'])) {
                return $cached;
            }
        }
        $response = self::http('GET', $url);
        if (!$response['ok'] || !is_array($response['json']) || empty($response['json']['keys'])) {
            return null;
        }
        @file_put_contents($cacheFile, $response['body']);
        return $response['json'];
    }

    private static function jwksPublicKey(array $keys, string $kid): ?string
    {
        foreach ($keys as $key) {
            if (!is_array($key) || (string) ($key['kid'] ?? '') !== $kid) {
                continue;
            }
            if (!empty($key['x5c'][0])) {
                $pem = "-----BEGIN CERTIFICATE-----\n"
                    . chunk_split((string) $key['x5c'][0], 64, "\n")
                    . "-----END CERTIFICATE-----\n";
                if (openssl_pkey_get_public($pem) !== false) {
                    return $pem;
                }
            }
            if (($key['kty'] ?? '') === 'RSA' && !empty($key['n']) && !empty($key['e'])) {
                $modulus = self::b64urlDecode((string) $key['n']);
                $exponent = self::b64urlDecode((string) $key['e']);
                if ($modulus === null || $exponent === null) {
                    continue;
                }
                $rsaPublicKey = self::derEncodeSequence(
                    self::derEncodeInteger($modulus) . self::derEncodeInteger($exponent)
                );
                $pem = "-----BEGIN PUBLIC KEY-----\n"
                    . chunk_split(base64_encode(self::derEncodeSequence(
                        self::derEncodeSequence(self::derEncodeOid('1.2.840.113549.1.1.1') . "\x05\x00") . "\x03" . self::derEncodeLength(strlen($rsaPublicKey) + 1) . "\x00" . $rsaPublicKey
                    )), 64, "\n")
                    . "-----END PUBLIC KEY-----\n";
                if (openssl_pkey_get_public($pem) !== false) {
                    return $pem;
                }
            }
        }
        return null;
    }

    private static function derEncodeLength(int $length): string
    {
        if ($length < 0x80) {
            return chr($length);
        }
        $bytes = ltrim(pack('N', $length), "\0");
        return chr(0x80 | strlen($bytes)) . $bytes;
    }

    private static function derEncodeSequence(string $content): string
    {
        return "\x30" . self::derEncodeLength(strlen($content)) . $content;
    }

    private static function derEncodeInteger(string $value): string
    {
        $value = ltrim($value, "\0");
        if ($value === '' || ord($value[0]) > 0x7f) {
            $value = "\0" . $value;
        }
        return "\x02" . self::derEncodeLength(strlen($value)) . $value;
    }

    private static function derEncodeOid(string $oid): string
    {
        $parts = array_map('intval', explode('.', $oid));
        $first = array_shift($parts);
        $second = array_shift($parts);
        $bytes = chr($first * 40 + $second);
        foreach ($parts as $part) {
            $stack = [chr($part & 0x7f)];
            $part >>= 7;
            while ($part > 0) {
                array_unshift($stack, chr(0x80 | ($part & 0x7f)));
                $part >>= 7;
            }
            $bytes .= implode('', $stack);
        }
        return "\x06" . self::derEncodeLength(strlen($bytes)) . $bytes;
    }

    /**
     * Verifies an RS256 identity token against the provider JWKS.
     *
     * @return array{ok: bool, claims?: array, error?: string}
     */
    private static function verifyRs256(string $idToken, string $jwksUrl, string $issuer, string $audience, string $expectedNonce = ''): array
    {
        $jwt = self::jwtDecode($idToken);
        if (!$jwt) {
            return ['ok' => false, 'error' => 'Malformed identity token.'];
        }
        if (($jwt['header']['alg'] ?? '') !== 'RS256') {
            return ['ok' => false, 'error' => 'Unsupported identity token algorithm.'];
        }
        $kid = (string) ($jwt['header']['kid'] ?? '');
        if ($kid === '') {
            return ['ok' => false, 'error' => 'Identity token has no key identifier.'];
        }
        $jwks = self::jwksFetch($jwksUrl);
        if (!$jwks) {
            return ['ok' => false, 'error' => 'Could not load provider signing keys.'];
        }
        $publicKey = self::jwksPublicKey($jwks['keys'], $kid);
        if ($publicKey === null) {
            return ['ok' => false, 'error' => 'Provider signing key not found.'];
        }
        $verified = openssl_verify($jwt['signing_input'], $jwt['signature'], $publicKey, OPENSSL_ALGO_SHA256);
        if ($verified !== 1) {
            return ['ok' => false, 'error' => 'Identity token signature verification failed.'];
        }

        $claims = $jwt['payload'];
        $issuerMatches = $issuer === 'https://appleid.apple.com'
            ? ($claims['iss'] ?? '') === $issuer
            : in_array($claims['iss'] ?? '', [$issuer, 'accounts.google.com'], true);
        if (!$issuerMatches) {
            return ['ok' => false, 'error' => 'Identity token issuer mismatch.'];
        }
        $aud = $claims['aud'] ?? '';
        if (is_array($aud) ? !in_array($audience, $aud, true) : $aud !== $audience) {
            return ['ok' => false, 'error' => 'Identity token audience mismatch.'];
        }
        if ((int) ($claims['exp'] ?? 0) < time() - 60) {
            return ['ok' => false, 'error' => 'Identity token has expired.'];
        }
        if ($expectedNonce !== '' && !hash_equals($expectedNonce, (string) ($claims['nonce'] ?? ''))) {
            return ['ok' => false, 'error' => 'Identity token nonce mismatch.'];
        }
        return ['ok' => true, 'claims' => $claims];
    }
}
