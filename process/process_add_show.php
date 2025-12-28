<?php
include '../db.php';

header('Content-Type: application/json'); // set response JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan bersihkan data input
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $genre = isset($_POST['genre']) ? trim($_POST['genre']) : '';
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
    $image_path = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';
    $video_path = isset($_POST['video_path']) ? trim($_POST['video_path']) : '';
    $image_poster = isset($_POST['image_poster']) ? trim($_POST['image_poster']) : '';
    $rating_imdb = isset($_POST['rating_imdb']) ? (float) $_POST['rating_imdb'] : 0;
    $rating_rotten = isset($_POST['rating_rotten']) ? (int) $_POST['rating_rotten'] : 0;
    $rating_metacritic = isset($_POST['rating_metacritic']) ? (int) $_POST['rating_metacritic'] : 0;

    // Insert ke tabel shows
    $stmt = $conn->prepare("INSERT INTO shows 
        (title, description, genre, rating, image_path, video_path, image_poster, rating_imdb, rating_rotten, rating_metacritic) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => "Prepare statement failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param(
        "sssisssddd",
        $title,
        $description,
        $genre,
        $rating,
        $image_path,
        $video_path,
        $image_poster,
        $rating_imdb,
        $rating_rotten,
        $rating_metacritic
    );

    if ($stmt->execute()) {
        $show_id = $stmt->insert_id;

        // simpan cast
        if (!empty($_POST['cast_names']) && !empty($_POST['cast_images'])) {
            $cast_names = $_POST['cast_names'];
            $cast_images = $_POST['cast_images'];

            for ($i = 0; $i < count($cast_names); $i++) {
                $name = trim($cast_names[$i]);
                $image = trim($cast_images[$i]);

                if ($name !== '') {
                    $stmt_cast = $conn->prepare("INSERT INTO cast (show_id, name, image_path) VALUES (?, ?, ?)");
                    if ($stmt_cast) {
                        $stmt_cast->bind_param("iss", $show_id, $name, $image);
                        $stmt_cast->execute();
                        $stmt_cast->close();
                    }
                }
            }
        }

        // simpan downloads
        if (!empty($_POST['download_resolutions']) && !empty($_POST['download_paths'])) {
            $download_resolutions = $_POST['download_resolutions'];
            $download_paths = $_POST['download_paths'];

            for ($i = 0; $i < count($download_resolutions); $i++) {
                $resolution = trim($download_resolutions[$i]);
                $path = trim($download_paths[$i]);

                if ($path !== '') {
                    $stmt_dl = $conn->prepare("INSERT INTO downloads (show_id, resolution, file_path) VALUES (?, ?, ?)");
                    if ($stmt_dl) {
                        $stmt_dl->bind_param("iss", $show_id, $resolution, $path);
                        $stmt_dl->execute();
                        $stmt_dl->close();
                    }
                }
            }
        }

        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'Show added successfully']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => "Gagal menambahkan show: " . $stmt->error]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Akses tidak valid']);
    exit();
}
