<?php

declare(strict_types=1);

namespace MyKontato\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261020162000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the Admin Master user account';
    }

    public function up(Schema $schema): void
    {
        $existingUser = $this->connection->fetchOne(
            'SELECT id FROM users WHERE cpf = ? OR email = ?',
            ['99999999999', 'admin@admin.com']
        );

        $this->abortIf(
            $existingUser !== false,
            'Cannot create Admin Master: the CPF or email is already registered.'
        );

        $passwordHash = password_hash('123456789', PASSWORD_DEFAULT);
        $this->abortIf($passwordHash === false, 'Could not hash the Admin Master password.');

        $this->addSql(
            'INSERT INTO users (name, cpf, email, birth_date, sexo, phone, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)',
            ['Admin Master', '99999999999', 'admin@admin.com', '2001-01-01', 'masculino', '11999999999', $passwordHash]
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'DELETE FROM users WHERE name = ? AND cpf = ? AND email = ?',
            ['Admin Master', '99999999999', 'admin@admin.com']
        );
    }
}
