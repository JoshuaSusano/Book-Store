<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<div class="container mt-5">
	<h2 class="mb-4">Profile</h2>
	<?php if ($user): ?>
		<table class="table table-bordered w-50">
			<tr><th>User ID</th><td><?= htmlspecialchars($user['user_id']) ?></td></tr>
			<tr><th>Name</th><td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td></tr>
			<tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
			<tr><th>Address</th><td><?= htmlspecialchars($user['address']) ?></td></tr>
			<tr><th>Registered</th><td><?= $user['created_at'] ?></td></tr>
		</table>
	<?php endif; ?>
</div>
<?php require_once '../includes/footer.php'; ?>
