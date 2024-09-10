<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727025451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier.parcel ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE carrier.parcel ADD create_at TIMESTAMP(0) WITH TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN carrier.parcel.create_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrier.parcel DROP title');
        $this->addSql('ALTER TABLE carrier.parcel DROP create_at');
    }
}
