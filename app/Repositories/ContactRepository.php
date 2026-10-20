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

    public function countActiveByUser(User $user): int
    {
        return $this->entityManager->getRepository(Contact::class)->count([
            'user' => $user,
            'deletedAt' => null,
        ]);
    }

    /** @return list<Contact> */
    public function findActiveDuplicatesByUser(User $user): array
    {
        $duplicatePhones = $this->createDuplicatePhonesQuery();

        return $this->entityManager->getRepository(Contact::class)->createQueryBuilder('contact')
            ->where('contact.user = :user')
            ->andWhere('contact.deletedAt IS NULL')
            ->andWhere('contact.phone IN (' . $duplicatePhones . ')')
            ->setParameter('user', $user)
            ->orderBy('contact.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countActiveDuplicatesByUser(User $user): int
    {
        $duplicatePhones = $this->createDuplicatePhonesQuery();

        return (int) $this->entityManager->getRepository(Contact::class)->createQueryBuilder('contact')
            ->select('COUNT(contact.id)')
            ->where('contact.user = :user')
            ->andWhere('contact.deletedAt IS NULL')
            ->andWhere('contact.phone IN (' . $duplicatePhones . ')')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findActiveByIdForUser(int $id, User $user): ?Contact
    {
        return $this->entityManager->getRepository(Contact::class)->findOneBy([
            'id' => $id,
            'user' => $user,
            'deletedAt' => null,
        ]);
    }

    private function createDuplicatePhonesQuery(): string
    {
        return $this->entityManager->createQueryBuilder()
            ->select('duplicate.phone')
            ->from(Contact::class, 'duplicate')
            ->where('duplicate.user = :user')
            ->andWhere('duplicate.deletedAt IS NULL')
            ->andWhere('duplicate.phone IS NOT NULL')
            ->andWhere("duplicate.phone <> ''")
            ->groupBy('duplicate.phone')
            ->having('COUNT(duplicate.id) > 1')
            ->getDQL();
    }
}
