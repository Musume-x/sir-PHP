<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Profile</h1>
        </header>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Account Details</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['name'] ?? $role . ' Demo'); ?>" />
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" value="<?php echo $role; ?>" disabled />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo strtolower($role); ?>@medicare.com" />
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" value="(555) 123-4567" />
                    </div>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Availability & Preferences</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Default Clinic Hours</label>
                        <input type="text" value="Mon‑Fri · 9:00 AM – 5:00 PM" />
                    </div>
                    <div class="form-group">
                        <label>Notifications</label>
                        <label class="checkbox-label">
                            <input type="checkbox" checked /> Email alerts for new appointments
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" checked /> Daily schedule summary
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" /> Billing notifications
                        </label>
                    </div>
                    <button type="submit" class="btn-primary">Update Preferences</button>
                </form>
            </div>
        </section>
    </main>
</div>

