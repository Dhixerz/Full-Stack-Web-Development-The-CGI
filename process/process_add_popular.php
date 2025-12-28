<?php
include '../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
    $movie_id = intval($_POST['movie_id']);

    // Cek apakah movie_id sudah ada di popular
    $check = $conn->prepare("SELECT id FROM popular WHERE movie_id = ?");
    $check->bind_param("i", $movie_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Movie already in popular.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO popular (movie_id) VALUES (?)");
    $stmt->bind_param("i", $movie_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Failed to insert.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
