<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$appointments = [];
$upcomingCount = 0;
$completedCount = 0;
if ($pdo && $user) {
    $pid = (int) $user['id'];
    $stmt = $pdo->prepare("
        SELECT a.*, u.name as doctor_name
        FROM appointments a
        JOIN users u ON a.doctor_id = u.id
        WHERE a.patient_id = ?
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute([$pid]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $today = date('Y-m-d');
    $upcomingCount = count(array_filter($appointments, function ($a) use ($today) {
        return $a['appointment_date'] >= $today && !in_array($a['status'], ['cancelled', 'completed'], true);
    }));
    $completedCount = count(array_filter($appointments, fn($a) => $a['status'] === 'completed'));
}
$success = !empty($_GET['success']);
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

        <?php if ($success): ?>
            <p class="auth-success">Appointment booked successfully.</p>
        <?php endif; ?>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Upcoming</h4>
                <div class="summary-value"><?php echo $upcomingCount; ?></div>
                <p class="summary-change">Appointments</p>
            </div>
            <div class="summary-card">
                <h4>Total</h4>
                <div class="summary-value"><?php echo count($appointments); ?></div>
                <p class="summary-change">All visits</p>
            </div>
            <div class="summary-card">
                <h4>Completed</h4>
                <div class="summary-value"><?php echo $completedCount; ?></div>
                <p class="summary-change">All time</p>
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
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $a): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($a['appointment_date']); ?><br><?php echo htmlspecialchars($a['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($a['doctor_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($a['department'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($a['reason'] ?? '—'); ?></td>
                        <td><span class="badge <?php echo $a['status'] === 'confirmed' || $a['status'] === 'completed' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($a['status']); ?></span></td>
                        <td>
                            <a href="index.php?page=patient-appointments" class="btn-outline small">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($appointments)): ?>
                    <tr><td colspan="6">No appointments yet. <a href="index.php?page=patient-book">Book one</a>.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
