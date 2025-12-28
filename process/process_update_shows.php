<?php
include '../db.php';

// UPDATE SHOW
$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$genre = $_POST['genre'];
$rating = $_POST['rating'];
$image_path = $_POST['image_path'];
$video_path = $_POST['video_path'];
$image_poster = $_POST['image_poster'];
$rating_imdb = $_POST['rating_imdb'];
$rating_rotten = $_POST['rating_rotten'];
$rating_metacritic = $_POST['rating_metacritic'];

$update_show = "UPDATE shows SET 
    title='$title',
    description='$description',
    genre='$genre',
    rating='$rating',
    image_path='$image_path',
    video_path='$video_path',
    image_poster='$image_poster',
    rating_imdb='$rating_imdb',
    rating_rotten='$rating_rotten',
    rating_metacritic='$rating_metacritic'
    WHERE id=$id";
$conn->query($update_show);

// UPDATE CAST
if (!empty($_POST['cast_ids'])) {
    foreach ($_POST['cast_ids'] as $index => $cast_id) {
        $name = $_POST['cast_names'][$index];
        $image = $_POST['cast_images'][$index];
        $conn->query("UPDATE cast SET name='$name', image_path='$image' WHERE id=$cast_id");
    }
}

// INSERT NEW CAST
if (!empty($_POST['new_cast_names'])) {
    foreach ($_POST['new_cast_names'] as $index => $name) {
        $image = $_POST['new_cast_images'][$index];
        $conn->query("INSERT INTO cast (show_id, name, image_path) VALUES ($id, '$name', '$image')");
    }
}

// DELETE CAST
if (!empty($_POST['deleted_cast_ids'])) {
    $ids = explode(',', $_POST['deleted_cast_ids']);
    foreach ($ids as $castId) {
        $castId = intval($castId);
        $conn->query("DELETE FROM cast WHERE id = $castId");
    }
}

// UPDATE DOWNLOADS
if (!empty($_POST['download_ids'])) {
    foreach ($_POST['download_ids'] as $index => $dl_id) {
        $res = $_POST['download_resolutions'][$index];
        $path = $_POST['download_paths'][$index];
        $conn->query("UPDATE downloads SET resolution='$res', file_path='$path' WHERE id=$dl_id");
    }
}

// INSERT NEW DOWNLOADS
if (!empty($_POST['new_download_resolutions']) && !empty($_POST['new_download_paths'])) {
    foreach ($_POST['new_download_resolutions'] as $index => $res) {
        $path = $_POST['new_download_paths'][$index];
        if (!empty($res) && !empty($path)) {
            $conn->query("INSERT INTO downloads (show_id, resolution, file_path) VALUES ($id, '$res', '$path')");
        }
    }
}

// DELETE DOWNLOADS
if (!empty($_POST['deleted_download_ids'])) {
    $ids = explode(',', $_POST['deleted_download_ids']);
    foreach ($ids as $dlId) {
        $dlId = intval($dlId);
        $conn->query("DELETE FROM downloads WHERE id = $dlId");
    }
}

header("Location: ../index.php?page=admin_shows");
exit;
?>