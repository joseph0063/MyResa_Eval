<?php
/**
 * Movies List View
 *
 * Displays movie grid with search, filter, sort, and pagination.
 *
 * @package MiniMovies\Views\Movies
 * @var array<int, array<string, mixed>> $movies Array of movie data
 * @var array<int, string> $genres Array of genre strings for dropdown
 * @var array<string, mixed> $filters Current filter values (q, genre, sort, page)
 * @var array<string, mixed> $pagination Pagination data (current_page, total_pages, per_page, total)
 */

$movies = $movies ?? [];
$genres = $genres ?? [];
$filters = $filters ?? [];
$pagination = $pagination ?? ['current_page' => 1, 'total_pages' => 1, 'total' => 0];

$q = $filters['q'] ?? '';
$genre = $filters['genre'] ?? '';
$sort = $filters['sort'] ?? 'rating_desc';
?>

<h1 class="mb-4">
    <i class="bi bi-collection-play me-2"></i>Movies
    <?php if ($pagination['total'] > 0) : ?>
    <small class="text-muted fs-6">(<?= $pagination['total'] ?> found)</small>
    <?php endif; ?>
</h1>

<!-- Filter Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form action="/movies" method="GET" class="row g-3 align-items-end">
            <!-- Search -->
            <div class="col-12 col-md-4">
                <label for="q" class="form-label">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="q" 
                        name="q" 
                        value="<?= h($q) ?>"
                        placeholder="Search by title..."
                    >
                </div>
            </div>

            <!-- Genre Filter -->
            <div class="col-12 col-md-3">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-select" id="genre" name="genre">
                    <option value="">All Genres</option>
                    <?php foreach ($genres as $g) : ?>
                    <option value="<?= h($g) ?>" <?= $genre === $g ? 'selected' : '' ?>>
                        <?= h($g) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sort -->
            <div class="col-12 col-md-3">
                <label for="sort" class="form-label">Sort By</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="rating_desc" <?= $sort === 'rating_desc' ? 'selected' : '' ?>>
                        Rating (High to Low)
                    </option>
                    <option value="year_desc" <?= $sort === 'year_desc' ? 'selected' : '' ?>>
                        Year (Newest First)
                    </option>
                    <option value="title_asc" <?= $sort === 'title_asc' ? 'selected' : '' ?>>
                        Title (A-Z)
                    </option>
                </select>
            </div>

            <!-- Submit -->
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
            </div>
        </form>

        <?php if ($q || $genre) : ?>
        <div class="mt-3">
            <a href="/movies" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-circle me-1"></i>Clear Filters
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Movies Grid -->
<?php if (empty($movies)) : ?>
<div class="text-center py-5">
    <i class="bi bi-film text-muted" style="font-size: 4rem;"></i>
    <h3 class="mt-3 text-muted">No Movies Found</h3>
    <p class="text-muted">
        <?php if ($q || $genre) : ?>
        Try adjusting your search or filter criteria.
        <?php else : ?>
        No movies are available at this time.
        <?php endif; ?>
    </p>
    <?php if ($q || $genre) : ?>
    <a href="/movies" class="btn btn-primary">
        <i class="bi bi-arrow-left me-1"></i>View All Movies
    </a>
    <?php endif; ?>
</div>
<?php else : ?>
<div class="row">
    <?php foreach ($movies as $movie) : ?>
        <?php include __DIR__ . '/../partials/movie-card.php'; ?>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<div class="mt-4">
    <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
<?php endif; ?>
