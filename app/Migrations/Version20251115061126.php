<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115061126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_comment ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE post_comment ALTER text TYPE VARCHAR(2000)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "post_comment" DROP author_id');
        $this->addSql('ALTER TABLE "post_comment" ALTER text TYPE VARCHAR(255)');
    }
}
