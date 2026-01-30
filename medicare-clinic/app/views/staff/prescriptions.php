<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
$prescriptions = [];
if ($pdo) {
    $stmt = $pdo->query("
        SELECT p.*, u1.name as patient_name, u2.name as doctor_name
        FROM prescriptions p
        JOIN users u1 ON p.patient_id = u1.id
        JOIN users u2 ON p.doctor_id = u2.id
        ORDER BY p.created_at DESC
    ");
    $prescriptions = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
$approved = !empty($_GET['approved']);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Prescriptions</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <?php if ($approved): ?>
            <p class="auth-success">Refill approved.</p>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-header">
                <h3>All Prescriptions</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Refill</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prescriptions as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['patient_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($p['medication']); ?></td>
                        <td><?php echo htmlspecialchars($p['dosage']); ?></td>
                        <td><?php echo (int)($p['duration_days'] ?? 0); ?> days</td>
                        <td><span class="badge <?php echo $p['status'] === 'active' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($p['status']); ?></span></td>
                        <td>
                            <?php if (!empty($p['refill_requested']) && empty($p['refill_approved'])): ?>
                                <span class="badge">Requested</span>
                            <?php elseif (!empty($p['refill_approved'])): ?>
                                <span class="badge cyan">Approved</span>
                            <?php else: ?>
                                — 
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($p['refill_requested']) && empty($p['refill_approved'])): ?>
                                <a href="index.php?page=staff-prescriptions&approve_refill=<?php echo (int)$p['id']; ?>" class="btn-primary small">Approve Refill</a>
                            <?php else: ?>
                                <span class="btn-outline small">View</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($prescriptions)): ?>
                    <tr><td colspan="7">No prescriptions yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
