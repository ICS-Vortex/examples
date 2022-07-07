<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924073611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_run ADD tournament_id INT NOT NULL, ADD stage_id INT NOT NULL, ADD aircraft_class_id INT NOT NULL');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A2298D193 FOREIGN KEY (stage_id) REFERENCES tournament_stages (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A8BA5E589 FOREIGN KEY (aircraft_class_id) REFERENCES aircraft_classes (id)');
        $this->addSql('CREATE INDEX IDX_457DE93A33D1A3E7 ON race_run (tournament_id)');
        $this->addSql('CREATE INDEX IDX_457DE93A2298D193 ON race_run (stage_id)');
        $this->addSql('CREATE INDEX IDX_457DE93A8BA5E589 ON race_run (aircraft_class_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A33D1A3E7');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A2298D193');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A8BA5E589');
        $this->addSql('DROP INDEX IDX_457DE93A33D1A3E7 ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93A2298D193 ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93A8BA5E589 ON race_run');
        $this->addSql('ALTER TABLE race_run DROP tournament_id, DROP stage_id, DROP aircraft_class_id');
    }
}
