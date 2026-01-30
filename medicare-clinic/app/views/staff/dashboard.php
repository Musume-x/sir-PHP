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
            <h1><?php echo $role; ?> Dashboard</h1>
            <div class="header-right">
                <div class="notifications-dot"></div>
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Today's Appointments</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Assigned Patients</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Pending Tasks</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Today's Appointments</h3>
                </div>
                <ul class="appointment-list compact">
                    <li>
                        <div>
                            <strong>No appointments</strong>
                            <p>Example data removed.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Assigned Patients</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>No assigned patients. Example data removed.</span>
                        <span>—</span>
                        <span>—</span>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</div>

