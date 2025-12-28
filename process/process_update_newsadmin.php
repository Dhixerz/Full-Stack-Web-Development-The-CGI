<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $type = $_POST['type']; // 'movie' atau 'celebrity'
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $publish_date = $_POST['publish_date'];
    $url = $_POST['url'];
    $image = $_POST['image'];

    // Tentukan tabel berdasarkan tipe konten
    $table = $type === 'movie' ? 'news_movies' : 'news_celebrity';

    // Lindungi dari SQL injection
    $stmt = $conn->prepare("UPDATE $table SET title = ?, description = ?, author = ?, publish_date = ?, url = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $author, $publish_date, $url, $image, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "invalid_request"]);
}
?>