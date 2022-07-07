<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922112752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournament_stage_race');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_stage_race (tournament_stage_id INT NOT NULL, race_id INT NOT NULL, INDEX IDX_72DA14606E59D40D (race_id), INDEX IDX_72DA1460C2263776 (tournament_stage_id), PRIMARY KEY(tournament_stage_id, race_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tournament_stage_race ADD CONSTRAINT FK_72DA14606E59D40D FOREIGN KEY (race_id) REFERENCES races (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_stage_race ADD CONSTRAINT FK_72DA1460C2263776 FOREIGN KEY (tournament_stage_id) REFERENCES tournament_stages (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
