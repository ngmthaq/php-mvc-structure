<?php

session_start();

use Core\App;
use Core\Request;

require_once("../vendor/autoload.php");
require_once("../routes/index.php");

$request = new Request();
$app = new App($request, $router);
$app->run();
