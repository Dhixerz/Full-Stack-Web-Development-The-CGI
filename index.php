<!DOCTYPE html>
<html lang="en">
<?php include("framework/html_head.html"); ?>

<body>
    <div class="container">
        <?php
        session_start();

        // Halaman tanpa navbar dan footer (login dan register)
        $authPages = [
            "login_select",
            "login_user",
            "login_admin",
            "register_user",
            "register_admin"
        ];

        // Halaman admin yang pakai nav_admin
        $adminPages = [
            "admin_page",
            "admin_shows",
            "popular",
            "top_admin",
            "news_admin",
            "admin_users",
            "admin_admins",
            "awards_admin",
            "user_adminview",
            "admin_view"
        ];

        // Ambil halaman dari parameter
        $page = $_GET['page'] ?? "login_select";

        // Navbar
        if (!in_array($page, $authPages)) {
            if (in_array($page, $adminPages)) {
                // Hanya tampilkan navbar admin jika sudah login sebagai admin
                if (!isset($_SESSION['admin_id'])) {
                    header("Location: /412023037_Final%20Project/login_admin");
                    exit();
                }
                include("framework/nav_admin.php");
            } else {
                // Tampilkan navbar user hanya jika sudah login sebagai user
                if (isset($_SESSION['user_id'])) {
                    include("framework/nav.php");
                }
            }
        }

        echo '<section>';

        // Halaman trailer khusus
        if (isset($_GET['movie']) || isset($_GET['show'])) {
            include("pages/trailer.php");
        } else {
            // Routing halaman
            switch ($page) {
                // Halaman user biasa
                case "home":
                    $content = "pages/home.php";
                    break;
                case "movies":
                    $content = "pages/movies.php";
                    break;
                case "topPicks":
                    $content = "pages/topPicks.php";
                    break;
                case "news":
                    $content = "pages/news.php";
                    break;
                case "awards":
                    $content = "pages/awards.php";
                    break;
                case "category":
                    $content = "pages/category.php";
                    break;
                case "category_movies":
                    $content = "pages/category_movies.php";
                    break;
                case "about":
                    $content = "pages/about.php";
                    break;
                case "user_profile":
                    $content = "pages/user_profile.php";
                    break;

                // Halaman admin
                case "admin_page":
                    $content = "pages/admin_page.php";
                    break;
                case "admin_shows":
                    $content = "pages/admin_shows.php";
                    break;
                case "popular":
                    $content = "pages/popular_admin.php";
                    break;
                case "top_admin":
                    $content = "pages/top_admin.php";
                    break;
                case "news_admin":
                    $content = "pages/news_admin.php";
                    break;
                case "admin_users":
                    $content = "pages/admin_users.php";
                    break;
                case "admin_admins":
                    $content = "pages/admin_admins.php";
                    break;
                case "awards_admin":
                    $content = "pages/awards_admin.php";
                    break;
                case "user_adminview":
                    $content = "pages/user_adminview.php";
                    break;
                case "admin_view":
                    $content = "pages/admin_view.php";
                    break;

                // Halaman login & register
                case "login_select":
                case "login_user":
                case "login_admin":
                case "register_user":
                case "register_admin":
                    $content = "pages/$page.php";
                    break;

                // Default jika tidak ditemukan
                default:
                    $content = "pages/login_select.php";
                    break;
            }

            include($content);
        }

        echo '</section>';

        // Footer ditampilkan jika bukan halaman login/register
        if (!in_array($page, $authPages)) {
            include("framework/footer.php");
        }
        ?>
    </div>
</body>

</html>