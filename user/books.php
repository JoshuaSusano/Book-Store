<?php
require '../config/database.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$stmt = $pdo->query("
    SELECT books.*, categories.category_name
    FROM books
    JOIN categories ON books.category_id = categories.category_id
");
$books = $stmt->fetchAll();
?>

<div class="main-wrapper">
    <?php include '../includes/topbar.php'; ?>

    <main class="content">
        <h2>My Books</h2>

        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img 
                        src="<?= htmlspecialchars($book['image']) ?>" 
                        alt="<?= htmlspecialchars($book['title']) ?>"
                    >

                    <div class="book-info">
                        <h5><?= htmlspecialchars($book['title']) ?></h5>
                        <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                        <p class="desc">
                            <?= substr(htmlspecialchars($book['description']), 0, 100) ?>...
                        </p>

    <button
  type="button"
  class="btn-read read-more-btn"
  data-id="<?= $book['book_id'] ?>"
  data-title="<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>"
  data-author="<?= htmlspecialchars($book['author'], ENT_QUOTES) ?>"
  data-desc="<?= htmlspecialchars($book['description'], ENT_QUOTES) ?>"
  data-stock="<?= $book['stock'] ?>"
  data-image="<?= htmlspecialchars($book['image'], ENT_QUOTES) ?>"
>
  Read More
</button>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

      <!-- BOOK MODAL -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content book-modal">

      <div class="modal-body">
        <img id="modalImage" class="modal-book-img" alt="Book cover">

        <h4 id="modalTitle"></h4>
        <p id="modalAuthor"></p>
        <p id="modalDesc"></p>

        <p class="stock">
          Stock available: <span id="modalStock"></span>
        </p>

        <button id="addToCartBtn" class="btn-add-cart">
          Add to Cart
        </button>
      </div>

    </div>
  </div>
</div>



<?php include '../includes/footer.php'; ?>
