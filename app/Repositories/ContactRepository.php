<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Contact;
use App\Models\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

final class ContactRepository
{
    private FilesystemAdapter $cache;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->cache = new FilesystemAdapter('contact_data', 300, sys_get_temp_dir() . '/mykontato-contact-cache');
    }

    public function save(Contact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->invalidateUserCache((int) $contact->getUser()->getId());
    }

    /** @return list<array{id: int, name: string, nickname: ?string, phone: ?string, birth_date: ?string}> */
    public function findActiveByUser(User $user): array
    {
        return $this->cache->get($this->cacheKey('list', $user), function (ItemInterface $item) use ($user): array {
            $item->expiresAfter(300);
            return $this->entityManager->getConnection()->executeQuery(
                'SELECT id, name, nickname, phone, birth_date FROM contacts WHERE id_user = ? AND deleted_at IS NULL ORDER BY name ASC',
                [$user->getId()]
            )->fetchAllAssociative();
        });
    }

    public function countActiveByUser(User $user): int
    {
        return $this->cache->get($this->cacheKey('count', $user), function (ItemInterface $item) use ($user): int {
            $item->expiresAfter(300);
            return $this->entityManager->getRepository(Contact::class)->count([
                'user' => $user,
                'deletedAt' => null,
            ]);
        });
    }

    /** @return list<array{id: int, name: string, nickname: ?string, phone: ?string, birth_date: ?string}> */
    public function findActiveDuplicatesByUser(User $user): array
    {
        return $this->cache->get($this->cacheKey('duplicates', $user), function (ItemInterface $item) use ($user): array {
            $item->expiresAfter(300);
            return $this->entityManager->getConnection()->executeQuery(
                "SELECT c.id, c.name, c.nickname, c.phone, c.birth_date FROM contacts c INNER JOIN (SELECT phone FROM contacts WHERE id_user = ? AND deleted_at IS NULL AND phone IS NOT NULL AND phone <> '' GROUP BY phone HAVING COUNT(id) > 1) d ON d.phone = c.phone WHERE c.id_user = ? AND c.deleted_at IS NULL ORDER BY c.name ASC",
                [$user->getId(), $user->getId()]
            )->fetchAllAssociative();
        });
    }

    public function countActiveDuplicatesByUser(User $user): int
    {
        return $this->cache->get($this->cacheKey('duplicate_count', $user), function (ItemInterface $item) use ($user): int {
            $item->expiresAfter(300);
            $duplicatePhones = $this->createDuplicatePhonesQuery();
            return (int) $this->entityManager->getRepository(Contact::class)->createQueryBuilder('contact')
                ->select('COUNT(contact.id)')
                ->where('contact.user = :user')
                ->andWhere('contact.deletedAt IS NULL')
                ->andWhere('contact.phone IN (' . $duplicatePhones . ')')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
        });
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

    private function cacheKey(string $type, User $user): string
    {
        return sprintf('v2_%s_%d', $type, $user->getId());
    }

    private function invalidateUserCache(int $userId): void
    {
        $this->cache->deleteItems([
            'v2_list_' . $userId,
            'v2_count_' . $userId,
            'v2_duplicates_' . $userId,
            'v2_duplicate_count_' . $userId,
        ]);
    }
}
