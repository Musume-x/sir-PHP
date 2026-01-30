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
            <h1><?php echo $role; ?> Medical Records</h1>
            <div class="header-right">
                <button class="btn-primary">+ New Note</button>
            </div>
        </header>

        <section class="panel">
            <div class="panel-header">
                <h3>Recent Records</h3>
                <select>
                    <option>All Types</option>
                    <option>Consultations</option>
                    <option>Lab Results</option>
                    <option>Prescriptions</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Summary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Michael Brown</td>
                        <td>Consultation</td>
                        <td>Nov 12, 2025</td>
                        <td>Follow‑up for chest pain, medication adjusted.</td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                    <tr>
                        <td>Sarah Johnson</td>
                        <td>Lab Results</td>
                        <td>Nov 10, 2025</td>
                        <td>Blood work stable, continue current plan.</td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                    <tr>
                        <td>David Lee</td>
                        <td>Consultation</td>
                        <td>Nov 8, 2025</td>
                        <td>Discussed lifestyle changes, schedule follow‑up.</td>
                        <td><button class="btn-outline small">View</button></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>

