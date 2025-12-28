<?php include("db.php"); ?>

<!-- Home -->
<section class="home container" id="home">
    <img src="img/home-background.png" alt="" class="home-img">
    <div class="home-text">
        <h1 class="home-title">One Story <br>At a Time</h1>
    </div>
</section>

<!-- Popular Section Start -->
<section class="popular container" id="popular">
    <div class="heading">
        <h2 class="heading-title">Popular Movies</h2>
        <div class="swiper-btn">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <div class="popular-content swiper">
        <div class="swiper-wrapper">
            <?php
            $sql = "SELECT m.* FROM movies m 
                    INNER JOIN popular p ON m.id = p.movie_id";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="swiper-slide">
                    <div class="movie-box">
                        <img src="<?= htmlspecialchars($row['image_poster']) ?>" alt="" class="movie-box-img">
                        <div class="box-text">
                            <h2 class="movie-title"><?= htmlspecialchars($row['title']) ?></h2>
                            <span class="movie-type"><?= htmlspecialchars($row['genre']) ?></span>
                            <a href="index.php?movie=<?= $row['id'] ?>" class="watch-btn play-btn">
                                <i class='bx bx-right-arrow'></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Movies & Shows Section Start -->
<section class="movies container" id="movies">
    <div class="heading">
        <h2 class="heading-title">Movies & Shows</h2>
    </div>
    <div class="movies-content">
        <?php
        // Ambil data dari movies
        $sql_movies = "SELECT id, title, genre, image_poster FROM movies";
        $result_movies = $conn->query($sql_movies);

        // Ambil data dari shows
        $sql_shows = "SELECT id, title, genre, image_poster FROM shows";
        $result_shows = $conn->query($sql_shows);

        // Tampilkan data movies dulu
        while ($movie = $result_movies->fetch_assoc()) {
            ?>
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
        <?php } ?>

        <!-- Tampilkan data shows -->
        <?php while ($show = $result_shows->fetch_assoc()) { ?>
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
        <?php } ?>
    </div>
</section>