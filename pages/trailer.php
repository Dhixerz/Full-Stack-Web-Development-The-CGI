<?php
include("db.php");

$movie_id = $_GET['movie'] ?? null;
$show_id = $_GET['show'] ?? null;

if ($movie_id !== null) {
    $movie_id = intval($movie_id);
    $sql = "SELECT * FROM movies WHERE id = $movie_id";
    $result = $conn->query($sql);
    $movie = $result->fetch_assoc();

    if (!$movie) {
        echo "<h2>Movie not found.</h2>";
        exit;
    }

    $cast = $conn->query("SELECT * FROM cast WHERE movie_id = $movie_id");
    $downloads = $conn->query("SELECT * FROM downloads WHERE movie_id = $movie_id");
    ?>

    <!-- existing movie structure -->
    <div class="play-container container">
        <img src="<?= htmlspecialchars($movie['image_path']) ?>" alt="" class="play-img">
        <div class="play-text">
            <h2><?= htmlspecialchars($movie['title']) ?></h2>
            <div class="rating">
                <?php for ($i = 0; $i < floor($movie['rating']); $i++): ?>
                    <i class='bx bxs-star'></i>
                <?php endfor; ?>
            </div>
            <div class="tags">
                <?php foreach (explode(', ', $movie['genre']) as $g): ?>
                    <span><?= htmlspecialchars($g) ?></span>
                <?php endforeach; ?>
            </div>
            <a href="#" class="watch-btn">
                <i class='bx bx-right-arrow'></i>
                <span style="color: white">Watch the trailer</span>
            </a>
        </div>
        <i class='bx bx-right-arrow play-movie'></i>
        <div class="video-container">
            <div class="video-box">
                <video id="myvideo" src="<?= htmlspecialchars($movie['video_path']) ?>" controls></video>
                <i class='bx bx-x close-video'></i>
            </div>
        </div>
    </div>

    <div class="about-movie container">
        <h2><?= htmlspecialchars($movie['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>

        <h2 class="cast-heading">Movie Cast</h2>
        <div class="cast">
            <?php while ($c = $cast->fetch_assoc()): ?>
                <div class="cast-box">
                    <img src="<?= htmlspecialchars($c['image_path']) ?>" alt="" class="cast-img">
                    <span class="cast-title"><?= htmlspecialchars($c['name']) ?></span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="download">
        <h2 class="download-title">Download Movie</h2>
        <div class="download-links">
            <?php while ($d = $downloads->fetch_assoc()): ?>
                <a href="<?= htmlspecialchars($d['file_path']) ?>" download><?= htmlspecialchars($d['resolution']) ?></a>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="next-page">
        <a href="index.php?page=movies" class="next-btn">Browse More</a>
    </div>

    <?php
} elseif ($show_id !== null) {
    $show_id = intval($show_id);
    $sql = "SELECT * FROM shows WHERE id = $show_id";
    $result = $conn->query($sql);
    $show = $result->fetch_assoc();

    if (!$show) {
        echo "<h2>Show not found.</h2>";
        exit;
    }

    $cast = $conn->query("SELECT * FROM cast WHERE show_id = $show_id");
    $downloads = $conn->query("SELECT * FROM downloads WHERE show_id = $show_id");
    ?>

    <!-- show section starts -->
    <div class="play-container container">
        <img src="<?= htmlspecialchars($show['image_path']) ?>" alt="" class="play-img">
        <div class="play-text">
            <h2><?= htmlspecialchars($show['title']) ?></h2>
            <div class="rating">
                <?php for ($i = 0; $i < floor($show['rating']); $i++): ?>
                    <i class='bx bxs-star'></i>
                <?php endfor; ?>
            </div>
            <div class="tags">
                <?php foreach (explode(', ', $show['genre']) as $g): ?>
                    <span><?= htmlspecialchars($g) ?></span>
                <?php endforeach; ?>
            </div>
            <a href="#" class="watch-btn">
                <i class='bx bx-right-arrow'></i>
                <span style="color: white">Watch the trailer</span>
            </a>
        </div>
        <i class='bx bx-right-arrow play-movie'></i>
        <div class="video-container">
            <div class="video-box">
                <video id="myvideo" src="<?= htmlspecialchars($show['video_path']) ?>" controls></video>
                <i class='bx bx-x close-video'></i>
            </div>
        </div>
    </div>

    <div class="about-movie container">
        <h2><?= htmlspecialchars($show['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($show['description'])) ?></p>

        <h2 class="cast-heading">Show Cast</h2>
        <div class="cast">
            <?php while ($c = $cast->fetch_assoc()): ?>
                <div class="cast-box">
                    <img src="<?= htmlspecialchars($c['image_path']) ?>" alt="" class="cast-img">
                    <span class="cast-title"><?= htmlspecialchars($c['name']) ?></span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="download">
        <h2 class="download-title">Download Show</h2>
        <div class="download-links">
            <?php while ($d = $downloads->fetch_assoc()): ?>
                <a href="<?= htmlspecialchars($d['file_path']) ?>" download><?= htmlspecialchars($d['resolution']) ?></a>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="next-page">
        <a href="index.php?page=movies" class="next-btn">Browse More</a>
    </div>

    <?php
} else {
    echo "<h2>No movie or show selected.</h2>";
}
?>


<script>
    // Tunggu sampai DOM siap
    document.addEventListener('DOMContentLoaded', () => {
        const playContainers = document.querySelectorAll('.play-container');

        playContainers.forEach(container => {
            const playBtn = container.querySelector('.play-movie');
            const watchBtn = container.querySelector('.watch-btn');
            const videoContainer = container.querySelector('.video-container');
            const video = videoContainer.querySelector('video');
            const closeBtn = videoContainer.querySelector('.close-video');

            function openVideo(e) {
                e.preventDefault();
                videoContainer.style.display = 'flex';
                video.play();
            }

            function closeVideo() {
                video.pause();
                video.currentTime = 0;
                videoContainer.style.display = 'none';
            }

            if (playBtn) {
                playBtn.addEventListener('click', openVideo);
            }
            if (watchBtn) {
                watchBtn.addEventListener('click', openVideo);
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', closeVideo);
            }
        });
    });
</script>