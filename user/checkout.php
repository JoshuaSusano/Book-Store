<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

/* FETCH BOOKS */
$ids = implode(',', array_keys($cart));
$stmt = $pdo->query("SELECT book_id, title, price FROM books WHERE book_id IN ($ids)");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* CALCULATE TOTAL */
$grandTotal = 0;
$orderItems = [];
foreach ($books as $book) {
    $qty = (int)$cart[$book['book_id']];
    $price = (float)$book['price'];
    $lineTotal = $qty * $price;
    $grandTotal += $lineTotal;

    $orderItems[] = [
        'book_id' => $book['book_id'],
        'title' => $book['title'],
        'qty' => $qty,
        'price' => $price,
        'total' => $lineTotal
    ];
}

/* PLACE ORDER */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['full_name','email','phone','address','city','zipcode','country'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = "Please fill in all fields.";
        }
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("
            INSERT INTO orders 
            (user_id, full_name, email, phone, address, city, zipcode, country, total_amount)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['city'],
            $_POST['zipcode'],
            $_POST['country'],
            $grandTotal
        ]);

        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare("
            INSERT INTO order_items (order_id, book_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($orderItems as $item) {
            $itemStmt->execute([
                $orderId,
                $item['book_id'],
                $item['qty'],
                $item['price']
            ]);
        }

        unset($_SESSION['cart']);
        header("Location: orders.php");
        exit;
    }
}
?>

<div class="main-wrapper">
<?php include '../includes/topbar.php'; ?>

<main class="content">

<h2 class="mb-4">Checkout</h2>

<div class="checkout-container">

<!-- LEFT -->
<form method="POST" class="checkout-form">

<h5>Delivery Information</h5>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<input type="text" name="full_name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="text" name="phone" placeholder="Phone Number" required>
<input type="text" name="address" placeholder="Street Address" required>

<div class="form-row">
  <input type="text" name="city" placeholder="City" required>
  <input type="text" name="zipcode" placeholder="Zip Code" required>
</div>

<input type="text" name="country" placeholder="Country" required>

<button type="submit" class="btn-place-order">
  Place Order
</button>

</form>

<!-- RIGHT -->
<div class="order-summary">
<h5>Order Summary</h5>

<?php foreach ($orderItems as $item): ?>
<div class="summary-item">
  <span><?= htmlspecialchars($item['title']) ?> × <?= $item['qty'] ?></span>
  <strong>$<?= number_format($item['total'],2) ?></strong>
</div>
<?php endforeach; ?>

<hr>

<div class="summary-total">
Total: <strong>$<?= number_format($grandTotal,2) ?></strong>
</div>

</div>

</div>
</main>
</div>

<?php include '../includes/footer.php'; ?>
