<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Course.php';

/**
 * Send JSON response
 */
function sendJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

/**
 * Send error response
 */
function sendError(string $message, int $status = 400): void
{
    sendJson(['success' => false, 'error' => $message], $status);
}

/**
 * Get teacher ID from user ID
 */
function getTeacherIdFromUserId(int $userId): int
{
    $sql = "SELECT teacherID FROM teacher WHERE userID = ?";
    $result = Database::getInstance()->fetchAll($sql, [$userId]);
    
    if (empty($result)) {
        sendError('Teacher record not found', 404);
    }
    
    return (int) $result[0]['teacherID'];
}

/**
 * Verify user is authenticated and return teacher ID
 */
function verifyAuth(): int
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        sendError('Unauthorized: User not authenticated', 401);
    }

    if ($_SESSION['user_role'] !== 'teacher' && $_SESSION['user_role'] !== 'admin') {
        sendError('Unauthorized: Only teachers and admins can access this', 403);
    }

    $userId = (int) $_SESSION['user_id'];
    return getTeacherIdFromUserId($userId);
}

/**
 * Format course data for frontend
 */
function formatCourseForView(array $course): array
{
    $scheduleData = [];
    if (isset($course['schedule']) && !empty($course['schedule'])) {
        $scheduleData = json_decode($course['schedule'], true) ?? [];
    }

    $enrolledStudents = (int) ($course['enrolledStudents'] ?? 0);
    $maxCapacity = (int) ($course['maxCapacity'] ?? 0);
    $occupancyPercent = $maxCapacity > 0 ? round(($enrolledStudents / $maxCapacity) * 100) : 0;

    return [
        'id'              => (int) $course['courseID'],
        'name'            => $course['name'] ?? '',
        'description'     => $course['description'] ?? '',
        'ageMin'          => (int) ($course['ageMin'] ?? 0),
        'ageMax'          => (int) ($course['ageMax'] ?? 0),
        'maxCapacity'     => $maxCapacity,
        'enrolledStudents' => $enrolledStudents,
        'occupancyPercent' => $occupancyPercent,
        'price'           => (float) ($course['price'] ?? 0),
        'schedule'        => $scheduleData,
        'isActive'        => (bool) ($course['isActive'] ?? true),
        'assignedTeacherId' => (int) ($course['assignedTeacherId'] ?? 0),
        'status'          => $enrolledStudents >= $maxCapacity ? 'full' : 'open',
    ];
}

/**
 * Handle GET requests
 */
function handleGet()
{
    $teacherId = verifyAuth();
    $action = $_GET['action'] ?? 'list';

    $courseModel = new Course();

    switch ($action) {
        case 'list':
            $courses = $courseModel->GetTeacherCourses($teacherId);
            
            if (!$courses) {
                sendJson([
                    'success' => true,
                    'data' => [],
                    'message' => 'No courses found'
                ]);
            }

            $formattedCourses = array_map('formatCourseForView', $courses);

            sendJson([
                'success' => true,
                'data' => $formattedCourses,
                'total' => count($formattedCourses)
            ]);
            break;

        case 'detail':
            $courseId = (int) ($_GET['id'] ?? 0);
            if (!$courseId) {
                sendError('Course ID is required');
            }

            $course = $courseModel->GetCourseById($courseId);
            
            if (!$course) {
                sendError('Course not found', 404);
            }

            // Verify ownership (teacher assigned to course)
            if ((int) $course['assignedTeacherId'] !== $teacherId && $_SESSION['user_role'] !== 'admin') {
                sendError('Unauthorized: You can only view your own courses', 403);
            }

            sendJson([
                'success' => true,
                'data' => formatCourseForView($course)
            ]);
            break;

        case 'all':
            // Get all active courses (for admins)
            $courses = $courseModel->GetAllActiveCourses();
            
            if (!$courses) {
                sendJson([
                    'success' => true,
                    'data' => [],
                    'message' => 'No courses available'
                ]);
            }

            $formattedCourses = array_map('formatCourseForView', $courses);

            sendJson([
                'success' => true,
                'data' => $formattedCourses,
                'total' => count($formattedCourses)
            ]);
            break;

        default:
            sendError('Invalid action');
    }
}

/**
 * Main handler
 */
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGet();
            break;

        case 'POST':
            sendError('POST method not yet implemented for this endpoint');
            break;

        default:
            sendError('Method not allowed', 405);
    }
} catch (Exception $e) {
    sendError('Server error: ' . $e->getMessage(), 500);
}
?>
