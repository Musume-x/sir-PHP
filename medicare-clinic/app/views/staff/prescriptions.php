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
            <h1><?php echo $role; ?> Prescriptions</h1>
            <div class="header-right">
                <button class="btn-primary">+ New Prescription</button>
            </div>
        </header>

        <section class="panel">
            <div class="panel-header">
                <h3>Recent Prescriptions</h3>
                <select>
                    <option>All</option>
                    <option>Active</option>
                    <option>Expired</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Michael Brown</td>
                        <td>Atorvastatin</td>
                        <td>20 mg</td>
                        <td>Once daily · 30 days</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                    <tr>
                        <td>Sarah Johnson</td>
                        <td>Metformin</td>
                        <td>500 mg</td>
                        <td>Twice daily · 60 days</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                    <tr>
                        <td>David Lee</td>
                        <td>Ibuprofen</td>
                        <td>400 mg</td>
                        <td>As needed · 7 days</td>
                        <td><span class="badge">Expired</span></td>
                        <td><button class="btn-outline small">Renew</button></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>

