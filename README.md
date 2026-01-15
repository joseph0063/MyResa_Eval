# Mini Movies

A movie browsing application with user authentication, favorites, and admin CRUD functionality.

## Requirements

- **PHP:** 8.1+
- **Database:** MySQL 8.0+ or MariaDB 10.5+
- **Web Server:** Apache with mod_rewrite or PHP built-in server

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/MyResa_Eval.git
cd MyResa_Eval
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create the database

```sql
CREATE DATABASE mini_movies CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Import schema and seed data
```bash
mysql -u root -p mini_movies < database/database.sql
```

### 5. Configure Database Connection
Edit `config/database.php` with your credentials:
```php
'mysql' => [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'mini_movies',
    'username' => 'root',
    'password' => '',
],
```

### 6. Start Server
```bash
php -S localhost:8000 -t public
```

### 7. Access Application
Open http://localhost:8000 in your browser.

## Test Accounts

| Role  | Email           | Password    |
|-------|-----------------|-------------|
| Admin | admin@test.com  | password123 |
| User  | user@test.com   | password123 |

## User Roles & Features

### Guest (Not Logged In)

Guests can:
- Browse the movie catalog
- Search movies by title
- Filter movies by genre
- Sort movies by rating, year, or title
- View movie details (poster, description, rating)
- Register for a new account
- Login to an existing account

### Registered User

Registered users have all guest features, plus:
- **Favorites:** Add or remove movies from personal favorites list
- **Profile:** View personal profile information
- **Edit Profile:** Update display name
- **Logout:** End session securely

### Administrator

Administrators have all registered user features, plus:
- **Movie Management:** Access `/admin/movies` dashboard
- **Create Movies:** Add new movies to the catalog
- **Edit Movies:** Modify existing movie information
- **Delete Movies:** Remove movies from the catalog

#### Admin Rule

**The first registered user automatically becomes an administrator.**

When you register a new account and the users table is empty, your account will be assigned `is_admin = 1`. All subsequent users will be regular users.

## Security

- All passwords hashed with `password_hash()` (bcrypt)
- CSRF protection on all POST forms
- PDO prepared statements for all database queries
- XSS protection via `htmlspecialchars()` output escaping
- Session ID regeneration on login

## Known Shortcuts

- No email verification on registration
- No password reset functionality
- Admin determined by first user (no admin panel to manage roles)
