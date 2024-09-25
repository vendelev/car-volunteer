<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use CarVolunteer\Component\Photo\Domain\PhotoType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925075933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Новая таблица photo.photo';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA photo');
        $this->addSql('CREATE TABLE photo.photo (
                id UUID NOT NULL, 
                object_id UUID NOT NULL, 
                photo_id VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
                PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN photo.photo.id 
            IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
        $this->addSql('COMMENT ON COLUMN photo.photo.object_id 
            IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
        $this->addSql('COMMENT ON COLUMN photo.photo.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE INDEX object_idx ON photo.photo (object_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE photo.photo');
        $this->addSql('DROP SCHEMA photo');
    }
}
