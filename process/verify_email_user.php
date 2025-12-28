<?php
include '../db.php';

$message = '';
$success = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ? AND is_verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Update is_verified
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            $message = "Verification successful! You can now log in.";
            $success = true;
        } else {
            $message = "Something went wrong while verifying your account. Please try again later.";
        }
    } else {
        $message = "Invalid or already used token.";
    }
    $stmt->close();
} else {
    $message = "No token provided.";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        :root {
            --main-color: #3afffc;
            --hover-color: hsl(181, 73%, 41%);
            --body-color: #1e1e2a;
            --container-color: #2d2e37;
            --text-color: #fcfeff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--body-color);
            color: var(--text-color);
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .verify-container {
            background-color: var(--container-color);
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.1);
            text-align: center;
            max-width: 500px;
        }

        .verify-container h1 {
            font-size: 26px;
            color: var(--main-color);
            margin-bottom: 25px;
        }

        .verify-container a.button {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--main-color);
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .verify-container a.button:hover {
            background-color: var(--hover-color);
        }
    </style>
</head>

<body>
    <div class="verify-container">
        <h1><?= htmlspecialchars($message) ?></h1>
        <?php if ($success): ?>
            <a href="../index.php?page=login_user" class="button">Login</a>
        <?php endif; ?>
    </div>
</body>

</html>