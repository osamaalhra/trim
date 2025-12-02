<?php
session_start();
require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $task_id = $data['task_id'];
    
    try {
        $stmt = $pdo->prepare("UPDATE daily_tasks SET completed = 1, completed_at = NOW() WHERE id = ?");
        $stmt->execute([$task_id]);
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>