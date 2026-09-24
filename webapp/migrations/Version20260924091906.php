<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260924091906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'users - add index for first and last name';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX IDX_1483A5E9A9D1C132 ON users (first_name)');
        $this->addSql('CREATE INDEX IDX_1483A5E9C808BA5A ON users (last_name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_1483A5E9A9D1C132');
        $this->addSql('DROP INDEX IDX_1483A5E9C808BA5A');
    }
}
