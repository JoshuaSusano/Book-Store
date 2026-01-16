<?php
session_start();
require_once '../config/database.php';

$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if (!$email || !$pass) {
  $_SESSION['error'] = 'Email and password required.';
  header('Location: /book_store/index.php?page=login');
  exit;
}

$stmt = $pdo->prepare("SELECT user_id, first_name, password FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($pass, $user['password'])) {
  $_SESSION['error'] = 'Invalid email or password.';
  header('Location: /book_store/index.php?page=login');
  exit;
}

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['user_name'] = $user['first_name'];

header('Location: /book_store/user/dashboard.php');
exit;
