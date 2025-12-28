<?php
include 'db.php';


$sql_movies = "SELECT * FROM news_movies ORDER BY publish_date DESC";
$result_movies = mysqli_query($conn, $sql_movies);


$sql_celebrity = "SELECT * FROM news_celebrity ORDER BY publish_date DESC";
$result_celebrity = mysqli_query($conn, $sql_celebrity);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>News Page</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <div class="news-container">

    <!-- News Header tetap statis seperti semula -->
    <div class="news-header">
      <div class="news-banner">
        <div class="news-banner-item">
          <img src="img/img_bignews/joker.webp" alt="Joker: Folie à Deux" />
          <div class="news-banner-joker-text">
            <h3>Joker: Folie à Deux is releasing soon !!</h3>
            <p>
              "Joker 2," the highly anticipated sequel to the 2019 film, is set to release soon, bringing Joaquin
              Phoenix back as the iconic character in another dark, gripping tale.
            </p>
          </div>
          <span class="news-banner-joker-time">3hrs ago</span>
        </div>

        <div class="news-banner-item2">
          <img src="img/img_bignews/doomsday.jpg" alt="Avengers: Doomsday" />
          <h3 class="news-banner-doomsday-text">
            Marvel's 'Avengers: Doomsday' Set to Begin Filming
          </h3>
        </div>
      </div>
    </div>

    <!-- News Body Section -->
    <div class="news-body">
      <h2>More to Explore</h2>
      <h3>Top News ></h3>
      <div class="news-list">

        <?php if (mysqli_num_rows($result_movies) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result_movies)): ?>
            <div class="news-card">
              <img src="img/img_news/<?php echo htmlspecialchars($row['image']); ?>"
                alt="<?php echo htmlspecialchars($row['title']); ?>" />
              <div class="news-card-content">
                <h4 class="news-card-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                <p class="news-card-description">
                  <a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" class="news-link"
                    style="color: #ccc !important; text-decoration: none !important;">
                    <?php echo htmlspecialchars($row['description']); ?>
                  </a>
                </p>
                <div class="news-card-footer">
                  <span class="news-card-author">by <?php echo htmlspecialchars($row['author']); ?></span>
                  <span class="news-card-date"><?php echo date('m/d/Y', strtotime($row['publish_date'])); ?></span>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No movie news available.</p>
        <?php endif; ?>

      </div>
    </div>

    <!-- Celebrity News -->
    <div class="news-body">
      <h2>More to Explore</h2>
      <h3>Celebrity News ></h3>
      <div class="news-list">

        <?php if (mysqli_num_rows($result_celebrity) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result_celebrity)): ?>
            <div class="news-card">
              <img src="img/img_celebrity/<?php echo htmlspecialchars($row['image']); ?>"
                alt="<?php echo htmlspecialchars($row['title']); ?>" />
              <div class="news-card-content">
                <h4 class="news-card-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                <p class="news-card-description">
                  <a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" class="news-link"
                    style="color: #ccc !important; text-decoration: none !important;">
                    <?php echo htmlspecialchars($row['description']); ?>
                  </a>
                </p>
                <div class="news-card-footer">
                  <span class="news-card-author">by <?php echo htmlspecialchars($row['author']); ?></span>
                  <span class="news-card-date"><?php echo date('m/d/Y', strtotime($row['publish_date'])); ?></span>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No celebrity news available.</p>
        <?php endif; ?>

      </div>
    </div>

  </div>
</body>

</html>

<?php
mysqli_close($conn);
?>