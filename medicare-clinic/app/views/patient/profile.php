<?php 
require __DIR__ . '/../layouts/patient_sidebar.php';
$user = current_user();
$sidebar = render_patient_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>My Profile</h1>
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
                    <h3>Personal Information</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?>" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="patient@email.com" />
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" value="(555) 123-4567" />
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" value="1985-05-15" />
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                            <option>Prefer not to say</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea>123 Main Street, City, State 12345</textarea>
                    </div>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Medical Information</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Blood Type</label>
                        <select>
                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                            <option>O+</option>
                            <option>O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Allergies</label>
                        <textarea placeholder="List any known allergies..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Current Medications</label>
                        <textarea placeholder="List current medications..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Emergency Contact</label>
                        <input type="text" placeholder="Name" />
                    </div>
                    <div class="form-group">
                        <label>Emergency Contact Phone</label>
                        <input type="tel" placeholder="(555) 123-4567" />
                    </div>
                    <button type="submit" class="btn-primary">Save Medical Info</button>
                </form>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Account Settings</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Change Password</label>
                        <input type="password" placeholder="Enter new password" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" placeholder="Confirm new password" />
                    </div>
                    <div class="form-group">
                        <label>Notification Preferences</label>
                        <label class="checkbox-label">
                            <input type="checkbox" checked /> Email notifications
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" checked /> SMS notifications
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" /> Appointment reminders
                        </label>
                    </div>
                    <button type="submit" class="btn-primary">Update Settings</button>
                </form>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Insurance Information</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Insurance Provider</label>
                        <input type="text" placeholder="Insurance company name" />
                    </div>
                    <div class="form-group">
                        <label>Policy Number</label>
                        <input type="text" placeholder="Policy number" />
                    </div>
                    <div class="form-group">
                        <label>Group Number</label>
                        <input type="text" placeholder="Group number (if applicable)" />
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" />
                    </div>
                    <button type="submit" class="btn-primary">Save Insurance Info</button>
                </form>
            </div>
        </section>
    </main>
</div>
