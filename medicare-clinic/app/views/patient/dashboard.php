<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>

    <main class="main-content">
        <header class="main-header">
            <h1>Welcome, <?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role">Patient</span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Upcoming Appointment</h4>
                <div class="summary-value">Nov 12</div>
                <p class="summary-change">09:00 AM · Dr. Jane Cooper</p>
            </div>
            <div class="summary-card">
                <h4>Prescriptions</h4>
                <div class="summary-value">5</div>
                <p class="summary-change">2 active</p>
            </div>
            <div class="summary-card">
                <h4>Billing Status</h4>
                <div class="summary-value">$120</div>
                <p class="summary-change">Due this month</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Medical History</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>Cardiology Consultation</span>
                        <span>Oct 10, 2025</span>
                        <span>Completed</span>
                    </li>
                    <li>
                        <span>Laboratory Tests</span>
                        <span>Sep 21, 2025</span>
                        <span>Completed</span>
                    </li>
                    <li>
                        <span>General Check‑up</span>
                        <span>Aug 03, 2025</span>
                        <span>Completed</span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Notifications</h3>
                </div>
                <ul class="notification-list">
                    <li>Reminder: Appointment with Dr. Cooper tomorrow at 09:00 AM.</li>
                    <li>Your lab results are now available for download.</li>
                    <li>Invoice #MC‑2041 has been generated.</li>
                </ul>
            </div>
        </section>
    </main>
</div>

