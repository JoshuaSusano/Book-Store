<?php
session_start();
require_once '../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

/* GET FILTER VALUES */
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

/* FETCH CATEGORIES */
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY category_name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

/* BUILD QUERY */
$sql = "
    SELECT b.*
    FROM books b
    WHERE 1
";

$params = [];

if (!empty($search)) {
    $sql .= " AND (b.title LIKE ? OR b.author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $sql .= " AND b.category_id = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-wrapper">
<?php include '../includes/topbar.php'; ?>

<main class="content">

<h2>My Books</h2>

<!-- 🔍 SEARCH + CATEGORY FILTER -->
<form method="GET" class="filter-bar">
    <input
        type="text"
        name="search"
        placeholder="Search books..."
        value="<?= htmlspecialchars($search) ?>"
        class="search-input"
    >

    <select name="category" class="category-select">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>"
                <?= ($category == $cat['category_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['category_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn-filter">Filter</button>
</form>

<!-- 📚 BOOK LIST -->
<div class="books-grid">
<?php if (empty($books)): ?>
    <p>No books found.</p>
<?php endif; ?>

<?php foreach ($books as $book): ?>
    <div class="book-card">

        <img src="<?= htmlspecialchars($book['image']) ?>" alt="Book">

        <div class="book-info">
            <h5><?= htmlspecialchars($book['title']) ?></h5>
            <p class="author"><?= htmlspecialchars($book['author']) ?></p>
            <p class="desc"><?= htmlspecialchars($book['description']) ?></p>

            <form method="POST" action="/book_store/api/cart.php">
                <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                <button 
  class="btn btn-primary add-to-cart-btn"
  data-id="<?= $book['book_id'] ?>">
  Add to Cart
</button>

            </form>
        </div>

    </div>
<?php endforeach; ?>
</div>

</main>
</div>

<?php include '../includes/footer.php'; ?>
