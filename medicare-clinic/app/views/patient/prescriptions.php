<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
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

        <section class="grid-3">
            <div class="summary-card">
                <h4>Active Prescriptions</h4>
                <div class="summary-value">2</div>
                <p class="summary-change">Currently taking</p>
            </div>
            <div class="summary-card">
                <h4>Total Prescriptions</h4>
                <div class="summary-value">5</div>
                <p class="summary-change">All time</p>
            </div>
            <div class="summary-card">
                <h4>Refills Needed</h4>
                <div class="summary-value">1</div>
                <p class="summary-change">This month</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Active Prescriptions</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>
                            <strong>Lisinopril 10mg</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Dr. Jane Cooper · Nov 12, 2025</span><br>
                            <span style="font-size: 0.85rem;">Take 1 tablet daily · 30 days supply</span>
                        </span>
                        <span>
                            <span class="badge cyan">Active</span><br>
                            <button class="btn-outline small" style="margin-top: 8px;">Request Refill</button>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>Metformin 500mg</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Dr. Sarah Wilson · Nov 8, 2025</span><br>
                            <span style="font-size: 0.85rem;">Take 1 tablet twice daily · 60 days supply</span>
                        </span>
                        <span>
                            <span class="badge cyan">Active</span><br>
                            <button class="btn-outline small" style="margin-top: 8px;">Request Refill</button>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Prescriptions</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>
                            <strong>Amoxicillin 500mg</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Dr. Robert Smith · Oct 28, 2025</span><br>
                            <span style="font-size: 0.85rem;">Take 1 capsule three times daily · 7 days</span>
                        </span>
                        <span class="badge">Completed</span>
                    </li>
                    <li>
                        <span>
                            <strong>Ibuprofen 400mg</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Dr. Jane Cooper · Oct 10, 2025</span><br>
                            <span style="font-size: 0.85rem;">Take as needed for pain</span>
                        </span>
                        <span class="badge">Completed</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Prescriptions</h3>
                <select>
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Completed</option>
                    <option>Expired</option>
                </select>
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
                    <tr>
                        <td>Nov 12, 2025</td>
                        <td>Lisinopril 10mg</td>
                        <td>Dr. Jane Cooper</td>
                        <td>1 tablet daily</td>
                        <td>30 days</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Refill</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 8, 2025</td>
                        <td>Metformin 500mg</td>
                        <td>Dr. Sarah Wilson</td>
                        <td>1 tablet twice daily</td>
                        <td>60 days</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Refill</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Oct 28, 2025</td>
                        <td>Amoxicillin 500mg</td>
                        <td>Dr. Robert Smith</td>
                        <td>1 capsule three times daily</td>
                        <td>7 days</td>
                        <td><span class="badge">Completed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
