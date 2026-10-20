<?php

declare(strict_types=1);

namespace MyKontato\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261020041459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE users (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(100) NOT NULL,
                cpf VARCHAR(11) NOT NULL,
                email VARCHAR(50) NOT NULL,
                birth_date DATE NOT NULL,
                sexo VARCHAR(20) NOT NULL,
                phone VARCHAR(11) NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                PRIMARY KEY(id),
                UNIQUE INDEX UNIQ_USERS_CPF (cpf),
                UNIQUE INDEX UNIQ_USERS_EMAIL (email)
            ) ENGINE = InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE `utf8mb4_unicode_ci`
        SQL);

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');

    }
}
