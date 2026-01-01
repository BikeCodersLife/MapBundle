<?php

declare(strict_types=1);

namespace BikeCoders\MapBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260101120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add metadata fields to map_source';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE map_source ADD min_zoom INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE map_source ADD max_zoom INT DEFAULT 15 NOT NULL');
        $this->addSql('ALTER TABLE map_source ADD bounds JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE map_source DROP min_zoom');
        $this->addSql('ALTER TABLE map_source DROP max_zoom');
        $this->addSql('ALTER TABLE map_source DROP bounds');
    }
}
