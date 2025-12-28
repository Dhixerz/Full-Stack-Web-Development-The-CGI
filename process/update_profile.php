<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?page=user_profile");
    exit();
}

// Cek tipe update: info (name, username, email) atau password
$type = $_POST['type'] ?? '';

if ($type === 'password') {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';

    if (empty($old) || empty($new)) {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Please fill all password fields."));
        exit();
    }

    // Ambil hash password lama
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbPass);
    $stmt->fetch();
    $stmt->close();

    if (!$dbPass || !password_verify($old, $dbPass)) {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Incorrect current password."));
        exit();
    }

    // Hash password baru
    $newHash = password_hash($new, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newHash, $userId);
    if ($stmt->execute()) {
        header("Location: ../index.php?page=user_profile&status=success&msg=" . urlencode("Password updated successfully."));
    } else {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Failed to update password."));
    }
    $stmt->close();
    exit();

} elseif ($type === 'info') {
    $field = $_POST['field'] ?? '';
    $newValue = $_POST['new_value'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$field || !$newValue || !$password) {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Please fill all fields."));
        exit();
    }

    // Validasi field yang bisa diupdate
    $allowedFields = ['name', 'username', 'email'];
    if (!in_array($field, $allowedFields)) {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Invalid field."));
        exit();
    }

    // Verifikasi password user
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbPass);
    $stmt->fetch();
    $stmt->close();

    if (!$dbPass || !password_verify($password, $dbPass)) {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Incorrect password."));
        exit();
    }

    // Update field yang dipilih
    // Note: field name tidak bisa langsung di-bind param, jadi pakai prepared statement dinamis aman dari injection karena sudah dicek di whitelist
    $sql = "UPDATE users SET $field = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newValue, $userId);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=user_profile&status=success&msg=" . urlencode(ucfirst($field) . " updated successfully."));
    } else {
        header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Failed to update " . $field . "."));
    }
    $stmt->close();
    exit();

} else {
    // Jika tipe tidak valid
    header("Location: ../index.php?page=user_profile&status=error&msg=" . urlencode("Invalid request."));
    exit();
}
