<?php

namespace App\Services;

use DI\Container;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
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
        public private(set) RouteCollection $routes = new RouteCollection(),
        public private(set) array $middlewares = [],
    ) {}

    public function handleRequest(Request $request): Response
    {
        $this->container->set(Request::class, $request);

        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);

        $parameters = $matcher->match($request->getPathInfo());
        $request->attributes->set('parameters', $parameters);

        $handler = function (Request $request) use ($parameters): Response {
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
