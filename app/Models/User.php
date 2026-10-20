<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Sexo;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use RuntimeException;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 11, unique: true)]
    private string $cpf;

    #[ORM\Column(length: 50, unique: true)]
    private string $email;

    #[ORM\Column(name: 'birth_date', type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $birthDate;

    #[ORM\Column(type: Types::STRING, length: 20, enumType: Sexo::class)]
    private Sexo $sexo;

    #[ORM\Column(length: 11)]
    private string $phone;

    #[ORM\Column(name: 'password_hash', length: 255)]
    private string $passwordHash;

    public function __construct(
        string $name,
        string $cpf,
        string $email,
        DateTimeImmutable $birthDate,
        Sexo $sexo,
        string $phone,
        string $password
    ) {
        $this->name = trim($name);
        $this->setCpf($cpf);
        $this->email = mb_strtolower(trim($email));
        $this->birthDate = $birthDate;
        $this->sexo = $sexo;
        $this->phone = trim($phone);
        $this->setPassword($password);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBirthDate(): DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getSexo(): Sexo
    {
        return $this->sexo;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPassword(string $password): void
    {
        if ($password === '') {
            throw new InvalidArgumentException('A senha não pode ficar vazia.');
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($passwordHash === false) {
            throw new RuntimeException('Não foi possível proteger a senha.');
        }

        $this->passwordHash = $passwordHash;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    private function setCpf(string $cpf): void
    {
        $normalizedCpf = preg_replace('/\D+/', '', $cpf);
        if ($normalizedCpf === null || strlen($normalizedCpf) !== 11) {
            throw new InvalidArgumentException('O CPF deve conter 11 dígitos.');
        }

        $this->cpf = $normalizedCpf;
    }
}
