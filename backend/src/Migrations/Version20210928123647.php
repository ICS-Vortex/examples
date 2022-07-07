<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928123647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_coupons (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, pilot_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_9E6BEA1233D1A3E7 (tournament_id), INDEX IDX_9E6BEA12CE55439B (pilot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_coupons ADD CONSTRAINT FK_9E6BEA1233D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE tournament_coupons ADD CONSTRAINT FK_9E6BEA12CE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournament_coupons');
    }
}
