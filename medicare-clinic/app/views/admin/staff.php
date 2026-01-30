<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
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
                <button class="btn-primary">+ Add Staff</button>
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Total Doctors</h4>
                <div class="summary-value">24</div>
                <p class="summary-change">+2 this month</p>
            </div>
            <div class="summary-card">
                <h4>Total Nurses</h4>
                <div class="summary-value">18</div>
                <p class="summary-change">+1 this month</p>
            </div>
            <div class="summary-card">
                <h4>Receptionists</h4>
                <div class="summary-value">8</div>
                <p class="summary-change">No change</p>
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
                        <th>Specialty</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dr. Jane Cooper</td>
                        <td><span class="badge">Doctor</span></td>
                        <td>Cardiology</td>
                        <td>Cardiac Surgery</td>
                        <td>Mon-Fri, 9AM-5PM</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Schedule</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Dr. Robert Smith</td>
                        <td><span class="badge">Doctor</span></td>
                        <td>Pediatrics</td>
                        <td>Child Care</td>
                        <td>Mon-Wed, 8AM-4PM</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Schedule</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Sarah Wilson</td>
                        <td><span class="badge">Nurse</span></td>
                        <td>General</td>
                        <td>-</td>
                        <td>Mon-Fri, 7AM-3PM</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Assign</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Emily Davis</td>
                        <td><span class="badge">Receptionist</td>
                        <td>Front Desk</td>
                        <td>-</td>
                        <td>Mon-Fri, 8AM-6PM</td>
                        <td><span class="badge cyan">Active</span></td>
                        <td>
                            <button class="btn-outline small">Edit</button>
                            <button class="btn-outline small">Assign</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>
