Test: Mini Movies (PHP + Bootstrap) — With Users
+ Favorites
Goal
Build a Movies app where:
• Users can register/login
• Each user has a profile page
• Users can favorite/unfavorite movies
• Users can browse movies with search/filter/sort/pagination
• Admin can manage movies (CRUD)
Stack constraints
• Backend: PHP (plain PHP, no frameworks required)
• Frontend: HTML/CSS + Bootstrap 5
• DB: MySQL preferred (SQLite acceptable)
• Auth: session-based login (PHP sessions)
1) Database schema (required)
users
• id (PK)
• name (varchar, required)
• email (varchar, required, unique)
• password_hash (varchar, required)
• created_at , updated_at
movies
Same as before:
• id , title , description , release_year , rating , genre , poster_url ,
created_at , updated_at
favorites
• user_id (FK -> users.id)
• movie_id (FK -> movies.id)
• created_at
Primary key: ( user_id , movie_id ) to prevent duplicates
✅ Deliver database.sql including tables + indexes:
• unique index on users.email
• index on favorites.user_id
• index on favorites.movie_id
• optional: index on movies.title, movies.genre, movies.release_year, movies.rating
2) Auth (required)
Pages
• GET /register + POST /register
• GET /login + POST /login
• POST /logout
Rules
• Passwords stored using password_hash() and checked using
password_verify()
• Validate:
◦ email format
◦ password min length (ex: 8)
◦ email uniqueness
• Use PHP sessions, e.g. $_SESSION['user_id']
3) User profile (required)
GET /profile
Shows:
• Name, email
• “Member since” date
• Count of favorites
• A section listing favorite movies (Bootstrap cards or table)
Optional:
• GET/POST /profile/edit to edit name (nice bonus, not required)
4) Favorites feature (required)
Behavior
• On movie list cards and on movie details page:
◦ Show a Favorite / Unfavorite button
◦ Only visible if logged in
◦ If not logged in, button redirects to login with a “return_to” param
Endpoints
• POST /movies/{id}/favorite
• POST /movies/{id}/unfavorite
Constraints
• Must prevent duplicates (DB PK handles it)
• Must validate that movie exists
• Must enforce logged-in user
• Must be protected by CSRF token
5) Movies browsing (public)
GET /movies
List with:
• Search by title (q)
• Filter by genre (genre)
• Sort (sort):
◦ rating_desc
◦ year_desc
◦ title_asc
• Pagination (6 per page)
✅ Query params example:
/movies?q=batman&genre=Action&sort=rating_desc&page=2
UI requirement (Bootstrap):
• Filter bar (form row)
• Cards grid (responsive 1/2/3 columns)
• Pagination
Favorites UI detail (important):
• If logged in, show a heart icon/button:
◦ Filled if favorited by this user
◦ Outline if not
• This requires the list endpoint to know which movies are favorited by current user
(efficient query encouraged).
6) Movie details
GET /movies/{id}
Shows:
• poster, title, genre badge, year, rating, description
• favorite/unfavorite button if logged in
7) Admin CRUD (movies)
Keep it simple:
• Admin routes under /admin/movies
• You can treat the first registered user as admin OR hardcode admin by email
(document it)
• Must include CSRF for create/edit/delete too
8) Security & quality expectations
(required)
• PDO prepared statements everywhere
• Escape outputs ( htmlspecialchars )
• CSRF token for all POST forms (login/register can be exempt but ideally included)
• Validate all inputs server-side
• Password hashing required
• Session handling clean
9) Submission requirements
Include:
• database.sql (+ seed movies: at least 10)
• README.md with:
◦ setup (DB config)
◦ admin rule (how admin is determined)
◦ any shortcuts
Evaluation rubric (/100)
• Auth + profile correctness: 25
• Favorites feature (DB + UI + logic): 25
• Movies listing (filters + sorting + pagination): 25
• Admin CRUD quality: 15
• Security + code quality: 10
Bonus options (optional)
Pick any 1:
• AJAX favorite/unfavorite (no page reload, still Bootstrap)
• Profile edit page
• “My Favorites” tab on movies list
• Simple flash messages (success/error)