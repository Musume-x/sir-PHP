<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$records = [];
if ($pdo && $user) {
    $pid = (int) $user['id'];
    $stmt = $pdo->prepare("
        SELECT m.*, u.name as doctor_name
        FROM medical_records m
        LEFT JOIN users u ON m.doctor_id = u.id
        WHERE m.patient_id = ?
        ORDER BY m.created_at DESC
    ");
    $stmt->execute([$pid]);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Medical Records</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search records..." />
                </div>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Total Records</h4>
                <div class="summary-value"><?php echo count($records); ?></div>
                <p class="summary-change">Medical files</p>
            </div>
            <div class="summary-card">
                <h4>—</h4>
                <div class="summary-value">—</div>
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
                <h3>All Medical Records</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Doctor/Provider</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($r['record_type']); ?></td>
                        <td><?php echo htmlspecialchars($r['doctor_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($r['description']); ?></td>
                        <td>
                            <a href="index.php?page=patient-view&type=record&id=<?php echo (int)$r['id']; ?>" class="btn-outline small">View</a>
                            <a href="index.php?page=patient-download&type=record&id=<?php echo (int)$r['id']; ?>" class="btn-outline small">Download</a>
                        </td>
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
