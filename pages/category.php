<div class="kategori-container">
    <div class="kategori-title">
        <span class="kategori-blue">Movies & Shows Based on</span>
        <span class="kategori-white">CATEGORY</span>
    </div>
    <div class="kategori-card-container">
        <?php
        $categories = ['ACTION', 'HORROR', 'DRAMA', 'THRILLER', 'COMEDY', 'FANTASY', 'MYSTERY', 'ROMANCE', 'SCI-FI', 'ADVENTURE'];
        foreach ($categories as $category): ?>
            <div class="kategori-card">
                <h2><?= $category ?></h2>
                <a href="index.php?page=category_movies&category=<?= urlencode($category) ?>" class="kategori-list-link">
                    <i class="bx bx-list-ul"></i> List
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>