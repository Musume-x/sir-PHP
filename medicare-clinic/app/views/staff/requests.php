<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();

$requests = [];
if ($pdo && $user) {
    $stmt = $pdo->prepare("
        SELECT r.*, u.name AS patient_name, u.email AS patient_email
        FROM appointment_requests r
        JOIN users u ON r.patient_id = u.id
        WHERE r.doctor_id = ? AND r.status = 'pending'
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([(int) $user['id']]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$scheduled = !empty($_GET['scheduled']);
$error = !empty($_GET['error']);
$todayDate = date('Y-m-d');
$minScheduleDate = ($todayDate > '2026-01-01') ? $todayDate : '2026-01-01';
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Requests</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <?php if ($scheduled): ?>
            <p class="auth-success">Appointment scheduled successfully.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="auth-error"><?php echo ($_GET['error'] === 'invalid_date') ? 'Selected date is in the past. Please choose today or a future date.' : 'Unable to schedule. Please try again.'; ?></p>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-header">
                <h3>Pending Requests</h3>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Department</th>
                        <th>Reason</th>
                        <th>Requested</th>
                        <th>Schedule</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $r): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($r['patient_name'] ?? '—'); ?><br>
                                <small style="color: var(--mc-gray);"><?php echo htmlspecialchars($r['patient_email'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($r['department'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($r['reason'] ?: '—'); ?></td>
                            <td><?php echo htmlspecialchars($r['created_at'] ?? '—'); ?></td>
                            <td>
                                <form method="post" action="index.php?page=staff-requests" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                    <input type="hidden" name="schedule_request" value="1" />
                                    <input type="hidden" name="request_id" value="<?php echo (int) $r['id']; ?>" />
                                    <input type="date" name="appointment_date" min="<?php echo htmlspecialchars($minScheduleDate); ?>" max="2030-12-31" required />
                                    <select name="appointment_time" required>
                                        <option value="">Time</option>
                                        <option value="08:00">08:00 AM</option>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="14:00">02:00 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="16:00">04:00 PM</option>
                                    </select>
                                    <button type="submit" class="btn-primary">Confirm</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="5">No pending requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

