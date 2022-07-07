<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921103450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_server (tournament_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_3EC72BA633D1A3E7 (tournament_id), INDEX IDX_3EC72BA61844E6B7 (server_id), PRIMARY KEY(tournament_id, server_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_server ADD CONSTRAINT FK_3EC72BA633D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_server ADD CONSTRAINT FK_3EC72BA61844E6B7 FOREIGN KEY (server_id) REFERENCES servers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournament_server');
    }
}
