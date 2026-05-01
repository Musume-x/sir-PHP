<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/auth.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
if (!$user) {
    header('Location: index.php?page=login');
    exit;
}
$type = $_GET['type'] ?? '';
$id = (int) ($_GET['id'] ?? 0);
if (!$type || !$id) {
    header('Location: index.php?page=' . ($user['role'] === 'patient' ? 'patient' : 'staff'));
    exit;
}
$data = null;
$title = '';
if ($type === 'prescription') {
    $sql = "SELECT p.*, u1.name as patient_name, u2.name as doctor_name FROM prescriptions p JOIN users u1 ON p.patient_id = u1.id JOIN users u2 ON p.doctor_id = u2.id WHERE p.id = ?";
    if ($user['role'] === 'patient') $sql .= " AND p.patient_id = " . (int)$user['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = 'Prescription Details';
} elseif ($type === 'record') {
    $sql = "SELECT m.*, u1.name as patient_name, u2.name as doctor_name FROM medical_records m JOIN users u1 ON m.patient_id = u1.id LEFT JOIN users u2 ON m.doctor_id = u2.id WHERE m.id = ?";
    if ($user['role'] === 'patient') $sql .= " AND m.patient_id = " . (int)$user['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = 'Medical Record';
} elseif ($type === 'invoice') {
    $sql = "SELECT i.*, p.payment_method, p.transaction_ref, p.paid_at as payment_date, u.name as patient_name FROM invoices i LEFT JOIN payments p ON i.id = p.invoice_id JOIN users u ON i.patient_id = u.id WHERE i.id = ?";
    if ($user['role'] === 'patient') $sql .= " AND i.patient_id = " . (int)$user['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = 'Invoice Details';
}
if (!$data) {
    header('Location: index.php?page=' . ($user['role'] === 'patient' ? 'patient' : 'staff'));
    exit;
}

if ($user['role'] === 'patient') {
    require __DIR__ . '/../layouts/patient_sidebar.php';
    $sidebar = render_patient_sidebar();
    $backPage = $type === 'prescription' ? 'patient-prescriptions' : ($type === 'record' ? 'patient-records' : 'patient-billing');
} else {
    require __DIR__ . '/../layouts/staff_sidebar.php';
    $sidebar = render_staff_sidebar();
    $backPage = $type === 'prescription' ? 'staff-prescriptions' : ($type === 'record' ? 'staff-records' : 'staff-billing');
}
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
                    <p><strong>Status:</strong> <span class="badge <?php echo $data['status'] === 'paid' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars(ucfirst($data['status'])); ?></span></p>
                    <p><strong>Date Generated:</strong> <?php echo htmlspecialchars($data['created_at']); ?></p>
                    
                    <?php if ($data['status'] === 'paid'): ?>
                        <hr style="border: 0; border-top: 1px solid var(--mc-light-gray); margin: 15px 0;" />
                        <h4 style="color: var(--mc-blue); margin-bottom: 10px;">Payment Details</h4>
                        <p><strong>Method:</strong> <?php echo htmlspecialchars($data['payment_method'] ?? '—'); ?></p>
                        <p><strong>Reference:</strong> <code style="background: var(--mc-bg-alt); padding: 2px 5px; border-radius: 4px;"><?php echo htmlspecialchars($data['transaction_ref'] ?? '—'); ?></code></p>
                        <p><strong>Paid At:</strong> <?php echo htmlspecialchars($data['payment_date'] ?? '—'); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
