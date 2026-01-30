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
                <div class="summary-value">14</div>
                <p class="summary-change positive">+3 new</p>
            </div>
            <div class="summary-card">
                <h4>Assigned Patients</h4>
                <div class="summary-value">32</div>
                <p class="summary-change">5 waiting</p>
            </div>
            <div class="summary-card">
                <h4>Pending Tasks</h4>
                <div class="summary-value">7</div>
                <p class="summary-change">2 lab reviews</p>
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
                            <strong>09:00 AM</strong>
                            <p>Savannah Nguyen · 2h</p>
                        </div>
                        <button class="btn-outline small">Open</button>
                    </li>
                    <li>
                        <div>
                            <strong>10:15 AM</strong>
                            <p>Dianne Russell · 1h</p>
                        </div>
                        <button class="btn-outline small">Open</button>
                    </li>
                    <li>
                        <div>
                            <strong>11:15 AM</strong>
                            <p>Ronald Richards · 1h</p>
                        </div>
                        <button class="btn-outline small">Open</button>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Assigned Patients</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>Jacob Jones</span>
                        <span>BP: 120/80</span>
                        <span>Temp: 36.8°C</span>
                    </li>
                    <li>
                        <span>Jenny Wilson</span>
                        <span>BP: 118/76</span>
                        <span>Temp: 37.1°C</span>
                    </li>
                    <li>
                        <span>Brooklyn Simmons</span>
                        <span>BP: 130/85</span>
                        <span>Temp: 36.9°C</span>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</div>

