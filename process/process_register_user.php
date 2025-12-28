<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi sederhana
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        die("Semua field wajib diisi.");
    }

    // Cek apakah username atau email sudah terdaftar
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        die("Username atau email sudah digunakan.");
    }
    $stmt_check->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate token verifikasi
    $token = bin2hex(random_bytes(16));
    $is_verified = 0;

    // Insert data user ke database
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, is_verified, token) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $name, $username, $email, $hashed_password, $is_verified, $token);

    if ($stmt->execute()) {
        echo "<p>Registrasi berhasil! Silakan klik link berikut untuk verifikasi akun Anda:</p>";
        echo "<p><a href='http://localhost/412023037_Final%20Project/process/verify_email_user.php?token=$token'>Verifikasi Email</a></p>";
        echo "<p><a href='http://localhost/412023037_Final%20Project/index.php?page=login_user'>Login</a></p>";
    } else {
        echo "Terjadi kesalahan saat registrasi: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Metode request tidak valid.";
}
?>