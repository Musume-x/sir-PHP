<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
$profile = [];
if ($pdo && $user) {
    $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}
$saved = !empty($_GET['saved']);
$error = !empty($_GET['error']);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Profile</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <?php if ($saved): ?>
            <p class="auth-success">Profile saved successfully.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="auth-error">Failed to save.</p>
        <?php endif; ?>

        <form method="post" action="index.php?page=staff-profile" class="settings-form">
            <input type="hidden" name="save_staff_profile" value="1" />
            <section class="grid-2">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Account Details</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? $role . ' Demo'); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" value="<?php echo $role; ?>" disabled />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled />
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" />
                        </div>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header">
                        <h3>Availability & Preferences</h3>
                    </div>
                    <div style="padding: 20px;">
                        <p>Mon–Fri · 9:00 AM – 5:00 PM (default clinic hours)</p>
                    </div>
                </div>
            </section>
        </form>
    </main>
</div>
