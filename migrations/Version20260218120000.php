<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260218120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add started_at column to games table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE games ADD started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT NOW()');
        $this->addSql('COMMENT ON COLUMN games.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE games ALTER started_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE games DROP started_at');
    }
}
