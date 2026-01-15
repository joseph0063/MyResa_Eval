<?php

declare(strict_types=1);

/**
 * Admin Controller
 *
 * Handles admin movie CRUD operations.
 *
 * @package MiniMovies\Controllers
 * @requires PHP 8.1
 */

require_once __DIR__ . '/../Models/Movie.php';
require_once __DIR__ . '/../Helpers/Validator.php';

// Require admin access for all routes
requireAdmin();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// GET /admin/movies - List all movies
if ($path === '/admin/movies' && $method === 'GET') {
    $movies = Movie::all(['per_page' => 1000]);

    view('admin/movies/index', [
        'movies' => $movies,
    ]);
}

// GET /admin/movies/create - Show create form
elseif ($path === '/admin/movies/create' && $method === 'GET') {
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    view('admin/movies/create', [
        'errors' => $errors,
    ]);
}

// POST /admin/movies - Create new movie
elseif ($path === '/admin/movies' && $method === 'POST') {
    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect('/admin/movies/create');
    }

    $validator = new Validator($_POST);
    $validator
        ->required('title', 'Title is required.')
        ->max('title', 255, 'Title must not exceed 255 characters.')
        ->required('genre', 'Genre is required.')
        ->max('genre', 100, 'Genre must not exceed 100 characters.');

    if (!empty($_POST['release_year'])) {
        $validator
            ->numeric('release_year', 'Release year must be a number.')
            ->between('release_year', 1888, 2030, 'Release year must be between 1888 and 2030.');
    }

    if (!empty($_POST['rating'])) {
        $validator
            ->numeric('rating', 'Rating must be a number.')
            ->between('rating', 0, 10, 'Rating must be between 0 and 10.');
    }

    if (!empty($_POST['poster_url'])) {
        $validator->url('poster_url', 'Poster URL must be a valid URL.');
    }

    if ($validator->fails()) {
        setOldInput($_POST);
        $_SESSION['errors'] = $validator->errors();
        redirect('/admin/movies/create');
    }

    Movie::create([
        'title' => trim($_POST['title']),
        'description' => trim($_POST['description'] ?? ''),
        'genre' => trim($_POST['genre']),
        'release_year' => !empty($_POST['release_year']) ? (int) $_POST['release_year'] : null,
        'rating' => !empty($_POST['rating']) ? (float) $_POST['rating'] : null,
        'poster_url' => trim($_POST['poster_url'] ?? ''),
    ]);

    clearOldInput();
    flash('success', 'Movie created successfully.');
    redirect('/admin/movies');
}

// GET /admin/movies/{id}/edit - Show edit form
elseif (preg_match('#^/admin/movies/(\d+)/edit$#', $path, $matches) && $method === 'GET') {
    $movieId = (int) $matches[1];
    $movie = Movie::find($movieId);

    if (!$movie) {
        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
        exit;
    }

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    view('admin/movies/edit', [
        'movie' => $movie,
        'errors' => $errors,
    ]);
}

// POST /admin/movies/{id} - Update movie
elseif (preg_match('#^/admin/movies/(\d+)$#', $path, $matches) && $method === 'POST') {
    $movieId = (int) $matches[1];
    $movie = Movie::find($movieId);

    if (!$movie) {
        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
        exit;
    }

    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect("/admin/movies/{$movieId}/edit");
    }

    $validator = new Validator($_POST);
    $validator
        ->required('title', 'Title is required.')
        ->max('title', 255, 'Title must not exceed 255 characters.')
        ->required('genre', 'Genre is required.')
        ->max('genre', 100, 'Genre must not exceed 100 characters.');

    if (!empty($_POST['release_year'])) {
        $validator
            ->numeric('release_year', 'Release year must be a number.')
            ->between('release_year', 1888, 2030, 'Release year must be between 1888 and 2030.');
    }

    if (!empty($_POST['rating'])) {
        $validator
            ->numeric('rating', 'Rating must be a number.')
            ->between('rating', 0, 10, 'Rating must be between 0 and 10.');
    }

    if (!empty($_POST['poster_url'])) {
        $validator->url('poster_url', 'Poster URL must be a valid URL.');
    }

    if ($validator->fails()) {
        setOldInput($_POST);
        $_SESSION['errors'] = $validator->errors();
        redirect("/admin/movies/{$movieId}/edit");
    }

    Movie::update($movieId, [
        'title' => trim($_POST['title']),
        'description' => trim($_POST['description'] ?? ''),
        'genre' => trim($_POST['genre']),
        'release_year' => !empty($_POST['release_year']) ? (int) $_POST['release_year'] : null,
        'rating' => !empty($_POST['rating']) ? (float) $_POST['rating'] : null,
        'poster_url' => trim($_POST['poster_url'] ?? ''),
    ]);

    clearOldInput();
    flash('success', 'Movie updated successfully.');
    redirect('/admin/movies');
}

// POST /admin/movies/{id}/delete - Delete movie
elseif (preg_match('#^/admin/movies/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    $movieId = (int) $matches[1];

    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect('/admin/movies');
    }

    $movie = Movie::find($movieId);

    if (!$movie) {
        flash('error', 'Movie not found.');
        redirect('/admin/movies');
    }

    Movie::delete($movieId);

    flash('success', 'Movie deleted successfully.');
    redirect('/admin/movies');
}

// Redirect /admin to /admin/movies
elseif ($path === '/admin') {
    redirect('/admin/movies');
}
