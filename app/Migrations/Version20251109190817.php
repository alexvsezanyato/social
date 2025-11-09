<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251109190817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set CURRENT_TIMESTAMP as the default value of the created_at column';
    }

    public function up(Schema $schema): void
    {
        $schema->getTable('`post`')->getColumn('created_at')->setDefault('CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('`post`')->getColumn('created_at')->setDefault(null);
    }
}
