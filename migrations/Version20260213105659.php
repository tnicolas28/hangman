<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213105659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evil_games (candidates JSON NOT NULL, id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE evil_games ADD CONSTRAINT FK_1096E612BF396750 FOREIGN KEY (id) REFERENCES games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games ADD used_letters JSON NOT NULL');
        $this->addSql('ALTER TABLE games ADD tries INT NOT NULL');
        $this->addSql('ALTER TABLE games ADD hint_used BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE games ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evil_games DROP CONSTRAINT FK_1096E612BF396750');
        $this->addSql('DROP TABLE evil_games');
        $this->addSql('ALTER TABLE games DROP used_letters');
        $this->addSql('ALTER TABLE games DROP tries');
        $this->addSql('ALTER TABLE games DROP hint_used');
        $this->addSql('ALTER TABLE games DROP type');
    }
}
