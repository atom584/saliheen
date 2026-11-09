<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Throwable;

final class AvatarService
{
    private const MAX_BYTES = 2 * 1024 * 1024;
    private const MAX_SOURCE_DIMENSION = 4096;
    private const OUTPUT_SIZE = 400;
    private const RELATIVE_DIR = 'uploads/avatars';

    /**
     * Validates the uploaded image, re-encodes it as a square JPEG and stores
     * the new path on the user. The previous photo is removed afterwards.
     *
     * @param array<string, mixed> $file
     * @return array{ok: bool, message?: string, error?: string, url?: string|null}
     */
    public static function update(int $userId, array $file, ?string $currentPath): array
    {
        $error = self::validateUpload($file);
        if ($error !== null) {
            return ['ok' => false, 'error' => $error];
        }

        $tmp = (string) $file['tmp_name'];
        $info = @getimagesize($tmp);
        if ($info === false) {
            return ['ok' => false, 'error' => 'That file is not a readable image.'];
        }

        $width = (int) $info[0];
        $height = (int) $info[1];
        $type = (int) $info[2];
        $allowed = [
            IMAGETYPE_JPEG => 'image/jpeg',
            IMAGETYPE_PNG => 'image/png',
            IMAGETYPE_WEBP => 'image/webp',
        ];
        if (!isset($allowed[$type])) {
            return ['ok' => false, 'error' => 'Upload a JPG, PNG or WebP photo.'];
        }
        if ($width < 32 || $height < 32) {
            return ['ok' => false, 'error' => 'The photo is too small. Use an image at least 32 × 32 pixels.'];
        }
        if ($width > self::MAX_SOURCE_DIMENSION || $height > self::MAX_SOURCE_DIMENSION) {
            return ['ok' => false, 'error' => 'The photo dimensions are too large. Use an image up to 4096 × 4096 pixels.'];
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($tmp);
        if (!in_array($mime, $allowed, true)) {
            return ['ok' => false, 'error' => 'Upload a JPG, PNG or WebP photo.'];
        }
        if (!function_exists('imagecreatetruecolor')) {
            return ['ok' => false, 'error' => 'Image processing is unavailable on this server.'];
        }

        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($tmp),
            IMAGETYPE_PNG => @imagecreatefrompng($tmp),
            IMAGETYPE_WEBP => @imagecreatefromwebp($tmp),
            default => false,
        };
        if (!$source) {
            return ['ok' => false, 'error' => 'The photo could not be processed. Try a different image.'];
        }

        $canvas = imagecreatetruecolor(self::OUTPUT_SIZE, self::OUTPUT_SIZE);
        if ($canvas === false) {
            imagedestroy($source);
            return ['ok' => false, 'error' => 'The photo could not be processed. Please try again.'];
        }
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        $side = min($width, $height);
        $srcX = (int) floor(($width - $side) / 2);
        $srcY = (int) floor(($height - $side) / 2);
        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            $srcX,
            $srcY,
            self::OUTPUT_SIZE,
            self::OUTPUT_SIZE,
            $side,
            $side
        );
        imagedestroy($source);

        $directory = APP_ROOT . '/' . self::RELATIVE_DIR;
        if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
            imagedestroy($canvas);
            return ['ok' => false, 'error' => 'The photo storage folder is not available.'];
        }

        $name = 'u' . $userId . '-' . bin2hex(random_bytes(12)) . '.jpg';
        $absolute = $directory . '/' . $name;
        $saved = imagejpeg($canvas, $absolute, 88);
        imagedestroy($canvas);
        if (!$saved) {
            return ['ok' => false, 'error' => 'The photo could not be saved. Please try again.'];
        }
        @chmod($absolute, 0644);

        $relative = self::RELATIVE_DIR . '/' . $name;
        try {
            User::updateAvatar($userId, $relative);
        } catch (Throwable $exception) {
            @unlink($absolute);
            throw $exception;
        }

        self::deleteFile($currentPath);
        AuditLog::record('account.avatar_updated', 'user', $userId, ['path' => $relative]);

        return [
            'ok' => true,
            'message' => 'Profile photo updated.',
            'url' => url('/' . $relative),
        ];
    }

    /**
     * @return array{ok: bool, message?: string, url?: null}
     */
    public static function remove(int $userId, ?string $currentPath): array
    {
        self::deleteFile($currentPath);
        User::updateAvatar($userId, null);
        AuditLog::record('account.avatar_removed', 'user', $userId);

        return ['ok' => true, 'message' => 'Profile photo removed.', 'url' => null];
    }

    /**
     * @param array<string, mixed> $file
     */
    private static function validateUpload(array $file): ?string
    {
        $error = isset($file['error']) && !is_array($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
        if ($error !== UPLOAD_ERR_OK) {
            return match ($error) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The image is too large. Choose a photo up to 2 MB.',
                UPLOAD_ERR_PARTIAL => 'The upload was interrupted. Please try again.',
                UPLOAD_ERR_NO_FILE => 'Choose a photo to upload.',
                default => 'The photo could not be uploaded. Please try again.',
            };
        }

        $tmp = isset($file['tmp_name']) && is_string($file['tmp_name']) ? $file['tmp_name'] : '';
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return 'The photo could not be uploaded. Please try again.';
        }

        $size = isset($file['size']) ? (int) $file['size'] : 0;
        if ($size <= 0) {
            return 'The selected file is empty.';
        }
        if ($size > self::MAX_BYTES) {
            return 'The image is too large. Choose a photo up to 2 MB.';
        }

        return null;
    }

    private static function deleteFile(?string $path): void
    {
        $path = is_string($path) ? $path : '';
        if (!preg_match('#^' . preg_quote(self::RELATIVE_DIR, '#') . '/[A-Za-z0-9._-]+$#', $path)) {
            return;
        }
        $directory = realpath(APP_ROOT . '/' . self::RELATIVE_DIR);
        $file = realpath(APP_ROOT . '/' . $path);
        if ($directory !== false && $file !== false && str_starts_with($file, $directory . DIRECTORY_SEPARATOR) && is_file($file)) {
            @unlink($file);
        }
    }
}
