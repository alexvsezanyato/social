<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\UserRepository;

class AuthController
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionInterface $session,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function login(Request $request)
    {
        $data = $request->toArray();

        $login = $data['login'];
        $password = $data['password'];

        $user = $this->userRepository->findOneBy([
            'login' => $login,
        ]);

        if (!$user) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $salt = $user->salt;
        $hash = hash('sha512', $password . $salt);

        if ($hash !== $user->hash) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $this->session->set('id', $user->id);
        $userAgent = $request->server->get('HTTP_USER_AGENT', 'ua');
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

                return new Response(status: Response::HTTP_BAD_REQUEST);
            }
        }

        $response = new Response(status: Response::HTTP_NO_CONTENT);

        $response->headers->setCookie(new Cookie(
            name: 'pid',
            value: $cookie,
            expire: time() + 60 * 60 * 24 * 365,
            secure: false,
        ));

        return $response;
    }

    public function register(Request $request)
    {
        $data = $request->toArray();

        $login = trim($data['login']);
        $password = $data['password'];
        $prepeat = $data['prepeat'];
        $age = trim($data['age']);

        if (strlen($login) === 0) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        if ($password != $prepeat) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        if (strlen($password) === 0) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        if (!is_numeric($age) || (int)$age < 14) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $salt = base64_encode(random_bytes((int)floor(32 * 0.66)));
        $hash = hash('sha512', $password . $salt);

        $count = $this->userRepository->count([
            'login' => $login,
        ]);

        if ($count) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->login = $login;
        $user->age = (int)$age;
        $user->hash = $hash;
        $user->salt = $salt;
        $user->random = rand();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
