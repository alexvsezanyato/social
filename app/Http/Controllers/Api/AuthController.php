<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\UserRepository;

class AuthController
{
    public function __construct(
        private Request $request,
        private UserRepository $userRepository,
        private SessionInterface $session,
        private Connection $connection,
        private EntityManagerInterface $entityManager,
    ) {}

    public function login()
    {
        $login = $this->request->request->get('login');
        $password = $this->request->request->get('password');

        $user = $this->userRepository->findOneBy([
            'login' => $login,
        ]);

        if (!$user) {
            return new Response(content: 1);
        }

        $salt = $user->salt;
        $hash = hash('sha512', $password . $salt);

        if ($hash !== $user->hash) {
            return new Response(content: 1);
        }

        $this->session->set('id', $user->id);
        $userAgent = $this->request->server->get('HTTP_USER_AGENT', 'ua');
        $this->session->set('hash', hash('md5', $userAgent));

        $random = $user->random ?? base64_encode(random_bytes(256 * 0.6666));
        $id = $user->id;
        $cookie = $id . '-' . hash('sha256', $id . $random);

        if ($user->random === null) {
            $user->random = $random;

            $this->entityManager->persist($user);
            
            try {
                $this->entityManager->flush();
            } catch (\Exception $e) {
                $this->session->remove('id');
                $this->session->remove('hash');

                return new Response(content: 2);
            }
        }

        $response = new Response(content: 0);

        $response->headers->setCookie(new Cookie(
            name: 'pid',
            value: $cookie,
            expire: time() + 60*60*24*365,
            secure: false,
        ));

        return $response;
    }

    public function register()
    {
        $login = trim($this->request->request->get('login', ''));
        $password = trim($this->request->request->get('password', ''));
        $prepeat = trim($this->request->request->get('prepeat', ''));
        $age = trim($this->request->request->get('age', ''));

        if (strlen($login) < 3) {
            return new Response(content: 1);
        }

        if ($password != $prepeat) {
            return new Response(content: 2);
        }

        if (strlen($password) < 6) { 
            return new Response(content: 3);
        }

        if (!is_numeric($age) || (int)$age < 14) {
            return new Response(content: 4);
        }

        $salt = base64_encode(random_bytes((int)floor(32*0.66)));
        $hash = hash('sha512', $password . $salt);

        $count = $this->userRepository->count([
            'login' => $login,
        ]);

        if ($count) { 
            return new Response(content: 5);
        }

        $user = new User();
        $user->login = $login;
        $user->age = (int)$age;
        $user->hash = $hash;
        $user->salt = $salt;
        $user->random = rand();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response(content: 0);
    }
}
