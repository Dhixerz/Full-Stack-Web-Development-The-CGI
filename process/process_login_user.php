<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $_SESSION['old_username'] = $username;

    // Ambil data user yang sudah verifikasi
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND is_verified = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $dbUsername, $dbPasswordHash);
        $stmt->fetch();

        if (password_verify($password, $dbPasswordHash)) {
            // Login sukses
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $dbUsername;
            unset($_SESSION['old_username']);
            header("Location: ../index.php?page=home"); // ✅ arahkan ke home
            exit;
        }
    }

    $stmt->close();
    $_SESSION['login_error'] = "Username or password is incorrect, or your account is not verified.";
    header("Location: ../index.php?page=login_user");
    exit;
}
?>