<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240728201002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier.parcel ADD packing_id UUID');
        $this->addSql('ALTER TABLE carrier.parcel ADD delivery_id UUID');
        $this->addSql('COMMENT ON COLUMN carrier.parcel.packing_id IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
        $this->addSql('COMMENT ON COLUMN carrier.parcel.delivery_id IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier.parcel DROP packing_id');
        $this->addSql('ALTER TABLE carrier.parcel DROP delivery_id');
    }
}
