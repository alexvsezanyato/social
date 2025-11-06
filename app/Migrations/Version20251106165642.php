<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251106165642 extends AbstractMigration
{
    const TABLE_NAME = 'user';

    public function getDescription(): string
    {
        return 'Create table "'.self::TABLE_NAME.'"';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('login', 'text');
        $table->addColumn('age', 'integer');
        $table->addColumn('hash', 'text');
        $table->addColumn('salt', 'text');
        $table->addColumn('random', 'text', ['default' => '']);
        $table->addColumn('public', 'text', ['notnull' => false]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}
