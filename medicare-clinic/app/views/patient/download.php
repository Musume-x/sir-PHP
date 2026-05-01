<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/auth.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
if (!$user) {
    header('Location: index.php?page=login');
    exit;
}
$type = $_GET['type'] ?? '';
$id = (int) ($_GET['id'] ?? 0);
if (!$type || !$id) {
    header('Location: index.php?page=' . ($user['role'] === 'patient' ? 'patient' : 'staff'));
    exit;
}
$data = null;
$filename = '';
if ($type === 'prescription') {
    $sql = "SELECT p.*, u1.name as patient_name, u2.name as doctor_name FROM prescriptions p JOIN users u1 ON p.patient_id = u1.id JOIN users u2 ON p.doctor_id = u2.id WHERE p.id = ?";
    if ($user['role'] === 'patient') $sql .= " AND p.patient_id = " . (int)$user['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = "prescription-$id.txt";
} elseif ($type === 'record') {
    $sql = "SELECT m.*, u1.name as patient_name, u2.name as doctor_name FROM medical_records m JOIN users u1 ON m.patient_id = u1.id LEFT JOIN users u2 ON m.doctor_id = u2.id WHERE m.id = ?";
    if ($user['role'] === 'patient') $sql .= " AND m.patient_id = " . (int)$user['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = "record-$id.txt";
} elseif ($type === 'invoice') {
    $sql = "SELECT i.*, u.name as patient_name FROM invoices i JOIN users u ON i.patient_id = u.id WHERE i.id = ?";
    if ($user['role'] === 'patient') $sql .= " AND i.patient_id = " . (int)$user['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = "invoice-" . ($data['invoice_number'] ?? $id) . ".txt";
}
if (!$data) {
    header('Location: index.php?page=' . ($user['role'] === 'patient' ? 'patient' : 'staff'));
    exit;
}
$content = '';
if ($type === 'prescription') {
    $content = "MediCare Clinic - Prescription\n" . str_repeat('=', 40) . "\n\n";
    $content .= "Patient: " . ($data['patient_name'] ?? '') . "\n";
    $content .= "Medication: " . $data['medication'] . "\n";
    $content .= "Dosage: " . $data['dosage'] . "\n";
    $content .= "Duration: " . ($data['duration_days'] ?? '') . " days\n";
    $content .= "Doctor: " . ($data['doctor_name'] ?? '') . "\n";
    $content .= "Date: " . $data['created_at'] . "\n";
    $content .= "Status: " . $data['status'] . "\n";
} elseif ($type === 'record') {
    $content = "MediCare Clinic - Medical Record\n" . str_repeat('=', 40) . "\n\n";
    $content .= "Patient: " . ($data['patient_name'] ?? '') . "\n";
    $content .= "Type: " . $data['record_type'] . "\n";
    $content .= "Description: " . $data['description'] . "\n";
    $content .= "Provider: " . ($data['doctor_name'] ?? '') . "\n";
    $content .= "Date: " . $data['created_at'] . "\n";
} elseif ($type === 'invoice') {
    $content = "MediCare Clinic - Invoice\n" . str_repeat('=', 40) . "\n\n";
    $content .= "Patient: " . ($data['patient_name'] ?? '') . "\n";
    $content .= "Invoice #: " . $data['invoice_number'] . "\n";
    $content .= "Service: " . $data['service'] . "\n";
    $content .= 'Amount: ' . mc_format_money((float) $data['amount']) . "\n";
    $content .= "Status: " . $data['status'] . "\n";
    $content .= "Date: " . $data['created_at'] . "\n";
}
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($content));
echo $content;
exit;
