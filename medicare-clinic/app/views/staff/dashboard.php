<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$rawRole = current_role() ?? '';
$sidebar = render_staff_sidebar();

$today = date('Y-m-d');
$from2026 = '2026-01-01';
$todayApptCount = 0;
$upcomingCount = 0;
$pendingRequests = 0;
$patientCount = 0;

if ($pdo && $user) {
    $uid = (int) $user['id'];
    if ($rawRole === 'doctor') {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status NOT IN ('cancelled')");
        $stmt->execute([$uid, $today]);
        $todayApptCount = (int) $stmt->fetchColumn();

        $listFrom = max($today, $from2026);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date >= ? AND status NOT IN ('cancelled','completed')");
        $stmt->execute([$uid, $listFrom]);
        $upcomingCount = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointment_requests WHERE doctor_id = ? AND status = 'pending'");
        $stmt->execute([$uid]);
        $pendingRequests = (int) $stmt->fetchColumn();
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND status NOT IN ('cancelled')");
        $stmt->execute([$today]);
        $todayApptCount = (int) $stmt->fetchColumn();

        $listFrom = max($today, $from2026);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date >= ? AND status NOT IN ('cancelled','completed')");
        $stmt->execute([$listFrom]);
        $upcomingCount = (int) $stmt->fetchColumn();
    }

    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'patient'");
    $patientCount = $stmt ? (int) $stmt->fetchColumn() : 0;
}

$todayList = [];
if ($pdo && $user) {
    if ($rawRole === 'doctor') {
        $stmt = $pdo->prepare("
            SELECT a.*, u.name AS patient_name
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            WHERE a.doctor_id = ? AND a.appointment_date = ?
            ORDER BY a.appointment_time
            LIMIT 8
        ");
        $stmt->execute([(int) $user['id'], $today]);
        $todayList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("
            SELECT a.*, u.name AS patient_name, d.name AS doctor_name
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            JOIN users d ON a.doctor_id = d.id
            WHERE a.appointment_date = ?
            ORDER BY a.appointment_time
            LIMIT 8
        ");
        $stmt->execute([$today]);
        $todayList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>

    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Dashboard</h1>
            <div class="header-right">
                <div class="notifications-dot"></div>
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Today's Appointments</h4>
                <div class="summary-value"><?php echo $todayApptCount; ?></div>
                <p class="summary-change"><?php echo htmlspecialchars($today); ?></p>
            </div>
            <div class="summary-card">
                <h4><?php echo $rawRole === 'doctor' ? 'Upcoming (2026+)' : 'Upcoming'; ?></h4>
                <div class="summary-value"><?php echo $upcomingCount; ?></div>
                <p class="summary-change">Scheduled visits</p>
            </div>
            <div class="summary-card">
                <h4><?php echo $rawRole === 'doctor' ? 'Pending requests' : 'Patients'; ?></h4>
                <div class="summary-value"><?php echo $rawRole === 'doctor' ? $pendingRequests : $patientCount; ?></div>
                <p class="summary-change"><?php echo $rawRole === 'doctor' ? 'Need scheduling' : 'Registered'; ?></p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Today's schedule</h3>
                </div>
                <ul class="appointment-list compact">
                    <?php foreach ($todayList as $a): ?>
                    <li>
                        <div>
                            <strong><?php echo htmlspecialchars($a['appointment_time']); ?></strong>
                            <p><?php echo htmlspecialchars($a['patient_name'] ?? '—'); ?> · <?php echo htmlspecialchars($a['reason'] ?: '—'); ?></p>
                            <?php if (!empty($a['doctor_name'])): ?>
                                <p style="font-size:0.85rem;color:var(--mc-gray);"><?php echo htmlspecialchars($a['doctor_name']); ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="badge <?php echo $a['status'] === 'confirmed' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($a['status']); ?></span>
                    </li>
                    <?php endforeach; ?>
                    <?php if (empty($todayList)): ?>
                    <li>
                        <div>
                            <strong>No appointments today</strong>
                            <?php if ($rawRole === 'doctor'): ?>
                                <p>Check <a href="index.php?page=staff-requests">Requests</a> to schedule patients.</p>
                            <?php else: ?>
                                <p>Help patients book visits from the appointments or patients pages.</p>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Quick links</h3>
                </div>
                <ul class="list-table">
                    <li><span><a href="index.php?page=staff-appointments">Appointments calendar</a></span><span>—</span></li>
                    <li><span><a href="index.php?page=staff-patients">Patient directory</a></span><span>—</span></li>
                    <?php if ($rawRole === 'doctor'): ?>
                    <li><span><a href="index.php?page=staff-requests">Schedule pending requests</a></span><span>—</span></li>
                    <li><span><a href="index.php?page=staff-prescriptions">Prescriptions &amp; refills</a></span><span>—</span></li>
                    <?php endif; ?>
                    <li><span><a href="index.php?page=staff-billing">Billing overview</a></span><span>—</span></li>
                </ul>
            </div>
        </section>
    </main>
</div>
