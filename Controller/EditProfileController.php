<?php
/**
 * EditProfileController
 * Handles user profile operations with session-based authentication
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Models/Database.php';

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'] ?? 'User';
$action = $_GET['action'] ?? 'get';
$db = Database::getInstance();

switch ($action) {

    // ── GET: Fetch user profile ──
    case 'get':
        $user = $db->fetchOne("SELECT userID, email, firstname, Lastname FROM user WHERE userID = ?", [$userId]);
        
        if ($user) {
            // Get role-specific data
            $roleData = [];
            if ($userRole === 'Teacher') {
                $teacher = $db->fetchOne("SELECT teacherID, exprience, qualifications, specialization, phone FROM teacher WHERE userID = ?", [$userId]);
                $roleData = $teacher ?? [];
            } elseif ($userRole === 'Parent') {
                $parent = $db->fetchOne("SELECT parentID FROM parent WHERE userID = ?", [$userId]);
                if ($parent) {
                    $childCount = $db->fetchOne("SELECT COUNT(*) as count FROM child WHERE parentID = ?", [$parent['parentID']]);
                    $roleData = ['childrenCount' => $childCount['count'] ?? 0];
                } else {
                    $roleData = ['childrenCount' => 0];
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => array_merge($user, $roleData)
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
        break;

    // ── POST: Update profile ──
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'POST required']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (empty($data['firstName']) || empty($data['lastName']) || empty($data['email'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        // Update user table
        $updateFields = ['firstname = ?', 'Lastname = ?', 'email = ?'];
        $params = [
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $userId
        ];
        
        $sql = "UPDATE user SET " . implode(', ', $updateFields) . " WHERE userID = ?";
        $db->query($sql, $params);
        
        // Update teacher-specific data if applicable
        if ($userRole === 'Teacher') {
            $teacherFields = [];
            $teacherParams = [];
            
            if (isset($data['qualifications'])) {
                $teacherFields[] = 'qualifications = ?';
                $teacherParams[] = $data['qualifications'];
            }
            if (isset($data['specialization'])) {
                $teacherFields[] = 'specialization = ?';
                $teacherParams[] = $data['specialization'];
            }
            if (isset($data['phoneNumber'])) {
                $teacherFields[] = 'phone = ?';
                $teacherParams[] = $data['phoneNumber'];
            }
            if (isset($data['experience'])) {
                $teacherFields[] = 'exprience = ?';  // Note: typo in database column name
                $teacherParams[] = $data['experience'];
            }
            
            if (!empty($teacherFields)) {
                $teacherParams[] = $userId;
                $db->query("UPDATE teacher SET " . implode(', ', $teacherFields) . " WHERE userID = ?", $teacherParams);
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        break;

    // ── POST: Delete account ──
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'POST required']);
            exit;
        }
        
        try {
            // Delete user-related data based on role
            if ($userRole === 'Teacher') {
                $db->query("DELETE FROM teacher WHERE userID = ?", [$userId]);
            } elseif ($userRole === 'Parent') {
                $parent = $db->fetchOne("SELECT parentID FROM parent WHERE userID = ?", [$userId]);
                if ($parent) {
                    $db->query("DELETE FROM child WHERE parentID = ?", [$parent['parentID']]);
                    $db->query("DELETE FROM parent WHERE parentID = ?", [$parent['parentID']]);
                }
            }
            
            // Delete user account
            $db->query("DELETE FROM user WHERE userID = ?", [$userId]);
            
            // Clear session
            session_destroy();
            
            echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error deleting account: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
}

?>

