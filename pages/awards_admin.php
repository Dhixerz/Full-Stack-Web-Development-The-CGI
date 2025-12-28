<?php
include 'db.php';

// award_years
$yearsResult = mysqli_query($conn, "SELECT * FROM award_years ORDER BY year DESC");

// Tangkap tahun yang dipilih dari URL
$selectedYear = $_GET['year'] ?? null;
$awards = [];

if ($selectedYear) {
    $stmt = $conn->prepare("
        SELECT a.* FROM awards a
        INNER JOIN award_years y ON a.year_id = y.id
        WHERE y.year = ?
    ");
    $stmt->bind_param("i", $selectedYear);
    $stmt->execute();
    $awards = $stmt->get_result();
}
?>

<link rel="stylesheet" href="style/awards_admin.css">
<link rel="stylesheet" href="style/modal-addawards.css">

<div class="awards-admin-container container">
    <h1 class="awards-admin-title">Admin Awards Management</h1>

    <!-- Tombol Tahun -->
    <div class="awards-admin-buttons">
        <?php while ($row = mysqli_fetch_assoc($yearsResult)): ?>
            <a href="index.php?page=awards_admin&year=<?= $row['year'] ?>"
                class="awards-admin-button <?= ($selectedYear == $row['year']) ? 'active' : '' ?>">
                <?= $row['year'] ?>
            </a>
        <?php endwhile; ?>

        <!-- Tombol Tambah Tahun -->
        <button class="awards-admin-button" id="btnAddYear">+ Add Year</button>
    </div>

    <!-- Tabel Awards -->
    <?php if ($selectedYear): ?>
        <div class="awards-admin-table-wrapper">
            <div class="awards-admin-table-header">
                <h2>Awards for <?= htmlspecialchars($selectedYear) ?></h2>
                <button class="awards-admin-add-btn" id="btnAddAward">+ Add Award</button>
            </div>
            <table class="awards-admin-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Winner</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($award = $awards->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($award['category']) ?></td>
                            <td><?= htmlspecialchars($award['winner']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($award['image_path']) ?>" alt="Award Image"
                                    class="awards-admin-img">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="awards-admin-info">Please select a year to view the awards.</p>
    <?php endif; ?>
</div>

<!-- MODAL: Add Year -->
<div class="modal-addawards-overlay" id="modalAddYear">
    <div class="modal-addawards">
        <h2>Add New Year</h2>
        <form action="process/process_add_award.php" method="POST">
            <input type="hidden" name="action" value="add_year">
            <input type="number" name="year" placeholder="Enter year (e.g. 2025)" required>
            <div class="modal-addawards-buttons">
                <button type="submit">Submit</button>
                <button type="button" class="modal-addawards-close">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Award -->
<div class="modal-addawards-overlay" id="modalAddAward">
    <div class="modal-addawards">
        <h2>Add New Award</h2>
        <form id="formAddAward" action="process/process_add_award.php" method="POST">
            <input type="hidden" name="year_id" id="year_id" value="">

            <label for="category">Category</label>
            <input type="text" id="category" name="category" placeholder="Category" required>

            <label for="winner">Winner</label>
            <input type="text" id="winner" name="winner" placeholder="Winner" required>

            <label for="image_path">Image Path</label>
            <input type="text" id="image_path" name="image_path"
                placeholder="Enter image file path (e.g. img/award1.jpg)" required>

            <div class="modal-addawards-buttons">
                <button type="submit">Add Award</button>
                <button type="button" class="modal-addawards-close" id="closeModalBtn">Cancel</button>
            </div>
        </form>
    </div>
</div>


<!-- JavaScript Modal Logic -->
<script>
    // Tombol Buka Modal
    document.getElementById("btnAddYear").onclick = () => {
        document.getElementById("modalAddYear").style.display = "flex";
    };
    document.getElementById("btnAddAward").onclick = () => {
        document.getElementById("modalAddAward").style.display = "flex";
    };

    // Tombol Tutup Modal
    document.querySelectorAll(".modal-addawards-close").forEach(btn => {
        btn.onclick = () => {
            btn.closest(".modal-addawards-overlay").style.display = "none";
        };
    });
</script>