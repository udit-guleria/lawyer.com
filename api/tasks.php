<?php
header('Content-Type: application/json');
require_once '../db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $stmt = $pdo->prepare("
                SELECT t.*, GROUP_CONCAT(ta.user_id) as assignees 
                FROM Task t 
                LEFT JOIN Task_Assignees ta ON t.id = ta.task_id 
                WHERE t.id = ?
                GROUP BY t.id
            ");
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->query("
                SELECT t.*, GROUP_CONCAT(ta.user_id) as assignees 
                FROM Task t 
                LEFT JOIN Task_Assignees ta ON t.id = ta.task_id 
                GROUP BY t.id
            ");
        }
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Add debug logging
        error_log('Received JSON: ' . $json);
        error_log('Decoded data: ' . print_r($data, true));

        // Validate required fields
        if (!$data || !isset($data['title']) || !isset($data['due_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields: title and due_date']);
            exit;
        }

        // Validate data types and formats
        if (!is_string($data['title']) || strlen($data['title']) < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid title']);
            exit;
        }

        if (!strtotime($data['due_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid date format']);
            exit;
        }

        try {
            $pdo->beginTransaction();
            
            // Generate UUID for the task
            $taskId = $pdo->query("SELECT UUID()")->fetchColumn();
            
            $stmt = $pdo->prepare("INSERT INTO Task (id, title, description, lead_id, created_by, 
                                  due_date, priority, status) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $taskId, // Use the generated UUID
                $data['title'],
                $data['description'],
                $data['lead_id'],
                $data['created_by'],
                $data['due_date'],
                $data['priority'] ?? 'MEDIUM',
                $data['status'] ?? 'NEW'
            ]);
            
            // Add assignees if provided
            if (!empty($data['assignees'])) {
                $assigneeStmt = $pdo->prepare("INSERT INTO Task_Assignees (task_id, user_id) VALUES (?, ?)");
                foreach ($data['assignees'] as $userId) {
                    $assigneeStmt->execute([$taskId, $userId]);
                }
            }
            
            $pdo->commit();
            http_response_code(201);
            echo json_encode([
                'message' => 'Task created successfully',
                'task_id' => $taskId
            ]);
        } catch(PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Database error']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("UPDATE Task SET title = ?, description = ?, lead_id = ?, 
                                  due_date = ?, priority = ?, status = ? WHERE id = ?");
            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['lead_id'],
                $data['due_date'],
                $data['priority'],
                $data['status'],
                $data['id']
            ]);
            
            // Update assignees if provided
            if (isset($data['assignees'])) {
                $pdo->prepare("DELETE FROM Task_Assignees WHERE task_id = ?")->execute([$data['id']]);
                $assigneeStmt = $pdo->prepare("INSERT INTO Task_Assignees (task_id, user_id) VALUES (?, ?)");
                foreach ($data['assignees'] as $userId) {
                    $assigneeStmt->execute([$data['id'], $userId]);
                }
            }
            
            $pdo->commit();
            echo json_encode(['message' => 'Task updated successfully']);
        } catch(PDOException $e) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM Task WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['message' => 'Task deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
        }
        break;
}
?> 