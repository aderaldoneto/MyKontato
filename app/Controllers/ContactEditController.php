<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ContactRegistrationService;
use Closure;
use Throwable;

final class ContactEditController
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

        $contactId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($contactId === false || $contactId === null || $contactId < 1) {
            http_response_code(404);
            echo 'Contato não encontrado.';
            return;
        }

        /** @var ContactRegistrationService $service */
        $service = ($this->serviceFactory)();
        $contact = $service->findForUser($contactId, (int) $_SESSION['user_id']);
        if ($contact === null) {
            http_response_code(404);
            echo 'Contato não encontrado.';
            return;
        }

        $_SESSION['contact_edit_csrf'] ??= bin2hex(random_bytes(32));
        $errors = [];
        $values = [
            'name' => $contact->getName(),
            'nickname' => $contact->getNickname() ?? '',
            'company' => $contact->getCompany() ?? '',
            'phone' => $contact->getPhone() ?? '',
            'birth_date' => $contact->getBirthDate()?->format('Y-m-d') ?? '',
            'address' => $contact->getAddress() ?? '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($values as $field => $_) {
                $values[$field] = trim((string) ($_POST[$field] ?? ''));
            }

            if (!hash_equals($_SESSION['contact_edit_csrf'], (string) ($_POST['csrf_token'] ?? ''))) {
                $errors[] = 'A sessão do formulário expirou. Recarregue a página e tente novamente.';
            } else {
                try {
                    $result = $service->update($contactId, $values, (int) $_SESSION['user_id']);
                    $errors = $result['errors'];
                } catch (Throwable $exception) {
                    error_log('Falha ao editar contato: ' . $exception->getMessage());
                    $errors[] = 'Não foi possível atualizar o contato agora. Tente novamente.';
                }

                if ($errors === []) {
                    unset($_SESSION['contact_edit_csrf']);
                    $_SESSION['flash_success'] = 'Contato atualizado com sucesso.';
                    header('Location: /home.php');
                    exit;
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            header('Allow: GET, POST');
            return;
        }

        $isEditing = true;
        $csrfToken = $_SESSION['contact_edit_csrf'];
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        require dirname(__DIR__) . '/Views/contact-create.php';
    }
}
