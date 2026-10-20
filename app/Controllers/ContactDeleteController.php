<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ContactRegistrationService;
use Closure;
use Throwable;

final class ContactDeleteController
{
    private Closure $serviceFactory;

    public function __construct(callable $serviceFactory)
    {
        $this->serviceFactory = Closure::fromCallable($serviceFactory);
    }

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            return;
        }

        $contactId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($contactId === false || $contactId === null || $contactId < 1) {
            http_response_code(404);
            echo 'Contato não encontrado.';
            return;
        }

        if (!isset($_SESSION['contact_delete_csrf'])
            || !hash_equals($_SESSION['contact_delete_csrf'], (string) ($_POST['csrf_token'] ?? ''))) {
            http_response_code(403);
            echo 'A sessão expirou. Recarregue a página e tente novamente.';
            return;
        }

        try {
            /** @var ContactRegistrationService $service */
            $service = ($this->serviceFactory)();
            if (!$service->softDelete($contactId, (int) $_SESSION['user_id'])) {
                http_response_code(404);
                echo 'Contato não encontrado.';
                return;
            }
        } catch (Throwable $exception) {
            error_log('Falha ao excluir contato: ' . $exception->getMessage());
            http_response_code(500);
            echo 'Não foi possível excluir o contato agora. Tente novamente.';
            return;
        }

        $_SESSION['flash_success'] = 'Contato excluído com sucesso.';
        header('Location: /home.php');
        exit;
    }
}
