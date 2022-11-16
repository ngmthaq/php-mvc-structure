<?php

use App\Controllers\WelcomeController;
use Core\Router;

$router = new Router();

/***************** WEB ROUTES WITH GET METHOD *****************/
$router->get("/", WelcomeController::class, "index");

/***************** WEB ROUTES WITH POST METHOD *****************/

/***************** API ROUTES WITH GET METHOD *****************/

/***************** API ROUTES WITH POST METHOD *****************/
