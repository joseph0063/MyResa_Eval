<?php

declare(strict_types=1);

require_once __DIR__ . '/Model.php';

/**
 * Movie Model
 *
 * Handles movie data operations: CRUD, filtering, sorting, and pagination.
 *
 * @package MiniMovies\Models
 * @requires PHP 8.1
 */
class Movie extends Model
{
    private const DEFAULT_PER_PAGE = 6;

    /**
     * Get movies with optional search, filter, sort, and pagination.
     *
     * @param array{q?: string, genre?: string, sort?: string, page?: int, per_page?: int, user_id?: int} $filters
     * @return array<int, array<string, mixed>>
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = 'title LIKE :q';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['genre'])) {
            $where[] = 'genre = :genre';
            $params['genre'] = $filters['genre'];
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $orderBy = match ($filters['sort'] ?? 'rating_desc') {
            'year_desc' => 'release_year DESC',
            'title_asc' => 'title ASC',
            default => 'rating DESC',
        };

        $perPage = $filters['per_page'] ?? self::DEFAULT_PER_PAGE;
        $page = max(1, $filters['page'] ?? 1);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT m.* FROM movies m {$whereClause} ORDER BY {$orderBy} LIMIT :limit OFFSET :offset";

        $stmt = self::db()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $movies = $stmt->fetchAll();

        if (!empty($filters['user_id'])) {
            $favoriteIds = self::getFavoriteMovieIds((int) $filters['user_id']);
            foreach ($movies as &$movie) {
                $movie['is_favorited'] = in_array($movie['id'], $favoriteIds, true);
            }
        }

        return $movies;
    }

    /**
     * Find a single movie by ID.
     *
     * @return array<string, mixed>|null Movie data or null if not found
     */
    public static function find(int $id): ?array
    {
        $sql = 'SELECT * FROM movies WHERE id = :id LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Create a new movie.
     *
     * @param array{title: string, description?: string, release_year?: int, rating?: float, genre?: string, poster_url?: string} $data
     * @return int The new movie's ID
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO movies (title, description, release_year, rating, genre, poster_url, created_at, updated_at)
                VALUES (:title, :description, :release_year, :rating, :genre, :poster_url, NOW(), NOW())';

        $stmt = self::db()->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'release_year' => $data['release_year'] ?? null,
            'rating' => $data['rating'] ?? null,
            'genre' => $data['genre'] ?? '',
            'poster_url' => $data['poster_url'] ?? '',
        ]);

        return (int) self::db()->lastInsertId();
    }

    /**
     * Update a movie.
     *
     * @param array<string, mixed> $data Fields to update
     */
    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        $allowedFields = ['title', 'description', 'release_year', 'rating', 'genre', 'poster_url'];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $sql = 'UPDATE movies SET ' . implode(', ', $fields) . ' WHERE id = :id';

        $stmt = self::db()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete a movie.
     */
    public static function delete(int $id): bool
    {
        $sql = 'DELETE FROM movies WHERE id = :id';
        $stmt = self::db()->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get all distinct genres.
     *
     * @return array<int, string>
     */
    public static function getGenres(): array
    {
        $sql = 'SELECT DISTINCT genre FROM movies WHERE genre IS NOT NULL AND genre != "" ORDER BY genre ASC';
        $stmt = self::db()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Count movies matching filters (for pagination).
     *
     * @param array{q?: string, genre?: string} $filters
     */
    public static function count(array $filters = []): int
    {
        $where = [];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = 'title LIKE :q';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['genre'])) {
            $where[] = 'genre = :genre';
            $params['genre'] = $filters['genre'];
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT COUNT(*) FROM movies {$whereClause}";
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Get array of movie IDs favorited by a user.
     *
     * @return array<int, int>
     */
    private static function getFavoriteMovieIds(int $userId): array
    {
        $sql = 'SELECT movie_id FROM favorites WHERE user_id = :user_id';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
