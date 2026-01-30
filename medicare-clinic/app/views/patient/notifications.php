<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Notifications</h1>
            <div class="header-right">
                <button class="btn-outline">Mark All as Read</button>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Unread</h4>
                <div class="summary-value">5</div>
                <p class="summary-change">Notifications</p>
            </div>
            <div class="summary-card">
                <h4>Today</h4>
                <div class="summary-value">3</div>
                <p class="summary-change">New messages</p>
            </div>
            <div class="summary-card">
                <h4>This Week</h4>
                <div class="summary-value">12</div>
                <p class="summary-change">Total notifications</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Unread Notifications</h3>
                </div>
                <ul class="notification-list">
                    <li style="background: var(--mc-cyan-soft); padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Appointment Reminder</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Reminder: Appointment with Dr. Cooper tomorrow at 09:00 AM.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">2 hours ago</span>
                    </li>
                    <li style="background: var(--mc-cyan-soft); padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Lab Results Available</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Your lab results are now available for download.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">5 hours ago</span>
                    </li>
                    <li style="background: var(--mc-cyan-soft); padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>New Invoice</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Invoice #MC-2041 has been generated.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">Yesterday</span>
                    </li>
                    <li style="padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Prescription Refill Approved</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Your refill request for Lisinopril has been approved.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">2 days ago</span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Notifications</h3>
                </div>
                <ul class="notification-list">
                    <li style="padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Appointment Confirmed</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Your appointment with Dr. Jane Cooper on Nov 15 has been confirmed.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">3 days ago</span>
                    </li>
                    <li style="padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Payment Received</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Payment of $450 for Invoice #INV-2025-001 has been received.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">4 days ago</span>
                    </li>
                    <li style="padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Medical Record Updated</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Your medical record from Nov 12 consultation has been updated.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">5 days ago</span>
                    </li>
                    <li style="padding: 12px; border-radius: 12px; margin-bottom: 12px;">
                        <strong>Welcome to MediCare</strong><br>
                        <span style="font-size: 0.85rem; color: var(--mc-gray);">Thank you for registering with MediCare Clinic System.</span><br>
                        <span style="font-size: 0.75rem; color: var(--mc-gray); margin-top: 4px; display: block;">1 week ago</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Notifications</h3>
                <select>
                    <option>All Types</option>
                    <option>Appointments</option>
                    <option>Prescriptions</option>
                    <option>Billing</option>
                    <option>Medical Records</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background: var(--mc-cyan-soft);">
                        <td>Today, 10:00 AM</td>
                        <td>Appointment</td>
                        <td>Reminder: Appointment with Dr. Cooper tomorrow at 09:00 AM.</td>
                        <td><span class="badge">Unread</span></td>
                        <td>
                            <button class="btn-outline small">Mark Read</button>
                        </td>
                    </tr>
                    <tr style="background: var(--mc-cyan-soft);">
                        <td>Today, 7:00 AM</td>
                        <td>Medical Records</td>
                        <td>Your lab results are now available for download.</td>
                        <td><span class="badge">Unread</span></td>
                        <td>
                            <button class="btn-outline small">Mark Read</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Yesterday, 3:00 PM</td>
                        <td>Billing</td>
                        <td>Invoice #MC-2041 has been generated.</td>
                        <td><span class="badge cyan">Read</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 10, 2025</td>
                        <td>Prescription</td>
                        <td>Your refill request for Lisinopril has been approved.</td>
                        <td><span class="badge cyan">Read</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
