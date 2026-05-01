<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$rawRole = current_role() ?? '';
$sidebar = render_staff_sidebar();
$prescriptions = [];
if ($pdo) {
    if ($rawRole === 'doctor' && !empty($user['id'])) {
        $stmt = $pdo->prepare("
            SELECT p.*, u1.name as patient_name, u2.name as doctor_name
            FROM prescriptions p
            JOIN users u1 ON p.patient_id = u1.id
            JOIN users u2 ON p.doctor_id = u2.id
            WHERE p.doctor_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([(int) $user['id']]);
        $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("
            SELECT p.*, u1.name as patient_name, u2.name as doctor_name
            FROM prescriptions p
            JOIN users u1 ON p.patient_id = u1.id
            JOIN users u2 ON p.doctor_id = u2.id
            ORDER BY p.created_at DESC
        ");
        $prescriptions = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
$approved = !empty($_GET['approved']);
$rejected = !empty($_GET['rejected']);
$approveError = !empty($_GET['error']);
$requests = [];
if ($pdo && $rawRole === 'doctor') {
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as patient_name 
        FROM prescription_requests r 
        JOIN users u ON r.patient_id = u.id 
        WHERE r.doctor_id = ? AND r.status = 'pending'
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([(int)$user['id']]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Prescriptions</h1>
            <div class="header-right">
                <div class="user-info">
                    <span class="role"><?php echo $role; ?></span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? $role); ?></span>
                </div>
            </div>
        </header>

        <?php if ($approved): ?>
            <p class="auth-success">Action processed successfully.</p>
        <?php endif; ?>
        <?php if ($rejected): ?>
            <p class="auth-success">Request rejected successfully.</p>
        <?php endif; ?>
        <?php if ($approveError): ?>
            <p class="auth-error">Unable to process. Please check permissions.</p>
        <?php endif; ?>

        <?php if (!empty($requests)): ?>
            <section class="panel" style="margin-bottom: 24px;">
                <div class="panel-header">
                    <h3>New Prescription Requests</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Medication</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['medication_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['reason'] ?: '—'); ?></td>
                            <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                            <td style="display:flex; gap:10px;">
                                <button class="btn-primary small" onclick="openIssueModal(<?php echo (int)$r['id']; ?>, '<?php echo addslashes($r['patient_name']); ?>', '<?php echo addslashes($r['medication_name']); ?>')">Issue</button>
                                <a href="index.php?page=staff-prescriptions&reject_prescription_request=<?php echo (int)$r['id']; ?>" class="btn-outline small" style="color: var(--mc-red); border-color: var(--mc-red);" onclick="return confirm('Are you sure you want to reject this request?')">Reject</a>
                            </td>
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
                        <th>Patient</th>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Refill</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prescriptions as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['patient_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($p['medication']); ?></td>
                        <td><?php echo htmlspecialchars($p['dosage']); ?></td>
                        <td><?php echo (int)($p['duration_days'] ?? 0); ?> days</td>
                        <td><span class="badge <?php echo $p['status'] === 'active' ? 'cyan' : ''; ?>"><?php echo htmlspecialchars($p['status']); ?></span></td>
                        <td>
                            <?php if (!empty($p['refill_requested']) && empty($p['refill_approved'])): ?>
                                <span class="badge">Requested</span>
                            <?php elseif (!empty($p['refill_approved'])): ?>
                                <span class="badge cyan">Approved</span>
                            <?php else: ?>
                                — 
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $canApproveRefill = $rawRole === 'doctor'
                                && (int) ($p['doctor_id'] ?? 0) === (int) ($user['id'] ?? 0)
                                && !empty($p['refill_requested'])
                                && empty($p['refill_approved']);
                            ?>
                            <?php if ($canApproveRefill): ?>
                                <a href="index.php?page=staff-prescriptions&approve_refill=<?php echo (int)$p['id']; ?>" class="btn-primary small">Approve Refill</a>
                            <?php elseif (!empty($p['refill_requested']) && empty($p['refill_approved']) && $rawRole === 'receptionist'): ?>
                                <span class="btn-outline small" title="Only the prescribing doctor can approve">Pending doctor</span>
                            <?php else: ?>
                                <span class="btn-outline small">—</span>
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

<!-- Issue Prescription Modal -->
<div id="issue-modal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color:#fff; margin:10% auto; padding:20px; border-radius:10px; width:450px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Issue Prescription</h3>
            <span style="cursor:pointer; font-size:24px;" onclick="document.getElementById('issue-modal').style.display='none'">&times;</span>
        </div>
        <form method="post" action="index.php?page=staff-prescriptions">
            <input type="hidden" name="issue_from_request" value="1" />
            <input type="hidden" id="modal-request-id" name="request_id" value="" />
            
            <p>Patient: <strong id="modal-patient-name"></strong></p>
            
            <div class="form-group" style="margin-top:15px;">
                <label>Medication</label>
                <input type="text" id="modal-medication" name="medication" required style="width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ddd;" />
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label>Dosage Instructions</label>
                <input type="text" name="dosage" placeholder="e.g. 500mg, twice daily after meals" required style="width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ddd;" />
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label>Duration (days)</label>
                <input type="number" name="duration" value="7" required style="width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ddd;" />
            </div>

            <div style="margin-top:20px; display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1;">Confirm & Issue</button>
                <button type="button" class="btn-outline" style="flex:1;" onclick="document.getElementById('issue-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openIssueModal(requestId, patientName, medication) {
    document.getElementById('modal-request-id').value = requestId;
    document.getElementById('modal-patient-name').innerText = patientName;
    document.getElementById('modal-medication').value = medication;
    document.getElementById('issue-modal').style.display = 'block';
}
</script>
