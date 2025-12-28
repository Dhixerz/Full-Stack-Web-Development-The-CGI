<?php
include '../db.php';

// Cek apakah request menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST['type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $author = $_POST['author'] ?? '';
    $publish_date = $_POST['publish_date'] ?? date('Y-m-d'); // default hari ini
    $url = $_POST['url'] ?? '';
    $image = $_POST['image'] ?? '';

    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    $author = mysqli_real_escape_string($conn, $author);
    $url = mysqli_real_escape_string($conn, $url);
    $image = mysqli_real_escape_string($conn, $image);

    // Tentukan nama tabel berdasarkan tipe
    $table = '';
    if ($type === 'celebrity') {
        $table = 'news_celebrity';
    } elseif ($type === 'movie') {
        $table = 'news_movies';
    } else {
        http_response_code(400);
        echo "Tipe berita tidak valid.";
        exit;
    }

    // Query insert
    $query = "INSERT INTO $table (title, description, author, publish_date, url, image)
              VALUES ('$title', '$description', '$author', '$publish_date', '$url', '$image')";

    if (mysqli_query($conn, $query)) {
        http_response_code(200);
        echo "Berita berhasil ditambahkan.";
    } else {
        http_response_code(500);
        echo "Gagal menambahkan berita: " . mysqli_error($conn);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo "Metode tidak diizinkan.";
}

mysqli_close($conn);
?>