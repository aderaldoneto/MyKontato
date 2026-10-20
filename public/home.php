<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

\App\Middleware\AuthMiddleware::requireLogin();

require dirname(__DIR__) . '/app/Views/home.php';
