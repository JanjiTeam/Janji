<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123134334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, calendar_id INT NOT NULL, start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AC0E2067A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE event ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES event_type (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7C54C8C93 ON event (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP INDEX IDX_3BAE0AA7C54C8C93 ON event');
        $this->addSql('ALTER TABLE event DROP type_id');
    }
}
