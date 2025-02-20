<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// You can still use getenv() if the URL is set as an environment variable
// Or use these credentials directly for testing
$server = "ryvdxs57afyjk41z.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$username = "mfwvnwktuf2uj8vt";
$password = "lm3klk78ejzg7qa7";
$db = "ylzbr43ulkp101ul";

$conn = new mysqli($server, $username, $password, $db);

try {
    $pdo = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die(json_encode([
        'error' => 'Connection failed',
        'message' => $e->getMessage()
    ]));
}
?> 