<?php
require __DIR__ . '/../layouts/admin_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_admin_sidebar();

$users = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY role, id");
    $users = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>User Management</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search users..." />
                </div>
                <a class="btn-primary" href="index.php?page=admin-register">+ Add User</a>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="panel">
            <div class="panel-header">
                <h3>All Users</h3>
                <select>
                    <option>All Roles</option>
                    <option>Admin</option>
                    <option>Doctor</option>
                    <option>Nurse</option>
                    <option>Receptionist</option>
                    <option>Patient</option>
                </select>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars(ucfirst($u['role'])); ?></span></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>—</td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Deactivate</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                    <tr><td colspan="6">No users in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
