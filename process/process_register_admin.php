<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$username || !$email || !$password) {
        $_SESSION['error'] = "Semua field wajib diisi.";
        header("Location: ../index.php?page=register_admin");
        exit();
    }

    // Cek username/email sudah ada
    $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Username atau email sudah terdaftar.";
        $stmt->close();
        header("Location: ../index.php?page=register_admin");
        exit();
    }
    $stmt->close();

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert dengan status 'pending'
    $stmt = $conn->prepare("INSERT INTO admins (name, username, email, password, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssss", $name, $username, $email, $password_hash);

    // Setelah berhasil mendaftar, tetap di halaman register_admin
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registrasi berhasil! Tunggu persetujuan admin.";
        header("Location: ../index.php?page=register_admin"); // <- tetap di halaman register_admin
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat registrasi.";
        header("Location: ../index.php?page=register_admin");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../index.php?page=register_admin");
    exit();
}
