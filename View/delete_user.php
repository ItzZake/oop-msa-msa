<?php
session_start();
require_once __DIR__ . '/../Controller/DashboardController.php';
$controller = new DashboardController();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$id) {
    $_SESSION['error'] = 'Invalid user id.';
    header('Location: dashboard.php');
    exit;
}

try {
    $controller->deleteUser($id);
    $_SESSION['message'] = 'User deleted successfully.';
} catch (Exception $e) {
    error_log('Delete user failed: ' . $e->getMessage());
    $_SESSION['error'] = 'Unable to delete user. ' . $e->getMessage();
}

header('Location: dashboard.php');
exit;
?>