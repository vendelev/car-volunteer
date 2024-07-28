<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240728192548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carrier.packing (id UUID NOT NULL, parcel_id UUID NOT NULL, picker_id VARCHAR(20) NOT NULL, status VARCHAR(255) NOT NULL, create_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id, parcel_id))');
        $this->addSql('COMMENT ON COLUMN carrier.packing.id IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
        $this->addSql('COMMENT ON COLUMN carrier.packing.parcel_id IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
        $this->addSql('COMMENT ON COLUMN carrier.packing.create_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE carrier.packing');
    }
}
