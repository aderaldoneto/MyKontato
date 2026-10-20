<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserLoginService;
use Closure;
use Throwable;

final class LoginController
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

        if (isset($_SESSION['user_id']) && is_int($_SESSION['user_id'])) {
            header('Location: /home.php');
            exit;
        }

        $_SESSION['login_csrf'] ??= bin2hex(random_bytes(32));
        $errors = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            if (!hash_equals($_SESSION['login_csrf'], (string) ($_POST['csrf_token'] ?? ''))) {
                $errors[] = 'A sessão do formulário expirou. Recarregue a página e tente novamente.';
            } else {
                try {
                    /** @var UserLoginService $service */
                    $service = ($this->serviceFactory)();
                    $result = $service->login($email, (string) ($_POST['password'] ?? ''));
                    $errors = $result['errors'];
                } catch (Throwable $exception) {
                    error_log('Falha no login do usuário: ' . $exception->getMessage());
                    $errors[] = 'Não foi possível entrar agora. Tente novamente.';
                }

                if ($errors === []) {
                    session_regenerate_id(true);
                    unset($_SESSION['login_csrf']);
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

        $csrfToken = $_SESSION['login_csrf'];
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        require dirname(__DIR__) . '/Views/login.php';
    }
}
