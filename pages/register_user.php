<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name && $username && $email && $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));

        // Cek apakah username atau email sudah digunakan oleh user yang sudah terverifikasi
        $stmt = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND is_verified = 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or Email already registered and verified.";
        } else {
            // Hapus data lama yang belum diverifikasi (jika ada)
            $delete = $conn->prepare("DELETE FROM users WHERE (username = ? OR email = ?) AND is_verified = 0");
            $delete->bind_param("ss", $username, $email);
            $delete->execute();
            $delete->close();

            // Masukkan data baru
            $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, token, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("sssss", $name, $username, $email, $passwordHash, $token);

            if ($stmt->execute()) {
                $message = "Registered successfully. Click the following link to verify:<br>";
                $message .= "<a href='http://localhost/412023037_Final%20Project/process/verify_email_user.php?token=$token'>Email Verification</a>";
            } else {
                $message = "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
            }
        }

        $stmt->close();
    } else {
        $message = "Mohon isi semua kolom.";
    }
}
?>

<body class="loginadmin-body">

    <div class="loginadmin-container">
        <div class="loginadmin-left">
            <img src="img/home-background.png" alt="The CGI Poster" />
            <h1 class="loginadmin-poster-title">THE<span>CGI</span></h1>
        </div>

        <div class="loginadmin-right">
            <h2 class="loginadmin-title">Register as User</h2>

            <?php if ($message): ?>
                <div style="color: #333; background: #eef; padding: 10px; margin-bottom: 15px;">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="loginadmin-form">
                <div class="loginadmin-form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required
                        value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
                </div>

                <div class="loginadmin-form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required
                        value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
                </div>

                <div class="loginadmin-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required
                        value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                </div>

                <div class="loginadmin-form-group loginadmin-password-group">
                    <label for="password">Password</label>
                    <div class="loginadmin-password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="loginadmin-toggle-password" onclick="togglePassword()">🙈</span>
                    </div>
                </div>

                <button type="submit" class="loginadmin-btn">Register</button>
            </form>

            <p class="loginadmin-footer-text">
                Already have a user account?
                <a href="/412023037_Final%20Project/login_user">Login</a>
            </p>
            <p class="loginadmin-footer-back">
                <a href="/412023037_Final%20Project/">← Go Back</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.querySelector('.loginadmin-toggle-password');
            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "👁️";
            } else {
                input.type = "password";
                icon.textContent = "🙈";
            }
        }
    </script>
</body>