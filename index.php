<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap_core.php';
require_once __DIR__ . '/routes/web.php';

App\Core\Router::dispatch();
