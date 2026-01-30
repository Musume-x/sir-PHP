<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Appointment Management</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search appointments..." />
                </div>
                <button class="btn-primary">+ Create Appointment</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Today</h4>
                <div class="summary-value">47</div>
                <p class="summary-change">Appointments</p>
            </div>
            <div class="summary-card">
                <h4>This Week</h4>
                <div class="summary-value">312</div>
                <p class="summary-change positive">+8%</p>
            </div>
            <div class="summary-card">
                <h4>This Month</h4>
                <div class="summary-value">3,492</div>
                <p class="summary-change positive">+12%</p>
            </div>
            <div class="summary-card">
                <h4>Pending</h4>
                <div class="summary-value">12</div>
                <p class="summary-change">Requires action</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Today's Appointments</h3>
                    <select>
                        <option>All Doctors</option>
                        <option>Dr. Jane Cooper</option>
                        <option>Dr. Robert Smith</option>
                    </select>
                </div>
                <ul class="appointment-list">
                    <li>
                        <div>
                            <strong>08:00 AM</strong>
                            <p>John Doe · Cardiology · Dr. Jane Cooper</p>
                        </div>
                        <span class="badge">Confirmed</span>
                    </li>
                    <li>
                        <div>
                            <strong>09:30 AM</strong>
                            <p>Jane Smith · Pediatrics · Dr. Robert Smith</p>
                        </div>
                        <span class="badge cyan">Checked-in</span>
                    </li>
                    <li>
                        <div>
                            <strong>11:15 AM</strong>
                            <p>Michael Brown · Orthopedics · Dr. Jane Cooper</p>
                        </div>
                        <span class="badge">Pending</span>
                    </li>
                    <li>
                        <div>
                            <strong>02:00 PM</strong>
                            <p>Sarah Johnson · General · Dr. Robert Smith</p>
                        </div>
                        <span class="badge">Confirmed</span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Upcoming Appointments</h3>
                    <select>
                        <option>Next 7 days</option>
                        <option>Next 30 days</option>
                    </select>
                </div>
                <ul class="appointment-list">
                    <li>
                        <div>
                            <strong>Nov 13, 10:00 AM</strong>
                            <p>David Lee · Cardiology</p>
                        </div>
                        <span class="badge">Scheduled</span>
                    </li>
                    <li>
                        <div>
                            <strong>Nov 13, 2:30 PM</strong>
                            <p>Emma Wilson · Pediatrics</p>
                        </div>
                        <span class="badge">Scheduled</span>
                    </li>
                    <li>
                        <div>
                            <strong>Nov 14, 9:00 AM</strong>
                            <p>Robert Taylor · General</p>
                        </div>
                        <span class="badge">Scheduled</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Appointments</h3>
                <div style="display: flex; gap: 12px;">
                    <select>
                        <option>All Status</option>
                        <option>Confirmed</option>
                        <option>Pending</option>
                        <option>Cancelled</option>
                    </select>
                    <select>
                        <option>All Doctors</option>
                        <option>Dr. Jane Cooper</option>
                        <option>Dr. Robert Smith</option>
                    </select>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nov 12, 2025<br>08:00 AM</td>
                        <td>John Doe</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Cardiology</td>
                        <td><span class="badge">Confirmed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 12, 2025<br>09:30 AM</td>
                        <td>Jane Smith</td>
                        <td>Dr. Robert Smith</td>
                        <td>Pediatrics</td>
                        <td><span class="badge cyan">Checked-in</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 12, 2025<br>11:15 AM</td>
                        <td>Michael Brown</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Orthopedics</td>
                        <td><span class="badge">Pending</span></td>
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
