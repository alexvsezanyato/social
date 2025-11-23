<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserService $userService,
    ) {
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
}
