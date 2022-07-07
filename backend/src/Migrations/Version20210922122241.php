<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922122241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_stages ADD tournament_id INT NOT NULL');
        $this->addSql('ALTER TABLE tournament_stages ADD CONSTRAINT FK_4B08943433D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('CREATE INDEX IDX_4B08943433D1A3E7 ON tournament_stages (tournament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_stages DROP FOREIGN KEY FK_4B08943433D1A3E7');
        $this->addSql('DROP INDEX IDX_4B08943433D1A3E7 ON tournament_stages');
        $this->addSql('ALTER TABLE tournament_stages DROP tournament_id');
    }
}
