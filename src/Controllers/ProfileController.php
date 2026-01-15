<?php

declare(strict_types=1);

/**
 * Profile Controller
 *
 * Handles user profile page and profile editing.
 *
 * @package MiniMovies\Controllers
 * @requires PHP 8.1
 */

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Favorite.php';
require_once __DIR__ . '/../Helpers/Validator.php';

// Require authentication
requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$userId = currentUserId();

// GET /profile - Show profile page
if ($path === '/profile' && $method === 'GET') {
    $user = User::findById($userId);
    $favoriteCount = Favorite::countByUser($userId);
    $favorites = Favorite::getByUser($userId);

    view('profile/index', [
        'user' => $user,
        'favorites' => $favorites,
        'favorite_count' => $favoriteCount,
    ]);
}

// GET /profile/edit - Show edit form
elseif ($path === '/profile/edit' && $method === 'GET') {
    $user = User::findById($userId);
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    view('profile/edit', [
        'user' => $user,
        'errors' => $errors,
    ]);
}

// POST /profile/edit - Process edit
elseif ($path === '/profile/edit' && $method === 'POST') {
    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect('/profile/edit');
    }

    $validator = new Validator($_POST);
    $validator
        ->required('name', 'Name is required.')
        ->max('name', 255, 'Name must not exceed 255 characters.');

    if ($validator->fails()) {
        setOldInput($_POST);
        $_SESSION['errors'] = $validator->errors();
        redirect('/profile/edit');
    }

    User::update($userId, [
        'name' => trim($_POST['name']),
    ]);

    clearOldInput();
    flash('success', 'Profile updated successfully.');
    redirect('/profile');
}
