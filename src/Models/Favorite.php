<?php

declare(strict_types=1);

require_once __DIR__ . '/Model.php';

/**
 * Favorite Model
 *
 * Handles user favorites: add, remove, check, and retrieve favorite movies.
 *
 * @package MiniMovies\Models
 * @requires PHP 8.1
 */
class Favorite extends Model
{
    /**
     * Add a movie to user's favorites.
     * Uses INSERT IGNORE to handle duplicates gracefully.
     */
    public static function add(int $userId, int $movieId): bool
    {
        $sql = 'INSERT IGNORE INTO favorites (user_id, movie_id, created_at) VALUES (:user_id, :movie_id, NOW())';
        $stmt = self::db()->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'movie_id' => $movieId,
        ]);
    }

    /**
     * Remove a movie from user's favorites.
     */
    public static function remove(int $userId, int $movieId): bool
    {
        $sql = 'DELETE FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id';
        $stmt = self::db()->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'movie_id' => $movieId,
        ]);
    }

    /**
     * Check if a movie is in user's favorites.
     */
    public static function exists(int $userId, int $movieId): bool
    {
        $sql = 'SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id';
        $stmt = self::db()->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'movie_id' => $movieId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Get all favorites for a user with movie details.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getByUser(int $userId): array
    {
        $sql = 'SELECT m.*, f.created_at as favorited_at
                FROM favorites f
                INNER JOIN movies m ON f.movie_id = m.id
                WHERE f.user_id = :user_id
                ORDER BY f.created_at DESC';

        $stmt = self::db()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    /**
     * Count user's favorites.
     */
    public static function countByUser(int $userId): int
    {
        $sql = 'SELECT COUNT(*) FROM favorites WHERE user_id = :user_id';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Get array of movie IDs favorited by a user.
     *
     * @return array<int, int>
     */
    public static function getMovieIdsForUser(int $userId): array
    {
        $sql = 'SELECT movie_id FROM favorites WHERE user_id = :user_id';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
