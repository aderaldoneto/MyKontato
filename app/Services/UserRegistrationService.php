<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Sexo;
use App\Models\User;
use App\Repositories\UserRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class UserRegistrationService
{
    public function __construct(private UserRepository $users)
    {
    }

    /** @return array{errors: list<string>, user_id: ?int} */
    public function register(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $cpf = preg_replace('/\D+/', '', (string) ($input['cpf'] ?? '')) ?? '';
        $email = mb_strtolower(trim((string) ($input['email'] ?? '')));
        $birthDateValue = trim((string) ($input['birth_date'] ?? ''));
        $gender = trim((string) ($input['gender'] ?? ''));
        $phone = preg_replace('/\D+/', '', (string) ($input['phone'] ?? '')) ?? '';
        $password = (string) ($input['password'] ?? '');
        $passwordConfirmation = (string) ($input['password_confirmation'] ?? '');
        $errors = [];

        if ($name === '' || mb_strlen($name) > 100) {
            $errors[] = 'Informe seu nome (até 100 caracteres).';
        }
        if (strlen($cpf) !== 11) {
            $errors[] = 'O CPF deve conter 11 dígitos.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 50) {
            $errors[] = 'Informe um e-mail válido (até 50 caracteres).';
        }

        $birthDate = DateTimeImmutable::createFromFormat('!Y-m-d', $birthDateValue);
        if ($birthDate === false || $birthDate->format('Y-m-d') !== $birthDateValue) {
            $errors[] = 'Informe uma data de nascimento válida.';
        }

        $sexo = Sexo::tryFrom($gender);
        if ($sexo === null) {
            $errors[] = 'Selecione uma opção válida para sexo.';
        }
        if (strlen($phone) < 10 || strlen($phone) > 11) {
            $errors[] = 'O celular deve conter 10 ou 11 dígitos.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
        }
        if ($password !== $passwordConfirmation) {
            $errors[] = 'A confirmação de senha não corresponde.';
        }

        if ($errors !== []) {
            return ['errors' => $errors, 'user_id' => null];
        }

        if ($this->users->existsByCpf($cpf)) {
            $errors[] = 'Este CPF já está cadastrado.';
        }
        if ($this->users->existsByEmail($email)) {
            $errors[] = 'Este e-mail já está cadastrado.';
        }
        if ($errors !== []) {
            return ['errors' => $errors, 'user_id' => null];
        }

        try {
            $user = new User($name, $cpf, $email, $birthDate, $sexo, $phone, $password);
            $this->users->save($user);
        } catch (UniqueConstraintViolationException) {
            return ['errors' => ['CPF ou e-mail indisponíveis!'], 'user_id' => null];
        }

        return ['errors' => [], 'user_id' => $user->getId()];
    }
}
