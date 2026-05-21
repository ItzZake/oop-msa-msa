<?php
/**
 * ProfileController
 * Handles profile data fetching based on user role
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Models/Database.php';

error_log("=== ProfileController accessed ===");
error_log("Action: " . ($_GET['action'] ?? 'none'));
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));
error_log("Session user_role: " . ($_SESSION['user_role'] ?? 'not set'));

try {
    // Check authentication
    if (!isset($_SESSION['user_id'])) {
        error_log("Authentication failed - no user_id in session");
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized', 'error' => 'No user_id in session']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['user_role'] ?? 'User';
    $action = $_GET['action'] ?? 'get';
    
    error_log("Proceeding with userId: $userId, userRole: $userRole");
    
    try {
        $db = Database::getInstance();
    } catch (Exception $e) {
        error_log("Database connection failed: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed', 'error' => $e->getMessage()]);
        exit;
    }

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
                error_log("Fetching user data for userID: $userId");
                $user = $db->fetchOne("SELECT userID, email, firstname, Lastname FROM `user` WHERE userID = ?", [$userId]);
                
                error_log("User data query result: " . json_encode($user));
                
                if ($user) {
                    // Get teacher-specific data
                    error_log("Fetching teacher data for userID: $userId");
                    $teacher = $db->fetchOne("SELECT teacherID, exprience, qualifications, specialization, phone FROM `teacher` WHERE userID = ?", [$userId]);
                    
                    error_log("Teacher data query result: " . json_encode($teacher));
                    
                    // Merge user and teacher data
                    if ($teacher) {
                        $profileData['teacherData'] = array_merge($user, $teacher);
                        // Set default values for null fields
                        if (!isset($profileData['teacherData']['specialization']) || $profileData['teacherData']['specialization'] === null) {
                            $profileData['teacherData']['specialization'] = 'General Education';
                        }
                        if (!isset($profileData['teacherData']['phone']) || $profileData['teacherData']['phone'] === null) {
                            $profileData['teacherData']['phone'] = '';
                        }
                    } else {
                        $profileData['teacherData'] = $user;
                    }
                    
                    // Fetch enrolled students from courses assigned to this teacher
                    if ($teacher) {
                        error_log("Fetching students for userID (assignedTeacherID): " . $userId);
                        $students = $db->fetchAll(
                            "SELECT DISTINCT c.name as childName, c.childID, c.dateOfBirth, c.gender
                             FROM `child` c
                             JOIN `enrollment` e ON c.childID = e.childID
                             JOIN `course` co ON e.courseID = co.courseID
                             WHERE co.assignedTeacherID = ?
                             ORDER BY c.name",
                            [$userId]  // Use userID, not teacherID!
                        );
                        
                        $profileData['studentsList'] = $students ?? [];
                        error_log("Students found: " . count($profileData['studentsList']));
                    } else {
                        error_log("No teacher record found for userID: $userId");
                        $profileData['studentsList'] = [];
                    }
                } else {
                    error_log("No user record found for userID $userId");
                }
            } else {
                error_log("User role is not Teacher, role: $userRole");
            }

            echo json_encode([
                'success' => true,
                'data' => $profileData
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
    
} catch (Exception $e) {
    error_log("Unexpected error in ProfileController: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An unexpected error occurred',
        'error' => $e->getMessage()
    ]);
}

?>
