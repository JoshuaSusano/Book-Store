<?php
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-wrapper">
    <?php include '../includes/topbar.php'; ?>

    <main class="content">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?> 👋</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h6>Total Orders</h6>
                <h2>0</h2>
            </div>
            <div class="stat-card">
                <h6>Total Items Purchased</h6>
                <h2>0</h2>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
