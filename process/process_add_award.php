<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek action form
    $action = $_POST['action'] ?? '';

    if ($action === 'add_year') {
        // Tambah tahun baru ke tabel award_years
        $year = intval($_POST['year'] ?? 0);

        if ($year > 0) {
            $checkStmt = $conn->prepare("SELECT id FROM award_years WHERE year = ?");
            $checkStmt->bind_param("i", $year);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                header("Location: ../index.php?page=awards_admin&error=year_exists");
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO award_years (year) VALUES (?)");
            $stmt->bind_param("i", $year);
            if ($stmt->execute()) {
                header("Location: ../index.php?page=awards_admin&year=$year&success=year_added");
                exit;
            } else {
                header("Location: ../index.php?page=awards_admin&error=insert_failed");
                exit;
            }
        } else {
            header("Location: ../index.php?page=awards_admin&error=invalid_year");
            exit;
        }
    } elseif ($action === 'add_award') {
        // Tambah data award baru ke tabel awards
        $year_id = intval($_POST['year_id'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        $winner = trim($_POST['winner'] ?? '');
        $image_path = trim($_POST['image_path'] ?? '');

        // Validasi minimal
        if ($year_id > 0 && $category !== '' && $winner !== '' && $image_path !== '') {
            $stmt = $conn->prepare("INSERT INTO awards (year_id, category, winner, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $year_id, $category, $winner, $image_path);

            if ($stmt->execute()) {
                // Ambil tahun untuk redirect ke halaman yang tepat
                $yearStmt = $conn->prepare("SELECT year FROM award_years WHERE id = ?");
                $yearStmt->bind_param("i", $year_id);
                $yearStmt->execute();
                $yearRes = $yearStmt->get_result();
                if ($row = $yearRes->fetch_assoc()) {
                    $year = $row['year'];
                } else {
                    $year = '';
                }

                header("Location: ../index.php?page=awards_admin&year=$year&success=award_added");
                exit;
            } else {
                header("Location: ../index.php?page=awards_admin&error=insert_failed");
                exit;
            }
        } else {
            header("Location: ../index.php?page=awards_admin&error=invalid_input");
            exit;
        }
    } else {
        // Action tidak dikenal
        header("Location: ../index.php?page=awards_admin&error=unknown_action");
        exit;
    }
} else {
    // Bukan POST request
    header("Location: ../index.php?page=awards_admin");
    exit;
}
