<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505165846 extends AbstractMigration
{
    public function isTransactional(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return 'Adding new table for logss';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE system_logs (id INT AUTO_INCREMENT NOT NULL, initiator VARCHAR(255) NOT NULL, type INT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME DEFAULT \'2000-01-01 00:00:00\' NOT NULL, updated_at DATETIME DEFAULT \'2000-01-01 00:00:00\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE system_logs');
    }
}
