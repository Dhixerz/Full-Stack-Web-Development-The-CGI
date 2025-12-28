<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movies & Shows</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <!-- Home -->
    <section class="home container" id="home">
        <img src="img/hawkeye.webp" alt="" class="home-img2">
        <div class="home-text">
            <h1 class="home-title2">Hawkeye</h1>
            <p>Season 2 Releasing Soon !</p>
            <a href="index.php?movie=3" class="watch-btn">
                <i class='bx bx-right-arrow'></i>
                <span>Watch the trailer</span>
            </a>
        </div>
    </section>

    <?php
    // Ambil keyword search jika ada
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Pagination setup
    $perPage = 8;
    $currentPage = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
    if ($currentPage < 1)
        $currentPage = 1;
    $offset = ($currentPage - 1) * $perPage;

    // Escape search untuk query
    $search_esc = $conn->real_escape_string($search);

    // Hitung total data sesuai search (pakai LIKE)
    if ($search !== '') {
        $totalMovies = $conn->query("SELECT COUNT(*) AS total FROM movies WHERE title LIKE '%$search_esc%'")->fetch_assoc()['total'];
        $totalShows = $conn->query("SELECT COUNT(*) AS total FROM shows WHERE title LIKE '%$search_esc%'")->fetch_assoc()['total'];
    } else {
        $totalMovies = $conn->query("SELECT COUNT(*) AS total FROM movies")->fetch_assoc()['total'];
        $totalShows = $conn->query("SELECT COUNT(*) AS total FROM shows")->fetch_assoc()['total'];
    }

    $totalMoviePages = ceil($totalMovies / $perPage);
    $totalShowPages = ceil($totalShows / $perPage);

    // Ambil data movies dan shows sesuai search dan pagination
    if ($search !== '') {
        $movies_result = $conn->query("SELECT * FROM movies WHERE title LIKE '%$search_esc%' ORDER BY id ASC LIMIT $perPage OFFSET $offset");
        $shows_result = $conn->query("SELECT * FROM shows WHERE title LIKE '%$search_esc%' ORDER BY id ASC LIMIT $perPage OFFSET $offset");
    } else {
        $movies_result = $conn->query("SELECT * FROM movies ORDER BY id ASC LIMIT $perPage OFFSET $offset");
        $shows_result = $conn->query("SELECT * FROM shows ORDER BY id ASC LIMIT $perPage OFFSET $offset");
    }
    ?>

    <!-- Movies Section -->
    <section class="movies container" id="movies">
        <div class="heading2">
            <h2 class="heading-title">
                Movies<?= $search !== '' ? " — Search results for \"" . htmlspecialchars($search) . "\"" : '' ?></h2>
        </div>
        <div class="movies-content">
            <?php if ($movies_result && $movies_result->num_rows > 0): ?>
                <?php while ($movie = $movies_result->fetch_assoc()): ?>
                    <div class="movie-box">
                        <img src="<?= htmlspecialchars($movie['image_poster']) ?>" alt="" class="movie-box-img">
                        <div class="box-text">
                            <h2 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h2>
                            <span class="movie-type"><?= htmlspecialchars($movie['genre']) ?></span>
                            <a href="index.php?movie=<?= $movie['id'] ?>" class="watch-btn play-btn">
                                <i class='bx bx-right-arrow'></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; width: 100%; color: #777;">No movies
                    found<?= $search !== '' ? " matching \"" . htmlspecialchars($search) . "\"" : '' ?>.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Shows Section -->
    <section class="movies container" id="shows">
        <div class="heading3">
            <h2 class="heading-title">
                Shows<?= $search !== '' ? " — Search results for \"" . htmlspecialchars($search) . "\"" : '' ?></h2>
        </div>
        <div class="movies-content">
            <?php if ($shows_result && $shows_result->num_rows > 0): ?>
                <?php while ($show = $shows_result->fetch_assoc()): ?>
                    <div class="movie-box">
                        <img src="<?= htmlspecialchars($show['image_poster']) ?>" alt="" class="movie-box-img">
                        <div class="box-text">
                            <h2 class="movie-title"><?= htmlspecialchars($show['title']) ?></h2>
                            <span class="movie-type"><?= htmlspecialchars($show['genre']) ?></span>
                            <a href="index.php?show=<?= $show['id'] ?>" class="watch-btn play-btn">
                                <i class='bx bx-right-arrow'></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; width: 100%; color: #777;">No shows
                    found<?= $search !== '' ? " matching \"" . htmlspecialchars($search) . "\"" : '' ?>.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Pagination -->
    <div class="next-page" style="text-align: center; margin-bottom: 40px;">
        <?php if ($currentPage > 1): ?>
            <a href="index.php?page=movies&page_no=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>"
                class="next-btn">← Back</a>
        <?php endif; ?>

        <?php if ($currentPage < max($totalMoviePages, $totalShowPages)): ?>
            <a href="index.php?page=movies&page_no=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>"
                class="next-btn">Next Page</a>
        <?php endif; ?>
    </div>

</body>

</html>