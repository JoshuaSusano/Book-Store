document.addEventListener('click', async (e) => {
  const row = e.target.closest('tr');
  if (!row) return;

  const bookId = row.dataset.id;
  const stock = parseInt(row.dataset.stock);
  let action = null;

  if (e.target.classList.contains('plus')) action = 'increase';
  if (e.target.classList.contains('minus')) action = 'decrease';
  if (e.target.classList.contains('remove-btn')) action = 'remove';

  if (!action) return;

  const res = await fetch('/book_store/api/cart-update.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `book_id=${bookId}&action=${action}`
  });

  const data = await res.json();

  if (data.status === 'removed') {
    row.remove();
    recalcTotal();
    return;
  }

  if (data.status !== 'success') return;

  row.querySelector('.qty').textContent = data.qty;
  row.querySelector('.line-total').textContent =
    '$' + (data.qty * data.price).toFixed(2);

  recalcTotal();
});

function recalcTotal() {
  let total = 0;
  document.querySelectorAll('.line-total').forEach(el => {
    total += parseFloat(el.textContent.replace('$',''));
  });
  document.getElementById('grandTotal').textContent =
    'Grand Total: $' + total.toFixed(2);
}
