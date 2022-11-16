<?php

use Core\App;
use Core\Response;
use Core\View;

function response(): Response
{
    return new Response();
}

function view(string $template, array $data = [], array $mergeData = []): mixed
{
    $view = new View();
    return $view->render($template, $data, $mergeData);
}

function unsetFlashSession($key): void
{
    $view = new View();
    $view->unsetFlashSession($key);
}

function assets($path): string
{
    return isLocalHost() ? "./public/" . $path . "?v=" . time() : "./" . $path . "?v=" . time();
}

function isLocalHost()
{
    return $_SERVER["HTTP_HOST"] === "localhost" || $_SERVER["HTTP_HOST"] === "127.0.0.1" ? true : false;
}
