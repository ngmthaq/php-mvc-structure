<?php

use Core\Response;
use Core\View;

function response()
{
    return new Response();
}

function view(string $template, array $data = [], array $mergeData = [])
{
    $view = new View();
    return $view->render($template, $data, $mergeData);
}

function unsetFlashSession($key)
{
    $view = new View();
    $view->unsetFlashSession($key);
}

function assets($path)
{
    return "./" . $path . "?v=" . time();
}
