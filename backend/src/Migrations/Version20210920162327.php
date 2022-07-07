<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920162327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournaments_requests (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, aircraft_id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, code VARCHAR(6) NOT NULL, squad VARCHAR(255) DEFAULT NULL, squad_image LONGTEXT DEFAULT NULL, desired_tail_number NUMERIC(2, 0) DEFAULT NULL, INDEX IDX_D0A4D87E33D1A3E7 (tournament_id), INDEX IDX_D0A4D87E846E2F5C (aircraft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournaments_requests ADD CONSTRAINT FK_D0A4D87E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE tournaments_requests ADD CONSTRAINT FK_D0A4D87E846E2F5C FOREIGN KEY (aircraft_id) REFERENCES planes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournaments_requests');
    }
}
