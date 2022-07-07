<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922124136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race_run (id INT AUTO_INCREMENT NOT NULL, pilot_id INT NOT NULL, server_id INT NOT NULL, tour_id INT NOT NULL, mission_registry_id INT NOT NULL, plane_id INT NOT NULL, type_id INT NOT NULL, time NUMERIC(10, 2) NOT NULL, INDEX IDX_457DE93ACE55439B (pilot_id), INDEX IDX_457DE93A1844E6B7 (server_id), INDEX IDX_457DE93A15ED8D43 (tour_id), INDEX IDX_457DE93A2B284D0C (mission_registry_id), INDEX IDX_457DE93AF53666A8 (plane_id), INDEX IDX_457DE93AC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93ACE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A1844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A15ED8D43 FOREIGN KEY (tour_id) REFERENCES tours (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A2B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93AF53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93AC54C8C93 FOREIGN KEY (type_id) REFERENCES race_types (id)');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC915ED8D43');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC91844E6B7');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC92B284D0C');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9C54C8C93');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9CE55439B');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9F53666A8');
        $this->addSql('DROP INDEX IDX_5DBD1EC915ED8D43 ON races');
        $this->addSql('DROP INDEX IDX_5DBD1EC91844E6B7 ON races');
        $this->addSql('DROP INDEX IDX_5DBD1EC92B284D0C ON races');
        $this->addSql('DROP INDEX IDX_5DBD1EC9C54C8C93 ON races');
        $this->addSql('DROP INDEX IDX_5DBD1EC9CE55439B ON races');
        $this->addSql('DROP INDEX IDX_5DBD1EC9F53666A8 ON races');
        $this->addSql('ALTER TABLE races ADD title VARCHAR(255) NOT NULL, ADD title_en VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL, ADD description_en LONGTEXT NOT NULL, ADD image VARCHAR(255) DEFAULT NULL, DROP pilot_id, DROP server_id, DROP tour_id, DROP mission_registry_id, DROP plane_id, DROP type_id, DROP time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE race_run');
        $this->addSql('ALTER TABLE races ADD pilot_id INT NOT NULL, ADD server_id INT NOT NULL, ADD tour_id INT NOT NULL, ADD mission_registry_id INT NOT NULL, ADD plane_id INT NOT NULL, ADD type_id INT NOT NULL, ADD time NUMERIC(10, 2) NOT NULL, DROP title, DROP title_en, DROP description, DROP description_en, DROP image');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC915ED8D43 FOREIGN KEY (tour_id) REFERENCES tours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC91844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC92B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9C54C8C93 FOREIGN KEY (type_id) REFERENCES race_types (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9CE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9F53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5DBD1EC915ED8D43 ON races (tour_id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC91844E6B7 ON races (server_id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC92B284D0C ON races (mission_registry_id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC9C54C8C93 ON races (type_id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC9CE55439B ON races (pilot_id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC9F53666A8 ON races (plane_id)');
    }
}
