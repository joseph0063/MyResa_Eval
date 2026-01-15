<?php

declare(strict_types=1);

/**
 * Front Controller / Router
 *
 * Entry point for all requests. Routes to appropriate controllers.
 *
 * @package MiniMovies
 */

// Start session
session_start();

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load helper functions
require_once __DIR__ . '/../src/Helpers/functions.php';

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = $path !== '/' ? rtrim($path, '/') : '/';

// Simple router
try {
    match (true) {
        // Home - redirect to movies
        $path === '/' => redirect('/movies'),

        // Auth routes
        $path === '/register' && $method === 'GET' => require __DIR__ . '/../src/Controllers/AuthController.php',
        $path === '/register' && $method === 'POST' => require __DIR__ . '/../src/Controllers/AuthController.php',
        $path === '/login' && $method === 'GET' => require __DIR__ . '/../src/Controllers/AuthController.php',
        $path === '/login' && $method === 'POST' => require __DIR__ . '/../src/Controllers/AuthController.php',
        $path === '/logout' && $method === 'POST' => require __DIR__ . '/../src/Controllers/AuthController.php',

        // Profile routes
        $path === '/profile' && $method === 'GET' => require __DIR__ . '/../src/Controllers/ProfileController.php',
        $path === '/profile/edit' && $method === 'GET' => require __DIR__ . '/../src/Controllers/ProfileController.php',
        $path === '/profile/edit' && $method === 'POST' => require __DIR__ . '/../src/Controllers/ProfileController.php',

        // Movies routes
        $path === '/movies' && $method === 'GET' => require __DIR__ . '/../src/Controllers/MovieController.php',
        preg_match('#^/movies/(\d+)$#', $path) === 1 => require __DIR__ . '/../src/Controllers/MovieController.php',
        preg_match('#^/movies/(\d+)/favorite$#', $path) === 1 && $method === 'POST'
            => require __DIR__ . '/../src/Controllers/FavoriteController.php',
        preg_match('#^/movies/(\d+)/unfavorite$#', $path) === 1 && $method === 'POST'
            => require __DIR__ . '/../src/Controllers/FavoriteController.php',

        // Admin routes
        str_starts_with($path, '/admin') => require __DIR__ . '/../src/Controllers/AdminController.php',

        // 404
        default => require __DIR__ . '/../src/Views/errors/404.php',
    };
} catch (Throwable $e) {
    // Log error in production, show details in development
    error_log($e->getMessage());
    http_response_code(500);
    require __DIR__ . '/../src/Views/errors/500.php';
}
