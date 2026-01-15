<?php

declare(strict_types=1);

/**
 * Database Configuration Example
 *
 * Copy this file to database.php and update with your local settings.
 *
 * @package MiniMovies\Config
 */

return [
    'driver' => 'mysql', // 'mysql' or 'sqlite'

    // MySQL settings
    'mysql' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'mini_movies',
        'username' => 'your_username',
        'password' => 'your_password',
        'charset' => 'utf8mb4',
    ],

    // SQLite settings (fallback)
    'sqlite' => [
        'path' => __DIR__ . '/../database/mini_movies.sqlite',
    ],

    // PDO options
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
