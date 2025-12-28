<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=login_admin');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$_SESSION['old_admin_username'] = $username;

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "Please enter both username and password.";
    header('Location: ../index.php?page=login_admin');
    exit();
}

$stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ? AND status = 'approved'");
if (!$stmt) {
    $_SESSION['login_error'] = "Server error. Please try again later.";
    header('Location: ../index.php?page=login_admin');
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($adminId, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $_SESSION['admin_id'] = $adminId;
        $_SESSION['admin_username'] = $username;
        unset($_SESSION['old_admin_username']);
        header('Location: ../index.php?page=admin_page');
        exit();
    }
}

$_SESSION['login_error'] = "Incorrect username or password.";
$stmt->close();
header('Location: ../index.php?page=login_admin');
exit();
