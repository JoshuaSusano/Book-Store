<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
// Fetch featured books (latest 6)
$stmt = $pdo->query('SELECT b.*, c.category_name FROM books b JOIN categories c ON b.category_id = c.category_id ORDER BY b.created_at DESC LIMIT 6');
$books = $stmt->fetchAll();
?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
	body, .navbar, .card, .btn, .form-control, .display-4, .lead, h1, h2, h3, h4, h5, h6 {
		font-family: 'Inter', Arial, sans-serif !important;
	}
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2">
	<div class="container">
		<a class="navbar-brand fw-bold text-primary" href="/book_store/index.php" style="font-size:1.6rem;">Book Store</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ms-auto align-items-center">
				<?php if (isset($_SESSION['user_id'])): ?>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/dashboard.php"><i class="bi bi-person-circle"></i> Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/books.php"><i class="bi bi-book"></i> Browse Books</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/cart.php"><i class="bi bi-cart"></i> Cart</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/orders.php"><i class="bi bi-bag-check"></i> Orders</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
				<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="/book_store/auth/login.php"><i class="bi bi-person"></i> Login</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/auth/register.php"><i class="bi bi-person-plus"></i> Register</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
<section class="bg-white py-5 mb-0">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-7 mb-4 mb-lg-0">
				<h1 class="display-4 fw-bold text-primary mb-3">Welcome to Book Store</h1>
				<p class="lead text-secondary mb-4">Discover and order your favorite books online. Fast, secure, and easy shopping experience.</p>
				<a href="/book_store/user/books.php" class="btn btn-primary btn-lg px-4 shadow-sm"><i class="bi bi-book"></i> Browse Books</a>
			</div>
			<div class="col-lg-5 text-center">
				<img src="/book_store/assets/images/hero-books.png" alt="Books" class="img-fluid" style="max-height:260px;">
			</div>
		</div>
	</div>
</section>
<section class="container py-4">
	<h3 class="fw-bold text-primary mb-4">Featured Books</h3>
	<div class="row g-4">
		<?php foreach ($books as $book): ?>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
				<div class="card h-100 border-0 shadow-sm book-card position-relative">
					<img src="/book_store/assets/images/book-default.png" alt="<?= htmlspecialchars($book['title']) ?>" class="card-img-top p-3" style="height:180px;object-fit:contain;">
					<div class="card-body d-flex flex-column">
						<h5 class="card-title fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h5>
						<p class="card-text text-muted mb-2" style="font-size:0.98rem;">By <?= htmlspecialchars($book['author']) ?></p>
						<div class="mb-2">
							<span class="badge bg-light text-dark border me-1"><i class="bi bi-tag"></i> <?= htmlspecialchars($book['category_name']) ?></span>
							<span class="badge bg-primary"><i class="bi bi-currency-dollar"></i> <?= number_format($book['price'], 2) ?></span>
						</div>
						<div class="mt-auto">
							<?php if ($book['stock'] > 0): ?>
								<span class="badge bg-success"><i class="bi bi-check-circle"></i> In Stock</span>
							<?php else: ?>
								<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Out of Stock</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if (empty($books)): ?>
			<div class="col-12"><div class="alert alert-info">No books available.</div></div>
		<?php endif; ?>
	</div>
</section>
<?php require_once 'includes/footer.php'; ?>
