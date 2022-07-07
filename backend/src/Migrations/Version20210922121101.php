<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922121101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_stage_pilot (tournament_stage_id INT NOT NULL, pilot_id INT NOT NULL, INDEX IDX_866D35DCC2263776 (tournament_stage_id), INDEX IDX_866D35DCCE55439B (pilot_id), PRIMARY KEY(tournament_stage_id, pilot_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_stage_pilot ADD CONSTRAINT FK_866D35DCC2263776 FOREIGN KEY (tournament_stage_id) REFERENCES tournament_stages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_stage_pilot ADD CONSTRAINT FK_866D35DCCE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_stages ADD position INT NOT NULL, ADD winners INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournament_stage_pilot');
        $this->addSql('ALTER TABLE tournament_stages DROP position, DROP winners');
    }
}
