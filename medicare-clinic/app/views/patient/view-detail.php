<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/auth.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
if (!$user || $user['role'] !== 'patient') {
    header('Location: index.php?page=login');
    exit;
}
$type = $_GET['type'] ?? '';
$id = (int) ($_GET['id'] ?? 0);
if (!$type || !$id) {
    header('Location: index.php?page=patient');
    exit;
}
$patient_id = (int) $user['id'];
$data = null;
$title = '';
if ($type === 'prescription') {
    $stmt = $pdo->prepare("SELECT p.*, u.name as doctor_name FROM prescriptions p JOIN users u ON p.doctor_id = u.id WHERE p.id = ? AND p.patient_id = ?");
    $stmt->execute([$id, $patient_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = 'Prescription Details';
} elseif ($type === 'record') {
    $stmt = $pdo->prepare("SELECT m.*, u.name as doctor_name FROM medical_records m LEFT JOIN users u ON m.doctor_id = u.id WHERE m.id = ? AND m.patient_id = ?");
    $stmt->execute([$id, $patient_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = 'Medical Record';
} elseif ($type === 'invoice') {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ? AND patient_id = ?");
    $stmt->execute([$id, $patient_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = 'Invoice Details';
}
if (!$data) {
    header('Location: index.php?page=patient');
    exit;
}
$backPage = $type === 'prescription' ? 'patient-prescriptions' : ($type === 'record' ? 'patient-records' : 'patient-billing');
require __DIR__ . '/../layouts/patient_sidebar.php';
$sidebar = render_patient_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <div class="header-right">
                <a href="index.php?page=patient-download&type=<?php echo urlencode($type); ?>&id=<?php echo $id; ?>" class="btn-outline">Download</a>
                <a href="index.php?page=<?php echo $backPage; ?>" class="btn-primary">Back</a>
            </div>
        </header>
        <section class="panel" style="max-width: 600px;">
            <div style="padding: 20px;">
                <?php if ($type === 'prescription'): ?>
                    <p><strong>Medication:</strong> <?php echo htmlspecialchars($data['medication']); ?></p>
                    <p><strong>Dosage:</strong> <?php echo htmlspecialchars($data['dosage']); ?></p>
                    <p><strong>Duration:</strong> <?php echo (int)($data['duration_days'] ?? 0); ?> days</p>
                    <p><strong>Doctor:</strong> <?php echo htmlspecialchars($data['doctor_name'] ?? '—'); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($data['created_at']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?></p>
                <?php elseif ($type === 'record'): ?>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($data['record_type']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($data['description']); ?></p>
                    <p><strong>Doctor/Provider:</strong> <?php echo htmlspecialchars($data['doctor_name'] ?? '—'); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($data['created_at']); ?></p>
                <?php elseif ($type === 'invoice'): ?>
                    <p><strong>Invoice #:</strong> <?php echo htmlspecialchars($data['invoice_number']); ?></p>
                    <p><strong>Service:</strong> <?php echo htmlspecialchars($data['service']); ?></p>
                    <p><strong>Amount:</strong> <?php echo mc_format_money((float) $data['amount']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($data['created_at']); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
