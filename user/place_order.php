<?php
session_start();
require '../config/database.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: /book_store/auth/login.php");
    exit;
}

$pdo->beginTransaction();

try {
    // Insert order
    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (user_id, full_name, email, phone, address, city, zipcode, country, total_amount, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");

    $stmt->execute([
        $user_id,
        $_POST['full_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['city'],
        $_POST['zipcode'],
        $_POST['country'],
        $_POST['total_amount']
    ]);

    $order_id = $pdo->lastInsertId();

    // Fetch cart
    $cart = $pdo->prepare("
        SELECT c.book_id, c.quantity, b.price
        FROM cart c
        JOIN books b ON c.book_id = b.book_id
        WHERE c.user_id = ?
    ");
    $cart->execute([$user_id]);

    foreach ($cart as $item) {
        $pdo->prepare("
            INSERT INTO order_items (order_id, book_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ")->execute([
            $order_id,
            $item['book_id'],
            $item['quantity'],
            $item['price']
        ]);

        // Reduce stock
        $pdo->prepare("
            UPDATE books SET stock = stock - ?
            WHERE book_id = ?
        ")->execute([$item['quantity'], $item['book_id']]);
    }

    // Clear cart
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

    $pdo->commit();

    header("Location: orders.php?success=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Checkout failed: " . $e->getMessage());
}
