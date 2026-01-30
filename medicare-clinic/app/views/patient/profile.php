<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$profile = [];
if ($pdo && $user) {
    $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}
$saved = !empty($_GET['saved']);
$error = !empty($_GET['error']);
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

        <?php if ($saved): ?>
            <p class="auth-success">Profile saved successfully.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="auth-error">Failed to save. Try again.</p>
        <?php endif; ?>

        <form method="post" action="index.php?page=patient-profile" class="settings-form">
            <input type="hidden" name="save_profile" value="1" />
            <section class="grid-2">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Personal Information</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled title="Email cannot be changed" />
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($profile['date_of_birth'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">—</option>
                                <option value="Male" <?php echo ($profile['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($profile['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($profile['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                                <option value="Prefer not to say" <?php echo ($profile['gender'] ?? '') === 'Prefer not to say' ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address"><?php echo htmlspecialchars($profile['address'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h3>Medical Information</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label>Blood Type</label>
                            <select name="blood_type">
                                <option value="">—</option>
                                <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt): ?>
                                    <option value="<?php echo $bt; ?>" <?php echo ($profile['blood_type'] ?? '') === $bt ? 'selected' : ''; ?>><?php echo $bt; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Allergies</label>
                            <textarea name="allergies" placeholder="List any known allergies..."><?php echo htmlspecialchars($profile['allergies'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Current Medications</label>
                            <textarea name="current_medications" placeholder="List current medications..."><?php echo htmlspecialchars($profile['current_medications'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Emergency Contact</label>
                            <input type="text" name="emergency_contact" placeholder="Name" value="<?php echo htmlspecialchars($profile['emergency_contact'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Emergency Contact Phone</label>
                            <input type="tel" name="emergency_phone" placeholder="(555) 123-4567" value="<?php echo htmlspecialchars($profile['emergency_phone'] ?? ''); ?>" />
                        </div>
                        <button type="submit" class="btn-primary">Save Medical Info</button>
                    </div>
                </div>
            </section>

            <section class="grid-2">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Account Settings</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label>Notification Preferences</label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="email_notifications" value="1" <?php echo ($profile['email_notifications'] ?? 1) ? 'checked' : ''; ?> /> Email notifications
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="sms_notifications" value="1" <?php echo ($profile['sms_notifications'] ?? 1) ? 'checked' : ''; ?> /> SMS notifications
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="appointment_reminders" value="1" <?php echo ($profile['appointment_reminders'] ?? 1) ? 'checked' : ''; ?> /> Appointment reminders
                            </label>
                        </div>
                        <button type="submit" class="btn-primary">Update Settings</button>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h3>Insurance Information</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label>Insurance Provider</label>
                            <input type="text" name="insurance_provider" placeholder="Insurance company name" value="<?php echo htmlspecialchars($profile['insurance_provider'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Policy Number</label>
                            <input type="text" name="policy_number" placeholder="Policy number" value="<?php echo htmlspecialchars($profile['policy_number'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Group Number</label>
                            <input type="text" name="group_number" placeholder="Group number (if applicable)" value="<?php echo htmlspecialchars($profile['group_number'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" name="insurance_expiry" value="<?php echo htmlspecialchars($profile['insurance_expiry'] ?? ''); ?>" />
                        </div>
                        <button type="submit" class="btn-primary">Save Insurance Info</button>
                    </div>
                </div>
            </section>
        </form>
    </main>
</div>
