<header class="topbar">
    <form class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Search books...">
    </form>

    <div class="user-info">
        <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
        <img src="/book_store/assets/images/avatar.png" alt="User">
    </div>
</header>
