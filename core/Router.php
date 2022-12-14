<?php

namespace Core;

final class Router
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    final public function getRoutes(): array
    {
        return $this->routes;
    }

    final public function get(string $uri, string $controller, string $action): void
    {
        $this->routes["GET"][$uri] = ["controller" => $controller, "action" => $action];
    }

    final public function post(string $uri, string $controller, string $action): void
    {
        $this->routes["POST"][$uri] = ["controller" => $controller, "action" => $action];
    }
}
