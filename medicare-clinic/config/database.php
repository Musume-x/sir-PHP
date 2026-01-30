<?php

$pdo = null;
$dbPath = __DIR__ . '/../data/medicare.db';
$dbDir = dirname($dbPath);

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

try {
    $pdo = new PDO("sqlite:$dbPath", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $GLOBALS['pdo'] = $pdo;

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            name TEXT NOT NULL,
            role TEXT NOT NULL CHECK(role IN ('admin','doctor','nurse','receptionist','patient')),
            department TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )
    ");
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN department TEXT");
    } catch (PDOException $e) {
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_profiles (
            user_id INTEGER PRIMARY KEY,
            phone TEXT,
            date_of_birth TEXT,
            gender TEXT,
            address TEXT,
            blood_type TEXT,
            allergies TEXT,
            current_medications TEXT,
            emergency_contact TEXT,
            emergency_phone TEXT,
            insurance_provider TEXT,
            policy_number TEXT,
            group_number TEXT,
            insurance_expiry TEXT,
            email_notifications INTEGER DEFAULT 1,
            sms_notifications INTEGER DEFAULT 1,
            appointment_reminders INTEGER DEFAULT 1,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS appointments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            patient_id INTEGER NOT NULL,
            doctor_id INTEGER NOT NULL,
            appointment_date TEXT NOT NULL,
            appointment_time TEXT NOT NULL,
            reason TEXT,
            department TEXT,
            status TEXT DEFAULT 'pending',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id),
            FOREIGN KEY (doctor_id) REFERENCES users(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS prescriptions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            patient_id INTEGER NOT NULL,
            doctor_id INTEGER NOT NULL,
            medication TEXT NOT NULL,
            dosage TEXT NOT NULL,
            duration_days INTEGER,
            status TEXT DEFAULT 'active',
            refill_requested INTEGER DEFAULT 0,
            refill_approved INTEGER DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id),
            FOREIGN KEY (doctor_id) REFERENCES users(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS invoices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            patient_id INTEGER NOT NULL,
            invoice_number TEXT NOT NULL,
            service TEXT NOT NULL,
            amount REAL NOT NULL,
            status TEXT DEFAULT 'pending',
            paid_at TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS notifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            message TEXT NOT NULL,
            type TEXT DEFAULT 'general',
            read_at TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS medical_records (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            patient_id INTEGER NOT NULL,
            doctor_id INTEGER,
            record_type TEXT NOT NULL,
            description TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES users(id),
            FOREIGN KEY (doctor_id) REFERENCES users(id)
        )
    ");

    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $defaultPassword = password_hash('password', PASSWORD_DEFAULT);
    if ($count === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, name, role, department) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin@medicare.com', $defaultPassword, 'Admin Demo', 'admin', null]);
        $stmt->execute(['doctor1@medicare.com', $defaultPassword, 'Dr. Kim Delicano', 'doctor', 'Pediatrics']);
        $stmt->execute(['doctor2@medicare.com', $defaultPassword, 'Dr. James Sarucam', 'doctor', 'Cardiology']);
        $stmt->execute(['doctor3@medicare.com', $defaultPassword, 'Dr. Zenrick Reconalla', 'doctor', 'Orthopedics']);
        $stmt->execute(['doctor4@medicare.com', $defaultPassword, 'Dr. Michael Sarol', 'doctor', 'General Medicine']);
        $stmt->execute(['doctor5@medicare.com', $defaultPassword, 'Dr. Jane Cooper', 'doctor', 'Cardiology']);
        $stmt->execute(['nurse@medicare.com', $defaultPassword, 'Nurse Demo', 'nurse', null]);
        $stmt->execute(['receptionist@medicare.com', $defaultPassword, 'Receptionist Demo', 'receptionist', null]);
        $stmt->execute(['patient@medicare.com', $defaultPassword, 'Patient Demo', 'patient', null]);
    }
    $doctorCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'doctor'")->fetchColumn();
    if ($doctorCount < 5) {
        $doctorPassword = password_hash('password', PASSWORD_DEFAULT);
        $doctors = [
            ['doctor1@medicare.com', 'Dr. Kim Delicano', 'Pediatrics'],
            ['doctor2@medicare.com', 'Dr. James Sarucam', 'Cardiology'],
            ['doctor3@medicare.com', 'Dr. Zenrick Reconalla', 'Orthopedics'],
            ['doctor4@medicare.com', 'Dr. Michael Sarol', 'General Medicine'],
            ['doctor5@medicare.com', 'Dr. Jane Cooper', 'Cardiology'],
        ];
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO users (email, password_hash, name, role, department) VALUES (?, ?, ?, 'doctor', ?)");
        foreach ($doctors as $d) {
            $stmt->execute([$d[0], $doctorPassword, $d[1], $d[2]]);
        }
    }
    $adminExists = $pdo->query("SELECT 1 FROM users WHERE email = 'admin@medicare.com' LIMIT 1")->fetch();
    if (!$adminExists) {
        $adminPass = password_hash('password', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (email, password_hash, name, role, department) VALUES (?, ?, ?, ?, ?)")->execute(['admin@medicare.com', $adminPass, 'Admin Demo', 'admin', null]);
    }
    $patientId = $pdo->query("SELECT id FROM users WHERE role = 'patient' LIMIT 1")->fetchColumn();
    $doctorId = $pdo->query("SELECT id FROM users WHERE role = 'doctor' LIMIT 1")->fetchColumn();
    if ($patientId && $doctorId && $pdo->query("SELECT COUNT(*) FROM invoices")->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO invoices (patient_id, invoice_number, service, amount, status) VALUES (?, 'INV-001', 'Consultation', 120.00, 'pending')")->execute([$patientId]);
        $pdo->prepare("INSERT INTO invoices (patient_id, invoice_number, service, amount, status, paid_at) VALUES (?, 'INV-002', 'Check-up', 80.00, 'paid', datetime('now'))")->execute([$patientId]);
        $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, 'Welcome', 'Thank you for registering with MediCare Clinic.', 'general')")->execute([$patientId]);
        $pdo->prepare("INSERT INTO prescriptions (patient_id, doctor_id, medication, dosage, duration_days, status) VALUES (?, ?, 'Sample Med 10mg', '1 tablet daily', 30, 'active')")->execute([$patientId, $doctorId]);
        $pdo->prepare("INSERT INTO medical_records (patient_id, doctor_id, record_type, description) VALUES (?, ?, 'Consultation', 'Initial consultation note.')")->execute([$patientId, $doctorId]);
    }
} catch (PDOException $e) {
    $pdo = null;
    $GLOBALS['pdo'] = null;
}
