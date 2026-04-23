<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_admin_sidebar();
$invoices = [];
$revenue = 0;
$pending = 0;
$overdue = 0;
if ($pdo) {
    $stmt = $pdo->query("
        SELECT i.*, u.name as patient_name
        FROM invoices i
        JOIN users u ON i.patient_id = u.id
        ORDER BY i.created_at DESC
    ");
    $invoices = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    foreach ($invoices as $inv) {
        if ($inv['status'] === 'paid') $revenue += (float)$inv['amount'];
        if ($inv['status'] === 'pending') $pending += (float)$inv['amount'];
        if ($inv['status'] === 'overdue') $overdue += (float)$inv['amount'];
    }
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
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <?php if ($paid): ?>
            <p class="auth-success">Invoice marked as paid.</p>
        <?php endif; ?>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Total Revenue</h4>
                <div class="summary-value"><?php echo mc_format_money($revenue, 0); ?></div>
                <p class="summary-change">Paid</p>
            </div>
            <div class="summary-card">
                <h4>Pending Payments</h4>
                <div class="summary-value"><?php echo mc_format_money($pending, 0); ?></div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>This Month</h4>
                <div class="summary-value"><?php echo mc_format_money($revenue, 0); ?></div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Overdue</h4>
                <div class="summary-value"><?php echo mc_format_money($overdue, 0); ?></div>
                <p class="summary-change">—</p>
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
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inv['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($inv['patient_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($inv['service']); ?></td>
                        <td><?php echo mc_format_money((float) $inv['amount']); ?></td>
                        <td><?php echo htmlspecialchars($inv['created_at']); ?></td>
                        <td><span class="badge <?php echo $inv['status'] === 'paid' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($inv['status']); ?></span></td>
                        <td>
                            <?php if ($inv['status'] !== 'paid'): ?>
                                <a href="index.php?page=admin-billing&pay_invoice=<?php echo (int)$inv['id']; ?>" class="btn-primary small">Mark Paid</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($invoices)): ?>
                    <tr><td colspan="7">No invoices yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
