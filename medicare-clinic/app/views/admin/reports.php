<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Reports & Analytics</h1>
            <div class="header-right">
                <button class="btn-primary">+ Generate Report</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Patient Reports</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Appointment Analytics</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Financial Reports</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>System Logs</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">—</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Patient Reports</h3>
                    <select>
                        <option>All Reports</option>
                        <option>New Patients</option>
                        <option>Active Patients</option>
                        <option>Patient Demographics</option>
                    </select>
                </div>
                <ul class="list-table">
                    <li>
                        <span>No reports. Example data removed.</span>
                        <span>—</span>
                        <span>—</span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Appointment Analytics</h3>
                    <select>
                        <option>This Month</option>
                        <option>This Week</option>
                        <option>This Year</option>
                    </select>
                </div>
                <ul class="list-table">
                    <li>
                        <span><strong>Monthly Appointments</strong><br>Nov 2025</span>
                        <span>3,492 total</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span><strong>Doctor Performance</strong><br>Nov 2025</span>
                        <span>24 doctors</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span><strong>Department Stats</strong><br>Nov 2025</span>
                        <span>8 departments</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Financial Reports</h3>
                    <select>
                        <option>This Month</option>
                        <option>This Quarter</option>
                        <option>This Year</option>
                    </select>
                </div>
                <ul class="list-table">
                    <li>
                        <span><strong>Revenue Report</strong><br>Nov 2025</span>
                        <span>$45,230</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span><strong>Payment Summary</strong><br>Nov 2025</span>
                        <span>$42,080 paid</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span><strong>Outstanding Invoices</strong><br>Current</span>
                        <span>$8,420 pending</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>System Activity Logs</h3>
                    <select>
                        <option>Today</option>
                        <option>This Week</option>
                        <option>This Month</option>
                    </select>
                </div>
                <ul class="list-table">
                    <li>
                        <span><strong>User Activities</strong><br>Nov 12, 2025</span>
                        <span>1,542 logs</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Export</button>
                        </span>
                    </li>
                    <li>
                        <span><strong>Login History</strong><br>Nov 2025</span>
                        <span>892 sessions</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Export</button>
                        </span>
                    </li>
                    <li>
                        <span><strong>Audit Trail</strong><br>Nov 2025</span>
                        <span>234 changes</span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Export</button>
                        </span>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</div>
