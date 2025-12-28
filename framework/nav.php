<?php
$currentPage = $_GET['page'] ?? '';
if (isset($_GET['movie']) || isset($_GET['show'])) {
    $currentPage = 'movies';
}
?>

<header>
    <div class="nav container">
        <a href="/412023037_Final%20Project/" class="logo">The<span>CGI</span></a>

        <form class="search-box" action="/412023037_Final%20Project/movies" method="get"
            style="display: inline-flex; align-items: center;">
            <input type="search" name="search" id="search-input" placeholder="Search Movie"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                style="padding: 5px 10px; border-radius: 4px; border: 1px solid #ccc;">
            <button type="submit" style="background:none; border:none; cursor:pointer; margin-left:5px; color:#333;">
                <i class='bx bx-search' style="font-size: 1.3rem;"></i>
            </button>
        </form>

        <a href="/412023037_Final%20Project/user_profile" class="user" title="Your Profile">
            <img src="img/user.jpg" alt="User Profile" class="user-img">
        </a>

        <div class="navbar">
            <a href="/412023037_Final%20Project/home"
                class="nav-link <?= $currentPage === 'home' ? 'nav-active' : '' ?>">
                <i class='bx bx-home'></i>
                <div class="span nav-link-title">Home</div>
            </a>
            <a href="/412023037_Final%20Project/topPicks"
                class="nav-link <?= $currentPage === 'topPicks' ? 'nav-active' : '' ?>">
                <i class='bx bxs-hot'></i>
                <div class="span nav-link-title">Top Picks</div>
            </a>
            <a href="/412023037_Final%20Project/movies"
                class="nav-link <?= $currentPage === 'movies' ? 'nav-active' : '' ?>">
                <i class='bx bx-tv'></i>
                <div class="span nav-link-title">Movies</div>
            </a>
            <a href="/412023037_Final%20Project/news"
                class="nav-link <?= $currentPage === 'news' ? 'nav-active' : '' ?>">
                <i class='bx bx-news'></i>
                <div class="span nav-link-title">News</div>
            </a>
            <a href="/412023037_Final%20Project/awards"
                class="nav-link <?= $currentPage === 'awards' ? 'nav-active' : '' ?>">
                <i class='bx bx-trophy'></i>
                <div class="span nav-link-title">Awards</div>
            </a>
            <a href="/412023037_Final%20Project/category"
                class="nav-link <?= $currentPage === 'category' ? 'nav-active' : '' ?>">
                <i class='bx bx-category'></i>
                <div class="span nav-link-title">Category</div>
            </a>
            <a href="/412023037_Final%20Project/about"
                class="nav-link <?= $currentPage === 'about' ? 'nav-active' : '' ?>">
                <i class='bx bx-info-circle'></i>
                <div class="span nav-link-title">About</div>
            </a>
        </div>
    </div>
</header>