<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

$parts = explode("/", $_SERVER['REQUEST_URI']);

$service = $parts[2];

$database = new Database(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$user_gateway = new UserGateway($database);

$codec = new JWTCodec($_ENV['SECRET_KEY']);

$auth = new Auth($user_gateway, $codec);

if(!$auth->authenticateAccessToken()){
    exit;
}

//$user_id = $auth->getUserID();

$reception_gateway = new ReceptionGateway($database);

$reception_controller = new ReceptionController($reception_gateway);

$reception_controller->processRequest($_SERVER['REQUEST_METHOD'], $service);

exit; 