<?php

namespace Core;

use App\Helpers\Console;

final class App
{
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
            $uri = explode("?", $_SERVER["REQUEST_URI"])[0];
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
            Console::log("SERVER INTERNAL ERROR");
            Console::log($th->getMessage());
        }
    }

    private function detectErrorResponse($uri, $method)
    {
        $anotherMethod = array_filter($this->routes, function ($m) use ($method) {
            return $m !== $method;
        }, ARRAY_FILTER_USE_KEY);

        if (count($anotherMethod) > 0) {
            Console::log($anotherMethod);
            $anotherRoutes = $this->routes[array_key_first($anotherMethod)];
            if (array_key_exists($uri, $anotherRoutes)) {
                Console::log("METHOD NOT ALLOWED");
            } else {
                Console::log("NOT FOUND");
            }
        } else {
            Console::log("NOT FOUND");
        }
    }
}
