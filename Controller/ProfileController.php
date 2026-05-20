<?php
/**
 * ProfileController
 * Handles profile data fetching based on user role
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Models/Database.php';

error_log("=== ProfileController accessed ===");
error_log("Action: " . ($_GET['action'] ?? 'none'));
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));
error_log("Session user_role: " . ($_SESSION['user_role'] ?? 'not set'));

// Check authentication
if (!isset($_SESSION['user_id'])) {
    error_log("Authentication failed - no user_id in session");
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'] ?? 'User';
$action = $_GET['action'] ?? 'get';
$db = Database::getInstance();

error_log("Proceeding with userId: $userId, userRole: $userRole");

switch ($action) {

    // ── GET: Fetch profile data based on user role ──
    case 'get':
        $profileData = [
            'userRole' => $userRole,
            'userId' => $userId,
            'teacherData' => null,
            'studentsList' => []
        ];

        if ($userRole === 'Teacher') {
            // Get user data first
            $user = $db->fetchOne("SELECT userID, email, firstname, Lastname FROM user WHERE userID = ?", [$userId]);
            
            error_log("User data for userID $userId: " . json_encode($user));
            
            if ($user) {
                // Get teacher-specific data
                $teacher = $db->fetchOne("SELECT teacherID, exprience, qualifications, specialization, phone FROM teacher WHERE userID = ?", [$userId]);
                
                error_log("Teacher data: " . json_encode($teacher));
                
                // Merge user and teacher data
                $profileData['teacherData'] = array_merge($user, $teacher ?? []);
                
                // Fetch enrolled students from courses assigned to this teacher
                if ($teacher) {
                    $students = $db->fetchAll(
                        "SELECT DISTINCT c.name as childName, c.childID, c.dateOfBirth, c.gender
                         FROM child c
                         JOIN enrollment e ON c.childID = e.childID
                         JOIN course co ON e.courseID = co.courseID
                         WHERE co.assignedTeacherID = ?
                         ORDER BY c.name",
                        [$teacher['teacherID']]
                    );
                    
                    $profileData['studentsList'] = $students ?? [];
                    error_log("Students found: " . count($profileData['studentsList']));
                }
            } else {
                error_log("No user record found for userID $userId");
            }
        }

        echo json_encode([
            'success' => true,
            'data' => $profileData
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
}

?>
