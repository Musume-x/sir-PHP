<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>My Appointments</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search appointments..." />
                </div>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Upcoming</h4>
                <div class="summary-value">2</div>
                <p class="summary-change">Appointments</p>
            </div>
            <div class="summary-card">
                <h4>This Month</h4>
                <div class="summary-value">5</div>
                <p class="summary-change">Total visits</p>
            </div>
            <div class="summary-card">
                <h4>Completed</h4>
                <div class="summary-value">12</div>
                <p class="summary-change">All time</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Upcoming Appointments</h3>
                </div>
                <ul class="appointment-list">
                    <li>
                        <div>
                            <strong>Nov 15, 2025 · 09:00 AM</strong>
                            <p>Dr. Jane Cooper · Cardiology</p>
                            <p style="font-size: 0.85rem; color: var(--mc-gray);">Reason: Follow-up consultation</p>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <span class="badge cyan">Confirmed</span>
                            <button class="btn-outline small">Reschedule</button>
                            <button class="btn-outline small">Cancel</button>
                        </div>
                    </li>
                    <li>
                        <div>
                            <strong>Nov 20, 2025 · 10:00 AM</strong>
                            <p>Dr. Robert Smith · Pediatrics</p>
                            <p style="font-size: 0.85rem; color: var(--mc-gray);">Reason: General check-up</p>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <span class="badge">Pending</span>
                            <button class="btn-outline small">Reschedule</button>
                            <button class="btn-outline small">Cancel</button>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Appointments</h3>
                </div>
                <ul class="appointment-list">
                    <li>
                        <div>
                            <strong>Nov 12, 2025 · 09:00 AM</strong>
                            <p>Dr. Jane Cooper · Cardiology</p>
                        </div>
                        <span class="badge cyan">Completed</span>
                    </li>
                    <li>
                        <div>
                            <strong>Nov 8, 2025 · 02:00 PM</strong>
                            <p>Dr. Sarah Wilson · General Medicine</p>
                        </div>
                        <span class="badge cyan">Completed</span>
                    </li>
                    <li>
                        <div>
                            <strong>Oct 28, 2025 · 11:00 AM</strong>
                            <p>Dr. Robert Smith · Pediatrics</p>
                        </div>
                        <span class="badge cyan">Completed</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Appointments</h3>
                <select>
                    <option>All Status</option>
                    <option>Upcoming</option>
                    <option>Completed</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nov 15, 2025<br>09:00 AM</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Cardiology</td>
                        <td>Follow-up consultation</td>
                        <td><span class="badge cyan">Confirmed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Reschedule</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 20, 2025<br>10:00 AM</td>
                        <td>Dr. Robert Smith</td>
                        <td>Pediatrics</td>
                        <td>General check-up</td>
                        <td><span class="badge">Pending</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Reschedule</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 12, 2025<br>09:00 AM</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Cardiology</td>
                        <td>Cardiology consultation</td>
                        <td><span class="badge cyan">Completed</span></td>
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
