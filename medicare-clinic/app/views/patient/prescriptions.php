<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$prescriptions = [];
$pendingRequests = [];
$doctors = [];
if ($pdo && $user) {
    $pid = (int) $user['id'];
    
    // Fetch official prescriptions
    $stmt = $pdo->prepare("
        SELECT p.*, u.name as doctor_name 
        FROM prescriptions p 
        JOIN users u ON p.doctor_id = u.id 
        WHERE p.patient_id = ? 
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$pid]);
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch pending requests
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as doctor_name 
        FROM prescription_requests r 
        JOIN users u ON r.doctor_id = u.id 
        WHERE r.patient_id = ? AND r.status = 'pending'
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$pid]);
    $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch doctors for the request form
    $stmt = $pdo->query("SELECT id, name, department FROM users WHERE role = 'doctor' ORDER BY name");
    $doctors = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
$requested = !empty($_GET['requested']);
$error = $_GET['error'] ?? '';
?>
<style>
.summary-item strong {
    color: var(--mc-dark);
}
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>My Prescriptions</h1>
            <div class="header-right">
                <button class="btn-primary" onclick="document.getElementById('request-modal').style.display='flex'">+ Request Prescription</button>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <?php if ($requested): ?>
            <p class="auth-success" style="margin: 20px; padding: 15px; background: #d4edda; color: #155724; border-radius: 8px; border: 1px solid #c3e6cb;">
                ✅ Prescription request sent successfully! Your doctor will review it shortly.
            </p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="auth-error" style="margin: 20px; padding: 15px; background: #f8d7da; color: #721c24; border-radius: 8px; border: 1px solid #f5c6cb;">
                ❌ Error: <?php echo htmlspecialchars($error === 'missing_fields' ? 'Please fill in all required fields.' : 'Something went wrong. Please try again.'); ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($pendingRequests)): ?>
            <section class="panel" style="margin-bottom: 24px; border: 1px solid var(--mc-light-blue);">
                <div class="panel-header" style="background: var(--mc-light-blue);">
                    <h3 style="color: var(--mc-blue);">Pending Requests</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Doctor</th>
                            <th>Reason</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingRequests as $r): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($r['medication_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($r['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['reason'] ?: '—'); ?></td>
                            <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                            <td><span class="badge">Pending</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-header">
                <h3>All Prescriptions</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Medication</th>
                        <th>Doctor</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prescriptions as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($p['medication']); ?></td>
                        <td><?php echo htmlspecialchars($p['doctor_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($p['dosage']); ?></td>
                        <td><?php echo (int)($p['duration_days'] ?? 0); ?> days</td>
                        <td>
                            <span class="badge <?php echo $p['status'] === 'active' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($p['status']); ?></span>
                            <?php if (!empty($p['refill_requested'])): ?>
                                <br><span style="font-size:0.8rem;">Refill requested</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?page=patient-view&type=prescription&id=<?php echo (int)$p['id']; ?>" class="btn-outline small">View</a>
                            <?php if ($p['status'] === 'active'): ?>
                                <?php if (empty($p['refill_requested'])): ?>
                                    <a href="index.php?page=patient-prescriptions&request_refill=<?php echo (int)$p['id']; ?>" class="btn-outline small">Request refill</a>
                                <?php elseif (!empty($p['refill_approved'])): ?>
                                    <span class="badge cyan small">Refill approved</span>
                                <?php else: ?>
                                    <span class="btn-outline small" style="pointer-events:none;opacity:0.85;">Awaiting doctor</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="index.php?page=patient-download&type=prescription&id=<?php echo (int)$p['id']; ?>" class="btn-outline small">Download</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($prescriptions)): ?>
                    <tr><td colspan="7">No prescriptions yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- Request Modal -->
<div id="request-modal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); align-items:center; justify-content:center;">
    <div class="modal-content" style="background-color:#fff; padding:30px; border-radius:12px; width:450px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); position:relative; animation: modalFadeIn 0.3s ease-out;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
            <h3 style="margin:0; color: var(--mc-blue);">Request New Prescription</h3>
            <span style="cursor:pointer; font-size:28px; color: #999;" onclick="document.getElementById('request-modal').style.display='none'">&times;</span>
        </div>
        <form method="post" action="index.php?page=patient-prescriptions">
            <input type="hidden" name="request_new_prescription" value="1" />
            
            <div class="form-group">
                <label>Select Doctor</label>
                <select name="doctor_id" required style="width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ddd;">
                    <option value="">Choose a doctor</option>
                    <?php foreach ($doctors as $d): ?>
                        <option value="<?php echo $d['id']; ?>">
                            <?php echo htmlspecialchars($d['name'] . ($d['department'] ? " ({$d['department']})" : "")); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label>Medication Name</label>
                <input type="text" name="medication_name" placeholder="e.g. Amoxicillin" required style="width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ddd;" />
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label>Reason / Symptoms</label>
                <textarea name="reason" rows="3" placeholder="Describe why you need this medication..." style="width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ddd;"></textarea>
            </div>

            <div style="margin-top:20px; display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1;">Send Request</button>
                <button type="button" class="btn-outline" style="flex:1;" onclick="document.getElementById('request-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>
