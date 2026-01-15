<?php
/**
 * Admin Create Movie Form
 *
 * Form for creating a new movie.
 *
 * @package MiniMovies\Views\Admin\Movies
 * @var array<string, string> $errors Validation errors (optional)
 */

$errors = $errors ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-plus-circle me-2"></i>Add New Movie
    </h1>
    <a href="/admin/movies" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to List
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="/admin/movies" method="POST">
                    <?= csrfField() ?>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                            id="title" 
                            name="title" 
                            value="<?= old('title') ?>"
                            placeholder="Enter movie title"
                            required
                        >
                        <?php if (isset($errors['title'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea 
                            class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                            id="description" 
                            name="description" 
                            rows="4"
                            placeholder="Enter movie description"
                        ><?= old('description') ?></textarea>
                        <?php if (isset($errors['description'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['description']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <!-- Genre -->
                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control <?= isset($errors['genre']) ? 'is-invalid' : '' ?>" 
                                id="genre" 
                                name="genre" 
                                value="<?= old('genre') ?>"
                                placeholder="e.g., Action, Drama, Comedy"
                                required
                            >
                            <?php if (isset($errors['genre'])) : ?>
                            <div class="invalid-feedback"><?= h($errors['genre']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Release Year -->
                        <div class="col-md-6 mb-3">
                            <label for="release_year" class="form-label">Release Year</label>
                            <input 
                                type="number" 
                                class="form-control <?= isset($errors['release_year']) ? 'is-invalid' : '' ?>" 
                                id="release_year" 
                                name="release_year" 
                                value="<?= old('release_year') ?>"
                                placeholder="e.g., 2024"
                                min="1888"
                                max="2030"
                            >
                            <?php if (isset($errors['release_year'])) : ?>
                            <div class="invalid-feedback"><?= h($errors['release_year']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Rating -->
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label">Rating (0-10)</label>
                            <input 
                                type="number" 
                                class="form-control <?= isset($errors['rating']) ? 'is-invalid' : '' ?>" 
                                id="rating" 
                                name="rating" 
                                value="<?= old('rating') ?>"
                                placeholder="e.g., 8.5"
                                min="0"
                                max="10"
                                step="0.1"
                            >
                            <?php if (isset($errors['rating'])) : ?>
                            <div class="invalid-feedback"><?= h($errors['rating']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Poster URL -->
                        <div class="col-md-6 mb-3">
                            <label for="poster_url" class="form-label">Poster URL</label>
                            <input 
                                type="url" 
                                class="form-control <?= isset($errors['poster_url']) ? 'is-invalid' : '' ?>" 
                                id="poster_url" 
                                name="poster_url" 
                                value="<?= old('poster_url') ?>"
                                placeholder="https://example.com/poster.jpg"
                            >
                            <?php if (isset($errors['poster_url'])) : ?>
                            <div class="invalid-feedback"><?= h($errors['poster_url']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Create Movie
                        </button>
                        <a href="/admin/movies" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
