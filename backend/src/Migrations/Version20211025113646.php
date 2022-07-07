<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025113646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_coupon_requests (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, server_id INT DEFAULT NULL, pilot_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME DEFAULT \'2000-01-01 00:00:00\' NOT NULL, updated_at DATETIME DEFAULT \'2000-01-01 00:00:00\' NOT NULL, INDEX IDX_8A0B0E0133D1A3E7 (tournament_id), INDEX IDX_8A0B0E011844E6B7 (server_id), INDEX IDX_8A0B0E01CE55439B (pilot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_coupon_requests ADD CONSTRAINT FK_8A0B0E0133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE tournament_coupon_requests ADD CONSTRAINT FK_8A0B0E011844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id)');
        $this->addSql('ALTER TABLE tournament_coupon_requests ADD CONSTRAINT FK_8A0B0E01CE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournament_coupon_requests');
    }
}
