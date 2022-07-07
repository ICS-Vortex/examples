<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927150309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faqs ADD tournament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE faqs ADD CONSTRAINT FK_8934BEE533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('CREATE INDEX IDX_8934BEE533D1A3E7 ON faqs (tournament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faqs DROP FOREIGN KEY FK_8934BEE533D1A3E7');
        $this->addSql('DROP INDEX IDX_8934BEE533D1A3E7 ON faqs');
        $this->addSql('ALTER TABLE faqs DROP tournament_id');
    }
}
