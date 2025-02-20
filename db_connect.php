<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get database URL from environment variable
$db_url = getenv("JAWSDB_URL") ?: getenv("CLEARDB_DATABASE_URL");

$url = parse_url($db_url);

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

try {
    // Create connection
    $conn = new mysqli($server, $username, $password, $db);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }

    // Create PDO connection
    $pdo = new PDO("mysql:host=$server;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(Exception $e) {
    die(json_encode([
        'error' => 'Connection failed',
        'message' => $e->getMessage()
    ]));
}
?> 