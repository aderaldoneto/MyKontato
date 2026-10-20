<?php

declare(strict_types=1);

use App\Controllers\CadastroController;
use App\Repositories\UserRepository;
use App\Services\UserRegistrationService;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$controller = new CadastroController(static function (): UserRegistrationService {
    $entityManager = (require dirname(__DIR__) . '/config/doctrine.php')();

    return new UserRegistrationService(new UserRepository($entityManager));
});

$controller->handle();
