<?php
declare(strict_types=1);

namespace App\Database;

use App\Core\Config;
use PDO;

/**
 * Single, lazily created PDO connection shared by the whole request.
 */
final class Database
{
    private static ?self $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            (string) Config::get('db.host', 'localhost'),
            (int) Config::get('db.port', 3306),
            (string) Config::get('db.name', 'saliheen_portal'),
            (string) Config::get('db.charset', 'utf8mb4')
        );

        $this->pdo = new PDO(
            $dsn,
            (string) Config::get('db.user', ''),
            (string) Config::get('db.pass', ''),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]
        );
    }

    public static function connection(): PDO
    {
        return self::instance()->pdo;
    }

    private static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \RuntimeException('The database connection cannot be unserialised.');
    }
}
