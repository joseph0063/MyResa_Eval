# Mini Movies - Implementation Plan

## Project Overview
**Stack:** PHP (vanilla) + Bootstrap 5 + MySQL  
**Type:** Movie browsing app with user auth, favorites, and admin CRUD

---

## Phase 1: Project Setup & Infrastructure

### 1.1 Directory Structure ✅
- [x] Create folder structure:
  ```
  /
  ├── config/
  │   └── database.php
  ├── public/
  │   ├── index.php (front controller)
  │   ├── css/
  │   └── js/
  ├── src/
  │   ├── Controllers/
  │   ├── Models/
  │   ├── Views/
  │   │   ├── layouts/
  │   │   ├── auth/
  │   │   ├── movies/
  │   │   ├── profile/
  │   │   └── admin/
  │   ├── Helpers/
  │   └── Middleware/
  ├── database/
  │   └── database.sql
  └── README.md
  ```

### 1.2 Core Infrastructure ✅
- [x] Create `config/database.php` - PDO connection with error handling
- [x] Create `.htaccess` for URL rewriting (or use query-string routing)
- [x] Create `public/index.php` - simple router/front controller
- [x] Create `src/Helpers/functions.php` - utility functions:
  - `redirect($url)`
  - `view($template, $data)`
  - `old($field)` - form repopulation
  - `csrf_token()` / `csrf_field()`
  - `h($string)` - htmlspecialchars wrapper
  - `is_logged_in()` / `current_user()`
  - `is_admin()`

### 1.3 Security Foundation ✅
- [x] Create CSRF token generation & validation helper
- [x] Create session initialization in bootstrap
- [ ] Create base validation helper class (Phase 10)

### 1.4 Error Handling ✅
- [x] Create `src/Views/errors/404.php` - Not Found page
- [x] Create `src/Views/errors/403.php` - Forbidden page
- [x] Create `src/Views/errors/500.php` - Server Error page
- [x] Add error handler in router for invalid routes

### 1.5 Database Abstraction ✅
- [x] Support MySQL (primary) and SQLite (fallback) as per project spec
- [x] Use PDO abstraction to allow both drivers

---

## Phase 2: Database

### 2.1 Schema Design ✅
- [x] Create `database/database.sql` with:
  ```sql
  -- users table (id, name, email, password_hash, created_at, updated_at)
  -- movies table (id, title, description, release_year, rating, genre, poster_url, created_at, updated_at)
  -- favorites table (user_id FK, movie_id FK, created_at) - composite PK
  ```
- [x] Add `ON DELETE CASCADE` on `favorites.user_id` → when user deleted, remove their favorites
- [x] Add `ON DELETE CASCADE` on `favorites.movie_id` → when movie deleted, remove from favorites

### 2.2 Indexes ✅
- [x] `UNIQUE INDEX` on `users.email`
- [x] `INDEX` on `favorites.user_id`
- [x] `INDEX` on `favorites.movie_id`
- [x] `INDEX` on `movies.title`
- [x] `INDEX` on `movies.genre`
- [x] `INDEX` on `movies.release_year`
- [x] `INDEX` on `movies.rating`

### 2.3 Seed Data ✅
- [x] Insert 12 sample movies with varied genres, years, ratings
- [ ] Create test admin user (will be first registered user)

---

## Phase 3: Models (Data Layer)

### 3.1 Base Model
- [ ] Create `src/Models/Model.php` - base class with PDO instance

### 3.2 User Model
- [ ] `User::create(array $data)` - with password_hash()
- [ ] `User::findByEmail(string $email)`
- [ ] `User::findById(int $id)`
- [ ] `User::emailExists(string $email)`
- [ ] `User::verifyPassword(string $password, string $hash)`

### 3.3 Movie Model
- [ ] `Movie::all(array $filters)` - with search/filter/sort/pagination
- [ ] `Movie::find(int $id)`
- [ ] `Movie::create(array $data)`
- [ ] `Movie::update(int $id, array $data)`
- [ ] `Movie::delete(int $id)`
- [ ] `Movie::getGenres()` - distinct genres for filter dropdown
- [ ] `Movie::count(array $filters)` - for pagination

### 3.4 Favorite Model
- [ ] `Favorite::add(int $userId, int $movieId)`
- [ ] `Favorite::remove(int $userId, int $movieId)`
- [ ] `Favorite::exists(int $userId, int $movieId)`
- [ ] `Favorite::getByUser(int $userId)` - with movie details
- [ ] `Favorite::countByUser(int $userId)`
- [ ] `Favorite::getMovieIdsForUser(int $userId)` - for efficient list display

---

## Phase 4: Views & Layouts

### 4.1 Base Layout
- [ ] Create `src/Views/layouts/main.php`:
  - Bootstrap 5 CDN
  - Navigation bar (logo, Movies, Login/Register OR Profile/Logout)
  - Flash messages area
  - Footer
  - CSRF meta tag

### 4.2 Auth Views
- [ ] `src/Views/auth/register.php` - registration form (with password confirmation field)
- [ ] `src/Views/auth/login.php` - login form
- [ ] Both forms: preserve old input values on validation error (form repopulation)

### 4.3 Movie Views
- [ ] `src/Views/movies/index.php` - list with filter bar, cards grid, pagination
- [ ] `src/Views/movies/show.php` - single movie detail page

### 4.4 Profile Views
- [ ] `src/Views/profile/index.php` - user info + favorites list

### 4.5 Admin Views
- [ ] `src/Views/admin/movies/index.php` - movies table with actions
- [ ] `src/Views/admin/movies/create.php` - create form
- [ ] `src/Views/admin/movies/edit.php` - edit form

### 4.6 Partials
- [ ] `src/Views/partials/movie-card.php` - reusable card with favorite button
- [ ] `src/Views/partials/pagination.php` - reusable pagination
- [ ] `src/Views/partials/flash.php` - flash messages

---

## Phase 5: Authentication System

### 5.1 Registration
- [ ] `GET /register` - show form
- [ ] `POST /register` - process:
  - Validate: name required, email format, email unique, password min 8 chars, password confirmation match
  - Hash password with `password_hash()`
  - Create user
  - Auto-login (set session)
  - Redirect to `/movies`

### 5.2 Login
- [ ] `GET /login` - show form (handle `return_to` param)
- [ ] `POST /login` - process:
  - Validate credentials
  - Verify with `password_verify()`
  - Set `$_SESSION['user_id']`
  - Redirect to `return_to` or `/movies`

### 5.3 Logout
- [ ] `POST /logout` - destroy session, redirect to `/movies`

### 5.4 Auth Middleware
- [ ] Create `requireAuth()` - redirect to login if not authenticated
- [ ] Create `requireAdmin()` - 403 if not admin
- [ ] Create `requireGuest()` - redirect if already logged in

---

## Phase 6: Movies Browsing (Public)

### 6.1 List Endpoint
- [ ] `GET /movies` with query params:
  - `q` - search by title (LIKE %q%)
  - `genre` - filter by exact genre
  - `sort` - rating_desc | year_desc | title_asc
  - `page` - pagination (6 per page)

### 6.2 List Logic
- [ ] Build dynamic SQL with prepared statements
- [ ] If logged in: JOIN or subquery to check if each movie is favorited
- [ ] Calculate total pages for pagination
- [ ] Pass to view: movies, genres, current filters, pagination data

### 6.3 List UI
- [ ] Filter bar (search input, genre dropdown, sort dropdown, submit)
- [ ] Responsive cards grid (col-12 col-md-6 col-lg-4)
- [ ] Each card: poster (with fallback placeholder if URL broken), title, year, rating badge, genre badge, favorite heart
- [ ] Pagination controls

### 6.4 Detail Endpoint
- [ ] `GET /movies/{id}`:
  - Fetch movie
  - 404 if not found
  - Check if favorited (if logged in)
  - Render detail view

### 6.5 Detail UI
- [ ] Large poster
- [ ] Title, year, rating, genre badge
- [ ] Description
- [ ] Favorite/Unfavorite button (if logged in)

---

## Phase 7: Favorites Feature

### 7.1 Favorite Endpoint
- [ ] `POST /movies/{id}/favorite`:
  - Require auth
  - Validate CSRF
  - Validate movie exists
  - Add favorite (ignore if duplicate - DB handles it)
  - Redirect back or JSON response

### 7.2 Unfavorite Endpoint
- [ ] `POST /movies/{id}/unfavorite`:
  - Require auth
  - Validate CSRF
  - Remove favorite
  - Redirect back or JSON response

### 7.3 UI Integration
- [ ] Heart icon on movie cards (filled = favorited, outline = not)
- [ ] Heart button on movie detail page
- [ ] If not logged in: button links to `/login?return_to=current_url`

---

## Phase 8: User Profile

### 8.1 Profile Page
- [ ] `GET /profile`:
  - Require auth
  - Fetch user data
  - Fetch favorite count
  - Fetch favorite movies list
  - Render profile view

### 8.2 Profile UI
- [ ] User info card: name, email, member since
- [ ] Favorites section: count + movie cards/table

### 8.3 (Bonus) Profile Edit
- [ ] `GET /profile/edit` - show form
- [ ] `POST /profile/edit` - update name

---

## Phase 9: Admin CRUD

### 9.1 Admin Determination
- [ ] Define admin rule (first user OR specific email)
- [ ] Document in README

### 9.2 Admin Middleware
- [ ] Apply `requireAdmin()` to all `/admin/*` routes

### 9.3 List Movies
- [ ] `GET /admin/movies` - table with all movies + actions

### 9.4 Create Movie
- [ ] `GET /admin/movies/create` - form
- [ ] `POST /admin/movies` - validate & insert:
  - title (required)
  - description
  - release_year (numeric, 1888-current_year+5 range)
  - rating (numeric, 0.0-10.0 range, 1 decimal)
  - genre (required)
  - poster_url (valid URL)
  - CSRF validation

### 9.5 Edit Movie
- [ ] `GET /admin/movies/{id}/edit` - form with current data
- [ ] `POST /admin/movies/{id}` - validate & update + CSRF

### 9.6 Delete Movie
- [ ] `POST /admin/movies/{id}/delete` - delete + CSRF
- [ ] Cascade delete favorites (or handle in DB with ON DELETE CASCADE)

---

## Phase 10: Validation Layer

### 10.1 Validation Rules
- [ ] Create validation helper/class with rules:
  - `required`
  - `email`
  - `min:length`
  - `max:length`
  - `numeric`
  - `between:min,max`
  - `url`
  - `unique:table,column`
  - `confirmed` (for password confirmation)
  - `integer`
  - `decimal`

### 10.2 Apply Validations
- [ ] Registration: name, email, password
- [ ] Login: email, password
- [ ] Movie create/edit: all fields
- [ ] Display validation errors in forms

---

## Phase 11: Security Hardening

### 11.1 SQL Injection Prevention
- [ ] Audit ALL queries use PDO prepared statements
- [ ] No string concatenation in queries

### 11.2 XSS Prevention
- [ ] Audit ALL outputs use `htmlspecialchars()` or `h()` helper
- [ ] Especially: movie titles, descriptions, user names

### 11.3 CSRF Protection
- [ ] Generate token on session start
- [ ] Include hidden field in ALL POST forms
- [ ] Validate token on ALL POST handlers

### 11.4 Session Security
- [ ] Regenerate session ID on login
- [ ] Set secure session cookie params (if HTTPS)

### 11.5 Password Security
- [ ] Verify `password_hash()` with `PASSWORD_DEFAULT`
- [ ] Verify `password_verify()` usage

---

## Phase 12: Testing & Polish

### 12.1 Functional Testing
- [ ] Test registration (valid + invalid inputs)
- [ ] Test login (valid + invalid)
- [ ] Test logout
- [ ] Test movie browsing (all filter combinations)
- [ ] Test pagination
- [ ] Test favorite/unfavorite (logged in + logged out)
- [ ] Test profile page
- [ ] Test admin CRUD (create, edit, delete)
- [ ] Test as non-admin accessing admin routes

### 12.2 Security Testing
- [ ] Test SQL injection attempts
- [ ] Test XSS attempts
- [ ] Test CSRF bypass attempts
- [ ] Test direct URL access without auth

### 12.3 Edge Cases
- [ ] Empty search results (show "No movies found" message)
- [ ] Invalid movie ID (404 page)
- [ ] Duplicate favorite attempt (graceful handling)
- [ ] Invalid pagination page (redirect to page 1 or 404)
- [ ] Broken poster URL (fallback placeholder image)
- [ ] Very long movie titles/descriptions (CSS truncation)
- [ ] Special characters in search query (proper escaping)
- [ ] Zero movies in database (empty state UI)

### 12.4 UI Polish
- [ ] Responsive design check (mobile, tablet, desktop)
- [ ] Form validation error display
- [ ] Loading states
- [ ] Flash messages styling

---

## Phase 13: Documentation & Delivery

### 13.1 README.md
- [ ] Project description
- [ ] Requirements (PHP version, MySQL)
- [ ] Setup instructions:
  - Clone repo
  - Import database.sql
  - Configure database.php
  - Start PHP server
- [ ] Admin rule documentation
- [ ] Default credentials (if any)
- [ ] Known limitations/shortcuts

### 13.2 Database Script
- [ ] Verify `database.sql` is complete and runnable
- [ ] Includes all tables, indexes, seed data

### 13.3 Final Checklist
- [ ] All files committed
- [ ] No hardcoded credentials
- [ ] No debug code left
- [ ] Clean code formatting

---

## Bonus Features (Pick 1)

### Option A: AJAX Favorites
- [ ] Convert favorite/unfavorite to fetch() API calls
- [ ] Return JSON response
- [ ] Update heart icon without page reload

### Option B: Profile Edit
- [ ] Add edit form for name
- [ ] Validate and update

### Option C: "My Favorites" Tab
- [ ] Add tab/filter on movies list to show only favorites

### Option D: Flash Messages
- [ ] Session-based flash messages
- [ ] Success/error styling
- [ ] Auto-dismiss

---

## Estimated Timeline

| Phase | Description | Effort |
|-------|-------------|--------|
| 1 | Project Setup | 1-2 hrs |
| 2 | Database | 1 hr |
| 3 | Models | 2-3 hrs |
| 4 | Views/Layouts | 2-3 hrs |
| 5 | Authentication | 2-3 hrs |
| 6 | Movies Browsing | 3-4 hrs |
| 7 | Favorites | 1-2 hrs |
| 8 | User Profile | 1 hr |
| 9 | Admin CRUD | 2-3 hrs |
| 10 | Validation | 1-2 hrs |
| 11 | Security | 1 hr |
| 12 | Testing | 2-3 hrs |
| 13 | Documentation | 1 hr |
| **Total** | | **~20-28 hrs** |

---

## Quick Reference: Routes

| Method | Route | Auth | Description |
|--------|-------|------|-------------|
| GET | `/` | - | Redirect to /movies |
| GET | `/register` | Guest | Registration form |
| POST | `/register` | Guest | Process registration |
| GET | `/login` | Guest | Login form |
| POST | `/login` | Guest | Process login |
| POST | `/logout` | User | Logout |
| GET | `/profile` | User | User profile |
| GET | `/movies` | - | Movies list |
| GET | `/movies/{id}` | - | Movie detail |
| POST | `/movies/{id}/favorite` | User | Add favorite |
| POST | `/movies/{id}/unfavorite` | User | Remove favorite |
| GET | `/admin/movies` | Admin | Admin movies list |
| GET | `/admin/movies/create` | Admin | Create form |
| POST | `/admin/movies` | Admin | Store movie |
| GET | `/admin/movies/{id}/edit` | Admin | Edit form |
| POST | `/admin/movies/{id}` | Admin | Update movie |
| POST | `/admin/movies/{id}/delete` | Admin | Delete movie |
