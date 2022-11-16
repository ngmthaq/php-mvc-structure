<?php

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
    return "./" . $path . "?v=" . time();
}
