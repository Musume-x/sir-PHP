<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
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

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Select Doctor</h3>
                </div>
                <div style="padding: 20px;">
                    <div class="form-group">
                        <label>Department</label>
                        <select>
                            <option>All Departments</option>
                            <option>Cardiology</option>
                            <option>Pediatrics</option>
                            <option>Orthopedics</option>
                            <option>General Medicine</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doctor</label>
                        <select>
                            <option>Select a doctor</option>
                            <option>Dr. Jane Cooper - Cardiology</option>
                            <option>Dr. Robert Smith - Pediatrics</option>
                            <option>Dr. Michael Brown - Orthopedics</option>
                            <option>Dr. Sarah Wilson - General Medicine</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reason for Visit</label>
                        <textarea placeholder="Describe your symptoms or reason for appointment..."></textarea>
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
                        <input type="date" />
                    </div>
                    <div class="form-group">
                        <label>Preferred Time</label>
                        <select>
                            <option>Select time</option>
                            <option>08:00 AM</option>
                            <option>09:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>02:00 PM</option>
                            <option>03:00 PM</option>
                            <option>04:00 PM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <select>
                            <option>30 minutes</option>
                            <option>1 hour</option>
                            <option>2 hours</option>
                        </select>
                    </div>
                    <div style="margin-top: 20px;">
                        <button class="btn-primary full">Book Appointment</button>
                        <button class="btn-outline full" style="margin-top: 10px;">Check Availability</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>Available Time Slots - Dr. Jane Cooper</h3>
                <span>Nov 15, 2025</span>
            </div>
            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;">
                    <button class="btn-outline">08:00 AM</button>
                    <button class="btn-outline">09:00 AM</button>
                    <button class="btn-outline">10:00 AM</button>
                    <button class="btn-outline">11:00 AM</button>
                    <button class="btn-outline" style="opacity: 0.5; cursor: not-allowed;">02:00 PM</button>
                    <button class="btn-outline">03:00 PM</button>
                    <button class="btn-outline">04:00 PM</button>
                </div>
            </div>
        </section>
    </main>
</div>
