<?php
/**
 * Registration Form View
 *
 * @package MiniMovies\Views\Auth
 * @var array<string, string> $errors Validation errors (optional)
 */

$errors = $errors ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus text-primary" style="font-size: 3rem;"></i>
                    <h2 class="mt-2">Create Account</h2>
                    <p class="text-muted">Join Mini Movies today</p>
                </div>

                <form action="/register" method="POST">
                    <?= csrfField() ?>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                            id="name" 
                            name="name" 
                            value="<?= old('name') ?>"
                            placeholder="Enter your name"
                            required
                        >
                        <?php if (isset($errors['name'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                            id="email" 
                            name="email" 
                            value="<?= old('email') ?>"
                            placeholder="Enter your email"
                            required
                        >
                        <?php if (isset($errors['email'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                            id="password" 
                            name="password"
                            placeholder="Minimum 8 characters"
                            required
                        >
                        <?php if (isset($errors['password'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            placeholder="Repeat your password"
                            required
                        >
                        <?php if (isset($errors['password_confirmation'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['password_confirmation']) ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-plus me-2"></i>Register
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center mb-0">
                    Already have an account? 
                    <a href="/login" class="text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
