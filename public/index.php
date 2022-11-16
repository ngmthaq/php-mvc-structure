<?php

session_start();

use Core\App;
use Core\Request;
use Dotenv\Dotenv;

require_once("../vendor/autoload.php");
require_once("../routes/index.php");

$dotenv = Dotenv::createImmutable("../");
$dotenv->load();
$request = new Request();
$app = new App($request, $router);
$app->run();
