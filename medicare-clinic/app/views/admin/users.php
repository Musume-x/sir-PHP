<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
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
                <button class="btn-primary">+ Add User</button>
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
                    <tr>
                        <td>John Doe</td>
                        <td>john.doe@medicare.com</td>
                        <td><span class="badge">Admin</span></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>Today, 10:30 AM</td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Deactivate</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Dr. Jane Cooper</td>
                        <td>jane.cooper@medicare.com</td>
                        <td><span class="badge">Doctor</span></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>Today, 09:15 AM</td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Deactivate</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Sarah Wilson</td>
                        <td>sarah.wilson@medicare.com</td>
                        <td><span class="badge">Nurse</span></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>Yesterday, 4:20 PM</td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Deactivate</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Michael Brown</td>
                        <td>michael.brown@medicare.com</td>
                        <td><span class="badge">Patient</span></td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>Nov 10, 2:45 PM</td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Deactivate</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
