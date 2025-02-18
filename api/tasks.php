<?php
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
        $data = json_decode(file_get_contents('php://input'), true);
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
            echo json_encode(['message' => 'Task created successfully', 'id' => $taskId]);
        } catch(PDOException $e) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
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