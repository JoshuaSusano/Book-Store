document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const input = document.getElementById('searchInput');

    if (!form || !input) return;

    form.addEventListener('submit', e => {
        e.preventDefault();
        const q = input.value.trim();
        if (q !== '') {
            window.location.href = `/book_store/user/books.php?search=${encodeURIComponent(q)}`;
        }
    });

    function showToast(message, isError = false) {
    const toast = document.getElementById("toast");
    toast.textContent = message;

    toast.classList.remove("error");
    if (isError) toast.classList.add("error");

    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 2500);
}

});
