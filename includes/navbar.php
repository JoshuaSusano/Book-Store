<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
	<div class="container">
		<a class="navbar-brand" href="/book_store/index.php">Book Store</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ms-auto">
				<?php if (isset($_SESSION['user_id'])): ?>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/dashboard.php">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/books.php">Browse Books</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/cart.php">Cart</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/user/orders.php">Orders</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/auth/logout.php">Logout</a></li>
				<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="/book_store/auth/login.php">Login</a></li>
					<li class="nav-item"><a class="nav-link" href="/book_store/auth/register.php">Register</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
