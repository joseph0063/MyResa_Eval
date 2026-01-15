<?php

declare(strict_types=1);

/**
 * Database Configuration
 *
 * Returns PDO connection settings for MySQL (primary) or SQLite (fallback).
 * Copy to database.local.php and modify for your environment.
 *
 * @package MiniMovies\Config
 */

return [
    'driver' => 'mysql', // 'mysql' or 'sqlite'

    // MySQL settings
    'mysql' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'mini_movies', // Created and seeded
        'username' => 'root',
        'password' => '',
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
