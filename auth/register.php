<?php
session_start();
require_once '../config/database.php';

$first = trim($_POST['first_name'] ?? '');
$middle = trim($_POST['middle_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if (!$first || !$last || !$email || !$pass) {
  $_SESSION['error'] = 'All fields are required.';
  header('Location: /book_store/index.php?page=register');
  exit;
}

/* CHECK EMAIL */
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE email=?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
  $_SESSION['error'] = 'Email already exists.';
  header('Location: /book_store/index.php?page=register');
  exit;
}

/* INSERT USER */
$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("
  INSERT INTO users (first_name, middle_name, last_name, email, password)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$first, $middle, $last, $email, $hash]);

/* ✅ SUCCESS MESSAGE */
$_SESSION['success'] = 'Account created successfully! Please log in.';

/* 🔁 GO BACK TO LOGIN */
header('Location: /book_store/index.php?page=login');
exit;
