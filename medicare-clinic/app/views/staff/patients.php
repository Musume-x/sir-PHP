<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
$patients = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE role = 'patient' ORDER BY name");
    $patients = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Patients</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search patients..." />
                </div>
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <section class="panel">
            <div class="panel-header">
                <h3>Patients</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                        <td><?php echo htmlspecialchars($p['created_at'] ?? '—'); ?></td>
                        <td><span class="btn-outline small">View Chart</span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($patients)): ?>
                    <tr><td colspan="4">No patients yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
