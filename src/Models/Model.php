<?php

declare(strict_types=1);

/**
 * Base Model
 *
 * Abstract base class providing database access for all models.
 *
 * @package MiniMovies\Models
 * @requires PHP 8.1
 */
abstract class Model
{
    /**
     * Get PDO database connection instance.
     */
    protected static function db(): PDO
    {
        return db();
    }
}
