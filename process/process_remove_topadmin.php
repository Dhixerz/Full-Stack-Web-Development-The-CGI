<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['top_id'] ?? '';

    if (!is_numeric($id)) {
        echo "Invalid ID.";
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM toppicks WHERE id = ?");
    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
        exit();
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "success";
        } else {
            echo "No record deleted. Possibly invalid ID.";
        }
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}
?>