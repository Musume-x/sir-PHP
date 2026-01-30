<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$prescriptions = [];
$activeCount = 0;
$refillNeeded = 0;
if ($pdo && $user) {
    $pid = (int) $user['id'];
    $stmt = $pdo->prepare("
        SELECT p.*, u.name as doctor_name
        FROM prescriptions p
        JOIN users u ON p.doctor_id = u.id
        WHERE p.patient_id = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$pid]);
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $activeCount = count(array_filter($prescriptions, fn($r) => $r['status'] === 'active'));
    $refillNeeded = count(array_filter($prescriptions, fn($r) => (int)($r['refill_requested'] ?? 0) === 1));
}
$requested = !empty($_GET['requested']);
$refilled = !empty($_GET['refilled']);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Prescriptions</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search prescriptions..." />
                </div>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <?php if ($requested): ?>
            <p class="auth-success">Refill requested.</p>
        <?php endif; ?>
        <?php if ($refilled): ?>
            <p class="auth-success">Refill recorded.</p>
        <?php endif; ?>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Active Prescriptions</h4>
                <div class="summary-value"><?php echo $activeCount; ?></div>
                <p class="summary-change">Currently taking</p>
            </div>
            <div class="summary-card">
                <h4>Total Prescriptions</h4>
                <div class="summary-value"><?php echo count($prescriptions); ?></div>
                <p class="summary-change">All time</p>
            </div>
            <div class="summary-card">
                <h4>Refills Requested</h4>
                <div class="summary-value"><?php echo $refillNeeded; ?></div>
                <p class="summary-change">Pending approval</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Prescriptions</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Medication</th>
                        <th>Doctor</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prescriptions as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($p['medication']); ?></td>
                        <td><?php echo htmlspecialchars($p['doctor_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($p['dosage']); ?></td>
                        <td><?php echo (int)($p['duration_days'] ?? 0); ?> days</td>
                        <td>
                            <span class="badge <?php echo $p['status'] === 'active' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($p['status']); ?></span>
                            <?php if (!empty($p['refill_requested'])): ?>
                                <br><span style="font-size:0.8rem;">Refill requested</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?page=patient-view&type=prescription&id=<?php echo (int)$p['id']; ?>" class="btn-outline small">View</a>
                            <?php if ($p['status'] === 'active'): ?>
                                <?php if (empty($p['refill_requested'])): ?>
                                    <a href="index.php?page=patient-prescriptions&request_refill=<?php echo (int)$p['id']; ?>" class="btn-outline small">Request Refill</a>
                                <?php elseif (!empty($p['refill_approved'])): ?>
                                    <a href="index.php?page=patient-prescriptions&refill=<?php echo (int)$p['id']; ?>" class="btn-outline small">Refill</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="index.php?page=patient-download&type=prescription&id=<?php echo (int)$p['id']; ?>" class="btn-outline small">Download</a>
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
