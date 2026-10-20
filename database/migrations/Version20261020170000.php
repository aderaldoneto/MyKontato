<?php

declare(strict_types=1);

namespace MyKontato\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261020170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Index active contacts by owner and phone for duplicate lookups';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX IDX_CONTACTS_USER_DELETED_PHONE ON contacts (id_user, deleted_at, phone)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_CONTACTS_USER_DELETED_PHONE ON contacts');
    }
}
