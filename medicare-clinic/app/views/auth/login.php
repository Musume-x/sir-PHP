<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-overlay"></div>
            <div class="auth-quote">
                <p>“Putting people first in every healthcare moment.”</p>
            </div>
        </div>
        <div class="auth-right">
            <h2>Welcome Back!</h2>
            <p class="subtitle">Sign in with your email and password.</p>

            <?php if (!empty($_GET['error'])): ?>
                <p class="auth-error">Invalid email or password.</p>
            <?php endif; ?>
            <?php if (!empty($_GET['timeout'])): ?>
                <p class="auth-error">Your session has expired due to inactivity. Please log in again.</p>
            <?php endif; ?>
            <?php if (!empty($_GET['success'])): ?>
                <p class="auth-success">Registration successful. You can now log in.</p>
            <?php endif; ?>

            <form method="post" class="auth-form" autocomplete="off">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <span class="icon">@</span>
                        <input type="email" id="email" name="email" placeholder="Enter Email ID" required autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <span class="icon">••</span>
                        <input type="password" id="password" name="password" placeholder="Enter Password" required autocomplete="new-password" />
                    </div>
                </div>

                <div class="form-footer">
                    <a href="#" class="link-small">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-primary full">Login</button>

                <div class="auth-or">
                    <span>OR</span>
                </div>

                <div class="auth-social">
                    <button type="button" class="btn-social google">Google</button>
                    <button type="button" class="btn-social facebook">Facebook</button>
                </div>

                <p class="auth-register">
                    Don't have an account?
                    <a href="index.php?page=register">Register Now</a>
                </p>
            </form>
        </div>
    </div>
</div>

