<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924073856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A1844E6B7');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93A2B284D0C');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93AC54C8C93');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93ACE55439B');
        $this->addSql('ALTER TABLE race_run DROP FOREIGN KEY FK_457DE93AF53666A8');
        $this->addSql('DROP INDEX IDX_457DE93A1844E6B7 ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93A2B284D0C ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93AC54C8C93 ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93ACE55439B ON race_run');
        $this->addSql('DROP INDEX IDX_457DE93AF53666A8 ON race_run');
        $this->addSql('ALTER TABLE race_run DROP pilot_id, DROP server_id, DROP mission_registry_id, DROP plane_id, DROP type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_run ADD pilot_id INT NOT NULL, ADD server_id INT NOT NULL, ADD mission_registry_id INT NOT NULL, ADD plane_id INT NOT NULL, ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A1844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93A2B284D0C FOREIGN KEY (mission_registry_id) REFERENCES mission_registries (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93AC54C8C93 FOREIGN KEY (type_id) REFERENCES race_types (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93ACE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE race_run ADD CONSTRAINT FK_457DE93AF53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_457DE93A1844E6B7 ON race_run (server_id)');
        $this->addSql('CREATE INDEX IDX_457DE93A2B284D0C ON race_run (mission_registry_id)');
        $this->addSql('CREATE INDEX IDX_457DE93AC54C8C93 ON race_run (type_id)');
        $this->addSql('CREATE INDEX IDX_457DE93ACE55439B ON race_run (pilot_id)');
        $this->addSql('CREATE INDEX IDX_457DE93AF53666A8 ON race_run (plane_id)');
    }
}
