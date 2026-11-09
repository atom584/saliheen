<?php
declare(strict_types=1);

// Local development only. Never deploy this file.

return [
    'env' => 'development',
    'app_url' => 'http://127.0.0.1:8080',
    'base_path' => '',
    'timezone' => 'Asia/Dhaka',

    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'saliheen_portal',
        'user' => 'root',
        'pass' => '',
    ],

    'mail' => [
        'transport' => 'smtp',
        'host' => '127.0.0.1',
        'port' => 1025,
        'encryption' => 'none',
        'username' => '',
        'password' => '',
        'from_email' => 'no-reply@nsaliheen.local',
        'from_name' => 'Saliheen & Associates',
    ],

    'admin_email' => 'admin@nsaliheen.local',

    'security' => [
        'secure_cookies' => false,
        'session_lifetime' => 7200,
    ],
];
