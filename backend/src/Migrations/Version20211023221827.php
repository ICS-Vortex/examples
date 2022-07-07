<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211023221827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ucid_tokens (id INT AUTO_INCREMENT NOT NULL, pilot_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires BIGINT NOT NULL, created_at DATETIME DEFAULT \'2000-01-01 00:00:00\' NOT NULL, updated_at DATETIME DEFAULT \'2000-01-01 00:00:00\' NOT NULL, INDEX IDX_B44640E3CE55439B (pilot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ucid_tokens ADD CONSTRAINT FK_B44640E3CE55439B FOREIGN KEY (pilot_id) REFERENCES pilots (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ucid_tokens');
    }
}
