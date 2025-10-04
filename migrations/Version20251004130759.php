<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004130759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD author_b_id INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331CD847469 FOREIGN KEY (author_b_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331CD847469 ON book (author_b_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331CD847469');
        $this->addSql('DROP INDEX IDX_CBE5A331CD847469 ON book');
        $this->addSql('ALTER TABLE book DROP author_b_id');
    }
}
