<?php
function render_staff_sidebar(): string
{
    $role = ucfirst(current_role() ?? 'Staff');
    $current_page = $_GET['page'] ?? 'staff';

    ob_start();
    ?>
<aside class="sidebar">
    <div class="sidebar-logo">MediCare</div>
    <nav class="sidebar-nav">
        <a href="index.php?page=staff" class="<?php echo $current_page === 'staff' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=staff-appointments" class="<?php echo $current_page === 'staff-appointments' ? 'active' : ''; ?>">Appointments</a>
        <a href="index.php?page=staff-patients" class="<?php echo $current_page === 'staff-patients' ? 'active' : ''; ?>">Patients</a>
        <a href="index.php?page=staff-records" class="<?php echo $current_page === 'staff-records' ? 'active' : ''; ?>">Medical Records</a>
        <a href="index.php?page=staff-prescriptions" class="<?php echo $current_page === 'staff-prescriptions' ? 'active' : ''; ?>">Prescriptions</a>
        <a href="index.php?page=staff-billing" class="<?php echo $current_page === 'staff-billing' ? 'active' : ''; ?>">Billing</a>
        <a href="index.php?page=staff-profile" class="<?php echo $current_page === 'staff-profile' ? 'active' : ''; ?>">Profile</a>
        <a href="index.php?page=logout">Logout</a>
    </nav>
</aside>
    <?php
    return ob_get_clean();
}

