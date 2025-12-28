<body class="loginadmin-body">

    <div class="loginadmin-container">
        <!-- Left Poster -->
        <div class="loginadmin-left">
            <img src="img/home-background.png" alt="The CGI Poster">
            <h1 class="loginadmin-poster-title">THE<span>CGI</span></h1>
        </div>

        <!-- Right Register Form -->
        <div class="loginadmin-right">
            <h2 class="loginadmin-title">Register as Admin</h2>
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="error-msg" style="color: red; margin-bottom:10px;">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div class="success-msg" style="color: green; margin-bottom:10px;">' . htmlspecialchars($_SESSION['success']) . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <form action="process/process_register_admin.php" method="POST" class="loginadmin-form">

                <div class="loginadmin-form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="loginadmin-form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="loginadmin-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="loginadmin-form-group loginadmin-password-group">
                    <label for="password">Password</label>
                    <div class="loginadmin-password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="loginadmin-toggle-password" onclick="togglePassword()">🙈</span>
                    </div>
                </div>

                <button type="submit" class="loginadmin-btn">Request Access</button>
            </form>

            <p class="loginadmin-footer-text">
                Already have an admin account?
                <a href="/412023037_Final%20Project/login_admin">Login</a>
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