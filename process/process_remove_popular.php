<?php
include '../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['popular_id'])) {
    $popular_id = intval($_POST['popular_id']);

    $stmt = $conn->prepare("DELETE FROM popular WHERE id = ?");
    $stmt->bind_param("i", $popular_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        http_response_code(500);
        echo "Gagal menghapus data.";
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "Permintaan tidak valid.";
}

$conn->close();
