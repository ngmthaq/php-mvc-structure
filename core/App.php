<?php

namespace Core;

use App\Helpers\Console;
use App\Helpers\Logger;

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

    final public function run(): mixed
    {
        try {
            if ($_ENV["APP_STATUS"] === "maintain") {
                $responseType = $this->req->headers("Response-Type");
                if (isset($responseType) && $responseType === "application/json") {
                    return response()->json([
                        "message" => "Service Unavailable",
                    ], Response::STATUS_SERVICE_UNAVAILABLE);
                } else {
                    Console::error("Service Unavailable");
                    http_response_code(Response::STATUS_SERVICE_UNAVAILABLE);
                    return view("errors._503");
                }
            } else {
                $method = $_SERVER["REQUEST_METHOD"];
                $uri = isLocalHost() ? str_replace(self::DIR_NAME, "", $_SERVER["REQUEST_URI"]) : $_SERVER["REQUEST_URI"];
                $uri = explode("?", $uri)[0];
                $routes = null;

                if (array_key_exists($method, $this->routes)) {
                    $routes = $this->routes[$method];
                } else {
                    return $this->detectErrorResponse($uri, $method);
                }

                if (isset($routes) && array_key_exists($uri, $routes)) {
                    $configs = $routes[$uri];
                    $controller = $configs["controller"];
                    $action = $configs["action"];
                    $instance = new $controller($this->req);
                    return $instance->$action();
                } else {
                    return $this->detectErrorResponse($uri, $method);
                }
            }
        } catch (\Throwable $th) {
            $errorContent = $th->getMessage() . PHP_EOL . "# " . $th->getFile() . "(" . $th->getLine() . ")";
            Logger::write("error", $errorContent);
            $responseType = $this->req->headers("Response-Type");
            if (isset($responseType) && $responseType === "application/json") {
                return response()->json([
                    "message" => "Server Internal Error",
                    "detail" => $_ENV["APP_ENV"] === "production" ? "" : $th->getMessage(),
                ], Response::STATUS_INTERNAL_SERVER_ERROR);
            } else {
                Console::error("Server Internal Error");
                if ($_ENV["APP_ENV"] !== "production") Console::error($th->getMessage());
                http_response_code(Response::STATUS_INTERNAL_SERVER_ERROR);
                return view("errors._500");
            }
        }
    }

    private function detectErrorResponse($uri, $method): mixed
    {
        $responseType = $this->req->headers("Response-Type");
        $anotherMethod = array_filter($this->routes, function ($m) use ($method) {
            return $m !== $method;
        }, ARRAY_FILTER_USE_KEY);

        if (count($anotherMethod) > 0) {
            $anotherRoutes = $this->routes[array_key_first($anotherMethod)];
            if (array_key_exists($uri, $anotherRoutes)) {
                return response()->json(["message" => "Method not allowed"], Response::STATUS_METHOD_NOT_ALLOWED);
            } else {
                if (isset($responseType) && $responseType === "application/json") {
                    return response()->json(["message" => "Not found"], Response::STATUS_NOT_FOUND);
                } else {
                    Console::error("Not Found");
                    http_response_code(Response::STATUS_NOT_FOUND);
                    return view("errors._404");
                }
            }
        } else {
            Console::error("Not Found");
            http_response_code(Response::STATUS_NOT_FOUND);
            return view("errors._404");
        }
    }
}
