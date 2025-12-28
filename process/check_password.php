<?php
session_start();
include("../db.php");
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Ambil data JSON dari AJAX
$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'] ?? '';

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Password is required']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil hash password user dari database
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hash);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    $stmt->close();
    exit;
}
$stmt->close();

// Verifikasi password yang dikirim dengan password hash di DB
if (password_verify($password, $hash)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect password']);
}
