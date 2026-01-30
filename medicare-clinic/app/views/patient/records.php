<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
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
                <div class="summary-value">18</div>
                <p class="summary-change">Medical files</p>
            </div>
            <div class="summary-card">
                <h4>This Year</h4>
                <div class="summary-value">12</div>
                <p class="summary-change">2025 records</p>
            </div>
            <div class="summary-card">
                <h4>Lab Results</h4>
                <div class="summary-value">5</div>
                <p class="summary-change">Available</p>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Records</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>
                            <strong>Cardiology Consultation</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Dr. Jane Cooper · Nov 12, 2025</span>
                        </span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>Laboratory Tests</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Blood Work · Nov 10, 2025</span>
                        </span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>General Check-up</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Dr. Sarah Wilson · Nov 8, 2025</span>
                        </span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Lab Results</h3>
                </div>
                <ul class="list-table">
                    <li>
                        <span>
                            <strong>Complete Blood Count</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Nov 10, 2025</span>
                        </span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>Cholesterol Panel</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Oct 15, 2025</span>
                        </span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                    <li>
                        <span>
                            <strong>X-Ray - Chest</strong><br>
                            <span style="font-size: 0.85rem; color: var(--mc-gray);">Sep 21, 2025</span>
                        </span>
                        <span>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Medical Records</h3>
                <select>
                    <option>All Types</option>
                    <option>Consultations</option>
                    <option>Lab Results</option>
                    <option>Diagnostics</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Doctor/Provider</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nov 12, 2025</td>
                        <td>Consultation</td>
                        <td>Dr. Jane Cooper</td>
                        <td>Cardiology Consultation</td>
                        <td><span class="badge cyan">Completed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 10, 2025</td>
                        <td>Lab Results</td>
                        <td>Lab Services</td>
                        <td>Complete Blood Count</td>
                        <td><span class="badge cyan">Available</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Nov 8, 2025</td>
                        <td>Consultation</td>
                        <td>Dr. Sarah Wilson</td>
                        <td>General Check-up</td>
                        <td><span class="badge cyan">Completed</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Download</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Oct 15, 2025</td>
                        <td>Lab Results</td>
                        <td>Lab Services</td>
                        <td>Cholesterol Panel</td>
                        <td><span class="badge cyan">Available</span></td>
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
