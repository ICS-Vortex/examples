<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927083145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE servers DROP FOREIGN KEY FK_4F8AF5F73A51721D');
        $this->addSql('DROP INDEX IDX_4F8AF5F73A51721D ON servers');
        $this->addSql('ALTER TABLE servers DROP instance_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE servers ADD instance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE servers ADD CONSTRAINT FK_4F8AF5F73A51721D FOREIGN KEY (instance_id) REFERENCES instances (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4F8AF5F73A51721D ON servers (instance_id)');
    }
}
