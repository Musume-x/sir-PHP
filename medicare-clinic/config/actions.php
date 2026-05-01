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
    $pdo = $GLOBALS['pdo'] ?? null;
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

    // Standard consultation fee
    $fee = 500.00;
    $invNo = 'INV-' . strtoupper(substr(uniqid(), -6));

    $stmt = $pdo->prepare("INSERT INTO appointment_requests (patient_id, doctor_id, reason, department, status) VALUES (?,?,?,?, 'pending')");
    $stmt->execute([$patient_id, $doctor_id, $reason, $department]);

    // Create an invoice for this appointment
    $stmt = $pdo->prepare("INSERT INTO invoices (patient_id, doctor_id, invoice_number, service, amount, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$patient_id, $doctor_id, $invNo, 'Consultation Fee (' . $department . ')', $fee]);

    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
    $stmt->execute([$patient_id, 'Appointment Requested', "Your request has been sent. Please pay the invoice ($invNo) for ₱$fee to proceed.", 'appointment']);

    return 'index.php?page=patient-appointments&requested=1';
}

function handle_patient_checkout(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['process_payment'])) {
        return null;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'patient') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return 'index.php?page=patient-billing&error=1';
    }

    $invoice_id = (int) ($_POST['invoice_id'] ?? 0);
    $method = trim($_POST['payment_method'] ?? 'Card');
    $ref = trim($_POST['transaction_ref'] ?? ('REF-' . strtoupper(substr(uniqid(), -8))));
    
    // Validate invoice
    $stmt = $pdo->prepare("SELECT amount, invoice_number FROM invoices WHERE id = ? AND patient_id = ? AND status != 'paid'");
    $stmt->execute([$invoice_id, $user['id']]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$invoice) {
        return 'index.php?page=patient-billing&error=invalid_invoice';
    }

    $amount = (float) $invoice['amount'];

    try {
        $pdo->beginTransaction();

        // 1. Record the payment
        $stmt = $pdo->prepare("INSERT INTO payments (invoice_id, payment_method, transaction_ref, amount) VALUES (?, ?, ?, ?)");
        $stmt->execute([$invoice_id, $method, $ref, $amount]);

        // 2. Update invoice status
        $stmt = $pdo->prepare("UPDATE invoices SET status = 'paid', paid_at = datetime('now') WHERE id = ?");
        $stmt->execute([$invoice_id]);

        // 3. Create notification
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
        $stmt->execute([$user['id'], 'Payment Successful', "Payment of " . mc_format_money($amount) . " for invoice {$invoice['invoice_number']} has been processed via $method.", 'billing']);

        $pdo->commit();
        return 'index.php?page=patient-billing&paid=1';
    } catch (Exception $e) {
        $pdo->rollBack();
        return 'index.php?page=patient-billing&error=payment_failed';
    }
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
    $pdo = $GLOBALS['pdo'] ?? null;
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
    $pdo = $GLOBALS['pdo'] ?? null;
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

function handle_patient_prescription_request(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['request_new_prescription'])) {
        return null;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'patient') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return 'index.php?page=patient-prescriptions&error=1';
    }
    $patient_id = (int) $user['id'];
    $doctor_id = (int) ($_POST['doctor_id'] ?? 0);
    $medication = trim($_POST['medication_name'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    
    if (!$doctor_id || !$medication) {
        return 'index.php?page=patient-prescriptions&error=missing_fields';
    }
    
    $stmt = $pdo->prepare("INSERT INTO prescription_requests (patient_id, doctor_id, medication_name, reason, status) VALUES (?,?,?,?, 'pending')");
    $stmt->execute([$patient_id, $doctor_id, $medication, $reason]);
    
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
    $stmt->execute([$patient_id, 'Prescription Requested', "Your request for $medication has been sent to the doctor.", 'prescription']);
    
    return 'index.php?page=patient-prescriptions&requested=1';
}

function handle_patient_prescription_refill(): ?string
{
    $request_refill = (int) ($_GET['request_refill'] ?? $_POST['request_refill'] ?? 0);
    $user = current_user();
    if (!$user || $user['role'] !== 'patient') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return null;
    }
    if ($request_refill) {
        $stmt = $pdo->prepare("UPDATE prescriptions SET refill_requested = 1 WHERE id = ? AND patient_id = ? AND status = 'active'");
        $stmt->execute([$request_refill, $user['id']]);
        return 'index.php?page=patient-prescriptions&requested=1';
    }
    return null;
}

function handle_doctor_issue_prescription(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['issue_from_request'])) {
        return null;
    }
    $user = current_user();
    if (!$user || $user['role'] !== 'doctor') {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return 'index.php?page=staff-prescriptions&error=1';
    }

    $requestId = (int) ($_POST['request_id'] ?? 0);
    $medication = trim($_POST['medication'] ?? '');
    $dosage = trim($_POST['dosage'] ?? '');
    $duration = (int) ($_POST['duration'] ?? 7);

    if (!$requestId || !$medication || !$dosage) {
        return 'index.php?page=staff-prescriptions&error=1';
    }

    // 1. Get the request details
    $stmt = $pdo->prepare("SELECT patient_id FROM prescription_requests WHERE id = ? AND doctor_id = ? AND status = 'pending'");
    $stmt->execute([$requestId, (int)$user['id']]);
    $req = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$req) {
        return 'index.php?page=staff-prescriptions&error=1';
    }

    $patientId = (int)$req['patient_id'];

    try {
        $pdo->beginTransaction();

        // 2. Insert into prescriptions table
        $stmt = $pdo->prepare("INSERT INTO prescriptions (patient_id, doctor_id, medication, dosage, duration_days, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->execute([$patientId, (int)$user['id'], $medication, $dosage, $duration]);

        // 3. Update request status
        $stmt = $pdo->prepare("UPDATE prescription_requests SET status = 'issued' WHERE id = ?");
        $stmt->execute([$requestId]);

        // 4. Notify patient
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
        $stmt->execute([$patientId, 'Prescription Issued', "Doctor {$user['name']} has issued your prescription for $medication.", 'prescription']);

        $pdo->commit();
        return 'index.php?page=staff-prescriptions&approved=1';
    } catch (Exception $e) {
        $pdo->rollBack();
        return 'index.php?page=staff-prescriptions&error=1';
    }
}

function handle_doctor_reject_prescription(): ?string
{
    $rejectId = (int) ($_GET['reject_prescription_request'] ?? 0);
    $user = current_user();
    if (!$user || $user['role'] !== 'doctor' || !$rejectId) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT patient_id, medication_name FROM prescription_requests WHERE id = ? AND doctor_id = ? AND status = 'pending'");
    $stmt->execute([$rejectId, (int)$user['id']]);
    $req = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$req) {
        return 'index.php?page=staff-prescriptions&error=1';
    }

    $stmt = $pdo->prepare("UPDATE prescription_requests SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$rejectId]);

    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)");
    $stmt->execute([$req['patient_id'], 'Request Rejected', "Your request for {$req['medication_name']} has been declined by the doctor.", 'prescription']);

    return 'index.php?page=staff-prescriptions&rejected=1';
}

function handle_staff_prescription_approve_refill(): ?string
{
    $approve = (int) ($_GET['approve_refill'] ?? $_POST['approve_refill'] ?? 0);
    $user = current_user();
    if (!$user || $user['role'] !== 'doctor' || !$approve) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return null;
    }
    $doctorId = (int) $user['id'];
    $stmt = $pdo->prepare("SELECT patient_id FROM prescriptions WHERE id = ? AND doctor_id = ? AND refill_requested = 1 AND COALESCE(refill_approved, 0) = 0");
    $stmt->execute([$approve, $doctorId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return 'index.php?page=staff-prescriptions&error=1';
    }
    $pdo->prepare("UPDATE prescriptions SET refill_approved = 1, refill_requested = 0 WHERE id = ? AND doctor_id = ?")
        ->execute([$approve, $doctorId]);
    $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?,?,?,?)")
        ->execute([(int) $row['patient_id'], 'Refill Approved', 'Your prescription refill request has been approved by your doctor.', 'prescription']);
    return 'index.php?page=staff-prescriptions&approved=1';
}

function handle_staff_profile_save(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['save_staff_profile'])) {
        return null;
    }
    $user = current_user();
    if (!$user || !in_array($user['role'], ['doctor', 'receptionist'], true)) {
        return null;
    }
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return 'index.php?page=staff-profile&error=1';
    }
    $name = trim($_POST['name'] ?? $user['name']);
    $phone = trim($_POST['phone'] ?? '');
    $availability = trim($_POST['availability_schedule'] ?? '');
    $preferences = trim($_POST['work_preferences'] ?? '');
    $pdo->prepare("UPDATE users SET name = ? WHERE id = ?")->execute([$name, $user['id']]);
    $pdo->prepare("INSERT INTO user_profiles (user_id, phone, availability_schedule, work_preferences, updated_at) VALUES (?,?,?,?, datetime('now')) ON CONFLICT(user_id) DO UPDATE SET phone = excluded.phone, availability_schedule = excluded.availability_schedule, work_preferences = excluded.work_preferences, updated_at = datetime('now')")
        ->execute([$user['id'], $phone, $availability, $preferences]);
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
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return 'index.php?page=staff-requests&error=1';
    }

    $requestId = (int) ($_POST['request_id'] ?? 0);
    $date = trim($_POST['appointment_date'] ?? '');
    $time = trim($_POST['appointment_time'] ?? '');
    if (!$requestId || !$date || !$time) {
        return 'index.php?page=staff-requests&error=1';
    }
    $today = date('Y-m-d');
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dt || $dt->format('Y-m-d') !== $date || $date < $today || $date < '2026-01-01') {
        return 'index.php?page=staff-requests&error=invalid_date';
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
    $pdo = $GLOBALS['pdo'] ?? null;
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
