<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_admin_sidebar();
$appointments = [];
$today = date('Y-m-d');
if ($pdo) {
    $stmt = $pdo->query("
        SELECT a.*, u1.name as patient_name, u2.name as doctor_name
        FROM appointments a
        JOIN users u1 ON a.patient_id = u1.id
        JOIN users u2 ON a.doctor_id = u2.id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $appointments = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
$todayCount = count(array_filter($appointments, fn($a) => $a['appointment_date'] === $today));
$pendingCount = count(array_filter($appointments, fn($a) => $a['status'] === 'pending'));
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
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Today</h4>
                <div class="summary-value"><?php echo $todayCount; ?></div>
                <p class="summary-change">Appointments</p>
            </div>
            <div class="summary-card">
                <h4>Total</h4>
                <div class="summary-value"><?php echo count($appointments); ?></div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Pending</h4>
                <div class="summary-value"><?php echo $pendingCount; ?></div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>—</h4>
                <div class="summary-value">—</div>
                <p class="summary-change">—</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Appointments</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $a): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($a['appointment_date']); ?> <?php echo htmlspecialchars($a['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($a['patient_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($a['doctor_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($a['department'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($a['reason'] ?? '—'); ?></td>
                        <td><span class="badge <?php echo $a['status'] === 'confirmed' || $a['status'] === 'completed' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($a['status']); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($appointments)): ?>
                    <tr><td colspan="6">No appointments yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
