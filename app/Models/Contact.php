<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity]
#[ORM\Table(name: 'contacts', indexes: [new ORM\Index(name: 'IDX_CONTACTS_USER', columns: ['id_user'])])]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(name: 'birth_date', type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $birthDate = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(name: 'deleted_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct(string $name, User $user)
    {
        $this->setName($name);
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $name = trim($name);
        if ($name === '' || mb_strlen($name) > 100) {
            throw new InvalidArgumentException('O nome deve ser informado e ter até 100 caracteres.');
        }

        $this->name = $name;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): void
    {
        $nickname = $this->nullableTrim($nickname);
        $this->assertMaxLength($nickname, 50, 'O apelido');
        $this->nickname = $nickname;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): void
    {
        $company = $this->nullableTrim($company);
        $this->assertMaxLength($company, 100, 'A empresa');
        $this->company = $company;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $phone = $this->nullableTrim($phone);
        if ($phone !== null) {
            $digits = preg_replace('/\D+/', '', $phone);
            if ($digits === null || strlen($digits) > 11) {
                throw new InvalidArgumentException('O telefone deve conter até 11 dígitos.');
            }
            $phone = $digits;
        }

        $this->phone = $phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $this->nullableTrim($address);
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function softDelete(): void
    {
        $this->deletedAt ??= new DateTimeImmutable();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        return $value === '' ? null : $value;
    }

    private function assertMaxLength(?string $value, int $maxLength, string $field): void
    {
        if ($value !== null && mb_strlen($value) > $maxLength) {
            throw new InvalidArgumentException(sprintf('%s deve ter até %d caracteres.', $field, $maxLength));
        }
    }
}
