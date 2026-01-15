<?php
/**
 * Movie Detail View
 *
 * Displays single movie details with favorite button.
 *
 * @package MiniMovies\Views\Movies
 * @var array<string, mixed> $movie Movie data
 * @var bool $is_favorited Whether current user has favorited this movie
 */

$movie = $movie ?? [];
$is_favorited = $is_favorited ?? false;

$posterUrl = !empty($movie['poster_url']) ? h($movie['poster_url']) : '';

$ratingClass = 'bg-secondary';
if ($movie['rating'] >= 7) {
    $ratingClass = 'bg-success';
} elseif ($movie['rating'] >= 5) {
    $ratingClass = 'bg-warning text-dark';
} elseif ($movie['rating'] > 0) {
    $ratingClass = 'bg-danger';
}
?>

<div class="mb-4">
    <a href="/movies" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Movies
    </a>
</div>

<div class="row">
    <!-- Poster Column -->
    <div class="col-md-4 mb-4">
        <?php if ($posterUrl) : ?>
        <img 
            src="<?= $posterUrl ?>" 
            class="img-fluid rounded shadow" 
            alt="<?= h($movie['title']) ?>"
            style="max-height: 500px; width: 100%; object-fit: cover;"
            onerror="this.style.display='none';"
        >
        <?php else : ?>
        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
            <i class="bi bi-film text-muted" style="font-size: 5rem;"></i>
        </div>
        <?php endif; ?>
    </div>

    <!-- Details Column -->
    <div class="col-md-8">
        <h1 class="mb-3"><?= h($movie['title']) ?></h1>

        <div class="mb-3">
            <span class="badge bg-secondary fs-6 me-2">
                <i class="bi bi-calendar me-1"></i><?= (int)$movie['release_year'] ?>
            </span>
            <span class="badge bg-info text-dark fs-6 me-2">
                <i class="bi bi-tag me-1"></i><?= h($movie['genre']) ?>
            </span>
            <span class="badge <?= $ratingClass ?> fs-6">
                <i class="bi bi-star-fill me-1"></i><?= number_format((float)$movie['rating'], 1) ?> / 10
            </span>
        </div>

        <?php if (!empty($movie['description'])) : ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-text-paragraph me-2"></i>Description
                </h5>
                <p class="card-text"><?= nl2br(h($movie['description'])) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Favorite Button -->
        <?php if (isLoggedIn()) : ?>
        <div class="mb-4">
            <?php if ($is_favorited) : ?>
            <form action="/movies/<?= (int)$movie['id'] ?>/unfavorite" method="POST" class="d-inline">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-danger btn-lg">
                    <i class="bi bi-heart-fill me-2"></i>Remove from Favorites
                </button>
            </form>
            <?php else : ?>
            <form action="/movies/<?= (int)$movie['id'] ?>/favorite" method="POST" class="d-inline">
                <?= csrfField() ?>
                <button type="submit" class="btn btn-outline-danger btn-lg">
                    <i class="bi bi-heart me-2"></i>Add to Favorites
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php else : ?>
        <div class="mb-4">
            <a href="/login?return_to=<?= urlencode('/movies/' . $movie['id']) ?>" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-heart me-2"></i>Login to Favorite
            </a>
        </div>
        <?php endif; ?>

        <!-- Meta Info -->
        <div class="text-muted small">
            <?php if (!empty($movie['created_at'])) : ?>
            <p class="mb-1">
                <i class="bi bi-clock me-1"></i>Added: <?= date('F j, Y', strtotime($movie['created_at'])) ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>
