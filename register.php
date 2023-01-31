<?php

require __DIR__ . "/vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $database = new Database($_ENV["DB_HOST"],
                             $_ENV["DB_NAME"],
                             $_ENV["DB_USER"],
                             $_ENV["DB_PASS"]);
                             
    $conn = $database->getConnection();
    
    $sql = "INSERT INTO user (name, username, password_hash)
            VALUES (:name, :username, :password_hash)";
            
    $stmt = $conn->prepare($sql);
    
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
    $stmt->bindValue(":name", $_POST["name"], PDO::PARAM_STR);
    $stmt->bindValue(":username", $_POST["username"], PDO::PARAM_STR);
    $stmt->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);
    
    $stmt->execute();
    
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
</head>
<body>
    
    <main class="container">
    
        <h1>Register</h1>
        
        <form method="post">
            
            <label for="name">
                Name
                <input name="name" id="name">
            </label>
            
            <label for="username">
                Username
                <input name="username" id="username">
            </label>
            
            <label for="password">
                Password
                <input type="password" name="password" id="password">
            </label>
            
            <button>Register</button>
        </form>
    
    </main>
    
</body>
</html>