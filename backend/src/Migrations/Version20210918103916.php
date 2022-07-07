<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210918103916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE races ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9C54C8C93 FOREIGN KEY (type_id) REFERENCES race_types (id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC9C54C8C93 ON races (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9C54C8C93');
        $this->addSql('DROP INDEX IDX_5DBD1EC9C54C8C93 ON races');
        $this->addSql('ALTER TABLE races DROP type_id');
    }
}
