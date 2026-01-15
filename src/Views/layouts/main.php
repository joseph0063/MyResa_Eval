<?php
/**
 * Main Layout Template
 *
 * Base layout with Bootstrap 5, navigation, flash messages, and footer.
 *
 * @package MiniMovies\Views\Layouts
 * @var string $content The page content to render
 * @var string $title Optional page title
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title ?? 'Mini Movies') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .favorite-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        .favorite-btn .bi-heart-fill {
            color: #dc3545;
        }
        .favorite-btn .bi-heart {
            color: #6c757d;
        }
        .favorite-btn:hover .bi-heart {
            color: #dc3545;
        }
        .movie-card .card-img-top {
            height: 300px;
            object-fit: cover;
        }
        .poster-placeholder {
            height: 300px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        footer {
            margin-top: auto;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/movies">
                <i class="bi bi-film me-2"></i>Mini Movies
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <?php $isMoviesActive = str_starts_with(currentPath(), '/movies'); ?>
                        <a class="nav-link <?= $isMoviesActive ? 'active' : '' ?>" href="/movies">
                            <i class="bi bi-collection-play me-1"></i>Movies
                        </a>
                    </li>
                    <?php if (isAdmin()) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= str_starts_with(currentPath(), '/admin') ? 'active' : '' ?>" href="/admin/movies">
                            <i class="bi bi-gear me-1"></i>Admin
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= currentPath() === '/profile' ? 'active' : '' ?>" href="/profile">
                            <i class="bi bi-person-circle me-1"></i>Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline m-0">
                            <?= csrfField() ?>
                            <button type="submit" class="nav-link border-0 bg-transparent">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                    <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= currentPath() === '/login' ? 'active' : '' ?>" href="/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= currentPath() === '/register' ? 'active' : '' ?>" href="/register">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php $flash = getFlash(); ?>
        <?php if (!empty($flash['success'])) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= h($flash['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if (!empty($flash['error'])) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= h($flash['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="container my-4">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Mini Movies. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
