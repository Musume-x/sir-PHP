<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_admin_sidebar();

$staff = [];
if ($pdo) {
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE role IN ('doctor','nurse','receptionist') ORDER BY role");
    $stmt->execute();
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$counts = array_count_values(array_column($staff, 'role'));
$doctorCount = $counts['doctor'] ?? 0;
$nurseCount = $counts['nurse'] ?? 0;
$receptionistCount = $counts['receptionist'] ?? 0;
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Staff Management</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search staff..." />
                </div>
                <a class="btn-primary" href="index.php?page=admin-register">+ Add Staff</a>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Total Doctors</h4>
                <div class="summary-value"><?php echo $doctorCount; ?></div>
                <p class="summary-change">Example user</p>
            </div>
            <div class="summary-card">
                <h4>Total Nurses</h4>
                <div class="summary-value"><?php echo $nurseCount; ?></div>
                <p class="summary-change">Example user</p>
            </div>
            <div class="summary-card">
                <h4>Receptionists</h4>
                <div class="summary-value"><?php echo $receptionistCount; ?></div>
                <p class="summary-change">Example user</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>Staff Directory</h3>
                <select>
                    <option>All Staff</option>
                    <option>Doctors</option>
                    <option>Nurses</option>
                    <option>Receptionists</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $s): ?>
                    <tr class="<?php echo $s['role'] === 'doctor' ? 'doctor-row' : ($s['role'] === 'nurse' ? 'nurse-row' : 'receptionist-row'); ?>">
                        <td><?php echo htmlspecialchars($s['name']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars(ucfirst($s['role'])); ?></span></td>
                        <td><?php echo $s['role'] === 'doctor' ? 'General' : ($s['role'] === 'nurse' ? 'General' : 'Front Desk'); ?></td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Schedule</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($staff)): ?>
                    <tr><td colspan="6">No staff in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
