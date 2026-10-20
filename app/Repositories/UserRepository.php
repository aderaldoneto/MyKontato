<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Doctrine\ORM\EntityManagerInterface;

final class UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function existsByCpf(string $cpf): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['cpf' => $cpf]) !== null;
    }

    public function existsByEmail(string $email): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]) !== null;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
