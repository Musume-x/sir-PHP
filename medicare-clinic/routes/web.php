<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/actions.php';

function route_request(string $page): void
{
    switch ($page) {
        case 'home':
            require __DIR__ . '/../app/views/landing.php';
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $user = login_user($email, $password);
                if ($user) {
                    redirect_after_login($user['role']);
                    return;
                }
                header('Location: index.php?page=login&error=1');
                exit;
            }
            require __DIR__ . '/../app/views/auth/login.php';
            break;

        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $password_confirm = $_POST['password_confirm'] ?? '';
                if ($password !== $password_confirm) {
                    $register_error = 'Passwords do not match.';
                    require __DIR__ . '/../app/views/auth/register.php';
                    return;
                }
                $role = $_POST['role'] ?? 'patient';
                $allowedRoles = ['patient', 'doctor', 'nurse', 'receptionist'];
                if (!in_array($role, $allowedRoles, true)) {
                    $role = 'patient';
                }
                $result = register_user($name, $email, $password, $role);
                if ($result === true) {
                    header('Location: index.php?page=login&success=1');
                    exit;
                }
                $register_error = $result;
                require __DIR__ . '/../app/views/auth/register.php';
                return;
            }
            $register_error = '';
            require __DIR__ . '/../app/views/auth/register.php';
            break;

        case 'logout':
            logout_user();
            header('Location: index.php?page=home');
            break;

        case 'admin':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/dashboard.php';
            break;

        case 'admin-users':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/users.php';
            break;

        case 'admin-staff':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/staff.php';
            break;

        case 'admin-patients':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/patients.php';
            break;

        case 'admin-appointments':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/appointments.php';
            break;

        case 'admin-records':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/records.php';
            break;

        case 'admin-billing':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/billing.php';
            break;

        case 'admin-reports':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/reports.php';
            break;

        case 'admin-settings':
            require_role(['admin']);
            require __DIR__ . '/../app/views/admin/settings.php';
            break;

        case 'staff':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/dashboard.php';
            break;

        case 'staff-appointments':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/appointments.php';
            break;

        case 'staff-patients':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/patients.php';
            break;

        case 'staff-records':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/records.php';
            break;

        case 'staff-prescriptions':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/prescriptions.php';
            break;

        case 'staff-billing':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/billing.php';
            break;

        case 'staff-profile':
            require_role(['doctor', 'nurse', 'receptionist']);
            require __DIR__ . '/../app/views/staff/profile.php';
            break;

        case 'patient':
            require_role(['patient']);
            require __DIR__ . '/../app/views/patient/dashboard.php';
            break;

        case 'patient-book':
            require_role(['patient']);
            $redirect = handle_patient_book();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/patient/book-appointment.php';
            break;

        case 'patient-appointments':
            require_role(['patient']);
            require __DIR__ . '/../app/views/patient/my-appointments.php';
            break;

        case 'patient-records':
            require_role(['patient']);
            require __DIR__ . '/../app/views/patient/records.php';
            break;

        case 'patient-billing':
            require_role(['patient']);
            $redirect = handle_patient_billing_pay();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/patient/billing.php';
            break;

        case 'patient-notifications':
            require_role(['patient']);
            $redirect = handle_patient_notification_read();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/patient/notifications.php';
            break;

        case 'patient-profile':
            require_role(['patient']);
            $redirect = handle_patient_profile_save();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/patient/profile.php';
            break;

        case 'patient-prescriptions':
            require_role(['patient']);
            $redirect = handle_patient_prescription_refill();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/patient/prescriptions.php';
            break;

        case 'patient-view':
            require_role(['patient']);
            require __DIR__ . '/../app/views/patient/view-detail.php';
            break;

        case 'patient-download':
            require_role(['patient']);
            require __DIR__ . '/../app/views/patient/download.php';
            break;

        case 'staff-prescriptions':
            require_role(['doctor', 'nurse', 'receptionist']);
            $redirect = handle_staff_prescription_approve_refill();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/staff/prescriptions.php';
            break;

        case 'staff-profile':
            require_role(['doctor', 'nurse', 'receptionist']);
            $redirect = handle_staff_profile_save();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/staff/profile.php';
            break;

        case 'admin-billing':
            require_role(['admin']);
            $redirect = handle_admin_action();
            if ($redirect) {
                header("Location: $redirect");
                exit;
            }
            require __DIR__ . '/../app/views/admin/billing.php';
            break;

        default:
            require __DIR__ . '/../app/views/landing.php';
            break;
    }
}

function redirect_after_login(string $role): void
{
    if ($role === 'admin') {
        header('Location: index.php?page=admin');
    } elseif (in_array($role, ['doctor', 'nurse', 'receptionist'], true)) {
        header('Location: index.php?page=staff');
    } else {
        header('Location: index.php?page=patient');
    }
    exit;
}

