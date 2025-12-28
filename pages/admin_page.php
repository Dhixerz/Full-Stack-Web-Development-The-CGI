<?php
include 'db.php';

$query = "SELECT * FROM movies";
$result = $conn->query($query);
?>

<!-- Tombol Add Movie/Show -->
<div class="adminmovies-add-button-container">
    <button class="adminmovies-add-button">ADD MOVIE</button>
</div>

<!-- Modal Add Movie -->
<div class="modal-moviesupdate" id="modal-add" style="display:none;">
    <div class="modal-moviesupdate-content">
        <span class="modal-moviesupdate-close modal-moviesupdate-add-close" id="modal-add-close">&times;</span>
        <h2>Add New Movie</h2>
        <form id="form-add-movie" action="process/process_add_movie.php" method="post">
            <!-- MOVIE INFO -->
            <h3 style="margin-top:1rem;">Movie Info</h3>
            <label>Title</label>
            <input type="text" name="title" required>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>Genre</label>
            <input type="text" name="genre" required>

            <label>Rating</label>
            <select name="rating" required>
                <?php for ($i = 1; $i <= 5; $i++)
                    echo "<option value=\"$i\">$i</option>"; ?>
            </select>

            <label>Image Path</label>
            <input type="text" name="image_path" required>

            <label>Video Path</label>
            <input type="text" name="video_path" required>

            <label>Image Poster</label>
            <input type="text" name="image_poster" required>

            <label>IMDb Rating</label>
            <input type="number" step="0.1" min="0" max="10" name="rating_imdb" required>

            <label>Rotten Rating</label>
            <input type="number" step="1" min="0" max="100" name="rating_rotten" required>

            <label>Metacritic Rating</label>
            <input type="number" step="1" min="0" max="100" name="rating_metacritic" required>

            <!-- CAST -->
            <h3 style="margin-top:2rem;">Cast</h3>
            <div id="cast-container-add">
                <!-- Cast fields will be added here dynamically -->
            </div>
            <button type="button" class="add-cast-btn" data-movie-id="new">+ Add Cast</button>

            <!-- DOWNLOADS -->
            <h3 style="margin-top:2rem;">Downloads</h3>
            <?php foreach (['1080p', '720p', '480p'] as $res): ?>
                <input type="hidden" name="download_resolutions[]" value="<?= $res ?>">
                <label><?= $res ?> File Path</label>
                <input type="text" name="download_paths[]" required>
            <?php endforeach; ?>

            <!-- ACTION BUTTONS -->
            <div style="margin-top: 1rem;">
                <button type="submit">Save</button>
                <button type="button" id="modal-add-cancel" class="modal-moviesupdate-add-cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>




<?php while ($row = $result->fetch_assoc()): ?>
    <div class="adminmovies-container">
        <div class="adminmovies-content-box">
            <div class="adminmovies-poster" style="display: flex; justify-content: center; align-items: center;">
                <img src="<?php echo htmlspecialchars($row['image_poster']); ?>"
                    alt="<?php echo htmlspecialchars($row['title']); ?> Poster"
                    style="max-width: 250px; height: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.5);">
            </div>
            <div class="adminmovies-details">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <div class="adminmovies-tags">
                    <span class="adminmovies-tag"><?php echo htmlspecialchars($row['genre']); ?></span>
                    <span class="adminmovies-tag">Rating: <?php echo htmlspecialchars($row['rating']); ?></span>
                    <span class="adminmovies-tag">IMDb:
                        <?php echo ($row['rating_imdb'] !== null) ? htmlspecialchars($row['rating_imdb']) : '-'; ?></span>
                    <span class="adminmovies-tag">Rotten:
                        <?php echo ($row['rating_rotten'] !== null) ? htmlspecialchars($row['rating_rotten']) : '-'; ?></span>
                    <span class="adminmovies-tag">Meta:
                        <?php echo ($row['rating_metacritic'] !== null) ? htmlspecialchars($row['rating_metacritic']) : '-'; ?></span>
                </div>
                <div class="adminmovies-path-box">
                    <div>
                        <label>Image Path</label>
                        <p><?php echo htmlspecialchars($row['image_path']); ?></p>
                    </div>
                    <div>
                        <label>Video Path</label>
                        <p><?php echo htmlspecialchars($row['video_path']); ?></p>
                    </div>
                </div>
                <div class="adminmovies-cast">
                    <?php
                    $movie_id = $row['id'];
                    $cast_query = "SELECT * FROM cast WHERE movie_id = $movie_id";
                    $cast_result = $conn->query($cast_query);
                    while ($cast = $cast_result->fetch_assoc()):
                        ?>
                        <div class="adminmovies-cast-card">
                            <img src="<?php echo htmlspecialchars($cast['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($cast['name']); ?>"
                                style="width: 100px; border-radius: 5px; border: 2px solid var(--main-color);">
                            <p><?php echo htmlspecialchars($cast['name']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <div class="adminmovies-buttons"
            style="border-top: 1px solid var(--main-color); margin-top: 1.5rem; padding-top: 1rem;">
            <button class="adminmovies-edit" data-movie-id="<?= $row['id'] ?>">EDIT</button>
            <button class="adminmovies-delete" data-movie-id="<?= $row['id'] ?>">DELETE</button>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal-moviesupdate" id="modal-edit-<?= $row['id'] ?>" style="display:none;">
        <div class="modal-moviesupdate-content">
            <span class="modal-moviesupdate-close" data-movie-id="<?= $row['id'] ?>">&times;</span>
            <h2>Edit Movie: <?= htmlspecialchars($row['title']) ?></h2>
            <form action="process/process_update_movies.php" method="post">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <!-- MOVIES -->
                <h3 style="margin-top:1rem;">Movie Info</h3>
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>

                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea>

                <label>Genre</label>
                <input type="text" name="genre" value="<?= htmlspecialchars($row['genre']) ?>">

                <label>Rating</label>
                <select name="rating" required>
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        $selected = ($row['rating'] == $i) ? 'selected' : '';
                        echo "<option value=\"$i\" $selected>$i</option>";
                    }
                    ?>
                </select>

                <label>Image Path</label>
                <input type="text" name="image_path" value="<?= htmlspecialchars($row['image_path']) ?>">

                <label>Video Path</label>
                <input type="text" name="video_path" value="<?= htmlspecialchars($row['video_path']) ?>">

                <label>Image Poster</label>
                <input type="text" name="image_poster" value="<?= htmlspecialchars($row['image_poster']) ?>">

                <label>IMDb Rating</label>
                <input type="number" step="0.1" min="0" max="10" name="rating_imdb"
                    value="<?= htmlspecialchars($row['rating_imdb']) ?>">

                <label>Rotten Rating</label>
                <input type="number" step="1" min="0" max="100" name="rating_rotten"
                    value="<?= htmlspecialchars($row['rating_rotten']) ?>">

                <label>Metacritic Rating</label>
                <input type="number" step="1" min="0" max="100" name="rating_metacritic"
                    value="<?= htmlspecialchars($row['rating_metacritic']) ?>">


                <!-- CAST -->
                <h3 style="margin-top:2rem;">Cast</h3>
                <div id="cast-container-<?= $row['id'] ?>">
                    <?php
                    $cast_result = $conn->query("SELECT * FROM cast WHERE movie_id = $movie_id");
                    while ($cast = $cast_result->fetch_assoc()):
                        ?>
                        <div class="cast-group" data-cast-id="<?= $cast['id'] ?>">
                            <input type="hidden" name="cast_ids[]" value="<?= $cast['id'] ?>">
                            <label>Cast Name</label>
                            <input type="text" name="cast_names[]" value="<?= htmlspecialchars($cast['name']) ?>">
                            <label>Cast Image</label>
                            <input type="text" name="cast_images[]" value="<?= htmlspecialchars($cast['image_path']) ?>">
                            <button type="button" class="delete-cast-btn" data-cast-id="<?= $cast['id'] ?>">Delete</button>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Tombol Add Cast -->
                <button type="button" class="add-cast-btn" data-movie-id="<?= $row['id'] ?>">+ Add Cast</button>
                <input type="hidden" name="deleted_cast_ids" id="deleted-cast-ids-<?= $row['id'] ?>">


                <!-- DOWNLOADS -->
                <h3 style="margin-top:2rem;">Downloads</h3>
                <?php
                $download_query = "SELECT * FROM downloads WHERE movie_id = $movie_id";
                $download_result = $conn->query($download_query);
                while ($dl = $download_result->fetch_assoc()):
                    ?>
                    <input type="hidden" name="download_ids[]" value="<?= $dl['id'] ?>">
                    <label>Resolution</label>
                    <input type="text" name="download_resolutions[]" value="<?= htmlspecialchars($dl['resolution']) ?>"
                        readonly>
                    <input type="hidden" name="download_resolutions[]" value="<?= htmlspecialchars($dl['resolution']) ?>">
                    <label>File Path</label>
                    <input type="text" name="download_paths[]" value="<?= htmlspecialchars($dl['file_path']) ?>">
                <?php endwhile; ?>

                <div style="margin-top: 1rem;">
                    <button type="submit">Save</button>
                    <button type="button" class="modal-moviesupdate-cancel"
                        data-movie-id="<?= $row['id'] ?>">Cancel</button>
                </div>
            </form>
        </div>
    </div>

<?php endwhile; ?>

<!-- JAVASCRIPT -->
<script>
    document.querySelectorAll('.adminmovies-edit').forEach(button => {
        button.addEventListener('click', () => {
            const movieId = button.getAttribute('data-movie-id');
            document.getElementById('modal-edit-' + movieId).style.display = 'flex';
        });
    });

    document.querySelectorAll('.modal-moviesupdate-close, .modal-moviesupdate-cancel').forEach(button => {
        button.addEventListener('click', () => {
            const movieId = button.getAttribute('data-movie-id');
            document.getElementById('modal-edit-' + movieId).style.display = 'none';
        });
    });

    document.querySelectorAll('.modal-moviesupdate').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.add-cast-btn').forEach(button => {
        button.addEventListener('click', () => {
            const movieId = button.getAttribute('data-movie-id');
            const container = document.getElementById('cast-container-' + movieId);

            const wrapper = document.createElement('div');
            wrapper.className = 'cast-group';

            wrapper.innerHTML = `
            <label>New Cast Name</label>
            <input type="text" name="new_cast_names[]" required>
            <label>New Cast Image</label>
            <input type="text" name="new_cast_images[]" required>
        `;

            container.appendChild(wrapper);
        });
    });

    document.querySelectorAll('.delete-cast-btn').forEach(button => {
        button.addEventListener('click', () => {
            const castId = button.getAttribute('data-cast-id');
            const confirmDelete = confirm('Are you sure you want to delete this cast?');

            if (confirmDelete) {
                const group = button.closest('.cast-group');
                const form = button.closest('form');
                const hiddenField = form.querySelector('[id^="deleted-cast-ids"]');

                // Tambahkan ID cast ke hidden input
                if (hiddenField.value) {
                    hiddenField.value += ',' + castId;
                } else {
                    hiddenField.value = castId;
                }

                // Hapus dari tampilan
                group.remove();
            }
        });
    });

    document.querySelectorAll('.adminmovies-delete').forEach(button => {
        button.addEventListener('click', function () {
            const movieId = this.getAttribute('data-movie-id');
            if (confirm('Are you sure you want to delete this movie and all its related data?')) {
                fetch('process/process_delete_movie.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'movie_id=' + encodeURIComponent(movieId)
                })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        window.location.reload();
                    })
                    .catch(error => {
                        alert('Error deleting movie.');
                        console.error('Error:', error);
                    });
            }
        });
    });

    // === TOMBOL ADD MOVIE ===
    const addButton = document.querySelector('.adminmovies-add-button');
    const addModal = document.getElementById('modal-add');
    const formAddMovie = document.getElementById('form-add-movie');

    if (addButton && addModal && formAddMovie) {
        // Buka modal saat tombol add diklik
        addButton.addEventListener('click', () => {
            addModal.style.display = 'flex';
        });

        // Tutup modal saat klik tombol close (X) atau cancel
        document.querySelectorAll('.modal-moviesupdate-add-close, .modal-moviesupdate-add-cancel').forEach(button => {
            button.addEventListener('click', () => {
                addModal.style.display = 'none';
            });
        });

        // Tutup modal jika klik area luar konten modal
        addModal.addEventListener('click', (e) => {
            if (e.target === addModal) {
                addModal.style.display = 'none';
            }
        });

        // Tombol tambah cast di modal add
        const addCastBtn = addModal.querySelector('.add-cast-btn');
        const castContainer = addModal.querySelector('#cast-container-add');

        if (addCastBtn && castContainer) {
            addCastBtn.addEventListener('click', () => {
                const wrapper = document.createElement('div');
                wrapper.className = 'cast-group';

                wrapper.innerHTML = `
                <label>New Cast Name</label>
                <input type="text" name="cast_names[]" required>
                <label>New Cast Image</label>
                <input type="text" name="cast_images[]" required>
            `;

                castContainer.appendChild(wrapper);
            });
        }

        // Submit form dengan AJAX supaya tidak pindah halaman
        formAddMovie.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(formAddMovie);

            fetch(formAddMovie.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert('Movie added successfully!');

                    // Reset form dan tutup modal
                    formAddMovie.reset();
                    castContainer.innerHTML = ''; // reset cast container juga
                    addModal.style.display = 'none';

                    // Reload halaman supaya movie baru muncul
                    location.reload();
                })
                .catch(error => {
                    alert('Failed to add movie: ' + error.message);
                });
        });
    }

</script>