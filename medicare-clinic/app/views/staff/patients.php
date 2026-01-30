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
            <h1><?php echo $role; ?> Patients</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search patients..." />
                </div>
            </div>
        </header>

        <section class="panel">
            <div class="panel-header">
                <h3>Assigned Patients</h3>
                <select>
                    <option>All</option>
                    <option>Today</option>
                    <option>This week</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Last Visit</th>
                        <th>Primary Concern</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jacob Jones</td>
                        <td>Nov 10, 2025</td>
                        <td>Cardiology follow‑up</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td><button class="btn-outline small">Open Chart</button></td>
                    </tr>
                    <tr>
                        <td>Jenny Wilson</td>
                        <td>Nov 8, 2025</td>
                        <td>Diabetes check</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td><button class="btn-outline small">Open Chart</button></td>
                    </tr>
                    <tr>
                        <td>Brooklyn Simmons</td>
                        <td>Nov 5, 2025</td>
                        <td>Lab review</td>
                        <td><span class="badge">Pending</span></td>
                        <td><button class="btn-outline small">Open Chart</button></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>

