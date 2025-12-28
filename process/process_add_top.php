<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['content_type'] ?? '';
    $id = $_POST['content_id'] ?? '';
    $position = $_POST['position'] ?? '';

    if (!in_array($type, ['movie', 'show']) || !is_numeric($id) || !is_numeric($position)) {
        echo "Invalid input.";
        exit();
    }

    // Cek apakah posisi sudah dipakai
    $checkPos = $conn->prepare("SELECT COUNT(*) FROM toppicks WHERE position = ?");
    if (!$checkPos) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    $checkPos->bind_param("i", $position);
    $checkPos->execute();
    $checkPos->bind_result($posCount);
    $checkPos->fetch();
    $checkPos->close();

    if ($posCount > 0) {
        echo "Position already used.";
        exit();
    }

    // Insert Top Pick
    $stmt = $conn->prepare("INSERT INTO toppicks (content_type, content_id, position) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    $stmt->bind_param("sii", $type, $id, $position);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "DB Error: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
}
?>