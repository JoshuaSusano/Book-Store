<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = $_SESSION['cart'];
$cart_items = [];
$total = 0;
if ($cart) {
		$ids = implode(',', array_fill(0, count($cart), '?'));
		$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id IN ($ids)");
		$stmt->execute(array_keys($cart));
		$cart_items = $stmt->fetchAll(PDO::FETCH_UNIQUE);
		foreach ($cart as $book_id => $qty) {
				if (isset($cart_items[$book_id])) {
						$total += $cart_items[$book_id]['price'] * $qty;
				}
		}
}
?>
<div class="container mt-5">
	<h2 class="mb-4">Your Cart</h2>
	<?php if ($cart && $cart_items): ?>
		<form id="cartForm">
			<table class="table table-bordered align-middle">
				<thead><tr><th>Title</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr></thead>
				<tbody>
				<?php foreach ($cart as $book_id => $qty): if (!isset($cart_items[$book_id])) continue; $book = $cart_items[$book_id]; ?>
					<tr>
						<td><?= htmlspecialchars($book['title']) ?></td>
						<td>$<?= number_format($book['price'],2) ?></td>
						<td><input type="number" min="1" max="<?= $book['stock'] ?>" name="qty[<?= $book_id ?>]" value="<?= $qty ?>" class="form-control form-control-sm qty-input" style="width:80px;"></td>
						<td>$<?= number_format($book['price'] * $qty,2) ?></td>
						<td><button type="button" class="btn btn-danger btn-sm remove-item" data-id="<?= $book_id ?>">Remove</button></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<div class="mb-3 text-end">
				<strong>Total: $<span id="cartTotal"><?= number_format($total,2) ?></span></strong>
			</div>
			<div class="text-end">
				<button type="submit" class="btn btn-success">Checkout</button>
			</div>
		</form>
	<?php else: ?>
		<div class="alert alert-info">Your cart is empty.</div>
	<?php endif; ?>
</div>
<?php require_once '../includes/footer.php'; ?>
