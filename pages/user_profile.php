<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?page=login_user");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $username, $email);
$stmt->fetch();
$stmt->close();

// Tangkap pesan sukses atau error dari update_profile.php melalui query string (GET)
$feedbackMessage = $_GET['msg'] ?? '';
$feedbackStatus = $_GET['status'] ?? null; // success or error
?>

<section class="profileuser-container">
    <div class="profileuser-card">
        <h2 class="profileuser-title"><span class="profileuser-title-accent">THE</span>CGI<br>Your Profile</h2>

        <div class="profileuser-form-group">
            <label>Name</label>
            <input type="text" disabled value="<?= htmlspecialchars($name) ?>">
            <button class="profileuser-edit-btn"
                onclick="openPopup('name', '<?= htmlspecialchars($name) ?>')">EDIT</button>
        </div>

        <div class="profileuser-form-group">
            <label>Username</label>
            <input type="text" disabled value="<?= htmlspecialchars($username) ?>">
            <button class="profileuser-edit-btn"
                onclick="openPopup('username', '<?= htmlspecialchars($username) ?>')">EDIT</button>
        </div>

        <div class="profileuser-form-group">
            <label>Email</label>
            <input type="text" disabled value="<?= htmlspecialchars($email) ?>">
            <button class="profileuser-edit-btn"
                onclick="openPopup('email', '<?= htmlspecialchars($email) ?>')">EDIT</button>
        </div>

        <div class="profileuser-form-group">
            <label>Password</label>
            <input type="password" disabled value="********">
            <button class="profileuser-edit-btn" onclick="openPasswordPopup()">EDIT</button>
        </div>

        <div class="profileuser-actions">
            <a href="#" onclick="confirmLogout(event)" class="profileuser-logout">Log Out</a>
            <a href="#" onclick="openDeleteAccountPopup(event)" class="profileuser-delete">Delete Account</a>
        </div>
    </div>
</section>

<!-- Popup Edit Profile Info -->
<div id="editPopup" class="edit-popup" style="display:none;">
    <form method="POST" action="process/update_profile.php" class="edit-popup-form">
        <input type="hidden" name="field" id="editField">
        <input type="hidden" name="type" value="info">

        <label id="editLabel"></label>
        <input type="text" name="new_value" id="editValue" required>

        <label>Password</label>
        <div class="profileuser-password-wrapper">
            <input type="password" name="password" id="editPasswordInput" required>
            <span class="profileuser-toggle-password" onclick="togglePassword('editPasswordInput', this)">🙈</span>
        </div>

        <div class="popup-actions">
            <button type="submit" class="popup-save-btn">Save</button>
            <button type="button" onclick="closePopup()" class="popup-cancel-btn">Cancel</button>
        </div>
    </form>
</div>

<!-- Popup Change Password -->
<div id="passwordPopup" class="edit-popup" style="display:none;">
    <form method="POST" action="process/update_profile.php" class="edit-popup-form">
        <input type="hidden" name="type" value="password">

        <label>Current Password</label>
        <div class="profileuser-password-wrapper">
            <input type="password" name="old_password" id="oldPasswordInput" required>
            <span class="profileuser-toggle-password" onclick="togglePassword('oldPasswordInput', this)">🙈</span>
        </div>

        <label>New Password</label>
        <div class="profileuser-password-wrapper">
            <input type="password" name="new_password" id="newPasswordInput" required>
            <span class="profileuser-toggle-password" onclick="togglePassword('newPasswordInput', this)">🙈</span>
        </div>

        <div class="popup-actions">
            <button type="submit" class="popup-save-btn">Change</button>
            <button type="button" onclick="closePopup()" class="popup-cancel-btn">Cancel</button>
        </div>
    </form>
</div>

<!-- Popup Delete Account -->
<div id="deleteAccountPopup" class="edit-popup" style="display:none;">
    <form id="deleteAccountForm" class="edit-popup-form" onsubmit="submitDeleteAccount(event)">
        <label>Please enter your password to delete your account:</label>
        <div class="profileuser-password-wrapper">
            <input type="password" id="deletePasswordInput" name="password" required>
            <span class="profileuser-toggle-password" onclick="togglePassword('deletePasswordInput', this)">🙈</span>
        </div>

        <div class="popup-actions">
            <button type="submit" class="popup-save-btn">Delete</button>
            <button type="button" onclick="closeDeleteAccountPopup()" class="popup-cancel-btn">Cancel</button>
        </div>
    </form>
</div>

<!-- Modal Feedback -->
<div id="modalFeedback" class="modal-feedback-overlay" style="display:none;">
    <div class="modal-feedback-box"
        style="background-color: <?= $feedbackStatus === 'success' ? '#d4edda' : ($feedbackStatus === 'error' ? '#f8d7da' : '#fff') ?>; 
                color: <?= $feedbackStatus === 'success' ? '#155724' : ($feedbackStatus === 'error' ? '#721c24' : '#000') ?>;">
        <p id="modalFeedbackMessage"></p>
        <div id="modalFeedbackButtons" style="margin-top:1rem; display:flex; justify-content:center; gap:1rem;">
            <!-- Buttons injected by JS -->
        </div>
    </div>
</div>

<script>
    // Show feedback message from PHP GET parameter if exists
    <?php if ($feedbackMessage !== ''): ?>
        showFeedbackModal(<?= json_encode($feedbackMessage) ?>, `<button class="modal-feedback-btn" onclick="closeFeedbackModal()">OK</button>`);
    <?php endif; ?>

    function openPopup(field, currentValue) {
        document.getElementById('editPopup').style.display = 'block';
        document.getElementById('editField').value = field;
        document.getElementById('editValue').value = currentValue;
        document.getElementById('editLabel').innerText = "New " + field.charAt(0).toUpperCase() + field.slice(1);
    }

    function openPasswordPopup() {
        document.getElementById('passwordPopup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('editPopup').style.display = 'none';
        document.getElementById('passwordPopup').style.display = 'none';
    }

    function openDeleteAccountPopup(event) {
        event.preventDefault();
        document.getElementById('deleteAccountPopup').style.display = 'block';
    }

    function closeDeleteAccountPopup() {
        document.getElementById('deleteAccountPopup').style.display = 'none';
        document.getElementById('deletePasswordInput').value = '';
    }

    function showFeedbackModal(message, buttonsHtml = '') {
        document.getElementById('modalFeedbackMessage').innerText = message;
        const btnContainer = document.getElementById('modalFeedbackButtons');
        btnContainer.innerHTML = buttonsHtml;
        document.getElementById('modalFeedback').style.display = 'flex';
    }

    function closeFeedbackModal() {
        document.getElementById('modalFeedback').style.display = 'none';
    }

    function togglePassword(inputId, toggleElem) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            toggleElem.textContent = "👁️";
        } else {
            input.type = "password";
            toggleElem.textContent = "🙈";
        }
    }

    // Logout confirmation popup
    function confirmLogout(event) {
        event.preventDefault();
        showFeedbackModal("Are you sure you want to log out?",
            `<button class="modal-feedback-btn" onclick="window.location.href='http://localhost/412023037_Final%20Project/'">Yes</button>
         <button class="modal-feedback-btn" onclick="closeFeedbackModal()">No</button>`
        );
    }

    // Delete Account form submit with AJAX password check
    function submitDeleteAccount(e) {
        e.preventDefault();
        const password = document.getElementById('deletePasswordInput').value;

        fetch('process/check_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password: password })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeleteAccountPopup();
                    showFeedbackModal("Password correct. Are you sure you want to delete your account?",
                        `<button class="modal-feedback-btn" onclick="deleteAccount()">Yes</button>
                     <button class="modal-feedback-btn" onclick="closeFeedbackModal()">No</button>`);
                } else {
                    closeDeleteAccountPopup();
                    showFeedbackModal("You can't delete your account because the password is incorrect.",
                        `<button class="modal-feedback-btn" onclick="closeFeedbackModal()">OK</button>`);
                }
            })
            .catch(() => {
                closeDeleteAccountPopup();
                showFeedbackModal("Error checking password. Please try again later.",
                    `<button class="modal-feedback-btn" onclick="closeFeedbackModal()">OK</button>`);
            });
    }

    // AJAX delete account
    function deleteAccount() {
        fetch('process/delete_account.php', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showFeedbackModal("Your account has been successfully deleted.",
                        `<button class="modal-feedback-btn" onclick="redirectToHome()">OK</button>`);
                } else {
                    showFeedbackModal("Failed to delete your account. Please try again.",
                        `<button class="modal-feedback-btn" onclick="closeFeedbackModal()">OK</button>`);
                }
            })
            .catch(() => {
                showFeedbackModal("Error deleting account. Please try again later.",
                    `<button class="modal-feedback-btn" onclick="closeFeedbackModal()">OK</button>`);
            });
    }

    function redirectToHome() {
        window.location.href = "http://localhost/412023037_Final%20Project/";
    }
</script>