<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920161325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournaments (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, title VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, description_en LONGTEXT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, finished TINYINT(1) NOT NULL, INDEX IDX_E4BCFAC31844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC31844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournaments');
    }
}
