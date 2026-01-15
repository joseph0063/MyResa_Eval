<?php

declare(strict_types=1);

/**
 * Movie Controller
 *
 * Handles movie listing and detail pages.
 *
 * @package MiniMovies\Controllers
 * @requires PHP 8.1
 */

require_once __DIR__ . '/../Models/Movie.php';
require_once __DIR__ . '/../Models/Favorite.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// GET /movies - List movies with filters and pagination
if ($path === '/movies' && $method === 'GET') {
    $filters = [
        'q' => trim($_GET['q'] ?? ''),
        'genre' => trim($_GET['genre'] ?? ''),
        'sort' => $_GET['sort'] ?? 'rating_desc',
        'page' => max(1, (int) ($_GET['page'] ?? 1)),
        'per_page' => 6,
    ];

    if (isLoggedIn()) {
        $filters['user_id'] = currentUserId();
    }

    $movies = Movie::all($filters);
    $totalMovies = Movie::count($filters);
    $genres = Movie::getGenres();

    $totalPages = (int) ceil($totalMovies / $filters['per_page']);

    view('movies/index', [
        'movies' => $movies,
        'genres' => $genres,
        'filters' => $filters,
        'pagination' => [
            'total' => $totalMovies,
            'per_page' => $filters['per_page'],
            'current_page' => $filters['page'],
            'total_pages' => $totalPages,
        ],
    ]);
}

// GET /movies/{id} - Show single movie
elseif (preg_match('#^/movies/(\d+)$#', $path, $matches) && $method === 'GET') {
    $movieId = (int) $matches[1];
    $movie = Movie::find($movieId);

    if (!$movie) {
        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
        exit;
    }

    $isFavorited = false;
    if (isLoggedIn()) {
        $isFavorited = Favorite::exists(currentUserId(), $movieId);
    }

    view('movies/show', [
        'movie' => $movie,
        'is_favorited' => $isFavorited,
    ]);
}
