<?php

namespace Core;

use App\Helpers\Console;

final class App
{
    public const DIR_NAME = "/php-mvc";

    private Request $req;
    private array $routes;

    public function __construct(Request $request, Router $router)
    {
        $this->req = $request;
        $this->routes = $router->getRoutes();
    }

    final public function run(): void
    {
        try {
            $method = $_SERVER["REQUEST_METHOD"];
            $uri = isLocalHost() ? str_replace(self::DIR_NAME, "", $_SERVER["REQUEST_URI"]) : $_SERVER["REQUEST_URI"];
            $uri = explode("?", $uri)[0];
            $routes = null;

            if (array_key_exists($method, $this->routes)) {
                $routes = $this->routes[$method];
            } else {
                $this->detectErrorResponse($uri, $method);
                return;
            }

            if (isset($routes) && array_key_exists($uri, $routes)) {
                $configs = $routes[$uri];
                $controller = $configs["controller"];
                $action = $configs["action"];
                $instance = new $controller($this->req);
                $instance->$action();
            } else {
                $this->detectErrorResponse($uri, $method);
                return;
            }
        } catch (\Throwable $th) {
            Console::error("SERVER INTERNAL ERROR");
            Console::error($th->getMessage());
        }
    }

    private function detectErrorResponse($uri, $method): void
    {
        $anotherMethod = array_filter($this->routes, function ($m) use ($method) {
            return $m !== $method;
        }, ARRAY_FILTER_USE_KEY);

        if (count($anotherMethod) > 0) {
            $anotherRoutes = $this->routes[array_key_first($anotherMethod)];
            if (array_key_exists($uri, $anotherRoutes)) {
                Console::error("METHOD NOT ALLOWED");
            } else {
                Console::error("NOT FOUND");
            }
        } else {
            Console::error("NOT FOUND");
        }
    }
}
