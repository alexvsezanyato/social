<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106195959 extends AbstractMigration
{
    const TABLE_NAME = 'post';
    const COLUMN_NAME = 'created_at';

    public function getDescription(): string
    {
        return 'Add column "'.self::COLUMN_NAME.'" to table "'.self::TABLE_NAME.'"';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable(self::TABLE_NAME);
        $table->addColumn(self::COLUMN_NAME,'datetime', ['default' => 'CURRENT_TIMESTAMP']);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable(self::TABLE_NAME);
        $table->dropColumn(self::COLUMN_NAME);
    }
}
