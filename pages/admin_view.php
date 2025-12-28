<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?page=login_admin");
    exit();
}

// Tangani aksi approve/reject untuk admins
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    if ($action === 'accept') {
        $stmt = $conn->prepare("UPDATE admins SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE admins SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php?page=admin_view");
    exit();
}

// Ambil data admins
$sqlAdmins = "SELECT id, name, username, email, status, created_at FROM admins ORDER BY id ASC";
$resultAdmins = $conn->query($sqlAdmins);

// Ambil data request film beserta username user
$sqlRequests = "SELECT r.id, r.film_title, r.description, u.username, r.created_at 
                FROM request_film r 
                INNER JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC";
$resultRequests = $conn->query($sqlRequests);
?>

<link rel="stylesheet" href="css/user_adminview.css">

<div class="user-adminview container">
    <h1>Admins List</h1>
    <table class="user-adminview-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultAdmins && $resultAdmins->num_rows > 0): ?>
                <?php while ($admin = $resultAdmins->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($admin['id']) ?></td>
                        <td><?= htmlspecialchars($admin['name']) ?></td>
                        <td><?= htmlspecialchars($admin['username']) ?></td>
                        <td><?= htmlspecialchars($admin['email']) ?></td>
                        <td><?= htmlspecialchars($admin['created_at']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($admin['status'])) ?></td>
                        <td>
                            <?php if ($admin['status'] === 'approved'): ?>
                                <button
                                    style="background-color: #28a745; color: white; border:none; padding: 5px 10px; cursor: default;"
                                    disabled>Approved</button>
                            <?php elseif ($admin['status'] === 'pending'): ?>
                                <a href="index.php?page=admin_view&action=accept&id=<?= $admin['id'] ?>"
                                    style="background-color: #28a745; color: white; padding: 5px 10px; text-decoration: none; margin-right: 5px; border-radius: 3px;">
                                    Accept
                                </a>
                                <a href="index.php?page=admin_view&action=reject&id=<?= $admin['id'] ?>"
                                    style="background-color: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;">
                                    Reject
                                </a>
                            <?php else: /* rejected */ ?>
                                <button
                                    style="background-color: #dc3545; color: white; border:none; padding: 5px 10px; cursor: default;"
                                    disabled>Rejected</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center;">No admins found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<br><br>

<div class="user-adminview container">
    <h1>Film Requests</h1>
    <table class="user-adminview-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Film Title</th>
                <th>Description</th>
                <th>Requested By</th>
                <th>Requested At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultRequests && $resultRequests->num_rows > 0): ?>
                <?php while ($req = $resultRequests->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($req['id']) ?></td>
                        <td><?= htmlspecialchars($req['film_title']) ?></td>
                        <td><?= htmlspecialchars($req['description']) ?></td>
                        <td><?= htmlspecialchars($req['username']) ?></td>
                        <td><?= htmlspecialchars($req['created_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No film requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Logout Button -->
<div style="text-align: center; margin: 40px 0;">
    <a href="#" onclick="confirmLogout(event)" class="profileuser-logout"
        style="color: #3afffc; font-weight: bold; text-decoration: none; font-size: 20px;">Log Out</a>
</div>

<!-- Modal HTML -->
<div id="feedbackModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#3afffc; padding:20px 30px; border-radius:10px; max-width:400px; text-align:center;">
        <p id="feedbackMessage" style="font-size:18px; margin-bottom:20px; color: #1e1e2a;"></p>
        <div id="feedbackActions"></div>
    </div>
</div>


<script>
    function showFeedbackModal(message, buttonsHtml) {
        document.getElementById('feedbackMessage').innerText = message;
        document.getElementById('feedbackActions').innerHTML = buttonsHtml;
        document.getElementById('feedbackModal').style.display = 'flex';
    }

    function closeFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'none';
    }

    function confirmLogout(event) {
        event.preventDefault();
        showFeedbackModal("Are you sure you want to log out?",
            `<button class="modal-feedback-btn" onclick="window.location.href='http://localhost/412023037_Final%20Project/'" style="padding: 8px 15px; background: red; color: white; border: none; border-radius: 5px; margin-right: 10px;">Yes</button>
         <button class="modal-feedback-btn" onclick="closeFeedbackModal()" style="padding: 8px 15px; background: #ccc; color: black; border: none; border-radius: 5px;">No</button>`
        );
    }
</script>