<?php
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login_admin");
    exit();
}

// Query data popular dengan join ke movies
$sql = "SELECT p.id AS popular_id, m.*
        FROM popular p
        JOIN movies m ON p.movie_id = m.id
        ORDER BY p.id ASC";

$result = $conn->query($sql);
?>

<!-- Tombol Add Popular Movie -->
<div class="adminpopular-top-button">
    <button class="adminpopular-btn" onclick="openAddPopularModal()">ADD POPULAR MOVIE</button>
</div>

<!-- Modal Tambah Popular Movie -->
<div id="addbuttonpopularmovie-modal" class="addbuttonpopularmovie-modal">
    <div class="addbuttonpopularmovie-modal-content">
        <h3 class="addbuttonpopularmovie-title">Select Movie to Add as Popular</h3>
        <form id="addPopularForm">
            <select name="movie_id" id="movieSelect" required class="addbuttonpopularmovie-select">
                <option value="">-- Select Movie --</option>
                <?php
                // Ambil semua movie_id dari popular
                $existingPopular = [];
                $res = $conn->query("SELECT movie_id FROM popular");
                while ($row = $res->fetch_assoc()) {
                    $existingPopular[] = $row['movie_id'];
                }

                // Ambil semua movies yang belum masuk ke popular
                $moviesQuery = "SELECT id, title FROM movies";
                $moviesResult = $conn->query($moviesQuery);

                while ($movie = $moviesResult->fetch_assoc()):
                    if (!in_array($movie['id'], $existingPopular)):
                        ?>
                        <option value="<?= $movie['id'] ?>"><?= htmlspecialchars($movie['title']) ?></option>
                        <?php
                    endif;
                endwhile;
                ?>
            </select>


            <div class="addbuttonpopularmovie-buttons">
                <button type="button" onclick="closeAddPopularModal()"
                    class="addbuttonpopularmovie-cancel">Cancel</button>
                <button type="submit" class="addbuttonpopularmovie-confirm">Confirm</button>
            </div>
        </form>
    </div>
</div>


<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($movie = $result->fetch_assoc()): ?>
        <div class="adminpopular-container">
            <div class="adminpopular-content">
                <div class="adminpopular-poster">
                    <img src="<?= htmlspecialchars($movie['image_poster']) ?>"
                        alt="<?= htmlspecialchars($movie['title']) ?> Poster">
                </div>
                <div class="adminpopular-details">
                    <h2><?= htmlspecialchars($movie['title']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
                    <div class="adminpopular-tags">
                        <span class="adminpopular-tag"><?= htmlspecialchars($movie['genre']) ?></span>
                        <span class="adminpopular-tag">Rating: <?= htmlspecialchars($movie['rating']) ?></span>
                        <span class="adminpopular-tag">IMDb: <?= htmlspecialchars($movie['rating_imdb']) ?></span>
                        <span class="adminpopular-tag">Rotten: <?= htmlspecialchars($movie['rating_rotten']) ?></span>
                        <span class="adminpopular-tag">Meta: <?= htmlspecialchars($movie['rating_metacritic']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Tombol Remove -->
            <div class="adminpopular-remove-button">
                <form method="post" class="remove-popular-form" data-popular-id="<?= $movie['popular_id'] ?>"
                    onsubmit="return confirm('Remove <?= htmlspecialchars(addslashes($movie['title'])) ?> from popular?');">
                    <button type="submit" class="adminpopular-btn">REMOVE FROM POPULAR</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="padding: 20px;">No popular movies found.</p>
<?php endif; ?>

<script>
    document.querySelectorAll('.remove-popular-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const popularId = this.dataset.popularId;

            fetch('process/process_remove_popular.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'popular_id=' + encodeURIComponent(popularId)
            })
                .then(response => response.text())
                .then(result => {
                    // Auto-reload halaman untuk menampilkan hasil perubahan
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data.');
                });
        });
    });

    // add popular 
    function openAddPopularModal() {
        document.getElementById('addbuttonpopularmovie-modal').style.display = 'flex';
    }

    function closeAddPopularModal() {
        document.getElementById('addbuttonpopularmovie-modal').style.display = 'none';
        document.getElementById('movieSelect').value = '';
    }


    document.getElementById('addPopularForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const movieId = document.getElementById('movieSelect').value;

        if (!movieId) {
            alert('Please select a movie.');
            return;
        }

        fetch('process/process_add_popular.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'movie_id=' + encodeURIComponent(movieId)
        })
            .then(res => res.text())
            .then(result => {
                if (result === 'success') {
                    closeAddPopularModal();
                    window.location.reload();
                } else {
                    alert('Failed to add movie to popular: ' + result);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error occurred.');
            });
    });
</script>