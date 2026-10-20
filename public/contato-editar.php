<?php

declare(strict_types=1);

use App\Controllers\ContactEditController;
use App\Repositories\ContactRepository;
use App\Repositories\UserRepository;
use App\Services\ContactRegistrationService;

require_once dirname(__DIR__) . '/vendor/autoload.php';

\App\Middleware\AuthMiddleware::requireLogin();

$controller = new ContactEditController(static function (): ContactRegistrationService {
    $entityManager = (require dirname(__DIR__) . '/config/doctrine.php')();

    return new ContactRegistrationService(
        new ContactRepository($entityManager),
        new UserRepository($entityManager)
    );
});

$controller->handle();
