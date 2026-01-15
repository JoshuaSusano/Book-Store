document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('bookModal');
    const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

    let selectedBookId = null;

    // Handle Read More click
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('read-more-btn')) {

            const btn = e.target;

            selectedBookId = btn.dataset.id;

            document.getElementById('modalTitle').textContent = btn.dataset.title;
            document.getElementById('modalAuthor').textContent = btn.dataset.author;
            document.getElementById('modalDesc').textContent = btn.dataset.desc;
            document.getElementById('modalStock').textContent = btn.dataset.stock;
            document.getElementById('modalImage').src = btn.dataset.image;

            modal.show(); // 👈 FORCE modal to show
        }
    });

    // Add to Cart
    const addBtn = document.getElementById('addToCartBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            if (!selectedBookId) return;

            fetch('/book_store/api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ book_id: selectedBookId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                modal.hide();
            });
        });
    }

});
