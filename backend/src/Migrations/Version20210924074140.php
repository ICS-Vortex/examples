<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924074140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_run ADD pilot_id INT NOT NULL, ADD plane_id INT NOT NULL, ADD server_id INT NOT NULL, ADD mission_registry_id INT NOT NULL');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93ACE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93AF53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A1844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id)');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A2B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id)');
        $this->addSql('CREATE INDEX IDX_457DE93ACE55439B ON race_run (pilot_id)');
        $this->addSql('CREATE INDEX IDX_457DE93AF53666A8 ON race_run (plane_id)');
        $this->addSql('CREATE INDEX IDX_457DE93A1844E6B7 ON race_run (server_id)');
        $this->addSql('CREATE INDEX IDX_457DE93A2B284D0C ON race_run (mission_registry_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93ACE55439B');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93AF53666A8');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A1844E6B7');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A2B284D0C');
        $this->addSql('DROP INDEX IDX_457DE93ACE55439B ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93AF53666A8 ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93A1844E6B7 ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93A2B284D0C ON race_run');
        $this->addSql('ALTER TABLE race_run DROP pilot_id, DROP plane_id, DROP server_id, DROP mission_registry_id');
    }
}
