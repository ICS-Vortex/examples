<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924070834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournaments ADD aircrafts_class_id INT NOT NULL');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC318132320 FOREIGN KEY (aircrafts_class_id) REFERENCES aircraft_classes (id)');
        $this->addSql('CREATE INDEX IDX_E4BCFAC318132320 ON tournaments (aircrafts_class_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC318132320');
        $this->addSql('DROP INDEX IDX_E4BCFAC318132320 ON tournaments');
        $this->addSql('ALTER TABLE tournaments DROP aircrafts_class_id');
    }
}
