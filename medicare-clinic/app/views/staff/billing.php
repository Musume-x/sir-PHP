<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
$invoices = [];
$totals = ['pending' => 0, 'paid_today' => 0, 'overdue' => 0];
if ($pdo) {
    $stmt = $pdo->query("
        SELECT i.*, u.name as patient_name
        FROM invoices i
        JOIN users u ON i.patient_id = u.id
        ORDER BY i.created_at DESC
    ");
    $invoices = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    foreach ($invoices as $inv) {
        if ($inv['status'] === 'pending') $totals['pending'] += (float)$inv['amount'];
        if ($inv['status'] === 'overdue') $totals['overdue'] += (float)$inv['amount'];
        if ($inv['status'] === 'paid' && $inv['paid_at'] && date('Y-m-d', strtotime($inv['paid_at'])) === date('Y-m-d')) $totals['paid_today'] += (float)$inv['amount'];
    }
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Billing</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search invoices..." />
                </div>
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Today's Payments</h4>
                <div class="summary-value">$<?php echo number_format($totals['paid_today'], 0); ?></div>
                <p class="summary-change">Paid today</p>
            </div>
            <div class="summary-card">
                <h4>Pending</h4>
                <div class="summary-value">$<?php echo number_format($totals['pending'], 0); ?></div>
                <p class="summary-change">Unpaid invoices</p>
            </div>
            <div class="summary-card">
                <h4>Overdue</h4>
                <div class="summary-value">$<?php echo number_format($totals['overdue'], 0); ?></div>
                <p class="summary-change">Overdue</p>
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
                        <th>Patient</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inv['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($inv['patient_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($inv['service']); ?></td>
                        <td>$<?php echo number_format((float)$inv['amount'], 2); ?></td>
                        <td><span class="badge <?php echo $inv['status'] === 'paid' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($inv['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($inv['created_at']); ?></td>
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
