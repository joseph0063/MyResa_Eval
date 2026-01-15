<?php
/**
 * Admin Movies List View
 *
 * Displays all movies in a table with CRUD actions.
 *
 * @package MiniMovies\Views\Admin\Movies
 * @var array<int, array<string, mixed>> $movies Array of all movies
 */

$movies = $movies ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-gear me-2"></i>Manage Movies
    </h1>
    <a href="/admin/movies/create" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i>Add Movie
    </a>
</div>

<?php if (empty($movies)) : ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>No movies found. Add your first movie!
</div>
<?php else : ?>
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 80px;">Poster</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie) : ?>
                <tr>
                    <td class="align-middle"><?= (int)$movie['id'] ?></td>
                    <td class="align-middle">
                        <?php if (!empty($movie['poster_url'])) : ?>
                        <img 
                            src="<?= h($movie['poster_url']) ?>" 
                            alt="<?= h($movie['title']) ?>"
                            class="rounded"
                            style="width: 50px; height: 75px; object-fit: cover;"
                            onerror="this.style.display='none';"
                        >
                        <?php else : ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 75px;">
                            <i class="bi bi-film"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <a href="/movies/<?= (int)$movie['id'] ?>" class="text-decoration-none">
                            <?= h($movie['title']) ?>
                        </a>
                    </td>
                    <td class="align-middle">
                        <span class="badge bg-info text-dark"><?= h($movie['genre']) ?></span>
                    </td>
                    <td class="align-middle"><?= (int)$movie['release_year'] ?></td>
                    <td class="align-middle">
                        <?php
                        $ratingClass = 'bg-secondary';
                        if ($movie['rating'] >= 7) {
                            $ratingClass = 'bg-success';
                        } elseif ($movie['rating'] >= 5) {
                            $ratingClass = 'bg-warning text-dark';
                        } elseif ($movie['rating'] > 0) {
                            $ratingClass = 'bg-danger';
                        }
                        ?>
                        <span class="badge <?= $ratingClass ?>">
                            <?= number_format((float)$movie['rating'], 1) ?>
                        </span>
                    </td>
                    <td class="align-middle">
                        <a href="/admin/movies/<?= (int)$movie['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form 
                            action="/admin/movies/<?= (int)$movie['id'] ?>/delete" 
                            method="POST" 
                            class="d-inline" 
                            onsubmit="return confirm('Are you sure?');"
                        >
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 text-muted">
    <small>Total: <?= count($movies) ?> movie<?= count($movies) !== 1 ? 's' : '' ?></small>
</div>
<?php endif; ?>
