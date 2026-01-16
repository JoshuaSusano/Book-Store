<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$books = [];

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $stmt = $pdo->query("
        SELECT book_id, title, price, stock 
        FROM books 
        WHERE book_id IN ($ids)
    ");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="main-wrapper">
<?php include '../includes/topbar.php'; ?>

<main class="content">
<h2>My Cart</h2>

<table class="table cart-table">
<thead>
<tr>
  <th>Book</th>
  <th>Qty</th>
  <th>Price</th>
  <th>Total</th>
  <th></th>
</tr>
</thead>
<tbody>

<?php if (empty($books)): ?>
<tr><td colspan="5">Cart is empty</td></tr>
<?php endif; ?>

<?php foreach ($books as $book): 
  $qty = (int)$cart[$book['book_id']];
  $price = (float)$book['price'];
  $lineTotal = $qty * $price;
  $total += $lineTotal;
?>
<tr data-id="<?= $book['book_id'] ?>" data-stock="<?= $book['stock'] ?>">
  <td><?= htmlspecialchars($book['title']) ?></td>

  <td class="qty-cell">
    <button class="qty-btn minus">−</button>
    <span class="qty"><?= $qty ?></span>
    <button class="qty-btn plus">+</button>
  </td>

  <td>$<?= number_format($price,2) ?></td>

  <td class="line-total">$<?= number_format($lineTotal,2) ?></td>

  <td>
    <button class="remove-btn">🗑</button>
  </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<h4 id="grandTotal">Grand Total: $<?= number_format($total,2) ?></h4>

<a href="checkout.php" class="btn btn-primary mt-3">Checkout</a>

</main>
</div>

<script src="/book_store/assets/js/cart.js"></script>

<?php include '../includes/footer.php'; ?>
