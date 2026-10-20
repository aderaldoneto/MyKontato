<?php

declare(strict_types=1);

namespace App\Middleware;

final class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !is_int($_SESSION['user_id'])) {
            header('Location: /login.php');
            exit;
        }
    }
}
