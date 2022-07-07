<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921103135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC31844E6B7');
        $this->addSql('DROP INDEX IDX_E4BCFAC31844E6B7 ON tournaments');
        $this->addSql('ALTER TABLE tournaments DROP server_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournaments ADD server_id INT NOT NULL');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC31844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E4BCFAC31844E6B7 ON tournaments (server_id)');
    }
}
