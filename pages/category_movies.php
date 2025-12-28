<?php
include("db.php");

// Ambil kategori dari URL, default kosong
$category = isset($_GET['category']) ? $_GET['category'] : '';
$escapedCategory = $conn->real_escape_string($category);

// Query movies dan shows berdasarkan genre mengandung kategori
$movies_result = $conn->query("SELECT * FROM movies WHERE genre LIKE '%$escapedCategory%' ORDER BY id ASC");
$shows_result = $conn->query("SELECT * FROM shows WHERE genre LIKE '%$escapedCategory%' ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movies by Category</title>
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <!-- Home -->
    <section class="home container" id="home">
        <img src="img/home-background.png" alt="" class="home-img">
        <div class="home-text">
            <h1 class="home-title">One Story <br>At a Time</h1>
        </div>
    </section>

    <!-- Movies Section -->
    <section class="movies container" id="movies">
        <div class="heading2">
            <h2 class="heading-title">Movies - Category: <?= htmlspecialchars($category) ?></h2>
        </div>
        <div class="movies-content">
            <?php while ($movie = $movies_result->fetch_assoc()): ?>
                <div class="movie-box">
                    <img src="<?= htmlspecialchars($movie['image_poster']) ?>" alt="" class="movie-box-img">
                    <div class="box-text">
                        <h2 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h2>
                        <span class="movie-type"><?= htmlspecialchars($movie['genre']) ?></span>
                        <a href="index.php?movie=<?= $movie['id'] ?>" class="watch-btn play-btn"
                            title="Watch <?= htmlspecialchars($movie['title']) ?>">
                            <i class='bx bx-right-arrow'></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Shows Section -->
    <section class="movies container" id="shows">
        <div class="heading3">
            <h2 class="heading-title">Shows - Category: <?= htmlspecialchars($category) ?></h2>
        </div>
        <div class="movies-content">
            <?php while ($show = $shows_result->fetch_assoc()): ?>
                <div class="movie-box">
                    <img src="<?= htmlspecialchars($show['image_poster']) ?>" alt="" class="movie-box-img">
                    <div class="box-text">
                        <h2 class="movie-title"><?= htmlspecialchars($show['title']) ?></h2>
                        <span class="movie-type"><?= htmlspecialchars($show['genre']) ?></span>
                        <a href="index.php?show=<?= $show['id'] ?>" class="watch-btn play-btn"
                            title="Watch <?= htmlspecialchars($show['title']) ?>">
                            <i class='bx bx-right-arrow'></i>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

</body>

</html>