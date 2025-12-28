<?php
include 'db.php';

$sql_movies = "SELECT * FROM news_movies ORDER BY publish_date DESC";
$result_movies = mysqli_query($conn, $sql_movies);

$sql_celebrity = "SELECT * FROM news_celebrity ORDER BY publish_date DESC";
$result_celebrity = mysqli_query($conn, $sql_celebrity);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>News Admin</title>
</head>

<body>
    <div class="admin-news-container">

        <!-- Celebrity News -->
        <section class="admin-section admin-celebritynews-section">
            <h2>Celebrity News</h2>
            <a href="add_celebrity_news.php" class="add-btn">+ Add Celebrity News</a>
            <table class="admin-celebritynews-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publish Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_celebrity)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['author']) ?></td>
                            <td><?= htmlspecialchars($row['publish_date']) ?></td>
                            <td>
                                <button class="modal-newsadmin-editbtn"
                                    onclick='openEditModal("celebrity", <?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>Edit</button>
                                <button class="modal-newsadmin-delbtn"
                                    onclick='openDeleteModal("celebrity", <?= $row["id"] ?>)'>Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Movie News -->
        <section class="admin-section admin-movienews-section">
            <h2>Movie News</h2>
            <a href="add_movie_news.php" class="add-btn">+ Add Movie News</a>
            <table class="admin-movienews-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publish Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_movies)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['author']) ?></td>
                            <td><?= htmlspecialchars($row['publish_date']) ?></td>
                            <td>
                                <button class="modal-newsadmin-editbtn"
                                    onclick='openEditModal("movie", <?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>Edit</button>
                                <button class="modal-newsadmin-delbtn"
                                    onclick='openDeleteModal("movie", <?= $row["id"] ?>)'>Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

    </div>

    <!-- Edit Modal -->
    <div class="modal-newsadmin" id="editModal">
        <div class="modal-newsadmin-content">
            <span class="modal-newsadmin-close" onclick="closeModal()">&times;</span>
            <form id="editForm" method="POST" action="process/process_update_newsadmin.php">
                <input type="hidden" name="type" id="editType" />
                <input type="hidden" name="id" id="editId" />
                <label for="editTitle">Title:</label>
                <input type="text" name="title" id="editTitle" required />

                <label for="editDescription">Description:</label>
                <textarea name="description" id="editDescription" required></textarea>

                <label for="editAuthor">Author:</label>
                <input type="text" name="author" id="editAuthor" />

                <label for="editDate">Publish Date:</label>
                <input type="date" name="publish_date" id="editDate" />

                <label for="editUrl">URL:</label>
                <input type="text" name="url" id="editUrl" required />

                <label for="editImage">Image Path:</label>
                <input type="text" name="image" id="editImage" required />

                <button type="submit" class="modal-newsadmin-submitbtn">Update</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-newsadmin" id="deleteModal">
        <div class="modal-newsadmin-content">
            <span class="modal-newsadmin-close" onclick="closeModal()">&times;</span>
            <p>Are you sure you want to delete this news item?</p>
            <form id="deleteForm" method="POST" action="process/process_delete_newsadmin.php">
                <input type="hidden" name="type" id="deleteType" />
                <input type="hidden" name="id" id="deleteId" />
                <button type="submit" class="modal-newsadmin-delconfirmbtn">Yes, Delete</button>
                <button type="button" class="modal-newsadmin-cancelbtn" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add Celebrity News Modal -->
    <div class="modal-newsadmin" id="addModalCelebrity">
        <div class="modal-newsadmin-content">
            <span class="modal-newsadmin-close" onclick="closeModal()">&times;</span>
            <form id="addFormCelebrity" method="POST" action="process/process_add_news.php">
                <input type="hidden" name="type" value="celebrity" />
                <label for="addTitleCelebrity">Title:</label>
                <input type="text" name="title" id="addTitleCelebrity" required />

                <label for="addDescriptionCelebrity">Description:</label>
                <textarea name="description" id="addDescriptionCelebrity" required></textarea>

                <label for="addAuthorCelebrity">Author:</label>
                <input type="text" name="author" id="addAuthorCelebrity" />

                <label for="addDateCelebrity">Publish Date:</label>
                <input type="date" name="publish_date" id="addDateCelebrity" />

                <label for="addUrlCelebrity">URL:</label>
                <input type="text" name="url" id="addUrlCelebrity" required />

                <label for="addImageCelebrity">Image Path:</label>
                <input type="text" name="image" id="addImageCelebrity" required />

                <button type="submit" class="modal-newsadmin-submitbtn">Add Celebrity News</button>
            </form>
        </div>
    </div>

    <!-- Add Movie News Modal -->
    <div class="modal-newsadmin" id="addModalMovie">
        <div class="modal-newsadmin-content">
            <span class="modal-newsadmin-close" onclick="closeModal()">&times;</span>
            <form id="addFormMovie" method="POST" action="process/process_add_news.php">
                <input type="hidden" name="type" value="movie" />
                <label for="addTitleMovie">Title:</label>
                <input type="text" name="title" id="addTitleMovie" required />

                <label for="addDescriptionMovie">Description:</label>
                <textarea name="description" id="addDescriptionMovie" required></textarea>

                <label for="addAuthorMovie">Author:</label>
                <input type="text" name="author" id="addAuthorMovie" />

                <label for="addDateMovie">Publish Date:</label>
                <input type="date" name="publish_date" id="addDateMovie" />

                <label for="addUrlMovie">URL:</label>
                <input type="text" name="url" id="addUrlMovie" required />

                <label for="addImageMovie">Image Path:</label>
                <input type="text" name="image" id="addImageMovie" required />

                <button type="submit" class="modal-newsadmin-submitbtn">Add Movie News</button>
            </form>
        </div>
    </div>


    <!-- jQuery (needed for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function openEditModal(type, data) {
            document.getElementById('editModal').style.display = 'block';

            document.getElementById('editType').value = type || '';
            document.getElementById('editId').value = data.id || '';
            document.getElementById('editTitle').value = data.title || '';
            document.getElementById('editDescription').value = data.description || '';
            document.getElementById('editAuthor').value = data.author || '';
            document.getElementById('editDate').value = data.publish_date || '';
            document.getElementById('editUrl').value = data.url || '';
            document.getElementById('editImage').value = data.image || '';
        }

        function openDeleteModal(type, id) {
            document.getElementById('deleteModal').style.display = 'block';

            document.getElementById('deleteType').value = type || '';
            document.getElementById('deleteId').value = id || '';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Klik di luar modal tutup modal
        window.onclick = function (event) {
            let editModal = document.getElementById('editModal');
            let deleteModal = document.getElementById('deleteModal');
            if (event.target === editModal) {
                closeModal();
            }
            if (event.target === deleteModal) {
                closeModal();
            }
        };

        // AJAX Submit Edit Form
        $('#editForm').submit(function (e) {
            e.preventDefault(); // cegah form submit default
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert('Update berhasil!');
                    closeModal();
                    window.location.reload(); // reload halaman setelah sukses
                },
                error: function () {
                    alert('Terjadi kesalahan saat update.');
                }
            });
        });

        // AJAX Submit Delete Form
        $('#deleteForm').submit(function (e) {
            e.preventDefault(); // cegah form submit default
            if (confirm('Yakin ingin menghapus?')) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert('Data berhasil dihapus!');
                        closeModal();
                        window.location.reload(); // reload halaman setelah sukses
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat menghapus.');
                    }
                });
            }
        });

        // Open Add Modals
        document.querySelector('a[href="add_celebrity_news.php"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('addModalCelebrity').style.display = 'block';
        });

        document.querySelector('a[href="add_movie_news.php"]').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('addModalMovie').style.display = 'block';
        });

        // Optional: Ajax Submit Add (bisa juga di-handle langsung di PHP)
        $('#addFormCelebrity, #addFormMovie').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function () {
                    alert('News berhasil ditambahkan!');
                    closeModal();
                    window.location.reload();
                },
                error: function () {
                    alert('Gagal menambahkan news.');
                }
            });
        });

    </script>

</body>

</html>

<?php mysqli_close($conn); ?>