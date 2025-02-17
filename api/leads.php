<?php
require_once '../db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        if ($email) {
            $stmt = $pdo->prepare("SELECT * FROM Leads WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            $stmt = $pdo->query("SELECT * FROM Leads");
        }
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO Leads (id, name, email, phone, industry, company, status) 
                              VALUES (UUID(), ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([
                $data['name'], 
                $data['email'], 
                $data['phone'],
                $data['industry'],
                $data['company'],
                $data['status'] ?? 'NEW'
            ]);
            echo json_encode(['message' => 'Lead created successfully', 'id' => $pdo->lastInsertId()]);
        } catch(PDOException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE Leads SET name = ?, phone = ?, 
                              industry = ?, company = ?, status = ? WHERE email = ?");
        try {
            $stmt->execute([
                $data['name'],
                $data['phone'],
                $data['industry'],
                $data['company'],
                $data['status'],
                $data['email']
            ]);
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Lead not found']);
                return;
            }
            echo json_encode(['message' => 'Lead updated successfully']);
        } catch(PDOException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        if ($email) {
            $stmt = $pdo->prepare("DELETE FROM Leads WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Lead not found']);
                return;
            }
            echo json_encode(['message' => 'Lead deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Email is required']);
        }
        break;
}
?> 