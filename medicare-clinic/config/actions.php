<?php
/**
 * Centralized action handlers. Requires auth.php and database.php to be loaded.
 * Each function returns redirect URL or false to continue to page.
 */

function handle_patient_book(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['request_appointment'])) {
        return null;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'patient') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return 'index.php?page=patient-book&error=1';
    }
    $patient_id = (int) $user['id'];
    $doctor_id = (int) ($_POST['doctor_id'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');
    $department = trim($_POST['department'] ?? 'General');
    if (!$doctor_id) {
        return 'index.php?page=patient-book&error=1';
    }
    $stmt = $pdo->prepare("INSERT INTO appointment_requests (patient_id, doctor_id, reason, department, status) VALUES (?,?,?,?, 'pending')");
    $stmt->execute([$patient_id, $doctor_id, $reason, $department]);
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
    $stmt->execute([$patient_id, 'Appointment Requested', "Your appointment request has been sent. The doctor will schedule the date and time.", 'appointment']);
    return 'index.php?page=patient-appointments&requested=1';
}

function handle_patient_billing_pay(): ?string
{
    $pay = (int) ($_GET['pay'] ?? $_POST['pay'] ?? 0);
    if (!$pay) {
        return null;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'patient') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return 'index.php?page=patient-billing&error=1';
    }
    $stmt = $pdo->prepare("UPDATE invoices SET status = 'paid', paid_at = datetime('now') WHERE id = ? AND patient_id = ?");
    $stmt->execute([$pay, $user['id']]);
    if ($stmt->rowCount()) {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
        $stmt->execute([$user['id'], 'Payment Received', "Payment for invoice #$pay has been recorded.", 'billing']);
    }
    return 'index.php?page=patient-billing&paid=1';
}

function handle_patient_notification_read(): ?string
{
    $mark_read = (int) ($_GET['mark_read'] ?? $_POST['mark_read'] ?? 0);
    $mark_all = !empty($_GET['mark_all']) || !empty($_POST['mark_all']);
    $user = current_user();
    if (!$user) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return null;
    }
    if ($mark_all) {
        $stmt = $pdo->prepare("UPDATE notifications SET read_at = datetime('now') WHERE user_id = ? AND read_at IS NULL");
        $stmt->execute([$user['id']]);
    } elseif ($mark_read) {
        $stmt = $pdo->prepare("UPDATE notifications SET read_at = datetime('now') WHERE id = ? AND user_id = ?");
        $stmt->execute([$mark_read, $user['id']]);
    } else {
        return null;
    }
    return 'index.php?page=patient-notifications&read=1';
}

function handle_patient_profile_save(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['save_profile'])) {
        return null;
    }
    $user = current_user();
    if (!$user) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return 'index.php?page=patient-profile&error=1';
    }
    $name = trim($_POST['name'] ?? $user['name']);
    $phone = trim($_POST['phone'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $blood_type = trim($_POST['blood_type'] ?? '');
    $allergies = trim($_POST['allergies'] ?? '');
    $current_medications = trim($_POST['current_medications'] ?? '');
    $emergency_contact = trim($_POST['emergency_contact'] ?? '');
    $emergency_phone = trim($_POST['emergency_phone'] ?? '');
    $insurance_provider = trim($_POST['insurance_provider'] ?? '');
    $policy_number = trim($_POST['policy_number'] ?? '');
    $group_number = trim($_POST['group_number'] ?? '');
    $insurance_expiry = trim($_POST['insurance_expiry'] ?? '');
    $email_notifications = !empty($_POST['email_notifications']) ? 1 : 0;
    $sms_notifications = !empty($_POST['sms_notifications']) ? 1 : 0;
    $appointment_reminders = !empty($_POST['appointment_reminders']) ? 1 : 0;

    $pdo->prepare("UPDATE users SET name = ? WHERE id = ?")->execute([$name, $user['id']]);
    $pdo->prepare("INSERT OR REPLACE INTO user_profiles (user_id, phone, date_of_birth, gender, address, blood_type, allergies, current_medications, emergency_contact, emergency_phone, insurance_provider, policy_number, group_number, insurance_expiry, email_notifications, sms_notifications, appointment_reminders, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, datetime('now'))")
        ->execute([$user['id'], $phone, $date_of_birth, $gender, $address, $blood_type, $allergies, $current_medications, $emergency_contact, $emergency_phone, $insurance_provider, $policy_number, $group_number, $insurance_expiry, $email_notifications, $sms_notifications, $appointment_reminders]);
    return 'index.php?page=patient-profile&saved=1';
}

function handle_patient_prescription_refill(): ?string
{
    $request_refill = (int) ($_GET['request_refill'] ?? $_POST['request_refill'] ?? 0);
    $refill = (int) ($_GET['refill'] ?? $_POST['refill'] ?? 0);
    $user = current_user();
    if (!$user) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return null;
    }
    if ($request_refill) {
        $stmt = $pdo->prepare("UPDATE prescriptions SET refill_requested = 1 WHERE id = ? AND patient_id = ?");
        $stmt->execute([$request_refill, $user['id']]);
        return 'index.php?page=patient-prescriptions&requested=1';
    }
    if ($refill) {
        $stmt = $pdo->prepare("UPDATE prescriptions SET refill_requested = 0, refill_approved = 1 WHERE id = ? AND patient_id = ?");
        $stmt->execute([$refill, $user['id']]);
        return 'index.php?page=patient-prescriptions&refilled=1';
    }
    return null;
}

function handle_staff_prescription_approve_refill(): ?string
{
    $approve = (int) ($_GET['approve_refill'] ?? $_POST['approve_refill'] ?? 0);
    $user = current_user();
    if (!$user || !in_array($user['role'], ['doctor', 'nurse', 'receptionist'], true)) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo || !$approve) {
        return null;
    }
    $stmt = $pdo->prepare("UPDATE prescriptions SET refill_approved = 1, refill_requested = 0 WHERE id = ?");
    $stmt->execute([$approve]);
    $row = $pdo->query("SELECT patient_id FROM prescriptions WHERE id = $approve")->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)")
            ->execute([$row['patient_id'], 'Refill Approved', 'Your prescription refill request has been approved.', 'prescription']);
    }
    return 'index.php?page=staff-prescriptions&approved=1';
}

function handle_staff_profile_save(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['save_staff_profile'])) {
        return null;
    }
    $user = current_user();
    if (!$user || !in_array($user['role'], ['doctor', 'nurse', 'receptionist'], true)) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return 'index.php?page=staff-profile&error=1';
    }
    $name = trim($_POST['name'] ?? $user['name']);
    $phone = trim($_POST['phone'] ?? '');
    $pdo->prepare("UPDATE users SET name = ? WHERE id = ?")->execute([$name, $user['id']]);
    $pdo->prepare("INSERT INTO user_profiles (user_id, phone, updated_at) VALUES (?, ?, datetime('now')) ON CONFLICT(user_id) DO UPDATE SET phone = excluded.phone, updated_at = datetime('now')")->execute([$user['id'], $phone]);
    return 'index.php?page=staff-profile&saved=1';
}

function handle_doctor_schedule_request(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['schedule_request'])) {
        return null;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'doctor') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return 'index.php?page=staff-requests&error=1';
    }

    $requestId = (int) ($_POST['request_id'] ?? 0);
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');
    if (!$requestId || !$date || !$time) {
        return 'index.php?page=staff-requests&error=1';
    }

    $stmt = $pdo->prepare("SELECT * FROM appointment_requests WHERE id = ? AND doctor_id = ? AND status = 'pending' LIMIT 1");
    $stmt->execute([$requestId, (int) $user['id']]);
    $req = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$req) {
        return 'index.php?page=staff-requests&error=1';
    }

    $patientId = (int) ($req['patient_id'] ?? 0);
    $doctorId = (int) ($req['doctor_id'] ?? 0);
    $reason = (string) ($req['reason'] ?? '');
    $department = (string) ($req['department'] ?? 'General');

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, department, status) VALUES (?,?,?,?,?,?,'confirmed')");
    $stmt->execute([$patientId, $doctorId, $date, $time, $reason, $department]);
    $appointmentId = (int) $pdo->lastInsertId();

    $pdo->prepare("UPDATE appointment_requests SET status = 'scheduled', scheduled_appointment_id = ?, scheduled_at = datetime('now') WHERE id = ?")
        ->execute([$appointmentId, $requestId]);

    $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)")
        ->execute([$patientId, 'Appointment Scheduled', "Your appointment has been scheduled on $date at $time.", 'appointment']);

    return 'index.php?page=staff-requests&scheduled=1';
}

function handle_admin_action(): ?string
{
    $user = current_user();
    if (!$user || $user['role'] !== 'admin') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    if (!$pdo) {
        return null;
    }
    if (!empty($_GET['pay_invoice'])) {
        $id = (int) $_GET['pay_invoice'];
        $pdo->prepare("UPDATE invoices SET status = 'paid', paid_at = datetime('now') WHERE id = ?")->execute([$id]);
        return 'index.php?page=admin-billing&paid=1';
    }
    if (!empty($_GET['mark_notification_read'])) {
        $id = (int) $_GET['mark_notification_read'];
        $pdo->prepare("UPDATE notifications SET read_at = datetime('now') WHERE id = ?")->execute([$id]);
        return null;
    }
    return null;
}
