<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
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
                <div class="summary-value">1,254</div>
                <p class="summary-change">+58 this month</p>
            </div>

            <div class="summary-card">
                <h4>Appointments / Month</h4>
                <div class="summary-value">3,492</div>
                <p class="summary-change positive">+12%</p>
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
                        <span>New patient registered</span>
                        <span>10:15 AM</span>
                        <span>Receptionist</span>
                    </li>
                    <li>
                        <span>Invoice generated</span>
                        <span>09:42 AM</span>
                        <span>Billing</span>
                    </li>
                    <li>
                        <span>Doctor schedule updated</span>
                        <span>09:10 AM</span>
                        <span>Admin</span>
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
                            <strong>08:00 AM</strong>
                            <p>John Doe · Cardiology</p>
                        </div>
                        <span class="badge">Confirmed</span>
                    </li>
                    <li>
                        <div>
                            <strong>09:30 AM</strong>
                            <p>Jane Smith · Pediatrics</p>
                        </div>
                        <span class="badge cyan">Checked-in</span>
                    </li>
                    <li>
                        <div>
                            <strong>11:15 AM</strong>
                            <p>Michael Brown · Orthopedics</p>
                        </div>
                        <span class="badge">Pending</span>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</div>

