<?php
require_once '../config/database.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$first_name = trim($_POST['first_name'] ?? '');
	$last_name = trim($_POST['last_name'] ?? '');
	$address = trim($_POST['address'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';
	$confirm_password = $_POST['confirm_password'] ?? '';

	// Input validation
	if (!$first_name || !$last_name || !$email || !$password || !$confirm_password) {
		$errors[] = 'All fields are required.';
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = 'Invalid email address.';
	}
	if ($password !== $confirm_password) {
		$errors[] = 'Passwords do not match.';
	}
	if (strlen($password) < 6) {
		$errors[] = 'Password must be at least 6 characters.';
	}

	// Check for existing user
	if (empty($errors)) {
		$stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = ?');
		$stmt->execute([$email]);
		if ($stmt->fetch()) {
			$errors[] = 'Email already registered.';
		}
	}

	// Register user
	if (empty($errors)) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, address, email, password) VALUES (?, ?, ?, ?, ?)');
		if ($stmt->execute([$first_name, $last_name, $address, $email, $hashed_password])) {
			$success = true;
		} else {
			$errors[] = 'Registration failed. Please try again.';
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Registration</title>
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
	<h2 class="mb-4">User Registration</h2>
	<?php if ($success): ?>
		<div class="alert alert-success">Registration successful! <a href="login.php">Login here</a>.</div>
	<?php endif; ?>
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
		<!-- Student ID removed -->
		<div class="mb-3">
			<label for="first_name" class="form-label">First Name</label>
			<input type="text" class="form-control" id="first_name" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
		</div>
		<div class="mb-3">
			<label for="last_name" class="form-label">Last Name</label>
			<input type="text" class="form-control" id="last_name" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
		</div>
		<div class="mb-3">
			<label for="address" class="form-label">Address</label>
			<textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
		</div>
		<div class="mb-3">
			<label for="email" class="form-label">Email</label>
			<input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
		</div>
		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" id="password" name="password" required>
		</div>
		<div class="mb-3">
			<label for="confirm_password" class="form-label">Confirm Password</label>
			<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
		</div>
		<button type="submit" class="btn btn-primary">Register</button>
		<a href="login.php" class="btn btn-link">Already have an account? Login</a>
	</form>
</div>
</body>
</html>
