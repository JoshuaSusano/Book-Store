<?php
session_start();
require_once '../config/database.php';

$bookId = (int)($_POST['book_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($bookId <= 0 || !isset($_SESSION['cart'][$bookId])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$stmt = $pdo->prepare("SELECT stock, price FROM books WHERE book_id=?");
$stmt->execute([$bookId]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo json_encode(['status'=>'error']);
    exit;
}

$qty = $_SESSION['cart'][$bookId];

if ($action === 'increase' && $qty < $book['stock']) {
    $qty++;
}
if ($action === 'decrease' && $qty > 1) {
    $qty--;
}
if ($action === 'remove') {
    unset($_SESSION['cart'][$bookId]);
    echo json_encode(['status'=>'removed']);
    exit;
}

$_SESSION['cart'][$bookId] = $qty;

echo json_encode([
    'status' => 'success',
    'qty' => $qty,
    'price' => $book['price']
]);
