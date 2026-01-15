<?php
/**
 * Profile Edit View
 *
 * Form for editing user profile (name only).
 *
 * @package MiniMovies\Views\Profile
 * @var array<string, mixed> $user User data (id, name, email, created_at)
 * @var array<string, string> $errors Validation errors (optional)
 */

$user = $user ?? [];
$errors = $errors ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-white text-center py-3">
                <i class="bi bi-pencil-square text-primary" style="font-size: 2.5rem;"></i>
                <h2 class="mt-2 mb-0">Edit Profile</h2>
                <p class="text-muted mb-0">Update your account information</p>
            </div>
            <div class="card-body p-4">
                <form action="/profile/edit" method="POST">
                    <?= csrfField() ?>
                    
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name" 
                            value="<?= h(old('name') ?: ($user['name'] ?? '')) ?>"
                            placeholder="Enter your name"
                            required
                        >
                        <?php if (isset($errors['name'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Email Field (Read-only) -->
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            class="form-control bg-light" 
                            id="email" 
                            value="<?= h($user['email'] ?? '') ?>"
                            disabled
                        >
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Email cannot be changed.
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                        <a href="/profile" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Profile
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
