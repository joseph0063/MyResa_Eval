<?php

declare(strict_types=1);

require_once __DIR__ . '/Model.php';

/**
 * User Model
 *
 * Handles user data operations: creation, retrieval, and updates.
 * First registered user automatically becomes admin.
 *
 * @package MiniMovies\Models
 * @requires PHP 8.1
 */
class User extends Model
{
    /**
     * Create a new user.
     *
     * @param array{name: string, email: string, password: string, is_admin?: bool} $data
     * @return int The new user's ID
     */
    public static function create(array $data): int
    {
        $isAdmin = $data['is_admin'] ?? self::isFirstUser();

        $sql = 'INSERT INTO users (name, email, password_hash, is_admin, created_at, updated_at)
                VALUES (:name, :email, :password_hash, :is_admin, NOW(), NOW())';

        $stmt = self::db()->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'is_admin' => $isAdmin ? 1 : 0,
        ]);

        return (int) self::db()->lastInsertId();
    }

    /**
     * Find user by ID.
     *
     * @return array{id: int, name: string, email: string, password_hash: string, is_admin: int, created_at: string, updated_at: string}|null
     */
    public static function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM users WHERE id = :id LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Find user by email.
     *
     * @return array{id: int, name: string, email: string, password_hash: string, is_admin: int, created_at: string, updated_at: string}|null
     */
    public static function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Check if email already exists.
     */
    public static function emailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['email' => $email]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Update user fields.
     *
     * @param array<string, mixed> $data Fields to update (name, email, password, etc.)
     */
    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';

        $stmt = self::db()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Check if users table is empty (for first user admin rule).
     */
    private static function isFirstUser(): bool
    {
        $sql = 'SELECT COUNT(*) FROM users';
        $stmt = self::db()->query($sql);

        return (int) $stmt->fetchColumn() === 0;
    }
}
