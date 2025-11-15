<?php

namespace App\Http\Controllers\Api;

use App\Entities\User;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;

class ProfileController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserService $userService,
    ) {
    }

    public function apply(Request $request)
    {
        if (!$this->userService->isAuthenticated()) {
            return new Response(content: 1);
        }

        $public = $request->request->get('public');

        if (!preg_match('/^[0-9a-zA-Z\ ]{3,20}$/', $public)) {
            return new Response(content: 2);
        }

        $user = $this->userService->getCurrentUser();
        $user->public = $public;
        $this->entityManager->flush();

        return new Response(content: 0);
    }

    public function get(Request $request)
    {
        $id = (int)$request->query->get('id', $this->userService->getId());

        if ($id === null) {
            throw new \Exception('User not found', 404);
        }

        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ($user === null) {
            throw new \Exception('User not found', 404);
        }

        return new JsonResponse([
            'id' => $user->id,
            'public' => $user->public,
        ]);
    }
}
