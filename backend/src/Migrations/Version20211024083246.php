<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211024083246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pilots ADD region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pilots ADD CONSTRAINT FK_9EE6E18C98260155 FOREIGN KEY (region_id) REFERENCES location_regions (id)');
        $this->addSql('CREATE INDEX IDX_9EE6E18C98260155 ON pilots (region_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pilots DROP FOREIGN KEY FK_9EE6E18C98260155');
        $this->addSql('DROP INDEX IDX_9EE6E18C98260155 ON pilots');
        $this->addSql('ALTER TABLE pilots DROP region_id');
    }
}
