<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
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
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Today's Payments</h4>
                <div class="summary-value">$1,240</div>
                <p class="summary-change positive">24 invoices</p>
            </div>
            <div class="summary-card">
                <h4>Pending</h4>
                <div class="summary-value">$820</div>
                <p class="summary-change">12 invoices</p>
            </div>
            <div class="summary-card">
                <h4>Overdue</h4>
                <div class="summary-value">$210</div>
                <p class="summary-change">3 invoices</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>Recent Invoices</h3>
                <select>
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Patient</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#INV-3041</td>
                        <td>Michael Brown</td>
                        <td>Consultation</td>
                        <td>$250</td>
                        <td><span class="badge cyan">Paid</span></td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                    <tr>
                        <td>#INV-3042</td>
                        <td>Sarah Johnson</td>
                        <td>Lab Tests</td>
                        <td>$180</td>
                        <td><span class="badge">Pending</span></td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                    <tr>
                        <td>#INV-3043</td>
                        <td>David Lee</td>
                        <td>Check‑up</td>
                        <td>$120</td>
                        <td><span class="badge">Overdue</span></td>
                        <td><button class="btn-outline small">Remind</button></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>

