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
            <p class="subtitle">Enter your email, password and select your role.</p>

            <form method="post" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <span class="icon">@</span>
                        <input type="email" id="email" name="email" placeholder="Enter Email ID" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <span class="icon">••</span>
                        <input type="password" id="password" name="password" placeholder="Enter Password" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Login as</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="nurse">Nurse</option>
                        <option value="receptionist">Receptionist</option>
                        <option value="patient" selected>Patient</option>
                    </select>
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
                    <a href="#">Register Now</a>
                </p>
            </form>
        </div>
    </div>
</div>

