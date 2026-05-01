<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$invoices = [];
$totalDue = 0;
$paidThisMonth = 0;
if ($pdo && $user) {
    $pid = (int) $user['id'];
    $stmt = $pdo->prepare("
        SELECT i.*, u.name AS doctor_name 
        FROM invoices i 
        LEFT JOIN users u ON i.doctor_id = u.id 
        WHERE i.patient_id = ? 
        ORDER BY i.created_at DESC
    ");
    $stmt->execute([$pid]);
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($invoices as $inv) {
        if ($inv['status'] === 'pending' || $inv['status'] === 'overdue') {
            $totalDue += (float) $inv['amount'];
        }
        if ($inv['status'] === 'paid' && $inv['paid_at'] && date('Y-m', strtotime($inv['paid_at'])) === date('Y-m')) {
            $paidThisMonth += (float) $inv['amount'];
        }
    }
    $stmt = $pdo->query("SELECT COALESCE(SUM(amount),0) as total FROM invoices WHERE patient_id = $pid AND status = 'paid'");
    $allTimePaid = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
} else {
    $allTimePaid = 0;
}
$paid = !empty($_GET['paid']);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Billing & Payments</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search invoices..." />
                </div>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <?php if ($paid): ?>
            <p class="auth-success">Payment recorded successfully.</p>
        <?php endif; ?>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Total Due</h4>
                <div class="summary-value"><?php echo mc_format_money($totalDue, 0); ?></div>
                <p class="summary-change">Pending</p>
            </div>
            <div class="summary-card">
                <h4>Paid This Month</h4>
                <div class="summary-value"><?php echo mc_format_money($paidThisMonth, 0); ?></div>
                <p class="summary-change positive"><?php echo count(array_filter($invoices, fn($i) => $i['status']==='paid' && $i['paid_at'] && date('Y-m', strtotime($i['paid_at']))===date('Y-m'))); ?> invoices</p>
            </div>
            <div class="summary-card">
                <h4>All Time</h4>
                <div class="summary-value"><?php echo mc_format_money($allTimePaid, 0); ?></div>
                <p class="summary-change">Total paid</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Invoices</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Doctor</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inv['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($inv['doctor_name'] ?? 'Clinic'); ?></td>
                        <td><?php echo htmlspecialchars($inv['service']); ?></td>
                        <td><?php echo mc_format_money((float) $inv['amount']); ?></td>
                        <td>
                            <?php if ($inv['status'] === 'paid'): ?>
                                <span class="badge cyan">Paid</span>
                            <?php else: ?>
                                <span class="badge"><?php echo htmlspecialchars($inv['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($inv['status'] !== 'paid'): ?>
                                <a href="index.php?page=patient-checkout&invoice_id=<?php echo (int) $inv['id']; ?>" class="btn-primary small">Pay now</a>
                            <?php endif; ?>
                            <a href="index.php?page=patient-view&type=invoice&id=<?php echo (int)$inv['id']; ?>" class="btn-outline small">View</a>
                            <a href="index.php?page=patient-download&type=invoice&id=<?php echo (int)$inv['id']; ?>" class="btn-outline small">Download</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($invoices)): ?>
                    <tr><td colspan="6">No invoices yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
