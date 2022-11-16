<?php

use Dotenv\Dotenv;

require_once("./vendor/autoload.php");

$env = Dotenv::createImmutable(str_replace("\\utils", "", __DIR__));
$env->load();

$host = $_ENV["DB_HOST"];
$port = $_ENV["DB_PORT"];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV["DB_PASSWORD"];
$dbName = $_ENV["DB_NAME"];

$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);

$sql = "SELECT * FROM users";

$stmt = $conn->prepare($sql);
$stmt->execute();

print_r("Migrate successfully");
