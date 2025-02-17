<?php
require_once '../db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        if ($email) {
            $stmt = $pdo->prepare("SELECT id, name, email, type FROM User WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            $stmt = $pdo->query("SELECT id, name, email, type FROM User");
        }
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO User (id, name, email, type, password) VALUES (UUID(), ?, ?, ?, SHA2(?, 256))");
        try {
            $stmt->execute([$data['name'], $data['email'], $data['type'], $data['password']]);
            echo json_encode(['message' => 'User created successfully', 'id' => $pdo->lastInsertId()]);
        } catch(PDOException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE User SET name = ?, type = ? WHERE email = ?");
        try {
            $stmt->execute([$data['name'], $data['type'], $data['email']]);
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
                return;
            }
            echo json_encode(['message' => 'User updated successfully']);
        } catch(PDOException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        if ($email) {
            $stmt = $pdo->prepare("DELETE FROM User WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
                return;
            }
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Email is required']);
        }
        break;
}
?> 