<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015073930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coupons_files (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, source VARCHAR(255) NOT NULL, uploaded TINYINT(1) NOT NULL, INDEX IDX_3943436E33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coupons_files ADD CONSTRAINT FK_3943436E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coupons_files');
    }
}
