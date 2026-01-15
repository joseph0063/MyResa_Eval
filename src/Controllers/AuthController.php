<?php

declare(strict_types=1);

/**
 * Auth Controller
 *
 * Handles registration, login, and logout.
 *
 * @package MiniMovies\Controllers
 * @requires PHP 8.1
 */

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/Validator.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// GET /register - Show registration form
if ($path === '/register' && $method === 'GET') {
    requireGuest();

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    view('auth/register', ['errors' => $errors]);
}

// POST /register - Process registration
elseif ($path === '/register' && $method === 'POST') {
    requireGuest();

    if (!validateCsrf()) {
        flash('error', 'Invalid security token. Please try again.');
        redirect('/register');
    }

    $validator = new Validator($_POST);
    $validator
        ->required('name', 'Name is required.')
        ->max('name', 255, 'Name must not exceed 255 characters.')
        ->required('email', 'Email is required.')
        ->email('email', 'Please enter a valid email address.')
        ->unique('email', 'users', 'email', 'This email is already registered.')
        ->required('password', 'Password is required.')
        ->min('password', 8, 'Password must be at least 8 characters.')
        ->confirmed('password', 'Password confirmation does not match.');

    if ($validator->fails()) {
        setOldInput($_POST);
        $_SESSION['errors'] = $validator->errors();
        redirect('/register');
    }

    $userId = User::create([
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => $_POST['password'],
    ]);

    $user = User::findById($userId);

    $_SESSION['user_id'] = $userId;
    $_SESSION['is_admin'] = (bool) $user['is_admin'];

    session_regenerate_id(true);
    clearOldInput();

    flash('success', 'Welcome! Your account has been created.');
    redirect('/movies');
}

// GET /login - Show login form
elseif ($path === '/login' && $method === 'GET') {
    requireGuest();

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    $returnTo = $_GET['return_to'] ?? '';

    view('auth/login', [
        'errors' => $errors,
        'return_to' => $returnTo,
    ]);
}

// POST /login - Process login
elseif ($path === '/login' && $method === 'POST') {
    requireGuest();

    if (!validateCsrf()) {
        flash('error', 'Invalid security token. Please try again.');
        redirect('/login');
    }

    $validator = new Validator($_POST);
    $validator
        ->required('email', 'Email is required.')
        ->email('email', 'Please enter a valid email address.')
        ->required('password', 'Password is required.');

    if ($validator->fails()) {
        setOldInput($_POST);
        $_SESSION['errors'] = $validator->errors();
        redirect('/login');
    }

    $user = User::findByEmail(trim($_POST['email']));

    if (!$user || !password_verify($_POST['password'], $user['password_hash'])) {
        setOldInput($_POST);
        $_SESSION['errors'] = ['email' => 'Invalid email or password.'];
        redirect('/login');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['is_admin'] = (bool) $user['is_admin'];

    session_regenerate_id(true);
    clearOldInput();

    flash('success', 'Welcome back!');

    $returnTo = $_POST['return_to'] ?? '';
    if ($returnTo && str_starts_with($returnTo, '/')) {
        redirect($returnTo);
    }
    redirect('/movies');
}

// POST /logout - Process logout
elseif ($path === '/logout' && $method === 'POST') {
    if (!validateCsrf()) {
        flash('error', 'Invalid security token.');
        redirect('/movies');
    }

    // Clear session data but keep the session for flash message
    $_SESSION = [];
    
    // Set flash message before any session destruction
    $_SESSION['flash']['success'] = 'You have been logged out.';
    
    redirect('/movies');
}
