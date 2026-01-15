<?php
/**
 * Login Form View
 *
 * @package MiniMovies\Views\Auth
 * @var array<string, string> $errors Validation errors (optional)
 * @var string $return_to URL to redirect after login (optional)
 */

$errors = $errors ?? [];
$return_to = $return_to ?? '';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-box-arrow-in-right text-primary" style="font-size: 3rem;"></i>
                    <h2 class="mt-2">Welcome Back</h2>
                    <p class="text-muted">Login to your account</p>
                </div>

                <form action="/login" method="POST">
                    <?= csrfField() ?>
                    
                    <?php if ($return_to) : ?>
                    <input type="hidden" name="return_to" value="<?= h($return_to) ?>">
                    <?php endif; ?>

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
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                            id="password" 
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                        <?php if (isset($errors['password'])) : ?>
                        <div class="invalid-feedback"><?= h($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($errors['credentials'])) : ?>
                    <div class="alert alert-danger py-2">
                        <i class="bi bi-exclamation-circle me-2"></i><?= h($errors['credentials']) ?>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center mb-0">
                    Don't have an account? 
                    <a href="/register" class="text-decoration-none">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>
