<?php
/**
 * Admin Entry Point
 * จัดการการเรียกใช้ AdminController
 */

// เพิ่ม error reporting (เฉพาะการ debug)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// โหลด configuration
require_once __DIR__ . '/config/config.php';

session_start();

// ตรวจสอบการยืนยันตัวตน
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// ตรวจสอบสิทธิ์ Admin
$roleName = $_SESSION['role_name'] ?? '';
if (!in_array($roleName, ['admin', 'super_admin'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/app/controllers/AdminController.php';

$adminController = new AdminController();

// รับ action จาก URL
$action = $_GET['action'] ?? 'index';

// จัดการ routing
switch ($action) {
    // User Management
    case 'users':
        $adminController->users();
        break;
    case 'users_create':
        $adminController->createUser();
        break;
    case 'users_store':
        $adminController->storeUser();
        break;
    case 'users_edit':
        $adminController->editUser();
        break;
    case 'users_update':
        $adminController->updateUser();
        break;
    case 'users_delete':
        $adminController->deleteUser();
        break;

    // Company Management
    case 'companies':
        $adminController->companies();
        break;
    case 'companies_create':
        $adminController->createCompany();
        break;
    case 'companies_store':
        $adminController->storeCompany();
        break;
    case 'companies_edit':
        $adminController->editCompany();
        break;
    case 'companies_update':
        $adminController->updateCompany();
        break;
    case 'companies_delete':
        $adminController->deleteCompany();
        break;

    // Other Admin sections
    case 'products':
        $adminController->products();
        break;
    case 'settings':
        $adminController->settings();
        break;
    case 'workflow':
        $adminController->workflow();
        break;
    case 'customer_distribution':
        $adminController->customer_distribution();
        break;
    default:
        $adminController->index();
        break;
}
?> 