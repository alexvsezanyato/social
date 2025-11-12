<?php

namespace App\Middlewares;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceNotFoundHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function process(Request $request, callable $handler): Response
    {
        try {
            return $handler($request);
        } catch (ResourceNotFoundException $e) {
            $this->logger->error((string)$e);
            return new Response($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
