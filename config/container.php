<?php

declare(strict_types=1);

use App\Entities\PostComment;
use App\Helpers\Env;
use App\Helpers\ViewInterface;
use App\Helpers\TwigView;
use App\Repositories\PostCommentRepository;
use App\Services\Vite;
use App\Support\Paths;

use App\Entities\Document;
use App\Entities\Picture;
use App\Entities\Post;
use App\Entities\User;

use App\Services\UserService;

use App\Repositories\UserRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\PictureRepository;
use App\Repositories\PostRepository;

use DI\Container;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Configuration as ORMConfiguration;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Psr\Log\LoggerInterface;

return [
    'config' => fn (Paths $paths) => require $paths->base.'/config/app.php',

    SessionInterface::class      => DI\autowire(Session::class),
    UserService::class           => DI\autowire(UserService::class),

    UserRepository::class        => fn (EntityManagerInterface $em) => $em->getRepository(User::class),
    PostRepository::class        => fn (EntityManagerInterface $em) => $em->getRepository(Post::class),
    DocumentRepository::class    => fn (EntityManagerInterface $em) => $em->getRepository(Document::class),
    PictureRepository::class     => fn (EntityManagerInterface $em) => $em->getRepository(Picture::class),
    PostCommentRepository::class => fn (EntityManagerInterface $em) => $em->getRepository(PostComment::class),

    ViewInterface::class => function (Container $container, Paths $paths): TwigView {
        $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader($paths->view), [
            'cache' => (bool)Env::get('DEBUG_MODE', false) ? false : $paths->cache.'/twig',
        ]);

        $twig->addExtension($container->make(\App\Twig\Extensions\AppExtension::class));

        return $container->make(TwigView::class, [
            'twig' => $twig,
        ]);
    },

    LoggerInterface::class => function(Paths $paths): Logger {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler($paths->log.'/app.log', Logger::DEBUG));
        return $logger;
    },

    EntityManagerInterface::class => function(Connection $connection, Paths $paths): EntityManagerInterface {
        $config = new ORMConfiguration();
        $config->setMetadataDriverImpl(new AttributeDriver([$paths->app.'/Entities']));
        $config->setProxyDir($paths->cache.'/doctrine/proxy');
        $config->setProxyNamespace('App\\Cache\\Doctrine\\Proxy');
        return new EntityManager($connection, $config);
    },

    Connection::class => function(Container $container): Connection {
        return DriverManager::getConnection($container->get('config')['database']['connection']);
    },

    Vite::class => function(Paths $paths): Vite {
        $manifest = json_decode(file_get_contents($paths->public.'/.vite/manifest.json'), true);
        return new Vite($manifest);
    },
];