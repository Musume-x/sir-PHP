<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
$register_error = $register_error ?? '';
$register_success = $register_success ?? '';
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Create Account</h1>
            <div class="header-right">
                <a class="btn-primary" href="index.php?page=admin-users">Back to Users</a>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="panel" style="max-width: 780px;">
            <div class="panel-header">
                <h3>Create a staff/admin account</h3>
            </div>

            <?php if (!empty($register_error)): ?>
                <p class="auth-error" style="margin: 0 0 12px;"><?php echo htmlspecialchars($register_error); ?></p>
            <?php endif; ?>
            <?php if (!empty($register_success)): ?>
                <p class="auth-success" style="margin: 0 0 12px;"><?php echo htmlspecialchars($register_success); ?></p>
            <?php endif; ?>

            <form method="post" class="auth-form" style="padding: 0 8px 8px;">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-icon">
                        <span class="icon">👤</span>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter full name"
                            required
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <span class="icon">@</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter email"
                            required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Account Type</label>
                    <select id="role" name="role" required>
                        <option value="doctor" <?php echo ($_POST['role'] ?? '') === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                        <option value="receptionist" <?php echo ($_POST['role'] ?? '') === 'receptionist' ? 'selected' : ''; ?>>Receptionist</option>
                        <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Department (optional)</label>
                    <div class="input-icon">
                        <span class="icon">🏥</span>
                        <input
                            type="text"
                            id="department"
                            name="department"
                            placeholder="e.g., Cardiology"
                            value="<?php echo htmlspecialchars($_POST['department'] ?? ''); ?>"
                        />
                    </div>
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

                <button type="submit" class="btn-primary">Create Account</button>
            </form>
        </section>
    </main>
</div>

