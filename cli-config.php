<?php

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Dotenv\Dotenv;

define('BASE_DIR', __DIR__);
require BASE_DIR.'/vendor/autoload.php';
Dotenv::createImmutable(BASE_DIR)->safeLoad();

$app = require BASE_DIR.'/bootstrap/app.php';

return DependencyFactory::fromEntityManager(
    new PhpFile(BASE_DIR.'/migrations.php'),
    new ExistingEntityManager($app->container->get(EntityManagerInterface::class)),
);