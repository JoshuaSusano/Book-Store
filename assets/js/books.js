document.addEventListener("DOMContentLoaded", () => {

  const searchInput = document.getElementById("bookSearch");
  const booksGrid = document.getElementById("booksGrid");

  /* ===========================
     SEARCH BOOKS (AJAX)
  =========================== */
  if (searchInput && booksGrid) {
    searchInput.addEventListener("keyup", async () => {
      const keyword = searchInput.value.trim();

      const res = await fetch(
        `/book_store/api/books.php?action=list&search=${encodeURIComponent(keyword)}`
      );
      const books = await res.json();

      booksGrid.innerHTML = "";

      if (!books.length) {
        booksGrid.innerHTML = "<p>No books found.</p>";
        return;
      }

      books.forEach(book => {
        booksGrid.innerHTML += `
          <div class="book-card">
            <img src="${book.image}" alt="Book">

            <div class="book-info">
              <h5>${book.title}</h5>
              <p class="author">${book.author}</p>
              <p class="desc">${book.description}</p>

              <button 
                class="btn btn-primary add-to-cart-btn"
                data-id="${book.book_id}">
                Add to Cart
              </button>
            </div>
          </div>
        `;
      });
    });
  }

  /* ===========================
     ADD TO CART (DELEGATED)
  =========================== */
  document.addEventListener("click", (e) => {
    if (!e.target.classList.contains("add-to-cart-btn")) return;

    e.preventDefault();
    const bookId = e.target.dataset.id;

    fetch("/book_store/api/cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `book_id=${bookId}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        showToast("✅ Added to cart!");
      } else {
        showToast("❌ " + data.message);
      }
    })
    .catch(() => {
      showToast("❌ Something went wrong");
    });
  });

});

/* ===========================
   TOAST FUNCTION
=========================== */
function showToast(message) {
  const toast = document.getElementById("toast");
  const msg = document.getElementById("toast-message");

  msg.textContent = message;
  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 2500);
}
