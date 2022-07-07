<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922193952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aircraft_classes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aircraft_class_plane (aircraft_class_id INT NOT NULL, plane_id INT NOT NULL, INDEX IDX_B3E706AD8BA5E589 (aircraft_class_id), INDEX IDX_B3E706ADF53666A8 (plane_id), PRIMARY KEY(aircraft_class_id, plane_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aircraft_class_plane ADD CONSTRAINT FK_B3E706AD8BA5E589 FOREIGN KEY (aircraft_class_id) REFERENCES aircraft_classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aircraft_class_plane ADD CONSTRAINT FK_B3E706ADF53666A8 FOREIGN KEY (plane_id) REFERENCES planes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aircraft_class_plane DROP FOREIGN KEY FK_B3E706AD8BA5E589');
        $this->addSql('DROP TABLE aircraft_classes');
        $this->addSql('DROP TABLE aircraft_class_plane');
    }
}
