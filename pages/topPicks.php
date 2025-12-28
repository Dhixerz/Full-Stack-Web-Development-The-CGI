<?php
include 'db.php';

// Ambil 10 top picks urut berdasarkan posisi
$query = "
    SELECT tp.position, tp.content_type, tp.content_id,
           m.title AS movie_title, m.genre AS movie_genre, m.rating_imdb AS movie_imdb, m.rating_rotten AS movie_rotten, m.rating_metacritic AS movie_metacritic, m.image_poster AS movie_poster,
           s.title AS show_title, s.genre AS show_genre, s.rating_imdb AS show_imdb, s.rating_rotten AS show_rotten, s.rating_metacritic AS show_metacritic, s.image_poster AS show_poster
    FROM topPicks tp
    LEFT JOIN movies m ON (tp.content_type = 'movie' AND tp.content_id = m.id)
    LEFT JOIN shows s ON (tp.content_type = 'show' AND tp.content_id = s.id)
    ORDER BY tp.position ASC
    LIMIT 10
";
$result = mysqli_query($conn, $query);
?>

<div class="top-picks-container">
    <h2 class="top-picks-heading">Top 10 on CGI This Week</h2>

    <?php while ($row = mysqli_fetch_assoc($result)):

        if ($row['content_type'] === 'movie') {
            $title = $row['movie_title'];
            $genre = $row['movie_genre'];
            $imdb = $row['movie_imdb'];
            $rotten = $row['movie_rotten'];
            $metacritic = $row['movie_metacritic'];
            $poster = $row['movie_poster']; // sudah termasuk 'img/...'
        } else {
            $title = $row['show_title'];
            $genre = $row['show_genre'];
            $imdb = $row['show_imdb'];
            $rotten = $row['show_rotten'];
            $metacritic = $row['show_metacritic'];
            $poster = $row['show_poster'];
        }

        // Cek file gambar ada atau tidak di server (root folder project)
        if (empty($poster) || !file_exists($poster)) {
            $poster = 'img/default-poster.jpg'; // fallback jika file gak ada
        }
        ?>

        <div class="top-pick-box">
            <img src="<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($title); ?>"
                class="top-pick-img">

            <div class="top-pick-content">
                <h3 class="top-pick-title"><?php echo htmlspecialchars($title); ?></h3>
                <p class="top-pick-genre"><?php echo htmlspecialchars($genre); ?></p>

                <div class="rating-section">
                    <div class="rating-item">
                        <img src="img/imdb.jpg" alt="IMDb" class="rating-logo">
                        <span class="rating-score"><?php echo htmlspecialchars($imdb); ?> / 10</span>
                    </div>
                    <div class="rating-item">
                        <img src="img/rotten_tomatoes.jpeg" alt="Rotten Tomatoes" class="rating-logo">
                        <span class="rating-score"><?php echo htmlspecialchars($rotten); ?>%</span>
                    </div>
                    <div class="rating-item">
                        <img src="img/metacritic.webp" alt="Metacritic" class="rating-logo">
                        <span class="rating-score"><?php echo htmlspecialchars($metacritic); ?>%</span>
                    </div>
                </div>

                <span class="rank-number">#<?php echo $row['position']; ?></span>
            </div>
        </div>
    <?php endwhile; ?>
</div>