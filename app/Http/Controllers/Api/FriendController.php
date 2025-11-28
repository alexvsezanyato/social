<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FriendController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserService $userService,
    ) {
    }

    public function index(int $userId)
    {
        $result = [];

        foreach ($this->userRepository->find($userId)->friends as $user) {
            $result[] = [
                'id' => $user->id,
                'login' => $user->login,
                'age' => $user->age,
                'public' => $user->public,
            ];
        }

        return new JsonResponse($result);
    }

    public function create(int $userId, int $friendId)
    {
        if ($userId === 0) {
            $userId = $this->userService->getId();
        }

        $user = $this->userRepository->find($userId);
        $friend = $this->userRepository->find($friendId);
        $user->friends->add($friend);

        try {
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            return new Response(content: $e, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(status: Response::HTTP_CREATED);
    }

    public function delete(int $userId, int $friendId)
    {
        $user = $this->userRepository->find($userId);
        $friend = $this->userRepository->find($friendId);

        $user->friends->removeElement($friend);

        try {
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            return new Response(content: $e, status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    public function suggestions(int $userId)
    {
        $result = [];

        foreach ($this->userRepository->findSuggestedFriends($userId) as $user) {
            $result[] = [
                'id' => $user->id,
                'login' => $user->login,
                'age' => $user->age,
                'public' => $user->public,
            ];
        }

        return new JsonResponse($result);
    }
}
