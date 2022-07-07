<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922194137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournaments ADD aircraft_class_id INT NOT NULL');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC38BA5E589 FOREIGN KEY (aircraft_class_id) REFERENCES aircraft_classes (id)');
        $this->addSql('CREATE INDEX IDX_E4BCFAC38BA5E589 ON tournaments (aircraft_class_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC38BA5E589');
        $this->addSql('DROP INDEX IDX_E4BCFAC38BA5E589 ON tournaments');
        $this->addSql('ALTER TABLE tournaments DROP aircraft_class_id');
    }
}
