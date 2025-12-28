<?php
include 'db.php';

// Tahun default jika tidak ada di URL
$year = isset($_GET['year']) ? (int) $_GET['year'] : 2025;

// Cek apakah tahun ada di database
$stmtYear = $conn->prepare("SELECT id FROM award_years WHERE year = ?");
$stmtYear->bind_param("i", $year);
$stmtYear->execute();
$resultYear = $stmtYear->get_result();

if ($resultYear->num_rows === 0) {
  $year = null;
  $year_id = null;
} else {
  $row = $resultYear->fetch_assoc();
  $year_id = $row['id'];
}

// Ambil daftar awards berdasarkan tahun (jika valid)
$awards = [];
if ($year_id !== null) {
  $stmtAwards = $conn->prepare("SELECT category, winner, image_path FROM awards WHERE year_id = ?");
  $stmtAwards->bind_param("i", $year_id);
  $stmtAwards->execute();
  $resultAwards = $stmtAwards->get_result();
  while ($row = $resultAwards->fetch_assoc()) {
    $awards[] = $row;
  }
}

// Ambil semua tahun yang tersedia untuk tombol samping
$resultYears = $conn->query("SELECT year FROM award_years ORDER BY year DESC");
$years = [];
while ($y = $resultYears->fetch_assoc()) {
  $years[] = $y['year'];
}
?>

<div class="row-awards">
  <div class="container-awards">
    <div class="header-awards">
      <h1><?= $year ? $year . ' Awards' : 'Year Not Found'; ?></h1>
      <h2>Oscar</h2>
    </div>

    <?php if ($year === null || empty($awards)): ?>
      <p>No awards available yet.</p>
    <?php else: ?>
      <?php foreach ($awards as $award): ?>
        <p class="card-description-awards"><?= htmlspecialchars($award['category']); ?></p>
        <div class="card-awards">
          <div class="card-content-awards">
            <div class="card-left">
              <img src="<?= htmlspecialchars($award['image_path']); ?>"
                alt="<?= htmlspecialchars($award['winner']); ?> Poster" class="poster-awards">
            </div>
            <div class="card-right">
              <h3 class="the-winner"><?= htmlspecialchars($award['winner']); ?></h3>
              <div class="winner-badge-awards">WINNER</div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Kolom Tahun -->
  <div class="history-column-awards">
    <h2>Event History</h2>
    <ul class="year-list-awards">
      <?php foreach ($years as $y): ?>
        <?php $isActive = ($y == $year) ? 'active-year' : ''; ?>
        <li class="<?= $isActive ?>" onclick="window.location.href='index.php?page=awards&year=<?= $y ?>'">
          <?= $y ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>