<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
    $movie_id = (int) $_POST['movie_id'];

    $conn->begin_transaction();

    try {
        // Hapus cast
        $conn->query("DELETE FROM cast WHERE movie_id = $movie_id");

        // Hapus downloads
        $conn->query("DELETE FROM downloads WHERE movie_id = $movie_id");

        // Hapus popular
        $conn->query("DELETE FROM popular WHERE movie_id = $movie_id");

        // Hapus dari toppicks
        $conn->query("DELETE FROM toppicks WHERE content_type = 'movie' AND content_id = $movie_id");

        // Hapus dari movies
        $conn->query("DELETE FROM movies WHERE id = $movie_id");

        $conn->commit();
        echo "Movie and all related data successfully deleted.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to delete movie: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>