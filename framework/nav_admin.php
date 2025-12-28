<?php
$currentPage = $_GET['page'] ?? '';

if ($currentPage === 'admin_page' || isset($_GET['movie'])) {
    $currentPage = 'admin_page';
}
?>

<header>
    <div class="nav container">
        <a href="/412023037_Final%20Project/admin_page" class="logo">The<span>CGI</span></a>

        <div class="search-box">
            <input type="search" name="search" id="search-input" placeholder="Search Movie">
            <i class='bx bx-search'></i>
        </div>

        <a href="/412023037_Final%20Project/admin_view" class="user" title="Admin View">
            <img src="img/user.jpg" alt="Admin View" class="user-img">
        </a>

        <div class="navbar">
            <a href="/412023037_Final%20Project/admin_page"
                class="nav-link <?= $currentPage === 'admin_page' ? 'nav-active' : '' ?>">
                <i class='bx bx-film'></i>
                <div class="span nav-link-title">Movies</div>
            </a>
            <a href="/412023037_Final%20Project/admin_shows"
                class="nav-link <?= $currentPage === 'admin_shows' ? 'nav-active' : '' ?>">
                <i class='bx bx-tv'></i>
                <div class="span nav-link-title">Shows</div>
            </a>
            <a href="/412023037_Final%20Project/popular"
                class="nav-link <?= $currentPage === 'popular' ? 'nav-active' : '' ?>">
                <i class='bx bxs-star'></i>
                <div class="span nav-link-title">Popular</div>
            </a>
            <a href="/412023037_Final%20Project/top_admin"
                class="nav-link <?= $currentPage === 'top_admin' ? 'nav-active' : '' ?>">
                <i class='bx bxs-hot'></i>
                <div class="span nav-link-title">Top Picks</div>
            </a>
            <a href="/412023037_Final%20Project/news_admin"
                class="nav-link <?= $currentPage === 'news_admin' ? 'nav-active' : '' ?>">
                <i class='bx bx-news'></i>
                <div class="span nav-link-title">News</div>
            </a>
            <a href="/412023037_Final%20Project/awards_admin"
                class="nav-link <?= $currentPage === 'awards_admin' ? 'nav-active' : '' ?>">
                <i class='bx bx-trophy'></i>
                <div class="span nav-link-title">Awards</div>
            </a>

            <a href="/412023037_Final%20Project/admin_view"
                class="nav-link <?= $currentPage === 'admin_view' ? 'nav-active' : '' ?>">
                <i class='bx bx-user-check'></i>
                <div class="span nav-link-title">Admin View</div>
            </a>

            <a href="/412023037_Final%20Project/user_adminview"
                class="nav-link <?= $currentPage === 'user_adminview' ? 'nav-active' : '' ?>">
                <i class='bx bx-user'></i>
                <div class="span nav-link-title">Users</div>
            </a>
        </div>
    </div>
</header>