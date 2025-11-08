<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Services\Database;
use App\Services\DocumentRepository;
use App\Services\PictureRepository;
use App\Services\PostRepository;
use App\Services\User;
use App\Services\UserRepository;

return [
    Request::class            => Request::createFromGlobals(),
    SessionInterface::class   => DI\autowire(Session::class),

    Database::class           => DI\autowire(Database::class)->constructor(connect()),
    User::class               => DI\autowire(User::class),

    DocumentRepository::class => DI\autowire(DocumentRepository::class),
    PictureRepository::class  => DI\autowire(PictureRepository::class),
    PostRepository::class     => DI\autowire(PostRepository::class),
    UserRepository::class     => DI\autowire(UserRepository::class),

    Connection::class => function() {
        $config = require CONFIG_DIR.'/db.php';
        $driver = new (DriverManager::DRIVER_MAP[$config['driver']])();
        return new Connection($config['connection'], $driver);
    },

    \Twig\Environment::class => function(\DI\Container $container) {
        $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(VIEW_DIR), [
            # 'cache' => CACHE_DIR.'/twig',
        ]);

        $twig->addExtension($container->make(\App\Twig\Extensions\AppExtension::class));
        return $twig;
    },
];