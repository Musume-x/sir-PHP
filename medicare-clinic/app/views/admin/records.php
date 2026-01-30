<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Medical Records</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search records..." />
                </div>
                <button class="btn-primary">+ New Record</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Total Records</h4>
                <div class="summary-value">8,542</div>
                <p class="summary-change">+234 this month</p>
            </div>
            <div class="summary-card">
                <h4>This Week</h4>
                <div class="summary-value">127</div>
                <p class="summary-change positive">+15%</p>
            </div>
            <div class="summary-card">
                <h4>Pending Review</h4>
                <div class="summary-value">18</div>
                <p class="summary-change">Requires attention</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Medical Records</h3>
                <div style="display: flex; gap: 12px;">
                    <select>
                        <option>All Types</option>
                        <option>Consultation</option>
                        <option>Lab Results</option>
                        <option>Prescriptions</option>
                        <option>Diagnostics</option>
                    </select>
                    <select>
                        <option>Sort by: Recent</option>
                        <option>Patient Name</option>
                        <option>Date</option>
                    </select>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#MR-2025-001</td>
                        <td>Michael Brown</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Consultation</td>
                        <td>Nov 12, 2025</td>
                        <td><span class="badge cyan">Completed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#MR-2025-002</td>
                        <td>Sarah Johnson</td>
                        <td>Dr. Robert Smith</td>
                        <td>Lab Results</td>
                        <td>Nov 10, 2025</td>
                        <td><span class="badge cyan">Completed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#MR-2025-003</td>
                        <td>David Lee</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Prescription</td>
                        <td>Nov 8, 2025</td>
                        <td><span class="badge cyan">Completed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#MR-2025-004</td>
                        <td>Emma Wilson</td>
                        <td>Dr. Robert Smith</td>
                        <td>Diagnostics</td>
                        <td>Oct 25, 2025</td>
                        <td><span class="badge">Pending Review</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Review</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
