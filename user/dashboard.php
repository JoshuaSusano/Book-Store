<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

/* STATS */
$userId = $_SESSION['user_id'] ?? 0;

$totalOrders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$totalOrders->execute([$userId]);
$totalOrders = $totalOrders->fetchColumn();

$totalItems = $pdo->prepare("
  SELECT SUM(oi.quantity)
  FROM orders o
  JOIN order_items oi ON o.order_id = oi.order_id
  WHERE o.user_id = ?
");
$totalItems->execute([$userId]);
$totalItems = $totalItems->fetchColumn() ?? 0;

/* BOOKS FOR SLIDER */
$books = $pdo->query("SELECT title, image FROM books LIMIT 6")->fetchAll();
?>

<div class="main-wrapper">
<?php include '../includes/topbar.php'; ?>

<main class="content">

<!-- WELCOME -->
<h2 class="welcome-title">
  Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?> 👋
</h2>

<!-- STATS -->
<div class="stats-row">
  <div class="stat-box">
    <p>Total Orders</p>
    <h1><?= $totalOrders ?></h1>
  </div>

  <div class="stat-box">
    <p>Total Items Purchased</p>
    <h1><?= $totalItems ?></h1>
  </div>
</div>

<!-- HERO SECTION -->
<div class="hero-section">
  <div class="hero-text">
    <h3>Keep the story going…</h3>
    <p>
      Don’t let the story end just yet. Continue reading your last book and
      immerse yourself in the world of literature.
    </p>
    <a href="books.php" class="btn-hero">Start Reading →</a>
  </div>

  <div class="hero-books">
    <?php foreach ($books as $book): ?>
      <div class="hero-book">
        <img src="<?= htmlspecialchars($book['image']) ?>" alt="">
        <p><?= htmlspecialchars($book['title']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</main>
</div>

<?php include '../includes/footer.php'; ?>
