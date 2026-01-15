<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['book_id'])) {
    echo json_encode(['message' => 'Invalid request']);
    exit;
}

$bookId = $data['book_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + 1;

echo json_encode(['message' => 'Book added to cart']);
