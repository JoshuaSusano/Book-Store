<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: ../auth/login.php");
    exit;
}

/* FETCH ORDERS */
$stmt = $pdo->prepare("
    SELECT *
    FROM orders
    WHERE user_id = ?
    ORDER BY order_date DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-wrapper">
<?php include '../includes/topbar.php'; ?>

<main class="content">
    <h2 class="mb-4">My Orders</h2>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p>You haven’t placed any orders yet.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($orders as $order): ?>

        <?php
        /* FETCH ORDER ITEMS */
        $itemsStmt = $pdo->prepare("
            SELECT oi.*, b.title
            FROM order_items oi
            JOIN books b ON oi.book_id = b.book_id
            WHERE oi.order_id = ?
        ");
        $itemsStmt->execute([$order['order_id']]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="order-card">
            <div class="order-header">
                <div>
                    <h6>Order #<?= $order['order_id'] ?></h6>
                    <span class="order-date">
                        <?= date('F d, Y', strtotime($order['order_date'])) ?>
                    </span>
                </div>

                <div class="order-meta">
                    <span class="status <?= strtolower($order['status']) ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>
                    <strong>$<?= number_format($order['total_amount'], 2) ?></strong>
                </div>
            </div>

            <div class="order-items">
                <?php foreach ($items as $item): ?>
                    <div class="order-item">
                        <span><?= htmlspecialchars($item['title']) ?></span>
                        <span>
                            <?= $item['quantity'] ?> × $<?= number_format($item['price'],2) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endforeach; ?>
</main>
</div>

<?php include '../includes/footer.php'; ?>
