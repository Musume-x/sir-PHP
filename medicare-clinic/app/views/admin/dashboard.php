<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_admin_sidebar();

$patientCount = 0;
if ($pdo) {
    $patientCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'patient'")->fetchColumn();
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>

    <main class="main-content">
        <header class="main-header">
            <h1>Admin Dashboard</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search" />
                </div>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="profile-card">
                <h3>Clinic Overview</h3>
                <p>MediCare Clinic System</p>
                <p>Manage patients, staff, billing and reports in one place.</p>
                <button class="btn-outline full">System Settings</button>
            </div>

            <div class="summary-card">
                <h4>Total Patients</h4>
                <div class="summary-value"><?php echo $patientCount; ?></div>
                <p class="summary-change">Example user(s)</p>
            </div>

            <div class="summary-card">
                <h4>Appointments / Month</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>System Activity Logs</h3>
                    <select>
                        <option>Today</option>
                        <option>This week</option>
                        <option>This month</option>
                    </select>
                </div>
                <ul class="list-table">
                    <li>
                        <span>Database connected (SQLite)</span>
                        <span>—</span>
                        <span>System</span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Appointments for Today</h3>
                </div>
                <ul class="appointment-list">
                    <li>
                        <div>
                            <strong>No appointments</strong>
                            <p>Example data removed.</p>
                        </div>
                        <span class="badge">—</span>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</div>

