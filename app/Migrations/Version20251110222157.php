<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251110222157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            <<<SQL
            INSERT INTO "user"("login", "age", "hash", "salt", "random", "public")
            VALUES (:login, :age, :hash, :salt, :random, :public)
            SQL,
            params: [
                'login' => 'test1057243321',
                'age' => 20,
                'hash' => '43f89904098cbada78eaec88d4c3619bfe40772c32d7e1fe2a10bf7761a87a5f07a6c34023af75312a53d2a29812d70f6ae3b93540468a19b427cd6658ab3ac0',
                'salt' => 'X2EnUUa8CnMv4TiXuzQjjq9RS/kv',
                'random' => '1923911067',
                'public' => 'test',
            ],
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            <<<SQL
            DELETE FROM `user` WHERE `login`=:login
            SQL,
            params: [
                'login' => 'test1057243321',
            ],
        );
    }
}
