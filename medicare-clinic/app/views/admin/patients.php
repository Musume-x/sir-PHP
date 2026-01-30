<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_admin_sidebar();

$patients = [];
if ($pdo) {
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE role = 'patient' ORDER BY id");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$patientCount = count($patients);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Patient Management</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search patients..." />
                </div>
                <button class="btn-primary">+ Register Patient</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-4">
            <div class="summary-card">
                <h4>Total Patients</h4>
                <div class="summary-value"><?php echo $patientCount; ?></div>
                <p class="summary-change">Example user</p>
            </div>
            <div class="summary-card">
                <h4>New This Week</h4>
                <div class="summary-value"><?php echo $patientCount; ?></div>
                <p class="summary-change">—</p>
            </div>
            <div class="summary-card">
                <h4>Active Today</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">Appointments</p>
            </div>
            <div class="summary-card">
                <h4>Pending</h4>
                <div class="summary-value">0</div>
                <p class="summary-change">Registrations</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Patients</h3>
                <div style="display: flex; gap: 12px;">
                    <select>
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                    <select>
                        <option>Sort by: Recent</option>
                        <option>Name A-Z</option>
                        <option>Registration Date</option>
                    </select>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $i => $p): ?>
                    <tr>
                        <td>#MC-<?php echo str_pad((string)($i + 1), 3, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">View</button>
                            <button class="btn-outline small">Edit</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($patients)): ?>
                    <tr><td colspan="5">No patients in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
