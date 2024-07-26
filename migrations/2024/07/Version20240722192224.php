<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240722192224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы с "разговорами"';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA conversation');
        $this->addSql('CREATE TABLE conversation.conversation (id UUID NOT NULL, user_id VARCHAR(20) NOT NULL, action_route VARCHAR(255) NOT NULL, play_load JSON, PRIMARY KEY(id))');
        $this->addSql('create index last_conversation on conversation.conversation (user_id asc, id desc)');
        $this->addSql('COMMENT ON COLUMN conversation.conversation.id IS \'(DC2Type:HardcorePhp\\Infrastructure\\Uuid\\DoctrineDBAL\\UuidType)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE conversation.conversation');
        $this->addSql('DROP SCHEMA conversation');
    }
}
