<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Repositories\UserRepository;
use DateTimeImmutable;
use InvalidArgumentException;

final class ContactRegistrationService
{
    public function __construct(
        private ContactRepository $contacts,
        private UserRepository $users
    ) {
    }

    /** @return array{errors: list<string>, contact_id: ?int} */
    public function register(array $input, int $userId): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $nickname = trim((string) ($input['nickname'] ?? ''));
        $company = trim((string) ($input['company'] ?? ''));
        $phone = preg_replace('/\D+/', '', (string) ($input['phone'] ?? '')) ?? '';
        $address = trim((string) ($input['address'] ?? ''));
        $birthDateValue = trim((string) ($input['birth_date'] ?? ''));
        $errors = [];

        if ($name === '' || mb_strlen($name) > 100) {
            $errors[] = 'Informe o nome do contato (até 100 caracteres).';
        }
        if (mb_strlen($nickname) > 50) {
            $errors[] = 'O apelido deve ter até 50 caracteres.';
        }
        if (mb_strlen($company) > 100) {
            $errors[] = 'A empresa deve ter até 100 caracteres.';
        }
        if ($phone !== '' && !in_array(strlen($phone), [10, 11], true)) {
            $errors[] = 'Informe um telefone com DDD e 10 ou 11 dígitos.';
        }

        $birthDate = null;
        if ($birthDateValue !== '') {
            $birthDate = DateTimeImmutable::createFromFormat('!Y-m-d', $birthDateValue);
            if ($birthDate === false || $birthDate->format('Y-m-d') !== $birthDateValue) {
                $errors[] = 'Informe uma data de nascimento válida.';
                $birthDate = null;
            } elseif ($birthDate > new DateTimeImmutable('today')) {
                $errors[] = 'A data de nascimento não pode ser futura.';
                $birthDate = null;
            }
        }

        if ($errors !== []) {
            return ['errors' => $errors, 'contact_id' => null];
        }

        $user = $this->users->findById($userId);
        if ($user === null) {
            return ['errors' => ['Não foi possível identificar o usuário da sessão. Entre novamente.'], 'contact_id' => null];
        }

        try {
            $contact = new Contact($name, $user);
            $contact->setNickname($nickname);
            $contact->setCompany($company);
            $contact->setPhone($phone === '' ? null : $phone);
            $contact->setAddress($address);
            $contact->setBirthDate($birthDate);
            $this->contacts->save($contact);
        } catch (InvalidArgumentException $exception) {
            return ['errors' => [$exception->getMessage()], 'contact_id' => null];
        }

        return ['errors' => [], 'contact_id' => $contact->getId()];
    }
}
