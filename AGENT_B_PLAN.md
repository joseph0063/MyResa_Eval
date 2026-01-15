# Agent B - Frontend & Views

## Your Responsibilities
You are responsible for the **frontend/UI**: Layouts, Views, Partials, and Bootstrap styling.

**DO NOT** work on Models or Controller logic (Agent A handles those). You create the HTML/Bootstrap UI, Agent A creates the PHP logic.

---

## Project Context

### Stack
- **Frontend:** Bootstrap 5 (CDN)
- **Templating:** Plain PHP views
- **Icons:** Bootstrap Icons (CDN)

### Available Helpers (from Agent A)
```php
h($string)           // Escape HTML
csrfField()          // Generate CSRF hidden input
old('field')         // Get old form input value
isLoggedIn()         // Check if user logged in
isAdmin()            // Check if user is admin
getFlash()           // Get flash messages array
currentPath()        // Get current URL path
```

---

## Phase 1: Base Layout

### Step 1.1 - Main Layout
Create `src/Views/layouts/main.php`:
- Bootstrap 5 CDN (CSS + JS)
- Bootstrap Icons CDN
- Navigation bar with:
  - Logo/Brand "Mini Movies" → links to `/movies`
  - "Movies" link → `/movies`
  - If logged in: "Profile" → `/profile`, "Logout" button (POST form)
  - If admin: "Admin" → `/admin/movies`
  - If guest: "Login" → `/login`, "Register" → `/register`
- Flash messages area (success/error alerts)
- Main content area (`<?= $content ?>`)
- Footer

### Step 1.2 - View Helper Function
Add to `functions.php` (coordinate with Agent A):
```php
function view(string $template, array $data = []): void
```

---

## Phase 2: Error Pages

### Step 2.1 - Style Error Pages
Update existing error pages with Bootstrap styling:
- `src/Views/errors/403.php` - Forbidden
- `src/Views/errors/404.php` - Not Found
- `src/Views/errors/500.php` - Server Error

Each should:
- Use main layout OR standalone Bootstrap
- Show error code prominently
- Show friendly message
- Link back to movies

---

## Phase 3: Auth Views

### Step 3.1 - Registration Form
Create `src/Views/auth/register.php`:
- Card-centered layout
- Fields: Name, Email, Password, Confirm Password
- CSRF field
- Validation error display per field
- Old input repopulation
- Link to login page

### Step 3.2 - Login Form
Create `src/Views/auth/login.php`:
- Card-centered layout
- Fields: Email, Password
- CSRF field
- Validation error display
- Old input repopulation (except password)
- Link to register page
- Handle `return_to` hidden field if present

---

## Phase 4: Partials (Reusable Components)

### Step 4.1 - Flash Messages
Create `src/Views/partials/flash.php`:
- Display success messages (green alert)
- Display error messages (red alert)
- Auto-dismissible with Bootstrap

### Step 4.2 - Movie Card
Create `src/Views/partials/movie-card.php`:
- Poster image (with fallback placeholder)
- Title
- Year badge
- Rating badge (color-coded: green >7, yellow 5-7, red <5)
- Genre badge
- Favorite heart button (if logged in)
- Link to movie detail

### Step 4.3 - Pagination
Create `src/Views/partials/pagination.php`:
- Previous/Next buttons
- Page numbers with current highlighted
- Preserve query params (search, filters)

---

## Phase 5: Movies Views

### Step 5.1 - Movies List
Create `src/Views/movies/index.php`:
- Filter bar (horizontal form):
  - Search input (q)
  - Genre dropdown
  - Sort dropdown
  - Submit button
- Responsive card grid (1/2/3 columns)
- Include movie-card partial for each movie
- Include pagination partial
- "No movies found" message if empty

### Step 5.2 - Movie Detail
Create `src/Views/movies/show.php`:
- Large poster
- Title (h1)
- Meta: Year, Genre badge, Rating
- Description
- Favorite/Unfavorite button (if logged in)
- Back to list link

---

## Phase 6: Profile View

### Step 6.1 - Profile Page
Create `src/Views/profile/index.php`:
- User info card: Name, Email, Member since
- Favorites count badge
- Favorites section:
  - Movie cards grid (reuse partial)
  - "No favorites yet" message if empty

---

## Phase 7: Admin Views

### Step 7.1 - Admin Movies List
Create `src/Views/admin/movies/index.php`:
- "Add Movie" button
- Table with columns: ID, Poster (small), Title, Genre, Year, Rating, Actions
- Actions: Edit, Delete buttons
- Delete with confirmation (JS confirm or modal)

### Step 7.2 - Admin Create Form
Create `src/Views/admin/movies/create.php`:
- Card form
- Fields: Title, Description (textarea), Genre, Release Year, Rating, Poster URL
- CSRF field
- Validation errors display
- Cancel/Submit buttons

### Step 7.3 - Admin Edit Form
Create `src/Views/admin/movies/edit.php`:
- Same as create but pre-populated
- Use old() for repopulation on error, else use $movie values

---

## Files You Create

### Layouts:
- `src/Views/layouts/main.php`

### Error Pages (update):
- `src/Views/errors/403.php`
- `src/Views/errors/404.php`
- `src/Views/errors/500.php`

### Auth Views:
- `src/Views/auth/register.php`
- `src/Views/auth/login.php`

### Partials:
- `src/Views/partials/flash.php`
- `src/Views/partials/movie-card.php`
- `src/Views/partials/pagination.php`

### Movie Views:
- `src/Views/movies/index.php`
- `src/Views/movies/show.php`

### Profile Views:
- `src/Views/profile/index.php`

### Admin Views:
- `src/Views/admin/movies/index.php`
- `src/Views/admin/movies/create.php`
- `src/Views/admin/movies/edit.php`

---

## UI Standards

### Bootstrap Classes Reference
- Cards: `card`, `card-body`, `card-title`, `card-text`
- Buttons: `btn btn-primary`, `btn btn-danger`, `btn btn-outline-*`
- Forms: `form-control`, `form-label`, `form-select`
- Grid: `container`, `row`, `col-12 col-md-6 col-lg-4`
- Alerts: `alert alert-success`, `alert alert-danger`
- Navigation: `navbar`, `nav-link`, `navbar-nav`

### Color Coding (Ratings)
- Rating >= 7.0: `bg-success` (green)
- Rating >= 5.0: `bg-warning` (yellow)
- Rating < 5.0: `bg-danger` (red)

### Favorite Button
- Favorited: `bi-heart-fill` (filled heart, red)
- Not favorited: `bi-heart` (outline heart)

---

## Testing Checklist

- [ ] Layout renders correctly with Bootstrap
- [ ] Navigation shows correct links based on auth state
- [ ] Flash messages display and can be dismissed
- [ ] Registration form shows validation errors
- [ ] Login form preserves email on error
- [ ] Movie cards display all info correctly
- [ ] Favorite hearts show correct state
- [ ] Pagination preserves filters
- [ ] Movie detail page renders all fields
- [ ] Profile shows user info and favorites
- [ ] Admin table lists all movies
- [ ] Admin forms validate and show errors
- [ ] All pages are responsive (mobile-friendly)
