<?php
declare(strict_types=1);

/**
 * Creates the first administrator account.
 * CLI only: php setup/create-admin.php
 *
 * Options (otherwise prompted):
 *   --name="Nazmus Saliheen" --email="admin@example.com" --password="..."
 *   --update   reset the password when the account already exists
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../app/bootstrap.php';

$options = getopt('', ['name::', 'email::', 'password::', 'update']);

$prompt = static function (string $label, bool $hidden = false): string {
    fwrite(STDOUT, $label);
    if ($hidden && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        shell_exec('stty -echo');
        $value = fgets(STDIN);
        shell_exec('stty echo');
        fwrite(STDOUT, PHP_EOL);
    } else {
        $value = fgets(STDIN);
    }
    return trim((string) $value);
};

$name = trim((string) ($options['name'] ?? ''));
$email = strtolower(trim((string) ($options['email'] ?? '')));
$password = (string) ($options['password'] ?? '');
$allowUpdate = array_key_exists('update', $options);

if ($name === '') {
    $name = $prompt('Administrator full name: ');
}
if ($email === '') {
    $email = strtolower($prompt('Administrator email: '));
}
if ($password === '') {
    $password = $prompt('Password (min 10 characters, letters and numbers): ', true);
    $confirm = $prompt('Confirm password: ', true);
    if ($password !== $confirm) {
        fwrite(STDERR, "Passwords do not match.\n");
        exit(1);
    }
}

$errors = [];
if (mb_strlen($name) < 2) {
    $errors[] = 'Enter the administrator name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}
if (strlen($password) < 10) {
    $errors[] = 'Use at least 10 characters for the administrator password.';
} elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    $errors[] = 'Include at least one letter and one number in the password.';
}

if ($errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, $error . PHP_EOL);
    }
    exit(1);
}

try {
    $existing = \App\Models\User::findByEmail($email);

    if ($existing) {
        if (!$allowUpdate) {
            fwrite(STDERR, "An account with {$email} already exists. Re-run with --update to reset its password and grant administrator access.\n");
            exit(1);
        }
        $stmt = db()->prepare(
            "UPDATE users
             SET full_name = :name, password_hash = :hash, role = 'admin', company_id = NULL,
                 auth_provider = 'email', email_verified_at = NOW(), status = 'active'
             WHERE id = :id"
        );
        $stmt->execute([
            ':name' => $name,
            ':hash' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => (int) $existing['id'],
        ]);
        $userId = (int) $existing['id'];
        $message = 'Administrator account updated.';
    } else {
        $userId = \App\Models\User::create([
            'company_id' => null,
            'full_name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'auth_provider' => 'email',
            'role' => 'admin',
            'company_role' => 'admin',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status' => 'active',
        ]);
        $message = 'Administrator account created.';
    }

    audit_log('admin.created_via_setup', 'user', $userId, ['email' => $email]);
    fwrite(STDOUT, $message . PHP_EOL);
    fwrite(STDOUT, 'Sign in at: ' . absolute_url('/admin-login/') . PHP_EOL);
    fwrite(STDOUT, 'Remove setup access from the server after use.' . PHP_EOL);
} catch (Throwable $error) {
    fwrite(STDERR, 'Could not create the administrator: ' . $error->getMessage() . PHP_EOL);
    exit(1);
}
