<?php 
function render_admin_sidebar() {
    $user = current_user();
    $current_page = $_GET['page'] ?? 'admin';
    ob_start();
    ?>
<aside class="sidebar">
    <div class="sidebar-logo">MediCare</div>
    <nav class="sidebar-nav">
        <a href="index.php?page=admin" class="<?php echo $current_page === 'admin' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=admin-users" class="<?php echo $current_page === 'admin-users' ? 'active' : ''; ?>">Users</a>
        <a href="index.php?page=admin-staff" class="<?php echo $current_page === 'admin-staff' ? 'active' : ''; ?>">Staff</a>
        <a href="index.php?page=admin-patients" class="<?php echo $current_page === 'admin-patients' ? 'active' : ''; ?>">Patients</a>
        <a href="index.php?page=admin-appointments" class="<?php echo $current_page === 'admin-appointments' ? 'active' : ''; ?>">Appointments</a>
        <a href="index.php?page=admin-records" class="<?php echo $current_page === 'admin-records' ? 'active' : ''; ?>">Medical Records</a>
        <a href="index.php?page=admin-billing" class="<?php echo $current_page === 'admin-billing' ? 'active' : ''; ?>">Billing</a>
        <a href="index.php?page=admin-reports" class="<?php echo $current_page === 'admin-reports' ? 'active' : ''; ?>">Reports</a>
        <a href="index.php?page=admin-settings" class="<?php echo $current_page === 'admin-settings' ? 'active' : ''; ?>">Settings</a>
        <a href="index.php?page=logout">Logout</a>
    </nav>
</aside>
    <?php
    return ob_get_clean();
}
?>
