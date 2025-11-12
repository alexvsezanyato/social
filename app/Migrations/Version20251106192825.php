<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106192825 extends AbstractMigration
{
    const TABLE_NAME = 'document';

    public function getDescription(): string
    {
        return 'Create table "' . self::TABLE_NAME . '"';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('pid', 'text');
        $table->addColumn('source', 'text');
        $table->addColumn('mime', 'text');
        $table->addColumn('name', 'text');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}
