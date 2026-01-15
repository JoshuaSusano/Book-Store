<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<div class="container mt-5">
	<h2 class="mb-4">Your Orders</h2>
	<?php if ($orders): ?>
		<table class="table table-bordered align-middle">
			<thead><tr><th>Order ID</th><th>Date</th><th>Status</th><th>Items</th><th>Total</th></tr></thead>
			<tbody>
			<?php foreach ($orders as $order):
				$stmt2 = $pdo->prepare('SELECT oi.*, b.title FROM order_items oi JOIN books b ON oi.book_id = b.book_id WHERE oi.order_id = ?');
				$stmt2->execute([$order['order_id']]);
				$items = $stmt2->fetchAll();
				$total = 0;
				foreach ($items as $item) $total += $item['price'] * $item['quantity'];
			?>
				<tr>
					<td><?= $order['order_id'] ?></td>
					<td><?= $order['order_date'] ?></td>
					<td><?= ucfirst($order['status']) ?></td>
					<td>
						<ul class="mb-0">
							<?php foreach ($items as $item): ?>
								<li><?= htmlspecialchars($item['title']) ?> (x<?= $item['quantity'] ?>)</li>
							<?php endforeach; ?>
						</ul>
					</td>
					<td>$<?= number_format($total,2) ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="alert alert-info">You have no orders yet.</div>
	<?php endif; ?>
</div>
<?php require_once '../includes/footer.php'; ?>
