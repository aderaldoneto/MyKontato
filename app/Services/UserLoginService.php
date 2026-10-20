<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

final class UserLoginService
{
    public function __construct(private UserRepository $users)
    {
    }

    /** @return array{errors: list<string>, user_id: ?int} */
    public function login(string $email, string $password): array
    {
        $email = mb_strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            return ['errors' => ['E-mail ou senha inválidos.'], 'user_id' => null];
        }

        $user = $this->users->findByEmail($email);
        if ($user === null || !$user->verifyPassword($password)) {
            return ['errors' => ['E-mail ou senha inválidos.'], 'user_id' => null];
        }

        return ['errors' => [], 'user_id' => $user->getId()];
    }
}
