# Agent A - Backend & Core Logic

## Progress Tracker
- [x] **Phase 1: Models** ✅ Complete (Tested)
- [x] **Phase 2: Validation Helper** ✅ Complete (Tested)
- [x] **Phase 3: Authentication System** ✅ Complete (Tested)
- [x] **Phase 4: Profile Logic** ✅ Complete (Tested)
- [x] **Phase 5: Admin CRUD** ✅ Complete (Tested)
- [x] **Phase 6: Favorites Logic** ✅ Complete (Tested)
- [x] **Phase 7: Movies Controller Logic** ✅ Complete (Tested)

---

## Your Responsibilities
You are responsible for the **backend core**: Models, Authentication, Profile logic, Admin CRUD, and Validation.

**DO NOT** work on views/templates (Agent B handles those). You create the PHP logic, Agent B creates the HTML/Bootstrap UI.

---

## Project Context

### Stack
- **Backend:** PHP 8.1+ (vanilla, no frameworks)
- **Database:** MySQL (mini_movies database - already created)
- **Auth:** Session-based (PHP sessions)

### Database Schema (Already Created)
```sql
users (id, name, email, password_hash, is_admin, created_at, updated_at)
movies (id, title, description, release_year, rating, genre, poster_url, created_at, updated_at)
favorites (user_id FK, movie_id FK, created_at) - composite PK
```

### Existing Files You Can Use
- `config/database.php` - PDO connection config
- `src/Helpers/functions.php` - Helper functions (db(), h(), redirect(), csrfToken(), etc.)
- `public/index.php` - Router (you may need to update routes)

---

## Phase 1: Models (Data Layer)

### 1.1 Base Model
Create `src/Models/Model.php`:
```php
<?php
declare(strict_types=1);

abstract class Model
{
    protected static function db(): PDO
    {
        return db(); // Uses helper function
    }
}
```

### 1.2 User Model
Create `src/Models/User.php` with these methods:

| Method | Signature | Description |
|--------|-----------|-------------|
| `create` | `static create(array $data): int` | Insert user, return ID. Use `password_hash($data['password'], PASSWORD_DEFAULT)` |
| `findById` | `static findById(int $id): ?array` | Get user by ID |
| `findByEmail` | `static findByEmail(string $email): ?array` | Get user by email |
| `emailExists` | `static emailExists(string $email): bool` | Check if email is taken |
| `update` | `static update(int $id, array $data): bool` | Update user fields |

**Admin Rule:** First registered user becomes admin (`is_admin = 1`). Check if users table is empty during registration.

### 1.3 Movie Model
Create `src/Models/Movie.php` with these methods:

| Method | Signature | Description |
|--------|-----------|-------------|
| `all` | `static all(array $filters = []): array` | Get movies with search/filter/sort/pagination |
| `find` | `static find(int $id): ?array` | Get single movie |
| `create` | `static create(array $data): int` | Insert movie |
| `update` | `static update(int $id, array $data): bool` | Update movie |
| `delete` | `static delete(int $id): bool` | Delete movie |
| `getGenres` | `static getGenres(): array` | Get distinct genres |
| `count` | `static count(array $filters = []): int` | Count for pagination |

**`all()` Filters Specification:**
```php
$filters = [
    'q' => 'search term',      // LIKE %term% on title
    'genre' => 'Action',       // Exact match
    'sort' => 'rating_desc',   // rating_desc | year_desc | title_asc
    'page' => 1,               // Current page
    'per_page' => 6,           // Items per page (default 6)
    'user_id' => 123           // Optional: to check favorites status
];
```

**Sort Options:**
- `rating_desc` → `ORDER BY rating DESC`
- `year_desc` → `ORDER BY release_year DESC`
- `title_asc` → `ORDER BY title ASC`
- Default: `rating_desc`

### 1.4 Favorite Model
Create `src/Models/Favorite.php` with these methods:

| Method | Signature | Description |
|--------|-----------|-------------|
| `add` | `static add(int $userId, int $movieId): bool` | Add favorite (use INSERT IGNORE for duplicates) |
| `remove` | `static remove(int $userId, int $movieId): bool` | Remove favorite |
| `exists` | `static exists(int $userId, int $movieId): bool` | Check if favorited |
| `getByUser` | `static getByUser(int $userId): array` | Get user's favorites with movie details |
| `countByUser` | `static countByUser(int $userId): int` | Count user's favorites |
| `getMovieIdsForUser` | `static getMovieIdsForUser(int $userId): array` | Get array of movie IDs user favorited |

---

## Phase 2: Validation Helper

Create `src/Helpers/Validator.php`:

```php
<?php
declare(strict_types=1);

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $message = null): self
    public function email(string $field, string $message = null): self
    public function min(string $field, int $length, string $message = null): self
    public function max(string $field, int $length, string $message = null): self
    public function confirmed(string $field, string $message = null): self // checks {field}_confirmation
    public function unique(string $field, string $table, string $column, string $message = null): self
    public function numeric(string $field, string $message = null): self
    public function between(string $field, float $min, float $max, string $message = null): self
    public function url(string $field, string $message = null): self

    public function fails(): bool
    public function errors(): array
    public function validated(): array // Returns only validated fields
}
```

**Usage Example:**
```php
$validator = new Validator($_POST);
$validator->required('name')
          ->required('email')->email('email')->unique('email', 'users', 'email')
          ->required('password')->min('password', 8)->confirmed('password');

if ($validator->fails()) {
    setOldInput($_POST);
    $_SESSION['errors'] = $validator->errors();
    redirect('/register');
}
```

---

## Phase 3: Authentication System

### 3.1 Update Router
Modify `public/index.php` to properly route to AuthController methods.

### 3.2 AuthController
Rewrite `src/Controllers/AuthController.php`:

**GET /register:**
- If logged in, redirect to `/movies`
- Show registration form (Agent B creates view)

**POST /register:**
- Validate: name required, email format + unique, password min 8 + confirmed
- If first user → set `is_admin = 1`
- Create user with `password_hash()`
- Set `$_SESSION['user_id']` and `$_SESSION['is_admin']`
- Flash success message
- Redirect to `/movies`

**GET /login:**
- If logged in, redirect to `/movies`
- Capture `return_to` query param
- Show login form

**POST /login:**
- Validate email + password
- Find user by email
- Verify password with `password_verify()`
- If valid: set session, regenerate session ID, redirect to `return_to` or `/movies`
- If invalid: flash error, redirect back

**POST /logout:**
- Validate CSRF
- Destroy session
- Redirect to `/movies`

### 3.3 Auth Middleware Functions
Add to `src/Helpers/functions.php`:

```php
function requireAuth(): void
{
    if (!isLoggedIn()) {
        flash('error', 'Please login to continue.');
        redirect('/login?return_to=' . urlencode(currentPath()));
    }
}

function requireAdmin(): void
{
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        require __DIR__ . '/../Views/errors/403.php';
        exit;
    }
}

function requireGuest(): void
{
    if (isLoggedIn()) {
        redirect('/movies');
    }
}
```

---

## Phase 4: Profile Logic

### 4.1 ProfileController
Rewrite `src/Controllers/ProfileController.php`:

**GET /profile:**
- `requireAuth()`
- Fetch current user data
- Fetch favorite count
- Fetch favorite movies
- Pass to view

---

## Phase 5: Admin CRUD

### 5.1 AdminController
Rewrite `src/Controllers/AdminController.php`:

**All Admin Routes:**
- Call `requireAdmin()` first

**GET /admin/movies:**
- Fetch all movies (no pagination needed, admin view)
- Pass to admin list view

**GET /admin/movies/create:**
- Show create form

**POST /admin/movies:**
- Validate CSRF
- Validate: title required, genre required, release_year numeric 1888-2030, rating 0-10, poster_url valid URL or empty
- Create movie
- Flash success
- Redirect to `/admin/movies`

**GET /admin/movies/{id}/edit:**
- Fetch movie or 404
- Show edit form with current data

**POST /admin/movies/{id}:**
- Validate CSRF
- Validate same as create
- Update movie
- Flash success
- Redirect to `/admin/movies`

**POST /admin/movies/{id}/delete:**
- Validate CSRF
- Delete movie (CASCADE handles favorites)
- Flash success
- Redirect to `/admin/movies`

---

## Phase 6: Favorites Logic

### 6.1 FavoriteController
Rewrite `src/Controllers/FavoriteController.php`:

**POST /movies/{id}/favorite:**
- `requireAuth()`
- Validate CSRF
- Validate movie exists
- Add favorite
- Redirect back (use `$_SERVER['HTTP_REFERER']` or `/movies`)

**POST /movies/{id}/unfavorite:**
- `requireAuth()`
- Validate CSRF
- Remove favorite
- Redirect back

---

## Phase 7: Movies Controller Logic

### 7.1 MovieController
Rewrite `src/Controllers/MovieController.php`:

**GET /movies:**
- Parse query params: q, genre, sort, page
- Call `Movie::all($filters)` with user_id if logged in
- Call `Movie::count($filters)` for pagination
- Get genres for dropdown
- Pass all data to view

**GET /movies/{id}:**
- Fetch movie or 404
- Check if favorited (if logged in)
- Pass to detail view

---

## Security Requirements (Apply Everywhere)

1. **SQL Injection:** ALL queries use PDO prepared statements
2. **XSS:** Use `h()` helper for ALL user-generated output
3. **CSRF:** Validate `csrf_token` on ALL POST requests
4. **Password:** Use `password_hash()` / `password_verify()`
5. **Session:** Call `session_regenerate_id()` on login

---

## Files You Create/Modify

### Create:
- `src/Models/Model.php`
- `src/Models/User.php`
- `src/Models/Movie.php`
- `src/Models/Favorite.php`
- `src/Helpers/Validator.php`

### Modify:
- `src/Controllers/AuthController.php`
- `src/Controllers/ProfileController.php`
- `src/Controllers/AdminController.php`
- `src/Controllers/FavoriteController.php`
- `src/Controllers/MovieController.php`
- `src/Helpers/functions.php` (add middleware functions)
- `public/index.php` (update routing if needed)

---

## Testing Checklist

After implementation, verify:
- [ ] User registration creates account with hashed password
- [ ] First user gets `is_admin = 1`
- [ ] Login works with correct credentials
- [ ] Login fails with wrong credentials
- [ ] Logout destroys session
- [ ] `Movie::all()` returns correct results with filters
- [ ] Pagination calculates correctly
- [ ] Favorites add/remove works
- [ ] Admin CRUD creates/updates/deletes movies
- [ ] Non-admin cannot access `/admin/*` routes
- [ ] CSRF validation blocks requests without token

---

## Coordination with Agent B

Agent B will create the views. Ensure your controllers pass these variables:

**Registration/Login Views:**
- `$errors` - validation errors array
- `$old` - old input values (use `old('field')` helper)

**Movies List View:**
- `$movies` - array of movies with `is_favorited` boolean
- `$genres` - array of genre strings for dropdown
- `$filters` - current filter values (q, genre, sort, page)
- `$pagination` - array with `total`, `per_page`, `current_page`, `total_pages`

**Movie Detail View:**
- `$movie` - single movie array
- `$is_favorited` - boolean

**Profile View:**
- `$user` - user data array
- `$favorites` - array of favorite movies
- `$favorite_count` - integer

**Admin Views:**
- `$movies` - all movies for list
- `$movie` - single movie for edit
- `$errors`, `$old` - for forms
