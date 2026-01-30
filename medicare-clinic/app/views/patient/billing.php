<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
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

        <section class="grid-3">
            <div class="summary-card">
                <h4>Total Due</h4>
                <div class="summary-value">$120</div>
                <p class="summary-change">This month</p>
            </div>
            <div class="summary-card">
                <h4>Paid This Month</h4>
                <div class="summary-value">$450</div>
                <p class="summary-change positive">3 invoices</p>
            </div>
            <div class="summary-card">
                <h4>All Time</h4>
                <div class="summary-value">$2,340</div>
                <p class="summary-change">Total paid</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Pending Payments</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>
                            <strong>#INV-2025-002</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Lab Tests · Nov 10, 2025</span>
                        </span>
                        <span>
                            <strong>$320</strong><br>
                            <button class="btn-primary small" style="margin-top: 8px;">Pay Now</button>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>#INV-2025-004</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Consultation · Oct 25, 2025</span>
                        </span>
                        <span>
                            <strong>$150</strong><br>
                            <button class="btn-primary small" style="margin-top: 8px;">Pay Now</button>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Payments</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>
                            <strong>#INV-2025-001</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Cardiology Consultation · Nov 12, 2025</span>
                        </span>
                        <span>
                            <strong>$450</strong><br>
                            <span class="badge cyan" style="margin-top: 8px;">Paid</span>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>#INV-2024-098</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">General Check-up · Nov 8, 2025</span>
                        </span>
                        <span>
                            <strong>$280</strong><br>
                            <span class="badge cyan" style="margin-top: 8px;">Paid</span>
                        </span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Invoices</h3>
                <select>
                    <option>All Status</option>
                    <option>Paid</option>
                    <option>Pending</option>
                    <option>Overdue</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#INV-2025-002</td>
                        <td>Nov 10, 2025</td>
                        <td>Lab Tests</td>
                        <td>$320</td>
                        <td><span class="badge">Pending</span></td>
                        <td>
                            <button class="btn-primary small">Pay Now</button>
                            <button class="btn-outline small">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-2025-001</td>
                        <td>Nov 12, 2025</td>
                        <td>Cardiology Consultation</td>
                        <td>$450</td>
                        <td><span class="badge cyan">Paid</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-2024-098</td>
                        <td>Nov 8, 2025</td>
                        <td>General Check-up</td>
                        <td>$280</td>
                        <td><span class="badge cyan">Paid</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#INV-2025-004</td>
                        <td>Oct 25, 2025</td>
                        <td>Consultation</td>
                        <td>$150</td>
                        <td><span class="badge" style="background: #ef4444; color: white;">Overdue</span></td>
                        <td>
                            <button class="btn-primary small">Pay Now</button>
                            <button class="btn-outline small">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
