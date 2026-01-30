<?php 
function render_patient_sidebar() {
    $user = current_user();
    $current_page = $_GET['page'] ?? 'patient';
    ob_start();
    ?>
<aside class="sidebar">
    <div class="sidebar-logo">MediCare</div>
    <nav class="sidebar-nav">
        <a href="index.php?page=patient" class="<?php echo $current_page === 'patient' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=patient-book" class="<?php echo $current_page === 'patient-book' ? 'active' : ''; ?>">Book Appointment</a>
        <a href="index.php?page=patient-appointments" class="<?php echo $current_page === 'patient-appointments' ? 'active' : ''; ?>">My Appointments</a>
        <a href="index.php?page=patient-records" class="<?php echo $current_page === 'patient-records' ? 'active' : ''; ?>">Medical Records</a>
        <a href="index.php?page=patient-prescriptions" class="<?php echo $current_page === 'patient-prescriptions' ? 'active' : ''; ?>">Prescriptions</a>
        <a href="index.php?page=patient-billing" class="<?php echo $current_page === 'patient-billing' ? 'active' : ''; ?>">Billing</a>
        <a href="index.php?page=patient-notifications" class="<?php echo $current_page === 'patient-notifications' ? 'active' : ''; ?>">Notifications</a>
        <a href="index.php?page=patient-profile" class="<?php echo $current_page === 'patient-profile' ? 'active' : ''; ?>">Profile</a>
        <a href="index.php?page=logout">Logout</a>
    </nav>
</aside>
    <?php
    return ob_get_clean();
}
?>
