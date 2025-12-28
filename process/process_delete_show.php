<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['show_id'])) {
    $show_id = (int) $_POST['show_id'];

    $conn->begin_transaction();

    try {
        // Hapus cast
        $conn->query("DELETE FROM cast WHERE show_id = $show_id");

        // Hapus downloads
        $conn->query("DELETE FROM downloads WHERE show_id = $show_id");

        // Hapus dari toppicks (jika ada entri bertipe show)
        $conn->query("DELETE FROM toppicks WHERE content_type = 'show' AND content_id = $show_id");

        // Hapus dari shows
        $conn->query("DELETE FROM shows WHERE id = $show_id");

        $conn->commit();
        echo "Show and all related data successfully deleted.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to delete show: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>