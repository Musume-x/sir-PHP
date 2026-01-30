<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
$records = [];
if ($pdo) {
    $stmt = $pdo->query("
        SELECT m.*, u1.name as patient_name, u2.name as doctor_name
        FROM medical_records m
        JOIN users u1 ON m.patient_id = u1.id
        LEFT JOIN users u2 ON m.doctor_id = u2.id
        ORDER BY m.created_at DESC
    ");
    $records = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Medical Records</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <section class="panel">
            <div class="panel-header">
                <h3>Medical Records</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Doctor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['patient_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($r['record_type']); ?></td>
                        <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($r['description']); ?></td>
                        <td><?php echo htmlspecialchars($r['doctor_name'] ?? '—'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($records)): ?>
                    <tr><td colspan="5">No medical records yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
