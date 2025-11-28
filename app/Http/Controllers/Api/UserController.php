<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserService $userService,
        private UserRepository $userRepository,
    ) {
    }

    public function index()
    {
        $result = [];

        foreach ($this->userRepository->findAll() as $user) {
            $result[] = [
                'id' => $user->id,
                'login' => $user->login,
                'age' => $user->age,
                'public' => $user->public,
            ];
        }

        return new JsonResponse($result);
    }

    public function show(int $id = 0)
    {
        if ($id === 0) {
            $id = $this->userService->getId();
        }

        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ($user === null) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $user->id,
            'login' => $user->login,
            'age' => $user->age,
            'public' => $user->public,
        ]);
    }

    public function patch(Request $request, int $id = 0)
    {
        $data = $request->toArray();

        if ($id === 0) {
            $id = $this->userService->getId();
        }

        if ($id !== $this->userService->getId()) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $public = $data['public'];

        if (!preg_match('/^[0-9a-zA-Z\ ]{3,20}$/', $public)) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->find($id);
        $user->public = $public;
        $this->entityManager->flush();

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
