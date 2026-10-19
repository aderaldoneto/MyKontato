<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;

return static function (): EntityManager {
    $projectRoot = dirname(__DIR__);
    (new Dotenv())->usePutenv()->loadEnv($projectRoot . '/.env');

    $required = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    foreach ($required as $key) {
        if (getenv($key) === false || getenv($key) === '') {
            throw new RuntimeException(sprintf('A variável de ambiente %s não foi definida.', $key));
        }
    }

    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: [$projectRoot . '/app/Models'],
        isDevMode: true
    );

    $connection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'host' => getenv('DB_HOST'),
        'port' => (int) (getenv('DB_PORT') ?: '3306'),
        'dbname' => getenv('DB_DATABASE'),
        'user' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8mb4',
    ], $config);

    try {
        $connection->connect();
        return new EntityManager($connection, $config);
    } catch (Throwable $exception) {
        error_log('Falha ao conectar ao banco MyKontato: ' . $exception->getMessage());
        throw new RuntimeException('Não foi possível conectar ao banco de dados.', 0, $exception);
    }
};
