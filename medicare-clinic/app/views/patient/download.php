<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/auth.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
if (!$user || $user['role'] !== 'patient') {
    header('Location: index.php?page=login');
    exit;
}
$type = $_GET['type'] ?? '';
$id = (int) ($_GET['id'] ?? 0);
if (!$type || !$id) {
    header('Location: index.php?page=patient');
    exit;
}
$patient_id = (int) $user['id'];
$data = null;
$filename = '';
if ($type === 'prescription') {
    $stmt = $pdo->prepare("SELECT p.*, u.name as doctor_name FROM prescriptions p JOIN users u ON p.doctor_id = u.id WHERE p.id = ? AND p.patient_id = ?");
    $stmt->execute([$id, $patient_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = "prescription-$id.txt";
} elseif ($type === 'record') {
    $stmt = $pdo->prepare("SELECT m.*, u.name as doctor_name FROM medical_records m LEFT JOIN users u ON m.doctor_id = u.id WHERE m.id = ? AND m.patient_id = ?");
    $stmt->execute([$id, $patient_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = "record-$id.txt";
} elseif ($type === 'invoice') {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ? AND patient_id = ?");
    $stmt->execute([$id, $patient_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = "invoice-" . ($data['invoice_number'] ?? $id) . ".txt";
}
if (!$data) {
    header('Location: index.php?page=patient');
    exit;
}
$content = '';
if ($type === 'prescription') {
    $content = "MediCare Clinic - Prescription\n" . str_repeat('=', 40) . "\n\n";
    $content .= "Medication: " . $data['medication'] . "\n";
    $content .= "Dosage: " . $data['dosage'] . "\n";
    $content .= "Duration: " . ($data['duration_days'] ?? '') . " days\n";
    $content .= "Doctor: " . ($data['doctor_name'] ?? '') . "\n";
    $content .= "Date: " . $data['created_at'] . "\n";
    $content .= "Status: " . $data['status'] . "\n";
} elseif ($type === 'record') {
    $content = "MediCare Clinic - Medical Record\n" . str_repeat('=', 40) . "\n\n";
    $content .= "Type: " . $data['record_type'] . "\n";
    $content .= "Description: " . $data['description'] . "\n";
    $content .= "Provider: " . ($data['doctor_name'] ?? '') . "\n";
    $content .= "Date: " . $data['created_at'] . "\n";
} elseif ($type === 'invoice') {
    $content = "MediCare Clinic - Invoice\n" . str_repeat('=', 40) . "\n\n";
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
