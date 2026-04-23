<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$upcomingAppointment = null;
$pendingRequest = null;
$prescriptionCount = 0;
$activePrescriptionCount = 0;
$totalDue = 0;
$recentRecords = [];
$recentNotifications = [];
if ($pdo && $user) {
    $pid = (int) $user['id'];
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT a.*, u.name as doctor_name FROM appointments a JOIN users u ON a.doctor_id = u.id WHERE a.patient_id = ? AND a.appointment_date >= ? AND a.status NOT IN ('cancelled','completed') ORDER BY a.appointment_date, a.appointment_time LIMIT 1");
    $stmt->execute([$pid, $today]);
    $upcomingAppointment = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT r.*, u.name as doctor_name
        FROM appointment_requests r
        JOIN users u ON r.doctor_id = u.id
        WHERE r.patient_id = ? AND r.status = 'pending'
        ORDER BY r.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$pid]);
    $pendingRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM prescriptions WHERE patient_id = ?");
    $stmt->execute([$pid]);
    $prescriptionCount = (int) $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM prescriptions WHERE patient_id = ? AND status = 'active'");
    $stmt->execute([$pid]);
    $activePrescriptionCount = (int) $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) FROM invoices WHERE patient_id = ? AND status IN ('pending','overdue')");
    $stmt->execute([$pid]);
    $totalDue = (float) $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$pid]);
    $recentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$pid]);
    $recentNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Welcome, <?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Upcoming Appointment</h4>
                <?php if ($upcomingAppointment): ?>
                    <div class="summary-value"><?php echo htmlspecialchars($upcomingAppointment['appointment_date']); ?></div>
                    <p class="summary-change"><?php echo htmlspecialchars($upcomingAppointment['appointment_time']); ?> · <?php echo htmlspecialchars($upcomingAppointment['doctor_name'] ?? '—'); ?></p>
                <?php elseif ($pendingRequest): ?>
                    <div class="summary-value">Pending</div>
                    <p class="summary-change">Waiting for <?php echo htmlspecialchars($pendingRequest['doctor_name'] ?? 'Doctor'); ?> to schedule.</p>
                <?php else: ?>
                    <div class="summary-value">—</div>
                    <p class="summary-change"><a href="index.php?page=patient-book">Book one</a></p>
                <?php endif; ?>
            </div>
            <div class="summary-card">
                <h4>Prescriptions</h4>
                <div class="summary-value"><?php echo $prescriptionCount; ?></div>
                <p class="summary-change"><?php echo $activePrescriptionCount; ?> active</p>
            </div>
            <div class="summary-card">
                <h4>Billing Status</h4>
                <div class="summary-value"><?php echo mc_format_money($totalDue, 0); ?></div>
                <p class="summary-change">Due</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Records</h3>
                </div>
                <ul class="list-table">
                    <?php foreach ($recentRecords as $r): ?>
                    <li>
                        <span><?php echo htmlspecialchars($r['record_type']); ?>: <?php echo htmlspecialchars($r['description']); ?></span>
                        <span><?php echo htmlspecialchars($r['created_at']); ?></span>
                        <a href="index.php?page=patient-view&type=record&id=<?php echo (int)$r['id']; ?>" class="btn-outline small">View</a>
                    </li>
                    <?php endforeach; ?>
                    <?php if (empty($recentRecords)): ?>
                    <li><span>No records yet.</span><span>—</span><a href="index.php?page=patient-records">Records</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <h3>Notifications</h3>
                </div>
                <ul class="notification-list">
                    <?php foreach ($recentNotifications as $n): ?>
                    <li><strong><?php echo htmlspecialchars($n['title']); ?></strong><br><?php echo htmlspecialchars($n['message']); ?></li>
                    <?php endforeach; ?>
                    <?php if (empty($recentNotifications)): ?>
                    <li>No notifications yet.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
    </main>
</div>
