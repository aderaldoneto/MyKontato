<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

$projectRoot = __DIR__;
(new Dotenv())->usePutenv()->loadEnv($projectRoot . '/.env');

return [
    'driver' => 'pdo_mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int) (getenv('DB_PORT') ?: '3306'),
    'dbname' => getenv('DB_DATABASE') ?: 'php_crud_agenda',
    'user' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: 'root',
    'charset' => 'utf8mb4',
];
