<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Contact;
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
}
