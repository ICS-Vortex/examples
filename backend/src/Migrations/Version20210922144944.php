<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922144944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_class DROP FOREIGN KEY FK_B75388E846E2F5C');
        $this->addSql('DROP INDEX IDX_B75388E846E2F5C ON race_class');
        $this->addSql('ALTER TABLE race_class DROP aircraft_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE race_class ADD aircraft_id INT NOT NULL');
        $this->addSql('ALTER TABLE race_class ADD CONSTRAINT FK_B75388E846E2F5C FOREIGN KEY (aircraft_id) REFERENCES planes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B75388E846E2F5C ON race_class (aircraft_id)');
    }
}
