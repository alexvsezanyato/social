<?php

namespace App\Middlewares;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use App\Support\Paths;

class RoutingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Paths $paths,
    ) {
    }

    public function process(Request $request, callable $handler): Response
    {
        $routes = new RouteCollection();
        require $this->paths->route . '/web.php';

        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($routes, $context);

        $parameters = $matcher->match($request->getPathInfo());
        $request->attributes->set('parameters', $parameters);

        return $handler($request);
    }
}
