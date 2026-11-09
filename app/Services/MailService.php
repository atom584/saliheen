<?php
declare(strict_types=1);

namespace App\Services;

use Throwable;

final class MailService
{
    /**
     * @return array{ok: bool, error?: string}
     */
    public static function send(string $to, string $toName, string $subject, string $html, string $text = ''): array
    {
        $to = trim(str_replace(["\r", "\n"], '', $to));
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'Invalid recipient address.'];
        }
        $subject = trim(str_replace(["\r", "\n"], '', $subject));
        $toName = trim(str_replace(["\r", "\n"], '', $toName));
        $fromEmail = (string) config('mail.from_email', 'no-reply@nsaliheen.com');
        $fromName = (string) config('mail.from_name', 'Saliheen & Associates');

        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            error_log('Mail configuration error: invalid from_email.');
            return ['ok' => false, 'error' => 'Mail sender is not configured.'];
        }

        $text = $text !== '' ? $text : trim(html_entity_decode(strip_tags(str_replace(['<br>', '</p>'], "\n", $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $boundary = 'sa_' . bin2hex(random_bytes(12));

        $headers = [
            'Date: ' . date('r'),
            'From: ' . self::encodeHeader($fromName) . ' <' . $fromEmail . '>',
            'To: ' . ($toName !== '' ? self::encodeHeader($toName) . ' ' : '') . '<' . $to . '>',
            'Subject: ' . self::encodeHeader($subject),
            'Message-ID: <' . bin2hex(random_bytes(10)) . '@' . preg_replace('/^.*@/', '', $fromEmail) . '>',
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'X-Mailer: Saliheen Portal',
        ];

        $body = '--' . $boundary . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n\r\n"
            . chunk_split(base64_encode($text), 76, "\r\n")
            . '--' . $boundary . "\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n\r\n"
            . chunk_split(base64_encode($html), 76, "\r\n")
            . '--' . $boundary . "--\r\n";

        $transport = (string) config('mail.transport', 'smtp');
        if ($transport === 'log') {
            $line = '[' . date('c') . "] To: {$to} | Subject: {$subject}\r\n{$text}\r\n\r\n";
            @file_put_contents(sys_get_temp_dir() . '/saliheen-mail.log', $line, FILE_APPEND);
            return ['ok' => true];
        }

        $result = self::smtpSend($to, implode("\r\n", $headers), $body);
        if (!$result['ok']) {
            error_log('Mail send failed for ' . $to . ': ' . ($result['error'] ?? 'unknown error'));
        }
        return $result;
    }

    /**
     * @return array{ok: bool, error?: string}
     */
    private static function smtpSend(string $recipient, string $headers, string $body): array
    {
        $host = (string) config('mail.host', 'localhost');
        $port = (int) config('mail.port', 25);
        $encryption = strtolower((string) config('mail.encryption', ''));
        $username = (string) config('mail.username', '');
        $password = (string) config('mail.password', '');
        $fromEmail = (string) config('mail.from_email', 'no-reply@nsaliheen.com');
        $timeout = 20;

        $remote = ($encryption === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
                'peer_name' => $host,
                'SNI_enabled' => true,
            ],
        ]);

        $socket = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            return ['ok' => false, 'error' => "Connection failed: {$errstr} ({$errno})"];
        }
        stream_set_timeout($socket, $timeout);

        $read = static function () use ($socket): array {
            $data = '';
            while (($line = fgets($socket, 515)) !== false) {
                $data .= $line;
                if (strlen($line) < 4 || $line[3] !== '-') {
                    break;
                }
            }
            return [$data, (int) substr($data, 0, 3)];
        };

        $command = static function (string $cmd, array $expected) use ($socket, $read): array {
            fwrite($socket, $cmd . "\r\n");
            [$response, $code] = $read();
            return [in_array($code, $expected, true), $code, $response];
        };

        try {
            [$response, $code] = $read();
            if ($code !== 220) {
                return ['ok' => false, 'error' => "Greeting failed ({$code}): {$response}"];
            }

            $hostname = $_SERVER['SERVER_NAME'] ?? 'localhost';
            [$ok, $code, $response] = $command('EHLO ' . $hostname, [250]);
            if (!$ok) {
                [$ok, $code, $response] = $command('HELO ' . $hostname, [250]);
                if (!$ok) {
                    return ['ok' => false, 'error' => "HELO failed ({$code}): {$response}"];
                }
            }

            if ($encryption === 'tls') {
                [$ok, $code, $response] = $command('STARTTLS', [220]);
                if (!$ok) {
                    return ['ok' => false, 'error' => "STARTTLS failed ({$code}): {$response}"];
                }
                if (!@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    return ['ok' => false, 'error' => 'Could not negotiate TLS.'];
                }
                [$ok, $code, $response] = $command('EHLO ' . $hostname, [250]);
                if (!$ok) {
                    return ['ok' => false, 'error' => "EHLO after TLS failed ({$code}): {$response}"];
                }
            }

            if ($username !== '') {
                [$ok, $code, $response] = $command('AUTH LOGIN', [334]);
                if (!$ok) {
                    return ['ok' => false, 'error' => "AUTH LOGIN failed ({$code}): {$response}"];
                }
                [$ok, $code, $response] = $command(base64_encode($username), [334]);
                if (!$ok) {
                    return ['ok' => false, 'error' => "SMTP username rejected ({$code})."];
                }
                [$ok, $code, $response] = $command(base64_encode($password), [235]);
                if (!$ok) {
                    return ['ok' => false, 'error' => "SMTP authentication failed ({$code})."];
                }
            }

            [$ok, $code, $response] = $command('MAIL FROM:<' . $fromEmail . '>', [250]);
            if (!$ok) {
                return ['ok' => false, 'error' => "MAIL FROM failed ({$code}): {$response}"];
            }
            [$ok, $code, $response] = $command('RCPT TO:<' . $recipient . '>', [250, 251]);
            if (!$ok) {
                return ['ok' => false, 'error' => "RCPT TO failed ({$code}): {$response}"];
            }
            [$ok, $code, $response] = $command('DATA', [354]);
            if (!$ok) {
                return ['ok' => false, 'error' => "DATA failed ({$code}): {$response}"];
            }

            $payload = str_replace("\r\n.", "\r\n..", $headers . "\r\n\r\n" . $body);
            fwrite($socket, $payload . "\r\n.\r\n");
            [$response, $code] = $read();
            if ($code !== 250) {
                return ['ok' => false, 'error' => "Message rejected ({$code}): {$response}"];
            }

            $command('QUIT', [221]);
            fclose($socket);
            return ['ok' => true];
        } catch (Throwable $error) {
            @fclose($socket);
            return ['ok' => false, 'error' => $error->getMessage()];
        }
    }

    private static function htmlLayout(string $title, string $bodyHtml): string
    {
        return '<!doctype html><html lang="en"><body style="margin:0;padding:0;background:#f7f5f3;font-family:Inter,Segoe UI,Arial,sans-serif;color:#292326">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f7f5f3;padding:28px 12px">'
            . '<tr><td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border:1px solid #e8e2db">'
            . '<tr><td style="background:#702632;padding:24px 30px">'
            . '<div style="font-family:Georgia,Times New Roman,serif;font-size:22px;color:#ffffff;letter-spacing:.02em">Saliheen &amp; Associates</div>'
            . '<div style="font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#e8d5a8;margin-top:6px">Legal Consultancy</div>'
            . '</td></tr>'
            . '<tr><td style="padding:30px">'
            . '<h1 style="margin:0 0 16px;font-family:Georgia,Times New Roman,serif;font-weight:400;font-size:24px;color:#501b25">' . e($title) . '</h1>'
            . $bodyHtml
            . '</td></tr>'
            . '<tr><td style="padding:20px 30px;border-top:1px solid #e8e2db;font-size:12px;line-height:1.6;color:#777176">'
            . 'Saliheen &amp; Associates · B176, Lane 23, DOHS, Mohakhali, Dhaka, Bangladesh<br>'
            . 'This message relates to your client portal account. Do not forward it.'
            . '</td></tr>'
            . '</table></td></tr></table></body></html>';
    }

    private static function button(string $label, string $url): string
    {
        return '<p style="margin:24px 0"><a href="' . e($url) . '" style="display:inline-block;background:#702632;color:#ffffff;text-decoration:none;padding:13px 22px;font-size:13px;font-weight:700;letter-spacing:.04em">' . e($label) . '</a></p>'
            . '<p style="margin:0 0 8px;font-size:12px;color:#777176">If the button does not open, copy this address into your browser:</p>'
            . '<p style="margin:0;font-size:12px;word-break:break-all"><a href="' . e($url) . '" style="color:#702632">' . e($url) . '</a></p>';
    }

    private static function encodeHeader(string $value): string
    {
        $value = trim(str_replace(["\r", "\n"], '', $value));
        return preg_match('/[^\x20-\x7E]/', $value) ? '=?UTF-8?B?' . base64_encode($value) . '?=' : $value;
    }

    public static function verification(array $user, string $token): array
    {
        $link = absolute_url('/verify?token=' . urlencode($token));
        $name = (string) ($user['full_name'] ?? 'there');
        $html = self::htmlLayout(
            'Verify your business email',
            '<p style="margin:0 0 14px">Dear ' . e($name) . ',</p>'
            . '<p style="margin:0 0 14px">Thank you for registering with the Saliheen &amp; Associates client portal. Confirm your business email address to activate your account and selected service.</p>'
            . self::button('Verify my email', $link)
            . '<p style="margin:18px 0 0;font-size:12px;color:#777176">This link expires in 24 hours. If you did not create this account, you can ignore this email.</p>'
        );
        return self::send((string) $user['email'], $name, 'Verify your Saliheen client portal email', $html);
    }

    public static function passwordReset(array $user, string $token): array
    {
        $link = absolute_url('/reset-password?token=' . urlencode($token));
        $name = (string) ($user['full_name'] ?? 'there');
        $html = self::htmlLayout(
            'Reset your password',
            '<p style="margin:0 0 14px">Dear ' . e($name) . ',</p>'
            . '<p style="margin:0 0 14px">A password reset was requested for your Saliheen &amp; Associates client portal account. Choose a new password using the button below.</p>'
            . self::button('Choose a new password', $link)
            . '<p style="margin:18px 0 0;font-size:12px;color:#777176">This link expires in 60 minutes. If you did not request a reset, no action is required and your current password remains unchanged.</p>'
        );
        return self::send((string) $user['email'], $name, 'Reset your Saliheen client portal password', $html);
    }

    public static function welcome(array $user, ?array $service = null): void
    {
        $name = (string) ($user['full_name'] ?? 'there');
        $serviceLine = $service
            ? '<p style="margin:0 0 14px">Your selected service, <strong>' . e((string) $service['name']) . '</strong>, is now available in your client dashboard.</p>'
            : '';
        $html = self::htmlLayout(
            'Your client portal is ready',
            '<p style="margin:0 0 14px">Dear ' . e($name) . ',</p>'
            . '<p style="margin:0 0 14px">Your business email has been verified and your Saliheen &amp; Associates client portal account is active.</p>'
            . $serviceLine
            . self::button('Open my dashboard', absolute_url('/client/dashboard'))
            . '<p style="margin:18px 0 0;font-size:12px;color:#777176">For your security, never share your password. Contact info@nsaliheen.com if you notice unusual account activity.</p>'
        );
        self::send((string) $user['email'], $name, 'Your Saliheen client portal is active', $html);
    }

    public static function notifyAdminServiceRequest(array $user, array $service, int $requestId): void
    {
        $adminEmail = (string) config('admin_email', 'admin@nsaliheen.com');
        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }
        $company = (string) ($user['company_name'] ?? 'Company');
        $link = absolute_url('/admin/services');
        $html = self::htmlLayout(
            'New service request',
            '<p style="margin:0 0 14px">A client has requested an additional legal service.</p>'
            . '<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;font-size:13px;border-collapse:collapse">'
            . '<tr><td style="padding:8px 0;border-bottom:1px solid #eee;color:#777176">Request ID</td><td style="padding:8px 0;border-bottom:1px solid #eee"><strong>SA-SR-' . str_pad((string) $requestId, 4, '0', STR_PAD_LEFT) . '</strong></td></tr>'
            . '<tr><td style="padding:8px 0;border-bottom:1px solid #eee;color:#777176">Client</td><td style="padding:8px 0;border-bottom:1px solid #eee">' . e((string) $user['full_name']) . '</td></tr>'
            . '<tr><td style="padding:8px 0;border-bottom:1px solid #eee;color:#777176">Company</td><td style="padding:8px 0;border-bottom:1px solid #eee">' . e($company) . '</td></tr>'
            . '<tr><td style="padding:8px 0;color:#777176">Service</td><td style="padding:8px 0">' . e((string) $service['name']) . '</td></tr>'
            . '</table>'
            . self::button('Review in Service Management', $link)
        );
        self::send($adminEmail, 'Saliheen Service Management', 'New service request — ' . $service['name'], $html);
    }
}
