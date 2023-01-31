<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$data = $_POST;

if ( ! array_key_exists("username", $data) ||
     ! array_key_exists("password", $data)) {

    http_response_code(400);
    echo json_encode(["message" => "missing login credentials"]);
    exit;
}

$database = new Database($_ENV["DB_HOST"],
                         $_ENV["DB_NAME"],
                         $_ENV["DB_USER"],
                         $_ENV["DB_PASS"]);
                         
$user_gateway = new UserGateway($database);

$user = $user_gateway->getByUsername($data["username"]);

if ($user === false) {
    
    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

if ( ! password_verify($data["password"], $user["password_hash"])) {
    
    http_response_code(401);
    echo json_encode(["message" => "invalid authentication"]);
    exit;
}

$payload = [
    "sub" => $user["id"],
    "name" => $user["name"]
];

$codec = new JWTCodec($_ENV["SECRET_KEY"]);
$access_token = $codec->encode($payload);

echo json_encode([
    "access_token" => $access_token
]);











