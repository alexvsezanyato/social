<?php

namespace App\Http\Controllers\Api;

use App\Repositories\PostRepository;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecommendedPostController
{
    public function __construct(
        private UserService $userService,
        private PostRepository $postRepository,
    ) {
    }

    public function index(Request $request)
    {
        $authorId = (int)$request->query->get('authorId', 0);
        $from = (int)$request->query->get('from', 0);
        $limit = (int)$request->query->get('limit', 1);

        if ($authorId === 0) {
            $authorId = $this->userService->getId();
        }

        $result = [];

        try {
            $posts = $this->postRepository->findRecommended(
                limit: $limit,
                from: $from,
            );
        } catch (\Exception $e) {
            return new Response(
                content: $e->getMessage(),
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        foreach ($posts as $post) {
            $result[$post->id] = [
                'id' => $post->id,
                'text' => $post->text,
                'author' => [
                    'id' => $post->author->id,
                    'login' => $post->author->login,
                    'age' => $post->author->age,
                    'public' => $post->author->public,
                ],
                'createdAt' => [
                    'date' => $post->createdAt->format('Y-m-d'),
                    'time' => $post->createdAt->format('H:i:s'),
                ],
                'pictures' => [],
                'documents' => [],
                'comments' => [],
            ];

            foreach ($post->documents as $document) {
                $result[$post->id]['documents'][] = [
                    'id' => $document->id,
                    'pid' => $document->post->id,
                    'name' => $document->name,
                    'mime' => $document->mime,
                    'source' => $document->source,
                ];
            }

            foreach ($post->pictures as $picture) {
                $result[$post->id]['pictures'][] = [
                    'id' => $picture->id,
                    'pid' => $picture->post->id,
                    'name' => $picture->name,
                    'mime' => $picture->mime,
                    'source' => $picture->source,
                ];
            }

            foreach ($post->comments as $comment) {
                $result[$post->id]['comments'][] = [
                    'id' => $comment->id,
                    'author' => [
                        'id' => $comment->author->id,
                        'login' => $post->author->login,
                        'age' => $post->author->age,
                        'public' => $comment->author->public,
                    ],
                    'text' => $comment->text,
                ];
            }
        }

        return new JsonResponse(array_values($result));
    }
}
