<?php

namespace Core;

final class Router
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    final public function getRoutes()
    {
        return $this->routes;
    }

    final public function get(string $uri, string $controller, string $action)
    {
        $this->routes["GET"][$uri] = ["controller" => $controller, "action" => $action];
    }

    final public function post(string $uri, string $controller, string $action)
    {
        $this->routes["POST"][$uri] = ["controller" => $controller, "action" => $action];
    }
}
