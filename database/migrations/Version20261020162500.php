<?php

declare(strict_types=1);

namespace MyKontato\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261020162500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create 30 sample contacts for the first registered user';
    }

    public function up(Schema $schema): void
    {
        $userId = $this->connection->fetchOne('SELECT id FROM users ORDER BY id ASC LIMIT 1');
        $this->abortIf($userId === false, 'Cannot create sample contacts: no users are registered.');

        $names = $this->getContactNames();
        $namePlaceholders = implode(', ', array_fill(0, count($names), '?'));
        $existingContact = $this->connection->fetchOne(
            "SELECT id FROM contacts WHERE id_user = ? AND name IN ({$namePlaceholders}) LIMIT 1",
            [$userId, ...$names]
        );
        $this->abortIf(
            $existingContact !== false,
            'Cannot create sample contacts: one or more sample names already exist for the first user.'
        );

        $rows = [];
        $parameters = [];
        foreach ($names as $index => $name) {
            $rows[] = '(?, ?, ?)';
            $parameters[] = $userId;
            $parameters[] = $name;
            $parameters[] = sprintf('1199000%04d', $index + 1);
        }

        $this->addSql(
            'INSERT INTO contacts (id_user, name, phone) VALUES ' . implode(', ', $rows),
            $parameters
        );
    }

    public function down(Schema $schema): void
    {
        $names = $this->getContactNames();
        $namePlaceholders = implode(', ', array_fill(0, count($names), '?'));

        $this->addSql(
            "DELETE FROM contacts WHERE id_user = (SELECT id FROM users ORDER BY id ASC LIMIT 1) AND name IN ({$namePlaceholders})",
            $names
        );
    }

    /** @return list<string> */
    private function getContactNames(): array
    {
        $names = [];
        for ($index = 1; $index <= 30; $index++) {
            $names[] = sprintf('Contato de exemplo %02d', $index);
        }

        return $names;
    }
}
