<?php
include 'db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login_admin");
    exit();
}

$sql = "SELECT id, name, username, email, created_at, is_verified FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="css/user_adminview.css">

<div class="user-adminview container">
    <h1>Users List</h1>
    <table class="user-adminview-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><?= $user['is_verified'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>