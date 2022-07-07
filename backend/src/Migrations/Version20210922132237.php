<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922132237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race_class (id INT AUTO_INCREMENT NOT NULL, aircraft_id INT NOT NULL, title VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, INDEX IDX_B75388E846E2F5C (aircraft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE race_class ADD CONSTRAINT FK_B75388E846E2F5C FOREIGN KEY (aircraft_id) REFERENCES planes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE race_class');
    }
}
