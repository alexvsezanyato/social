<?php

namespace App\Services;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App {
    public static ?self $instance;

    public function __construct(
        public private(set) \Di\Container $container,
        public private(set) RouteCollection $routes = new RouteCollection(),
        public private(set) array $middlewares = [],
    ) {}

    public function handleRequest(Request $request): Response
    {
        $this->container->set(Request::class, $request);

        $uri = parse_url($request->getRequestUri());
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $parameters = $matcher->match($uri['path']);
        } catch (ResourceNotFoundException $e) {
            (new Response($e->getMessage(), Response::HTTP_NOT_FOUND))->send();
        }

        $request->attributes->set('parameters', $parameters);

        $handler = function (Request $request) use ($parameters) {
            [$controller, $action] = $parameters['_controller'];
            $response = $this->container->call([$this->container->make($controller), $action]);

            if ($response instanceof Response) {
                return $response;
            } else {
                throw new \Exception(sprintf(
                    '(%s::%s) Response must be %s, but %s is provided',
                    $controller,
                    $action,
                    Response::class, 
                    gettype($response),
                ));
            }
        };

        foreach ($this->middlewares as $middleware) {
            $instance = $this->container->make($middleware);
            $handler = fn (Request $request): Response => $instance->process($request, $handler);
        }

        /**
         * @var Response
         */
        $response = $handler($request);
        $response->send();

        return $response;
    }
}
