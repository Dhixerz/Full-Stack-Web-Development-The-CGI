<?php
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login_admin");
    exit();
}

// Ambil semua top picks (movie dan show)
$sql = "SELECT tp.id AS top_id, tp.content_type, tp.content_id, tp.position,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.title
            ELSE s.title
        END AS title,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.image_poster
            ELSE s.image_poster
        END AS image_poster,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.description
            ELSE s.description
        END AS description,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.genre
            ELSE s.genre
        END AS genre,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.rating
            ELSE s.rating
        END AS rating,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.rating_imdb
            ELSE s.rating_imdb
        END AS rating_imdb,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.rating_rotten
            ELSE s.rating_rotten
        END AS rating_rotten,
        CASE 
            WHEN tp.content_type = 'movie' THEN m.rating_metacritic
            ELSE s.rating_metacritic
        END AS rating_metacritic
    FROM toppicks tp
    LEFT JOIN movies m ON tp.content_type = 'movie' AND tp.content_id = m.id
    LEFT JOIN shows s ON tp.content_type = 'show' AND tp.content_id = s.id
    ORDER BY tp.position ASC";

$result = $conn->query($sql);

$top_picks_count = $conn->query("SELECT COUNT(*) AS total FROM toppicks")->fetch_assoc()['total'];
?>
?>

<!-- Tombol Tambah Top Pick -->
<div class="adminpopular-top-button">
    <button class="adminpopular-btn" onclick="openAddTopPickModal()" <?= $top_picks_count >= 10 ? 'disabled' : '' ?>>
        ADD TOP PICK <?= $top_picks_count >= 10 ? '(MAX 10 REACHED)' : '' ?>
    </button>
</div>

<!-- Modal Tambah -->
<div id="addbuttonpopularmovie-modal" class="addbuttonpopularmovie-modal" style="display:none;">
    <div class="addbuttonpopularmovie-modal-content">
        <h3 class="addbuttonpopularmovie-title">Select Movie/Show to Add as Top Pick</h3>
        <form id="addTopPickForm">
            <!-- Select Movie/Show -->
            <select name="content_id" id="topPickSelect" required class="addbuttonpopularmovie-select">
                <option value="">-- Select Content --</option>
                <optgroup label="Movies">
                    <?php
                    $existing = [];
                    $res = $conn->query("SELECT content_id, content_type, position FROM toppicks");
                    $used_positions = [];
                    while ($row = $res->fetch_assoc()) {
                        $existing[$row['content_type']][] = $row['content_id'];
                        $used_positions[] = (int) $row['position'];
                    }

                    $movies = $conn->query("SELECT id, title FROM movies");
                    while ($m = $movies->fetch_assoc()):
                        if (!in_array($m['id'], $existing['movie'] ?? [])):
                            ?>
                            <option value="movie-<?= $m['id'] ?>">🎬 <?= htmlspecialchars($m['title']) ?></option>
                        <?php endif; endwhile; ?>
                </optgroup>
                <optgroup label="Shows">
                    <?php
                    $shows = $conn->query("SELECT id, title FROM shows");
                    while ($s = $shows->fetch_assoc()):
                        if (!in_array($s['id'], $existing['show'] ?? [])):
                            ?>
                            <option value="show-<?= $s['id'] ?>">📺 <?= htmlspecialchars($s['title']) ?></option>
                        <?php endif; endwhile; ?>
                </optgroup>
            </select>

            <!-- Select Position -->
            <label for="positionSelect" style="margin-top:10px; display:block;">Select Position (1–10):</label>
            <select name="position" id="positionSelect" required class="addbuttonpopularmovie-select">
                <option value="">-- Select Position --</option>
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    if (in_array($i, $used_positions))
                        continue;
                    echo "<option value=\"$i\">$i</option>";
                }
                ?>
            </select>

            <div class="addbuttonpopularmovie-buttons">
                <button type="button" onclick="closeAddTopPickModal()"
                    class="addbuttonpopularmovie-cancel">Cancel</button>
                <button type="submit" class="addbuttonpopularmovie-confirm">Confirm</button>
            </div>
        </form>

    </div>
</div>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="adminpopular-container">
            <div class="adminpopular-content">
                <div class="adminpopular-poster">
                    <img src="<?= htmlspecialchars($row['image_poster']) ?>"
                        alt="<?= htmlspecialchars($row['title']) ?> Poster">
                </div>
                <div class="adminpopular-details">
                    <h2><?= htmlspecialchars($row['title']) ?> <small>(<?= ucfirst($row['content_type']) ?>)</small></h2>
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <div class="adminpopular-tags">
                        <span class="adminpopular-tag"><?= htmlspecialchars($row['genre']) ?></span>
                        <span class="adminpopular-tag">Rating: <?= htmlspecialchars($row['rating']) ?></span>
                        <span class="adminpopular-tag">IMDb: <?= htmlspecialchars($row['rating_imdb']) ?></span>
                        <span class="adminpopular-tag">Rotten: <?= htmlspecialchars($row['rating_rotten']) ?></span>
                        <span class="adminpopular-tag">Meta: <?= htmlspecialchars($row['rating_metacritic']) ?></span>
                    </div>
                </div>
            </div>
            <div class="adminpopular-remove-button">
                <form method="post" class="remove-toppick-form" data-top-id="<?= $row['top_id'] ?>"
                    onsubmit="return confirm('Remove <?= htmlspecialchars(addslashes($row['title'])) ?> from Top Picks?');">
                    <button type="submit" class="adminpopular-btn">REMOVE FROM TOP PICKS</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="padding: 20px;">No top picks found.</p>
<?php endif; ?>

<script>
    // Remove Top Pick via AJAX
    document.querySelectorAll('.remove-toppick-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const topId = this.dataset.topId;

            fetch('process/process_remove_topadmin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'top_id=' + encodeURIComponent(topId)
            })
                .then(response => response.text())
                .then(result => {
                    console.log("Delete response:", result); // debug
                    if (result.trim() === 'success') {
                        window.location.reload();
                    } else {
                        alert('Failed to delete: ' + result);
                    }
                })

        });
    });

    function openAddTopPickModal() {
        document.getElementById('addbuttonpopularmovie-modal').style.display = 'flex';
    }

    function closeAddTopPickModal() {
        document.getElementById('addbuttonpopularmovie-modal').style.display = 'none';
        document.getElementById('topPickSelect').value = '';
    }

    document.getElementById('addTopPickForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const value = document.getElementById('topPickSelect').value;
        const position = document.getElementById('positionSelect').value;

        if (!value.includes("-")) return alert("Invalid selection");
        if (!position) return alert("Please select a position");

        const [type, id] = value.split("-");

        fetch('process/process_add_top.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `content_type=${type}&content_id=${id}&position=${position}`
        })

            .then(res => res.text())
            .then(result => {
                if (result === 'success') {
                    closeAddTopPickModal();
                    window.location.reload();
                } else {
                    alert('Failed to add Top Pick: ' + result);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error occurred.');
            });
    });

</script>