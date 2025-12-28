<?php
include("db.php");
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ambil user id dari session (pastikan sudah login)
$user_id = $_SESSION['user_id'] ?? null;

// Variabel untuk pesan sukses/error
$request_success = false;
$request_error = '';

// Handle form submit request film
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $film_title = trim($_POST['film_title'] ?? '');
  $description = trim($_POST['description'] ?? '');

  if (!$user_id) {
    $request_error = "You must be logged in to submit a request.";
  } elseif (empty($film_title) || empty($description)) {
    $request_error = "Please fill in all fields.";
  } else {
    // Prepared statement untuk insert ke tabel request_film
    $stmt = $conn->prepare("INSERT INTO request_film (user_id, film_title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $film_title, $description);

    if ($stmt->execute()) {
      $request_success = true;
    } else {
      $request_error = "Failed to submit your request. Please try again.";
    }
    $stmt->close();
  }
}
?>

<div class="about-container">
  <div class="about-row">
    <div class="about-col-70">
      <h1>Dhian Juwita Putri (412023037)</h1>
      <div class="about-description-box">
        <p>
          I am Dhian Juwita Putri, a computer science student at UKRIDA, Indonesia. As someone who is passionate about
          web development, I'm dedicated to build Cinema Guide Insights, a website that helps people find the right
          movies to watch, especially for those who often feel unsure about what to pick.
        </p>
        <p class="about-quote">"Movies bring us together, one story at a time."</p>
      </div>
    </div>
    <div class="about-col-30">
      <div class="about-image-container">
        <img src="img/foto-dhian.png" alt="Dhian Juwita Putri" />
      </div>
      <div class="about-blue-box"></div>
    </div>
  </div>
</div>

<!-- Form Request Film -->
<div class="about-request-wrapper">
  <h2>Request a Movie Addition</h2>

  <?php if ($request_success): ?>
    <p class="about-request-success">Thank you for your request! We will review it soon.</p>
  <?php endif; ?>

  <?php if ($request_error): ?>
    <p class="about-request-error"><?= htmlspecialchars($request_error) ?></p>
  <?php endif; ?>

  <form method="post" action="" class="about-request-form" novalidate>
    <label for="film_title">Movie Title</label>
    <input type="text" id="film_title" name="film_title" placeholder="Enter movie title" required>

    <label for="description">Description / Reason</label>
    <textarea id="description" name="description" rows="4" placeholder="Why do you want to add this movie?"
      required></textarea>

    <button type="submit" class="about-request-submit">Submit Request</button>
  </form>
</div>