<?php

namespace App\Middlewares;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function process(Request $request, callable $handler): Response
    {
        try {
            return $handler($request);
        } catch (\Throwable $e) {
            $this->logger->error($e);
            return new Response($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}