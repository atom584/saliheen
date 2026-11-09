<?php
declare(strict_types=1);

function render_flash_alerts(): string
{
    ob_start();
    require dirname(__DIR__) . '/views/partials/flash.php';
    return (string) ob_get_clean();
}

function service_icon(string $icon): string
{
    $allowed = ['i-scale', 'i-check', 'i-shield', 'i-lock', 'i-briefcase', 'i-bank', 'i-gavel', 'i-chart'];
    $icon = in_array($icon, $allowed, true) ? $icon : 'i-scale';
    return '<svg viewBox="0 0 24 24" aria-hidden="true"><use href="#' . $icon . '"/></svg>';
}
