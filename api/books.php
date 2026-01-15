<?php
require_once '../config/database.php';
header('Content-Type: application/json');
$action = $_GET['action'] ?? '';
if ($action === 'list') {
	$category = $_GET['category'] ?? '';
	$search = $_GET['search'] ?? '';
	$sql = 'SELECT b.*, c.category_name FROM books b JOIN categories c ON b.category_id = c.category_id WHERE 1';
	$params = [];
	if ($category) {
		$sql .= ' AND b.category_id = ?';
		$params[] = $category;
	}
	if ($search) {
		$sql .= ' AND (b.title LIKE ? OR b.author LIKE ?)';
		$params[] = "%$search%";
		$params[] = "%$search%";
	}
	$sql .= ' ORDER BY b.created_at DESC';
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	echo json_encode($stmt->fetchAll());
	exit;
}
if ($action === 'details' && isset($_GET['id'])) {
	$stmt = $pdo->prepare('SELECT b.*, c.category_name FROM books b JOIN categories c ON b.category_id = c.category_id WHERE b.book_id = ?');
	$stmt->execute([$_GET['id']]);
	echo json_encode($stmt->fetch());
	exit;
}
echo json_encode(['error' => 'Invalid request']);
