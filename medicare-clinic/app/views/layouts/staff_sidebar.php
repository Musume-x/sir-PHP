<?php
function render_staff_sidebar(): string
{
    $rawRole = current_role() ?? 'staff';
    $role = ucfirst($rawRole);
    $current_page = $_GET['page'] ?? 'staff';
    $dashboardPage = 'staff';
    if ($rawRole === 'receptionist') {
        $dashboardPage = 'receptionist';
    }

    ob_start();
    ?>
<aside class="sidebar">
    <div class="sidebar-logo">MediCare</div>
    <nav class="sidebar-nav">
        <a href="index.php?page=<?php echo htmlspecialchars($dashboardPage); ?>" class="<?php echo $current_page === $dashboardPage ? 'active' : ''; ?>">Dashboard</a>
        <?php if (($rawRole ?? '') === 'doctor'): ?>
            <a href="index.php?page=staff-requests" class="<?php echo $current_page === 'staff-requests' ? 'active' : ''; ?>">Requests</a>
        <?php endif; ?>
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

