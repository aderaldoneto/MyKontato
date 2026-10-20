<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserRegistrationService;
use Closure;
use Throwable;

final class CadastroController
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

        if (!isset($_SESSION['registration_csrf'])) {
            $_SESSION['registration_csrf'] = bin2hex(random_bytes(32));
        }

        $errors = [];
        $values = [
            'name' => '',
            'cpf' => '',
            'birth_date' => '',
            'gender' => '',
            'phone' => '',
            'email' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($values as $field => $_) {
                $values[$field] = trim((string) ($_POST[$field] ?? ''));
            }

            if (!hash_equals($_SESSION['registration_csrf'], (string) ($_POST['csrf_token'] ?? ''))) {
                $errors[] = 'A sessão do formulário expirou. Recarregue a página e tente novamente.';
            } else {
                try {
                    /** @var UserRegistrationService $service */
                    $service = ($this->serviceFactory)();
                    $result = $service->register([
                        ...$values,
                        'password' => (string) ($_POST['password'] ?? ''),
                        'password_confirmation' => (string) ($_POST['password_confirmation'] ?? ''),
                    ]);
                    $errors = $result['errors'];
                } catch (Throwable $exception) {
                    error_log('Falha no cadastro do usuário: ' . $exception->getMessage());
                    $errors[] = 'Não foi possível concluir o cadastro agora. Tente novamente.';
                }

                if ($errors === []) {
                    unset($_SESSION['registration_csrf']);
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $result['user_id'];
                    header('Location: /home.php');
                    exit;
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            header('Allow: GET, POST');
            return;
        }

        $csrfToken = $_SESSION['registration_csrf'];
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        require dirname(__DIR__) . '/Views/cadastro.php';
    }
}
