<?php

declare(strict_types=1);

namespace MyKontato\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261020060000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create contacts table with user ownership and soft delete';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE contacts (
                id INT AUTO_INCREMENT NOT NULL,
                id_user INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                nickname VARCHAR(50) DEFAULT NULL,
                company VARCHAR(100) DEFAULT NULL,
                phone VARCHAR(11) DEFAULT NULL,
                address LONGTEXT DEFAULT NULL,
                birth_date DATE DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                INDEX IDX_CONTACTS_USER (id_user),
                PRIMARY KEY(id),
                CONSTRAINT FK_CONTACTS_USER FOREIGN KEY (id_user) REFERENCES users (id) ON DELETE CASCADE
            ) ENGINE = InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE `utf8mb4_unicode_ci`
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE contacts');
    }
}
