<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

\App\Middleware\AuthMiddleware::requireLogin();

$entityManager = (require dirname(__DIR__) . '/config/doctrine.php')();
$user = $entityManager->find(\App\Models\User::class, $_SESSION['user_id']);
if ($user === null) {
    $_SESSION = [];
    session_destroy();
    header('Location: /login.php');
    exit;
}

$contactRepository = new \App\Repositories\ContactRepository($entityManager);
$searchQuery = trim((string) ($_GET['q'] ?? ''));
$contacts = $searchQuery === ''
    ? $contactRepository->findActiveByUser($user)
    : $contactRepository->searchActiveByUser($user, $searchQuery);
$contactCount = $contactRepository->countActiveByUser($user);
$duplicateCount = $contactRepository->countActiveDuplicatesByUser($user);
$userName = $user->getName();
$userInitial = mb_strtoupper(mb_substr(trim($userName), 0, 1));
$activeNavigation = 'contacts';
$pageTitle = 'Contatos';
$emptyMessage = $searchQuery !== '' ? 'Nenhum contato encontrado para esta busca.' : 'Nenhum contato cadastrado.';
$showNewContactButton = true;
$_SESSION['contact_delete_csrf'] ??= bin2hex(random_bytes(32));
$contactDeleteCsrfToken = $_SESSION['contact_delete_csrf'];

$successMessage = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
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

require dirname(__DIR__) . '/app/Views/home.php';
