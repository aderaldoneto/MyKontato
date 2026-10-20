<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Contact;
use App\Models\User;
use Doctrine\ORM\EntityManagerInterface;

final class ContactRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Contact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    /** @return list<Contact> */
    public function findActiveByUser(User $user): array
    {
        return $this->entityManager->getRepository(Contact::class)->findBy(
            ['user' => $user, 'deletedAt' => null],
            ['name' => 'ASC']
        );
    }
}
