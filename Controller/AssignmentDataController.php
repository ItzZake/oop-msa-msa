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
require_once __DIR__ . '/../Models/Assignment.php';

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
 * Format assignment data for frontend
 */
function formatAssignmentForView(array $assignment): array
{
    // Use actual course name from database, or default to empty string
    $subject = $assignment['courseName'] ?? '';

    // Calculate days left
    $dueDate = new DateTime($assignment['dueDate']);
    $today = new DateTime();
    $daysLeft = $today->diff($dueDate)->days;
    
    if ($today > $dueDate) {
        $daysLeft = -1 * $daysLeft;
    }

    $submitted = (int) ($assignment['submitted'] ?? 0);
    $graded = (int) ($assignment['graded'] ?? 0);
    $totalStudents = (int) ($assignment['totalStudents'] ?? 0);

    return [
        'id'           => (int) $assignment['assignmentID'],
        'title'        => $assignment['title'] ?? '',
        'subject'      => $subject,
        'description'  => $assignment['description'] ?? '',
        'dueDate'      => date('M d, Y', strtotime($assignment['dueDate'])),
        'dueDateRaw'   => $assignment['dueDate'],
        'daysLeft'     => $daysLeft,
        'status'       => $assignment['status'] ?? 'Draft',
        'emoji'        => '📚',
        'color'        => '#1565C0',
        'bg'           => '#EFF6FF',
        'submitted'    => $submitted,
        'graded'       => $graded,
        'totalStudents' => $totalStudents,
        'attachmentPath' => $assignment['attachmentpath'] ?? null,
        'wordwallCode' => $assignment['wordwallembedcode'] ?? null,
        'createdAt'    => $assignment['createdAt'] ?? null,
    ];
}

/**
 * Handle GET requests
 */
function handleGet()
{
    $teacherId = verifyAuth();
    $action = $_GET['action'] ?? 'list';

    $assignmentModel = new Assignment();

    switch ($action) {
        case 'list':
            $assignments = $assignmentModel->GetTeacherAssignments($teacherId);
            
            if (!$assignments) {
                sendJson([
                    'success' => true,
                    'data' => [],
                    'message' => 'No assignments found'
                ]);
            }

            $formattedAssignments = array_map('formatAssignmentForView', $assignments);

            sendJson([
                'success' => true,
                'data' => $formattedAssignments,
                'total' => count($formattedAssignments)
            ]);
            break;

        case 'detail':
            $assignmentId = (int) ($_GET['id'] ?? 0);
            if (!$assignmentId) {
                sendError('Assignment ID is required');
            }

            $assignment = $assignmentModel->GetAssignmentById($assignmentId);
            
            if (!$assignment) {
                sendError('Assignment not found', 404);
            }

            // Verify ownership
            if ((int) $assignment['teacherID'] !== $teacherId) {
                sendError('Unauthorized: You can only view your own assignments', 403);
            }

            sendJson([
                'success' => true,
                'data' => formatAssignmentForView($assignment)
            ]);
            break;

        default:
            sendError('Invalid action');
    }
}

/**
 * Handle POST requests
 */
function handlePost()
{
    $teacherId = verifyAuth();
    $action = $_GET['action'] ?? 'create';

    $assignmentModel = new Assignment();

    switch ($action) {
        case 'create':
            // Get JSON body
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Log the input for debugging
            error_log('CreateAssignment Input: ' . json_encode($input));
            error_log('TeacherId: ' . $teacherId);
            
            // Validate input
            $title = $input['title'] ?? '';
            $courseId = (int) ($input['courseId'] ?? 0);
            $dueDate = $input['dueDate'] ?? '';
            $description = $input['description'] ?? '';
            $embedCode = $input['embedCode'] ?? '';

            if (!$title) {
                sendError('Assignment title is required');
            }
            if (!$courseId) {
                sendError('Course ID is required');
            }
            if (!$dueDate) {
                sendError('Due date is required');
            }

            // Validate date format
            if (!strtotime($dueDate)) {
                sendError('Invalid date format');
            }

            // Create assignment
            $result = $assignmentModel->CreateAssignment([
                'teacherId' => $teacherId,
                'courseId' => $courseId,
                'title' => $title,
                'instructions' => $description,
                'dueDate' => $dueDate,
                'wordwallEmbedCode' => $embedCode,
            ]);

            error_log('CreateAssignment Result: ' . ($result ? 'Success - ID: ' . $result : 'Failed'));

            if (!$result) {
                sendError('Failed to create assignment', 500);
            }

            sendJson([
                'success' => true,
                'message' => 'Assignment created successfully',
                'data' => ['assignmentId' => $result]
            ]);
            break;

        case 'update':
            // Get JSON body
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Log the input for debugging
            error_log('UpdateAssignment Input: ' . json_encode($input));
            error_log('TeacherId: ' . $teacherId);
            
            // Validate input
            $assignmentId = (int) ($input['assignmentId'] ?? 0);
            $title = $input['title'] ?? '';
            $courseId = (int) ($input['courseId'] ?? 0);
            $dueDate = $input['dueDate'] ?? '';
            $description = $input['description'] ?? '';
            $embedCode = $input['embedCode'] ?? '';

            if (!$assignmentId) {
                sendError('Assignment ID is required');
            }
            if (!$title) {
                sendError('Assignment title is required');
            }
            if (!$courseId) {
                sendError('Course ID is required');
            }
            if (!$dueDate) {
                sendError('Due date is required');
            }

            // Validate date format
            if (!strtotime($dueDate)) {
                sendError('Invalid date format');
            }

            // Verify ownership of assignment
            $assignment = $assignmentModel->GetAssignmentById($assignmentId);
            if (!$assignment) {
                sendError('Assignment not found', 404);
            }
            if ((int) $assignment['teacherID'] !== $teacherId) {
                sendError('Unauthorized: You can only edit your own assignments', 403);
            }

            // Update assignment
            $result = $assignmentModel->UpdateAssignment($assignmentId, [
                'courseId' => $courseId,
                'title' => $title,
                'instructions' => $description,
                'dueDate' => $dueDate,
                'wordwallEmbedCode' => $embedCode,
            ]);

            error_log('UpdateAssignment Result: ' . ($result ? 'Success' : 'Failed'));

            if (!$result) {
                sendError('Failed to update assignment', 500);
            }

            sendJson([
                'success' => true,
                'message' => 'Assignment updated successfully',
                'data' => ['assignmentId' => $assignmentId]
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
            handlePost();
            break;

        default:
            sendError('Method not allowed', 405);
    }
} catch (Exception $e) {
    sendError('Server error: ' . $e->getMessage(), 500);
}
?>
