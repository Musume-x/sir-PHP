<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Patient Management</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search patients..." />
                </div>
                <button class="btn-primary">+ Register Patient</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Total Patients</h4>
                <div class="summary-value">1,254</div>
                <p class="summary-change">+58 this month</p>
            </div>
            <div class="summary-card">
                <h4>New This Week</h4>
                <div class="summary-value">42</div>
                <p class="summary-change positive">+12%</p>
            </div>
            <div class="summary-card">
                <h4>Active Today</h4>
                <div class="summary-value">89</div>
                <p class="summary-change">Appointments</p>
            </div>
            <div class="summary-card">
                <h4>Pending</h4>
                <div class="summary-value">23</div>
                <p class="summary-change">Registrations</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Patients</h3>
                <div style="display: flex; gap: 12px;">
                    <select>
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                    <select>
                        <option>Sort by: Recent</option>
                        <option>Name A-Z</option>
                        <option>Registration Date</option>
                    </select>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Age</th>
                        <th>Last Visit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#MC-001</td>
                        <td>Michael Brown</td>
                        <td>michael.brown@email.com</td>
                        <td>(555) 123-4567</td>
                        <td>45</td>
                        <td>Nov 12, 2025</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#MC-002</td>
                        <td>Sarah Johnson</td>
                        <td>sarah.j@email.com</td>
                        <td>(555) 234-5678</td>
                        <td>32</td>
                        <td>Nov 10, 2025</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#MC-003</td>
                        <td>David Lee</td>
                        <td>david.lee@email.com</td>
                        <td>(555) 345-6789</td>
                        <td>28</td>
                        <td>Nov 8, 2025</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#MC-004</td>
                        <td>Emma Wilson</td>
                        <td>emma.w@email.com</td>
                        <td>(555) 456-7890</td>
                        <td>55</td>
                        <td>Oct 25, 2025</td>
                        <td><span class="badge">Inactive</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
