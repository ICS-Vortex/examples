<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006064202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_page ADD tournament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_page ADD CONSTRAINT FK_157C7EDD33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('CREATE INDEX IDX_157C7EDD33D1A3E7 ON custom_page (tournament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_page DROP FOREIGN KEY FK_157C7EDD33D1A3E7');
        $this->addSql('DROP INDEX IDX_157C7EDD33D1A3E7 ON custom_page');
        $this->addSql('ALTER TABLE custom_page DROP tournament_id');
    }
}
