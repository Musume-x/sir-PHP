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
            <p class="auth-success">Appointment booked successfully. Check My Appointments.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="auth-error">Please select a doctor, date, and time.</p>
        <?php endif; ?>

        <form method="post" action="index.php?page=patient-book">
            <input type="hidden" name="book_appointment" value="1" />
            <section class="grid-2">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Select Doctor</h3>
                    </div>
                    <div style="padding: 20px;">
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
                                    <option value="<?php echo (int)$d['id']; ?>"><?php echo htmlspecialchars($d['name'] . ($d['department'] ? ' - ' . $d['department'] : '')); ?></option>
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
                        <h3>Select Date & Time</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label>Preferred Date</label>
                            <input type="date" name="appointment_date" required />
                        </div>
                        <div class="form-group">
                            <label>Preferred Time</label>
                            <select name="appointment_time" required>
                                <option value="">Select time</option>
                                <option value="08:00">08:00 AM</option>
                                <option value="09:00">09:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="14:00">02:00 PM</option>
                                <option value="15:00">03:00 PM</option>
                                <option value="16:00">04:00 PM</option>
                            </select>
                        </div>
                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn-primary full">Book Appointment</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </main>
</div>
