<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928071619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race_runs (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, tournament_id INT DEFAULT NULL, stage_id INT DEFAULT NULL, aircraft_class_id INT DEFAULT NULL, pilot_id INT NOT NULL, plane_id INT NOT NULL, server_id INT NOT NULL, mission_registry_id INT NOT NULL, time NUMERIC(10, 2) NOT NULL, INDEX IDX_DD476B5015ED8D43 (tour_id), INDEX IDX_DD476B5033D1A3E7 (tournament_id), INDEX IDX_DD476B502298D193 (stage_id), INDEX IDX_DD476B508BA5E589 (aircraft_class_id), INDEX IDX_DD476B50CE55439B (pilot_id), INDEX IDX_DD476B50F53666A8 (plane_id), INDEX IDX_DD476B501844E6B7 (server_id), INDEX IDX_DD476B502B284D0C (mission_registry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B5015ED8D43 FOREIGN KEY (tour_id) REFERENCES tours (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B5033D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B502298D193 FOREIGN KEY (stage_id) REFERENCES tournament_stages (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B508BA5E589 FOREIGN KEY (aircraft_class_id) REFERENCES aircraft_classes (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B50CE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B50F53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B501844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id)');
        $this->addSql('ALTER TABLE race_runs ADD CONSTRAINT FK_DD476B502B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id)');
        $this->addSql('DROP TABLE race_run');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race_run (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, tournament_id INT DEFAULT NULL, stage_id INT DEFAULT NULL, aircraft_class_id INT DEFAULT NULL, pilot_id INT NOT NULL, plane_id INT NOT NULL, server_id INT NOT NULL, mission_registry_id INT NOT NULL, time NUMERIC(10, 2) NOT NULL, INDEX IDX_457DE93A15ED8D43 (tour_id), INDEX IDX_457DE93A1844E6B7 (server_id), INDEX IDX_457DE93A2298D193 (stage_id), INDEX IDX_457DE93A2B284D0C (mission_registry_id), INDEX IDX_457DE93A33D1A3E7 (tournament_id), INDEX IDX_457DE93A8BA5E589 (aircraft_class_id), INDEX IDX_457DE93ACE55439B (pilot_id), INDEX IDX_457DE93AF53666A8 (plane_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A15ED8D43 FOREIGN KEY (tour_id) REFERENCES tours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A1844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A2298D193 FOREIGN KEY (stage_id) REFERENCES tournament_stages (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A2B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A8BA5E589 FOREIGN KEY (aircraft_class_id) REFERENCES aircraft_classes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93ACE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93AF53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE race_runs');
    }
}
