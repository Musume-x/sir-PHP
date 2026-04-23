<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$rawRole = current_role() ?? null;
$sidebar = render_staff_sidebar();
$appointments = [];
$today = date('Y-m-d');
$listFromDate = '2026-01-01';
if ($pdo) {
    if ($rawRole === 'doctor' && !empty($user['id'])) {
        $stmt = $pdo->prepare("
            SELECT a.*, u1.name as patient_name, u2.name as doctor_name
            FROM appointments a
            JOIN users u1 ON a.patient_id = u1.id
            JOIN users u2 ON a.doctor_id = u2.id
            WHERE a.doctor_id = ? AND a.appointment_date >= ?
            ORDER BY a.appointment_date, a.appointment_time
        ");
        $stmt->execute([(int) $user['id'], $listFromDate]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("
            SELECT a.*, u1.name as patient_name, u2.name as doctor_name
            FROM appointments a
            JOIN users u1 ON a.patient_id = u1.id
            JOIN users u2 ON a.doctor_id = u2.id
            WHERE a.appointment_date >= ?
            ORDER BY a.appointment_date, a.appointment_time
        ");
        $stmt->execute([$listFromDate]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
$todayAppointments = array_filter($appointments, fn($a) => $a['appointment_date'] === $today);
$upcoming = array_filter($appointments, fn($a) => $a['appointment_date'] >= $today && !in_array($a['status'], ['cancelled', 'completed'], true));
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Appointments</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search appointments..." />
                </div>
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Today's Appointments</h3>
                </div>
                <ul class="appointment-list compact">
                    <?php foreach (array_slice($todayAppointments, 0, 10) as $a): ?>
                    <li>
                        <div>
                            <strong><?php echo htmlspecialchars($a['appointment_time']); ?></strong>
                            <p><?php echo htmlspecialchars($a['patient_name']); ?> · <?php echo htmlspecialchars($a['reason'] ?: '—'); ?></p>
                        </div>
                        <span class="badge <?php echo $a['status'] === 'confirmed' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($a['status']); ?></span>
                    </li>
                    <?php endforeach; ?>
                    <?php if (empty($todayAppointments)): ?>
                    <li><div><strong>No appointments today</strong></div></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <h3>Upcoming</h3>
                </div>
                <ul class="appointment-list compact">
                    <?php foreach (array_slice($upcoming, 0, 5) as $a): ?>
                    <li>
                        <div>
                            <strong><?php echo htmlspecialchars($a['appointment_date']); ?> · <?php echo htmlspecialchars($a['appointment_time']); ?></strong>
                            <p><?php echo htmlspecialchars($a['patient_name']); ?> · <?php echo htmlspecialchars($a['department'] ?? '—'); ?></p>
                        </div>
                        <span class="badge"><?php echo htmlspecialchars($a['status']); ?></span>
                    </li>
                    <?php endforeach; ?>
                    <?php if (empty($upcoming)): ?>
                    <li><div><strong>No upcoming appointments</strong></div></li>
                    <?php endif; ?>
                </ul>
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
