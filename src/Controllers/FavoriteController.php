<?php

declare(strict_types=1);

/**
 * Favorite Controller
 *
 * Handles adding/removing movie favorites.
 *
 * @package MiniMovies\Controllers
 * @requires PHP 8.1
 */

require_once __DIR__ . '/../Models/Movie.php';
require_once __DIR__ . '/../Models/Favorite.php';

// Require authentication
requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Get redirect URL (referer or default to /movies)
$redirectTo = $_SERVER['HTTP_REFERER'] ?? '/movies';
if (!str_starts_with($redirectTo, '/')) {
    $parsed = parse_url($redirectTo);
    $redirectTo = $parsed['path'] ?? '/movies';
}

// POST /movies/{id}/favorite - Add to favorites
if (preg_match('#^/movies/(\d+)/favorite$#', $path, $matches) && $method === 'POST') {
    $movieId = (int) $matches[1];

    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect($redirectTo);
    }

    $movie = Movie::find($movieId);

    if (!$movie) {
        flash('error', 'Movie not found.');
        redirect('/movies');
    }

    Favorite::add(currentUserId(), $movieId);

    flash('success', 'Added to favorites!');
    redirect($redirectTo);
}

// POST /movies/{id}/unfavorite - Remove from favorites
elseif (preg_match('#^/movies/(\d+)/unfavorite$#', $path, $matches) && $method === 'POST') {
    $movieId = (int) $matches[1];

    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect($redirectTo);
    }

    Favorite::remove(currentUserId(), $movieId);

    flash('success', 'Removed from favorites.');
    redirect($redirectTo);
}
