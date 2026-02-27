<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$doctors = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT id, name, department FROM users WHERE role = 'doctor' ORDER BY name");
    $doctors = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
$success = !empty($_GET['success']);
$error = !empty($_GET['error']);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Book Appointment</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <?php if ($success): ?>
            <p class="auth-success">Request sent. The doctor will choose your appointment date and time.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="auth-error">Please select a doctor.</p>
        <?php endif; ?>

        <form method="post" action="index.php?page=patient-book">
            <input type="hidden" name="request_appointment" value="1" />
            <section class="grid-2">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Select Doctor</h3>
                    </div>
                    <div style="padding: 20px;">
                        <p style="margin: 0 0 14px; color: var(--mc-gray);">
                            Submit a request and the doctor will schedule the date and time for you.
                        </p>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department">
                                <option value="General">General</option>
                                <option value="Cardiology">Cardiology</option>
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Orthopedics">Orthopedics</option>
                                <option value="General Medicine">General Medicine</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Doctor</label>
                            <select name="doctor_id" required>
                                <option value="">Select a doctor</option>
                                <?php foreach ($doctors as $d): ?>
                                    <option
                                        value="<?php echo (int)$d['id']; ?>"
                                        class="doctor-option<?php echo $d['department'] ? ' dept-' . strtolower(str_replace(' ', '-', $d['department'])) : ''; ?>"
                                    >
                                        <?php echo htmlspecialchars($d['name'] . ($d['department'] ? ' - ' . $d['department'] : '')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Reason for Visit</label>
                            <textarea name="reason" placeholder="Describe your symptoms or reason for appointment..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h3>Submit Request</h3>
                    </div>
                    <div style="padding: 20px;">
                        <p style="margin: 0 0 14px; color: var(--mc-gray);">
                            After you submit, you’ll see this request in <strong>My Appointments</strong> as pending until the doctor schedules it.
                        </p>
                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn-primary full">Send Request</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </main>
</div>
