<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ContactRegistrationService;
use Closure;
use Throwable;

final class ContactRegistrationController
{
    private Closure $serviceFactory;

    public function __construct(callable $serviceFactory)
    {
        $this->serviceFactory = Closure::fromCallable($serviceFactory);
    }

    public function handle(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['contact_csrf'] ??= bin2hex(random_bytes(32));
        $service = null;
        $errors = [];
        $values = [
            'name' => '',
            'nickname' => '',
            'company' => '',
            'phone' => '',
            'birth_date' => '',
            'address' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($values as $field => $_) {
                $values[$field] = trim((string) ($_POST[$field] ?? ''));
            }

            if (!hash_equals($_SESSION['contact_csrf'], (string) ($_POST['csrf_token'] ?? ''))) {
                $errors[] = 'A sessão do formulário expirou. Recarregue a página e tente novamente.';
            } else {
                try {
                    /** @var ContactRegistrationService $service */
                    $service = ($this->serviceFactory)();
                    $result = $service->register($values, (int) $_SESSION['user_id']);
                    $errors = $result['errors'];
                } catch (Throwable $exception) {
                    error_log('Falha no cadastro do contato: ' . $exception->getMessage());
                    $errors[] = 'Não foi possível cadastrar o contato agora. Tente novamente.';
                }

                if ($errors === []) {
                    unset($_SESSION['contact_csrf']);
                    $_SESSION['flash_success'] = 'Contato cadastrado com sucesso.';
                    header('Location: /home.php');
                    exit;
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            header('Allow: GET, POST');
            return;
        }

        try {
            /** @var ContactRegistrationService $service */
            $service ??= ($this->serviceFactory)();
            $duplicateCount = $service->countActiveDuplicatesForUser((int) $_SESSION['user_id']);
            $contactCount = $service->countActiveContactsForUser((int) $_SESSION['user_id']);
            $userName = $service->getUserNameForId((int) $_SESSION['user_id']);
        } catch (Throwable $exception) {
            error_log('Falha ao carregar contadores de contatos: ' . $exception->getMessage());
            http_response_code(500);
            echo 'Não foi possível carregar os contatos agora. Tente novamente.';
            return;
        }

        if ($userName === null) {
            $_SESSION = [];
            session_destroy();
            header('Location: /login.php');
            exit;
        }

        $userInitial = mb_strtoupper(mb_substr(trim($userName), 0, 1));
        $activeNavigation = 'contacts';
        $csrfToken = $_SESSION['contact_csrf'];
        $isEditing = false;
        $contactId = null;
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        require dirname(__DIR__) . '/Views/contact-create.php';
    }
}
