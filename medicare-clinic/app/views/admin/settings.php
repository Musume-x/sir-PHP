<?php 
require __DIR__ . '/../layouts/admin_sidebar.php';
$user = current_user();
$sidebar = render_admin_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>System Settings</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role">Admin</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Clinic Information</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Clinic Name</label>
                        <input type="text" value="MediCare Clinic System" />
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" value="123 Medical Street, Health City" />
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" value="(555) 123-4567" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="info@medicare.com" />
                    </div>
                    <div class="form-group">
                        <label>Operating Hours</label>
                        <input type="text" value="Mon-Fri: 8:00 AM - 6:00 PM" />
                    </div>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Services Offered</h3>
                </div>
                <div class="services-list">
                    <label class="checkbox-label">
                        <input type="checkbox" checked /> General Check-ups
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" checked /> Diagnostics
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" checked /> Emergency Care
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" checked /> Laboratory Tests
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" checked /> Vaccinations
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" /> Physical Therapy
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" /> Mental Health
                    </label>
                </div>
                <button class="btn-primary" style="margin-top: 20px;">Update Services</button>
            </div>
        </section>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Security & Permissions</h3>
                </div>
                <form class="settings-form">
                    <div class="form-group">
                        <label>Session Timeout (minutes)</label>
                        <input type="number" value="30" />
                    </div>
                    <div class="form-group">
                        <label>Password Policy</label>
                        <select>
                            <option>Strong (8+ chars, mixed case, numbers)</option>
                            <option>Medium (6+ chars)</option>
                            <option>Weak (4+ chars)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Two-Factor Authentication</label>
                        <label class="checkbox-label">
                            <input type="checkbox" /> Enable 2FA for admins
                        </label>
                    </div>
                    <div class="form-group">
                        <label>IP Whitelist</label>
                        <textarea placeholder="Enter IP addresses (one per line)"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Save Security Settings</button>
                </form>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Backup & Recovery</h3>
                </div>
                <div class="backup-section">
                    <p><strong>Last Backup:</strong> Nov 11, 2025 at 2:00 AM</p>
                    <p><strong>Next Backup:</strong> Nov 12, 2025 at 2:00 AM</p>
                    <p><strong>Backup Frequency:</strong> Daily</p>
                    <div style="margin-top: 20px;">
                        <button class="btn-primary">Create Backup Now</button>
                        <button class="btn-outline" style="margin-left: 10px;">Restore Backup</button>
                    </div>
                    <div style="margin-top: 20px;">
                        <h4>Recent Backups</h4>
                        <ul class="list-table">
                            <li>
                                <span>Nov 11, 2025</span>
                                <span>2.4 GB</span>
                                <button class="btn-outline small">Download</button>
                            </li>
                            <li>
                                <span>Nov 10, 2025</span>
                                <span>2.3 GB</span>
                                <button class="btn-outline small">Download</button>
                            </li>
                            <li>
                                <span>Nov 9, 2025</span>
                                <span>2.4 GB</span>
                                <button class="btn-outline small">Download</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
