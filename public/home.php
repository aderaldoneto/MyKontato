<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

\App\Middleware\AuthMiddleware::requireLogin();

$successMessage = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

require dirname(__DIR__) . '/app/Views/home.php';
