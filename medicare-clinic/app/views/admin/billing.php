<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
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
                <button class="btn-primary">+ Generate Invoice</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Total Revenue</h4>
                <div class="summary-value">$124,580</div>
                <p class="summary-change positive">+18% this month</p>
            </div>
            <div class="summary-card">
                <h4>Pending Payments</h4>
                <div class="summary-value">$8,420</div>
                <p class="summary-change">23 invoices</p>
            </div>
            <div class="summary-card">
                <h4>This Month</h4>
                <div class="summary-value">$45,230</div>
                <p class="summary-change positive">+12%</p>
            </div>
            <div class="summary-card">
                <h4>Overdue</h4>
                <div class="summary-value">$2,150</div>
                <p class="summary-change">7 invoices</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Invoices</h3>
                    <select>
                        <option>Last 30 days</option>
                        <option>Last 7 days</option>
                        <option>This month</option>
                    </select>
                </div>
                <ul class="list-table">
                    <li>
                        <span><strong>#INV-2025-001</strong><br>Michael Brown</span>
                        <span>$450</span>
                        <span><span class="badge cyan">Paid</span></span>
                    </li>
                    <li>
                        <span><strong>#INV-2025-002</strong><br>Sarah Johnson</span>
                        <span>$320</span>
                        <span><span class="badge">Pending</span></span>
                    </li>
                    <li>
                        <span><strong>#INV-2025-003</strong><br>David Lee</span>
                        <span>$280</span>
                        <span><span class="badge cyan">Paid</span></span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Payment Methods</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span><strong>Credit Card</strong></span>
                        <span>$89,420</span>
                        <span>72%</span>
                    </li>
                    <li>
                        <span><strong>Cash</strong></span>
                        <span>$24,580</span>
                        <span>20%</span>
                    </li>
                    <li>
                        <span><strong>Bank Transfer</strong></span>
                        <span>$10,580</span>
                        <span>8%</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Invoices</h3>
                <div style="display: flex; gap: 12px;">
                    <select>
                        <option>All Status</option>
                        <option>Paid</option>
                        <option>Pending</option>
                        <option>Overdue</option>
                    </select>
                    <select>
                        <option>Sort by: Recent</option>
                        <option>Amount</option>
                        <option>Patient</option>
                    </select>
                </div>
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
                    <tr>
                        <td>#INV-2025-001</td>
                        <td>Michael Brown</td>
                        <td>Cardiology Consultation</td>
                        <td>$450</td>
                        <td>Nov 12, 2025</td>
                        <td><span class="badge cyan">Paid</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-2025-002</td>
                        <td>Sarah Johnson</td>
                        <td>Lab Tests</td>
                        <td>$320</td>
                        <td>Nov 10, 2025</td>
                        <td><span class="badge">Pending</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Remind</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-2025-003</td>
                        <td>David Lee</td>
                        <td>General Check-up</td>
                        <td>$280</td>
                        <td>Nov 8, 2025</td>
                        <td><span class="badge cyan">Paid</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-2025-004</td>
                        <td>Emma Wilson</td>
                        <td>Prescription</td>
                        <td>$150</td>
                        <td>Oct 25, 2025</td>
                        <td><span class="badge" style="background: #ef4444; color: white;">Overdue</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Remind</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
