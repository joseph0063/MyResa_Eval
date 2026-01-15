<?php

declare(strict_types=1);

http_response_code(403);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | Mini Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container text-center py-5">
        <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
        <h1 class="display-1 text-muted mt-3">403</h1>
        <h2 class="mb-4">Access Denied</h2>
        <p class="text-muted mb-4">You don't have permission to access this page.</p>
        <a href="/movies" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Movies
        </a>
    </div>
</body>
</html>
