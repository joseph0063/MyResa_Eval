<?php
/**
 * Movie Card Partial
 *
 * Displays a single movie as a Bootstrap card.
 *
 * @package MiniMovies\Views\Partials
 * @var array<string, mixed> $movie Movie data with keys: id, title, poster_url, release_year, rating, genre, is_favorited
 */

$posterUrl = !empty($movie['poster_url']) ? h($movie['poster_url']) : '';
$isFavorited = $movie['is_favorited'] ?? false;

$ratingClass = 'bg-secondary';
if ($movie['rating'] >= 7) {
    $ratingClass = 'bg-success';
} elseif ($movie['rating'] >= 5) {
    $ratingClass = 'bg-warning text-dark';
} elseif ($movie['rating'] > 0) {
    $ratingClass = 'bg-danger';
}
?>

<div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card movie-card h-100 shadow-sm">
        <a href="/movies/<?= (int)$movie['id'] ?>" class="text-decoration-none">
            <?php if ($posterUrl) : ?>
            <img 
                src="<?= $posterUrl ?>" 
                class="card-img-top" 
                alt="<?= h($movie['title']) ?>"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="poster-placeholder" style="display: none;">
                <i class="bi bi-film" style="font-size: 3rem;"></i>
            </div>
            <?php else : ?>
            <div class="poster-placeholder">
                <i class="bi bi-film" style="font-size: 3rem;"></i>
            </div>
            <?php endif; ?>
        </a>
        
        <div class="card-body d-flex flex-column position-relative">
            <h5 class="card-title">
                <a href="/movies/<?= (int)$movie['id'] ?>" class="text-decoration-none text-dark">
                    <?= h($movie['title']) ?>
                </a>
            </h5>
            
            <div class="mb-2">
                <span class="badge bg-secondary me-1"><?= (int)$movie['release_year'] ?></span>
                <span class="badge bg-info text-dark me-1"><?= h($movie['genre']) ?></span>
                <span class="badge <?= $ratingClass ?>">
                    <i class="bi bi-star-fill me-1"></i><?= number_format((float)$movie['rating'], 1) ?>
                </span>
            </div>
            
            <?php if (isLoggedIn()) : ?>
            <div class="mt-auto pt-2" style="position: relative; z-index: 2;">
                <?php if ($isFavorited) : ?>
                <form action="/movies/<?= (int)$movie['id'] ?>/unfavorite" method="POST" class="d-inline">
                    <?= csrfField() ?>
                    <button type="submit" class="favorite-btn" title="Remove from favorites">
                        <i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
                    </button>
                </form>
                <?php else : ?>
                <form action="/movies/<?= (int)$movie['id'] ?>/favorite" method="POST" class="d-inline">
                    <?= csrfField() ?>
                    <button type="submit" class="favorite-btn" title="Add to favorites">
                        <i class="bi bi-heart" style="font-size: 1.5rem;"></i>
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
