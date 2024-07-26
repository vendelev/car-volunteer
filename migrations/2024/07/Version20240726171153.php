<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726171153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA parcel');
        $this->addSql('CREATE TABLE parcel.parcel (id UUID NOT NULL, author_id VARCHAR(20) NOT NULL, status VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN parcel.parcel.id IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE parcel.parcel');
        $this->addSql('DROP SCHEMA parcel');
    }
}
