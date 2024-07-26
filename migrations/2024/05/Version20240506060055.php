<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506060055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание схемы таблицы с пользователями Телеграмма';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA telegram');
        $this->addSql('CREATE TABLE telegram."user" (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('telegram.user');
        $this->addSql('drop schema telegram');
    }
}
