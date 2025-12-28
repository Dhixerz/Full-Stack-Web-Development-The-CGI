<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$username = isset($_SESSION['old_username']) ? $_SESSION['old_username'] : '';
unset($_SESSION['old_username']);
?>

<body class="loginadmin-body">

    <div class="loginadmin-container">
        <!-- Left Poster -->
        <div class="loginadmin-left">
            <img src="img/home-background.png" alt="The CGI Poster">
            <h1 class="loginadmin-poster-title">THE<span>CGI</span></h1>
        </div>

        <!-- Right Login Form -->
        <div class="loginadmin-right">
            <h2 class="loginadmin-title">Login As User</h2>

            <?php if (isset($_SESSION['login_error'])): ?>
                <p style="color: red; margin-bottom: 15px;">
                    <?= htmlspecialchars($_SESSION['login_error']) ?>
                </p>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form action="process/process_login_user.php" method="POST" class="loginadmin-form" autocomplete="off">
                <div class="loginadmin-form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required
                        value="<?= htmlspecialchars($username) ?>">
                </div>

                <div class="loginadmin-form-group loginadmin-password-group">
                    <label for="password">Password</label>
                    <div class="loginadmin-password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="loginadmin-toggle-password" onclick="togglePassword()">🙈</span>
                    </div>
                </div>

                <button type="submit" class="loginadmin-btn">LOGIN</button>
            </form>

            <p class="loginadmin-footer-text">
                New User?
                <a href="/412023037_Final%20Project/register_user">Sign Up</a>
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