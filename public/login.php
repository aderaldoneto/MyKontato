<?php

declare(strict_types=1);

use App\Controllers\LoginController;
use App\Repositories\UserRepository;
use App\Services\UserLoginService;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$controller = new LoginController(static function (): UserLoginService {
    $entityManager = (require dirname(__DIR__) . '/config/doctrine.php')();

    return new UserLoginService(new UserRepository($entityManager));
});

$controller->handle();
