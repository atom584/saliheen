<?php
declare(strict_types=1);

// Copy this file to config.local.php and fill in the real values.
// config.local.php and the whole /app directory are blocked from HTTP access.

return [
    'env' => 'production',
    'app_url' => 'https://nsaliheen.com',
    'base_path' => '',
    'timezone' => 'Asia/Dhaka',

    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'u000000000_saliheen',
        'user' => 'u000000000_portal',
        'pass' => '',
    ],

    'mail' => [
        'transport' => 'smtp',
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => 'no-reply@nsaliheen.com',
        'password' => '',
        'from_email' => 'no-reply@nsaliheen.com',
        'from_name' => 'Saliheen & Associates',
    ],

    'admin_email' => 'admin@nsaliheen.com',

    'security' => [
        'secure_cookies' => true,
        'session_lifetime' => 7200,
    ],

    'oauth' => [
        'google' => [
            'client_id' => '',
            'client_secret' => '',
        ],
        'apple' => [
            'client_id' => '',
            'team_id' => '',
            'key_id' => '',
            'private_key_path' => '',
            'private_key' => '',
        ],
    ],
];
