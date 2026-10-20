<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

\App\Middleware\AuthMiddleware::requireLogin();

$contactId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$entityManager = (require dirname(__DIR__) . '/config/doctrine.php')();
$user = $entityManager->find(\App\Models\User::class, $_SESSION['user_id']);

if ($user === null) {
    $_SESSION = [];
    session_destroy();
    header('Location: /login.php');
    exit;
}

$contact = $contactId !== false && $contactId !== null && $contactId > 0
    ? (new \App\Repositories\ContactRepository($entityManager))->findActiveByIdForUser($contactId, $user)
    : null;

if ($contact === null) {
    http_response_code(404);
}

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
$formatPhone = static function (?string $phone): string {
    if ($phone === null || $phone === '') {
        return '—';
    }

    if (strlen($phone) === 11) {
        return sprintf('(%s) %s %s-%s', substr($phone, 0, 2), substr($phone, 2, 1), substr($phone, 3, 4), substr($phone, 7));
    }
    if (strlen($phone) === 10) {
        return sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 4), substr($phone, 6));
    }

    return $phone;
};

require dirname(__DIR__) . '/app/Views/contact-details.php';
