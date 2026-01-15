<?php
session_start();
require_once '../config/database.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';

	if (!$email || !$password) {
		$errors[] = 'Email and password are required.';
	} else {
		$stmt = $pdo->prepare('SELECT user_id, first_name, last_name, password FROM users WHERE email = ?');
		$stmt->execute([$email]);
		$user = $stmt->fetch();
		if ($user && password_verify($password, $user['password'])) {
			// Set session
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['first_name'] = $user['first_name'];
			$_SESSION['last_name'] = $user['last_name'];
			header('Location: ../user/dashboard.php');
			exit;
		} else {
			$errors[] = 'Invalid email or password.';
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Login</title>
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
	<h2 class="mb-4">User Login</h2>
	<?php if ($errors): ?>
		<div class="alert alert-danger">
			<ul class="mb-0">
				<?php foreach ($errors as $error): ?>
					<li><?= htmlspecialchars($error) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<form method="post" autocomplete="off">
		<div class="mb-3">
			<label for="email" class="form-label">Email</label>
			<input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
		</div>
		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" id="password" name="password" required>
		</div>
		<button type="submit" class="btn btn-primary">Login</button>
		<a href="register.php" class="btn btn-link">Don't have an account? Register</a>
	</form>
</div>
</body>
</html>
