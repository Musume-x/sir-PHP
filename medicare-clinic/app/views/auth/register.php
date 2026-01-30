<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-overlay"></div>
            <div class="auth-quote">
                <p>"Putting people first in every healthcare moment."</p>
            </div>
        </div>
        <div class="auth-right">
            <h2>Create Account</h2>
            <p class="subtitle">Choose your role and create an account.</p>

            <?php if (!empty($register_error)): ?>
                <p class="auth-error"><?php echo htmlspecialchars($register_error); ?></p>
            <?php endif; ?>
            <?php if (!empty($_GET['success'])): ?>
                <p class="auth-success">Registration successful. You can now log in.</p>
            <?php endif; ?>

            <form method="post" class="auth-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-icon">
                        <span class="icon">👤</span>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <span class="icon">@</span>
                        <input type="email" id="email" name="email" placeholder="Enter Email ID" required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Register as</label>
                    <select id="role" name="role" required>
                        <option value="patient" <?php echo ($_POST['role'] ?? '') === 'patient' ? 'selected' : ''; ?>>Patient</option>
                        <option value="doctor" <?php echo ($_POST['role'] ?? '') === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                        <option value="nurse" <?php echo ($_POST['role'] ?? '') === 'nurse' ? 'selected' : ''; ?>>Nurse</option>
                        <option value="receptionist" <?php echo ($_POST['role'] ?? '') === 'receptionist' ? 'selected' : ''; ?>>Receptionist</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <span class="icon">••</span>
                        <input type="password" id="password" name="password" placeholder="At least 6 characters" required minlength="6" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <div class="input-icon">
                        <span class="icon">••</span>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm password" required minlength="6" />
                    </div>
                </div>

                <button type="submit" class="btn-primary full">Register</button>

                <p class="auth-register">
                    Already have an account?
                    <a href="index.php?page=login">Log in</a>
                </p>
            </form>
        </div>
    </div>
</div>
