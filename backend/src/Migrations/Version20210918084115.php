<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210918084115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, pilot_id INT NOT NULL, server_id INT NOT NULL, tour_id INT NOT NULL, mission_registry_id INT NOT NULL, plane_id INT NOT NULL, time INT NOT NULL, INDEX IDX_DA6FBBAFCE55439B (pilot_id), INDEX IDX_DA6FBBAF1844E6B7 (server_id), INDEX IDX_DA6FBBAF15ED8D43 (tour_id), INDEX IDX_DA6FBBAF2B284D0C (mission_registry_id), INDEX IDX_DA6FBBAFF53666A8 (plane_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAFCE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF1844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id)');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF15ED8D43 FOREIGN KEY (tour_id) REFERENCES tours (id)');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF2B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id)');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAFF53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE race');
    }
}
