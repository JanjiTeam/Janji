<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123153113 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_type ADD calendar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_type ADD CONSTRAINT FK_93151B82A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('CREATE INDEX IDX_93151B82A40A2C8 ON event_type (calendar_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_type DROP FOREIGN KEY FK_93151B82A40A2C8');
        $this->addSql('DROP INDEX IDX_93151B82A40A2C8 ON event_type');
        $this->addSql('ALTER TABLE event_type DROP calendar_id');
    }
}
