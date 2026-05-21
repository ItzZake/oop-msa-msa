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
    $userRole = strtolower($_SESSION['user_role'] ?? 'user');
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
                'userData' => null,
                'teacherData' => null,
                'parentData' => null,
                'childData' => null,
                'adminData' => null,
                'studentsList' => [],
                'childrenList' => [],
                'teacherStats' => [
                    'totalStudents' => 0,
                    'presentToday' => 0,
                    'absentToday' => 0,
                    'lateToday' => 0,
                    'attendanceRate' => 0,
                ],
            ];

            error_log("Fetching user data for userID: $userId");
            $user = $db->fetchOne("SELECT userID, email, firstname, Lastname, Role FROM `User` WHERE userID = ?", [$userId]);
            error_log("User data query result: " . json_encode($user));
            if ($user) {
                $profileData['userData'] = $user;
            }

            if ($userRole === 'teacher') {
                error_log("Fetching teacher data for userID: $userId");
                $teacher = $db->fetchOne("SELECT teacherID, exprience, qualifications, specialization, phone FROM `Teacher` WHERE userID = ?", [$userId]);
                error_log("Teacher data query result: " . json_encode($teacher));
                if ($teacher) {
                    $profileData['teacherData'] = array_merge($user ?? [], $teacher);
                    if (!isset($profileData['teacherData']['specialization']) || $profileData['teacherData']['specialization'] === null) {
                        $profileData['teacherData']['specialization'] = 'General Education';
                    }
                    if (!isset($profileData['teacherData']['phone']) || $profileData['teacherData']['phone'] === null) {
                        $profileData['teacherData']['phone'] = '';
                    }

                    $teacherId = $teacher['teacherID'];
                    error_log("Fetching students for teacherID or userID: $teacherId / $userId");
                    $students = $db->fetchAll(
                        "SELECT DISTINCT c.name AS childName, c.childID, c.dateOfBirth, c.gender
                         FROM `Child` c
                         JOIN `Enrollment` e ON c.childID = e.childID
                         JOIN `Course` co ON e.courseID = co.courseID
                         WHERE co.assignedTeacherID = ? OR co.assignedTeacherID = ?
                         ORDER BY c.name",
                        [$teacherId, $userId]
                    );
                    $profileData['studentsList'] = $students ?? [];
                    error_log("Students found: " . count($profileData['studentsList']));

                    $stats = $db->fetchOne(
                        "SELECT
                            COUNT(DISTINCT e.childID) AS totalStudents,
                            SUM(CASE WHEN a.status = 'Present' AND a.sessionDate = CURDATE() THEN 1 ELSE 0 END) AS presentToday,
                            SUM(CASE WHEN a.status = 'Absent' AND a.sessionDate = CURDATE() THEN 1 ELSE 0 END) AS absentToday,
                            SUM(CASE WHEN a.status = 'Late' AND a.sessionDate = CURDATE() THEN 1 ELSE 0 END) AS lateToday,
                            ROUND(100 * SUM(a.status = 'Present') / NULLIF(COUNT(a.attendanceID), 0), 2) AS attendanceRate
                         FROM `Course` co
                         LEFT JOIN `Enrollment` e ON co.courseID = e.courseID AND e.status = 'Active'
                         LEFT JOIN `Attendance` a ON a.courseID = co.courseID AND a.childID = e.childID
                         WHERE co.assignedTeacherID IN (?, ?)",
                        [$teacherId, $userId]
                    );
                    if ($stats) {
                        $profileData['teacherStats'] = [
                            'totalStudents' => (int) ($stats['totalStudents'] ?? 0),
                            'presentToday' => (int) ($stats['presentToday'] ?? 0),
                            'absentToday' => (int) ($stats['absentToday'] ?? 0),
                            'lateToday' => (int) ($stats['lateToday'] ?? 0),
                            'attendanceRate' => $stats['attendanceRate'] !== null ? (float) $stats['attendanceRate'] : 0,
                        ];
                        error_log("Teacher stats: " . json_encode($profileData['teacherStats']));
                    }
                }
            } elseif ($userRole === 'parent') {
                error_log("Fetching parent profile for userID: $userId");
                $parent = $db->fetchOne("SELECT parentID, phone, address, notifPreferences FROM `Parent` WHERE userID = ?", [$userId]);
                error_log("Parent data query result: " . json_encode($parent));
                $profileData['parentData'] = array_merge($user ?? [], $parent ?? []);
                if ($parent) {
                    $parentId = $parent['parentID'];
                    error_log("Fetching child profiles for parentID: $parentId");
                    $children = $db->fetchAll(
                        "SELECT childID, name, dateOfBirth, gender, allergies, medicalNotes, emergencyContact, enrollmentStatus
                         FROM `Child`
                         WHERE parentID = ?
                         ORDER BY name",
                        [$parentId]
                    );
                    $profileData['childrenList'] = $children ?? [];
                    error_log("Children found: " . count($profileData['childrenList']));
                }
            } elseif ($userRole === 'admin') {
                error_log("Fetching admin profile for userID: $userId");
                $admin = $db->fetchOne("SELECT adminID FROM `Admin` WHERE userID = ?", [$userId]);
                error_log("Admin data query result: " . json_encode($admin));
                $profileData['adminData'] = array_merge($user ?? [], $admin ?? []);
            } elseif ($userRole === 'child') {
                error_log("Fetching child profile for userID: $userId");
                $child = $db->fetchOne("SELECT childID, parentID, name, dateOfBirth, gender, allergies, medicalNotes, emergencyContact, enrollmentStatus FROM `Child` WHERE childID = ?", [$userId]);
                error_log("Child data query result: " . json_encode($child));
                if ($child) {
                    $profileData['childData'] = array_merge($user ?? [], $child);
                }
            } else {
                error_log("No specific profile branch for role: $userRole");
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
