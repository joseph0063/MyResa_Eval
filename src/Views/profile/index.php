<?php
/**
 * User Profile View
 *
 * Displays user info and favorites list.
 *
 * @package MiniMovies\Views\Profile
 * @var array<string, mixed> $user User data (id, name, email, created_at)
 * @var array<int, array<string, mixed>> $favorites Array of favorite movies
 * @var int $favorite_count Total favorites count
 */

$user = $user ?? [];
$favorites = $favorites ?? [];
$favorite_count = $favorite_count ?? 0;

$memberSince = !empty($user['created_at'])
    ? date('F j, Y', strtotime($user['created_at']))
    : 'Unknown';
?>

<h1 class="mb-4">
    <i class="bi bi-person-circle me-2"></i>My Profile
</h1>

<div class="row">
    <!-- User Info Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                </div>
                <h4 class="card-title"><?= h($user['name'] ?? 'User') ?></h4>
                <p class="text-muted mb-2">
                    <i class="bi bi-envelope me-1"></i><?= h($user['email'] ?? '') ?>
                </p>
                <p class="text-muted small mb-3">
                    <i class="bi bi-calendar me-1"></i>Member since <?= $memberSince ?>
                </p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-danger fs-6">
                        <i class="bi bi-heart-fill me-1"></i><?= $favorite_count ?> Favorite<?= $favorite_count !== 1 ? 's' : '' ?>
                    </span>
                </div>
                
                <a href="/profile/edit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Profile
                </a>
            </div>
        </div>

        <?php if (isAdmin()) : ?>
        <div class="card mt-3 shadow-sm border-warning">
            <div class="card-body">
                <h6 class="card-title text-warning">
                    <i class="bi bi-shield-check me-1"></i>Administrator
                </h6>
                <a href="/admin/movies" class="btn btn-warning btn-sm w-100">
                    <i class="bi bi-gear me-1"></i>Manage Movies
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Favorites Section -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-heart-fill text-danger me-2"></i>My Favorites
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($favorites)) : ?>
                <div class="text-center py-4">
                    <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Favorites Yet</h5>
                    <p class="text-muted">Start adding movies to your favorites!</p>
                    <a href="/movies" class="btn btn-primary">
                        <i class="bi bi-collection-play me-1"></i>Browse Movies
                    </a>
                </div>
                <?php else : ?>
                <div class="row">
                    <?php foreach ($favorites as $movie) : ?>
                        <?php
                        $movie['is_favorited'] = true;
                        include __DIR__ . '/../partials/movie-card.php';
                        ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
