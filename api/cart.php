<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

$bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

if ($bookId <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid book"]);
    exit;
}

$stmt = $pdo->prepare("SELECT book_id, stock FROM books WHERE book_id = ?");
$stmt->execute([$bookId]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo json_encode(["status" => "error", "message" => "Invalid book"]);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$bookId])) {
    if ($_SESSION['cart'][$bookId] < $book['stock']) {
        $_SESSION['cart'][$bookId]++;
    }
} else {
    $_SESSION['cart'][$bookId] = 1;
}

echo json_encode([
    "status" => "success",
    "message" => "Added to cart"
]);
