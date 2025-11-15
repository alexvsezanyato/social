<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114143547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ALTER pid TYPE INT USING pid::integer');
        $this->addSql('ALTER TABLE document ALTER source TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE document ALTER mime TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE document ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE picture ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE picture ALTER source TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE picture ALTER mime TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE post ALTER text TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER login TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER hash TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER salt TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER random TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER public TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER public SET DEFAULT \'\'');
        $this->addSql('UPDATE "user" SET public = \'\' WHERE public IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER public SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "document" ALTER pid TYPE TEXT');
        $this->addSql('ALTER TABLE "document" ALTER source TYPE TEXT');
        $this->addSql('ALTER TABLE "document" ALTER mime TYPE TEXT');
        $this->addSql('ALTER TABLE "document" ALTER name TYPE TEXT');
        $this->addSql('ALTER TABLE "picture" ALTER source TYPE TEXT');
        $this->addSql('ALTER TABLE "picture" ALTER mime TYPE TEXT');
        $this->addSql('ALTER TABLE "picture" ALTER name TYPE TEXT');
        $this->addSql('ALTER TABLE "post" ALTER text TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER login TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER hash TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER salt TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER random TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER public TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER public DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER public DROP NOT NULL');
    }
}
