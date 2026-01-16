<?php
require '../config/database.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'details' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("
        SELECT b.*, c.category_name
        FROM books b
        JOIN categories c ON b.category_id = c.category_id
        WHERE b.book_id = ?
    ");
    $stmt->execute([$_GET['id']]);
    echo json_encode($stmt->fetch());
    exit;
}

echo json_encode(['error' => 'Invalid request']);
