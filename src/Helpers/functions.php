<?php

declare(strict_types=1);

/**
 * Global Helper Functions
 *
 * Utility functions available throughout the application.
 *
 * @package MiniMovies\Helpers
 */

/**
 * Get the PDO database connection instance (singleton).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $config = require __DIR__ . '/../../config/database.php';
        $driver = $config['driver'];

        if ($driver === 'mysql') {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['mysql']['host'],
                $config['mysql']['port'],
                $config['mysql']['database'],
                $config['mysql']['charset']
            );
            $pdo = new PDO($dsn, $config['mysql']['username'], $config['mysql']['password'], $config['options']);
        } else {
            $dsn = 'sqlite:' . $config['sqlite']['path'];
            $pdo = new PDO($dsn, null, null, $config['options']);
        }
    }

    return $pdo;
}

/**
 * Redirect to a URL.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Escape HTML entities for safe output.
 */
function h(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID or null.
 */
function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Check if current user is admin.
 */
function isAdmin(): bool
{
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

/**
 * Generate CSRF token.
 */
function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate CSRF hidden input field.
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrfToken()) . '">';
}

/**
 * Validate CSRF token from POST request.
 */
function validateCsrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get old form input value (for repopulation on validation errors).
 */
function old(string $field, string $default = ''): string
{
    return h($_SESSION['old_input'][$field] ?? $default);
}

/**
 * Set old input values in session.
 *
 * @param array<string, mixed> $data
 */
function setOldInput(array $data): void
{
    $_SESSION['old_input'] = $data;
}

/**
 * Clear old input values.
 */
function clearOldInput(): void
{
    unset($_SESSION['old_input']);
}

/**
 * Set a flash message.
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

/**
 * Get and clear flash messages.
 *
 * @return array<string, string>
 */
function getFlash(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Get base URL for the application.
 */
function baseUrl(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocol . '://' . $host;
}

/**
 * Get current URL path.
 */
function currentPath(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
}

/**
 * Require user to be authenticated.
 * Redirects to login with return_to parameter if not logged in.
 */
function requireAuth(): void
{
    if (!isLoggedIn()) {
        flash('error', 'Please login to continue.');
        redirect('/login?return_to=' . urlencode(currentPath()));
    }
}

/**
 * Require user to be admin.
 * Shows 403 error if not admin.
 */
function requireAdmin(): void
{
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        require __DIR__ . '/../Views/errors/403.php';
        exit;
    }
}

/**
 * Require user to be a guest (not logged in).
 * Redirects to movies if already logged in.
 */
function requireGuest(): void
{
    if (isLoggedIn()) {
        redirect('/movies');
    }
}

/**
 * Render a view template with layout.
 *
 * @param string $template Template path relative to Views folder (without .php)
 * @param array<string, mixed> $data Variables to pass to the view
 */
function view(string $template, array $data = []): void
{
    extract($data);

    ob_start();
    require __DIR__ . '/../Views/' . $template . '.php';
    $content = ob_get_clean();

    require __DIR__ . '/../Views/layouts/main.php';
}
