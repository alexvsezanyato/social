<?php

namespace App\Services;

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Middlewares\MiddlewareInterface;

class App {
    public static ?self $instance;

    /**
     * @param class-string<MiddlewareInterface>[] $middlewares
     */
    public function __construct(
        public private(set) Container $container,
        public private(set) array $middlewares = [],
    ) {}

    public function handleRequest(Request $request): Response
    {
        $this->container->set(Request::class, $request);

        $handler = function (Request $request): Response {
            $parameters = $request->attributes->get('parameters');
            [$controller, $action] = $parameters['_controller'];
            return $this->container->call([$this->container->make($controller), $action]);
        };

        foreach (array_reverse($this->middlewares) as $middleware) {
            $instance = $this->container->make($middleware);
            $handler = fn (Request $request): Response => $instance->process($request, $handler);
        }

        return $handler($request);
    }
}
