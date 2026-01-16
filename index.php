<?php
session_start();
$page = $_GET['page'] ?? 'login';

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Store | <?= ucfirst($page) ?></title>

<link href="/book_store/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* RESET */
* { box-sizing: border-box; }
html, body {
  margin: 0;
  height: 100%;
  font-family: 'Inter', sans-serif;
}

/* LAYOUT */
.auth-wrapper {
  display: grid;
  grid-template-columns: 50% 50%;
  height: 100vh;
  width: 100vw;
  overflow: hidden;
}

/* IMAGE */
.auth-image {
  background: url('/book_store/assets/images/login.png') center / cover no-repeat;
  position: relative;
}
.auth-image::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.35);
}

/* FORM SIDE */
.auth-form {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff;
}

/* FORM BOX */
.auth-box {
  width: 100%;
  max-width: 480px;
}

/* HEADINGS */
.auth-box h1 {
  font-weight: 800;
  font-size: 2.1rem;
  text-align: center;
  margin-bottom: 6px;
}

.subtitle {
  text-align: center;
  font-size: 0.9rem;
  color: #6c757d;
  margin-bottom: 28px;
}

/* LABELS */
.auth-box label {
  font-weight: 600;
  font-size: 0.85rem;
  margin-bottom: 6px;
}

/* INPUTS */
.form-control {
  height: 46px;
  border-radius: 8px;
  border: none;
  font-size: 0.9rem;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06);
}

/* LIGHT INPUT */
.light-input {
  background: #ffffff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06);
  border: 1px solid #e5e5e5;
}

/* PASSWORD ICON */
.password-wrapper {
  position: relative;
}
.password-wrapper i {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #888;
  cursor: pointer;
}

/* SPACING */
.mb-4 { margin-bottom: 22px !important; }
.mb-3 { margin-bottom: 14px !important; }

/* NAME GRID */
.name-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-top: 6px;
  margin-bottom: 22px;
}

/* REMEMBER ME */
.remember {
  font-size: 0.85rem;
  margin: 14px 0 22px;
}

/* BUTTON */
.auth-btn {
  width: 100%;
  height: 52px;
  border-radius: 30px;
  font-weight: 600;
  background: #6f86ff;
  border: none;
  margin-top: 10px;
}
.auth-btn:hover {
  background: #5c73f0;
}

/* BOTTOM TEXT */
.bottom-text {
  text-align: center;
  font-size: 0.85rem;
  margin-top: 16px;
}

/* MOBILE */
@media (max-width: 768px) {
  .auth-wrapper {
    grid-template-columns: 100%;
  }
  .auth-image {
    display: none;
  }
}
</style>
</head>

<body>

<div class="auth-wrapper">

  <!-- IMAGE LEFT (LOGIN) -->
  <?php if ($page === 'login'): ?>
    <div class="auth-image"></div>
  <?php endif; ?>

  <!-- FORM -->
  <div class="auth-form">
    <div class="auth-box">

      <?php if ($error): ?>
        <div class="alert alert-danger text-center mb-3">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($page === 'login'): ?>
        <!-- LOGIN -->
        <h1>WELCOME TO BOOK HIVE</h1>
        <p class="subtitle">Book store helps you to find and borrowing books easy and fast.</p>

        <form method="POST" action="/book_store/auth/login.php">
          <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
          </div>

          <div class="mb-3 password-wrapper">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            <i class="bi bi-eye"></i>
          </div>

          <div class="remember">
            <input type="checkbox"> Remember me
          </div>

          <button class="btn auth-btn text-white">LogIn</button>
        </form>

        <div class="bottom-text">
          Don’t have an account?
          <a href="?page=register">Register Now</a>
        </div>

      <?php else: ?>
        <!-- REGISTER -->
        <h1>Create Account</h1>
        <p class="subtitle">Book Store helps you to find and borrowing books easy and fast.</p>

        <form method="POST" action="/book_store/auth/register.php">
          <div class="mb-4">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control light-input" placeholder="Enter Email Address" required>
          </div>

          <label>Name</label>
          <div class="name-grid">
            <input type="text" name="first_name" class="form-control light-input" placeholder="First Name" required>
            <input type="text" name="middle_name" class="form-control light-input" placeholder="Middle Name">
            <input type="text" name="last_name" class="form-control light-input" placeholder="Last Name" required>
          </div>

          <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" class="form-control light-input" placeholder="Enter Password" required>
          </div>

          <button class="btn auth-btn text-white">Sign Up</button>
        </form>

        <div class="bottom-text">
          Already have an Account?
          <a href="?page=login">Login</a>
        </div>

      <?php endif; ?>

    </div>
  </div>

  <!-- IMAGE RIGHT (REGISTER) -->
  <?php if ($page === 'register'): ?>
    <div class="auth-image"></div>
  <?php endif; ?>

</div>

</body>
</html>
