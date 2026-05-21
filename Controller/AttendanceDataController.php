<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/AttendanceController.php';

function sendJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

function sendError(string $message, int $status = 400): void
{
    sendJson(['success' => false, 'error' => $message], $status);
}

function requireSessionRole(string $role): AttendanceController
{
    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== $role) {
        sendError("Unauthorized: {$role} access required", 401);
    }

    return new AttendanceController();
}

function parseYearMonth(): array
{
    $year = (int) ($_GET['year'] ?? date('Y'));
    $month = (int) ($_GET['month'] ?? date('n'));

    if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
        sendError('Invalid year or month');
    }

    return [$year, $month];
}

function handleTeacherGet(AttendanceController $controller): void
{
    $userId = (int) $_SESSION['user_id'];
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'courses':
            sendJson(['success' => true, 'data' => $controller->getTeacherCourses($userId)]);
            break;

        case 'students':
            $courseId = (int) ($_GET['courseId'] ?? 0);
            $date = trim((string) ($_GET['date'] ?? date('Y-m-d')));

            if ($courseId <= 0) {
                sendError('courseId is required');
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                sendError('Invalid date format');
            }

            if (!$controller->verifyTeacherOwnsCourse($userId, $courseId)) {
                sendError('You are not assigned to this course', 403);
            }

            $students = $controller->getStudentsForCourse($courseId, $date);
            sendJson([
                'success' => true,
                'data'    => $students,
                'total'   => count($students),
            ]);
            break;

        default:
            sendError('Invalid action');
    }
}

function handleTeacherPost(AttendanceController $controller): void
{
    $userId = (int) $_SESSION['user_id'];
    $action = $_GET['action'] ?? 'submit';

    if ($action !== 'submit') {
        sendError('Invalid action');
    }

    $body = json_decode(file_get_contents('php://input') ?: '', true);
    if (!is_array($body)) {
        $body = $_POST;
    }

    $courseId = (int) ($body['courseId'] ?? 0);
    $date = trim((string) ($body['date'] ?? date('Y-m-d')));
    $marks = $body['marks'] ?? [];

    if ($courseId <= 0) {
        sendError('courseId is required');
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        sendError('Invalid date format');
    }

    if (!is_array($marks) || empty($marks)) {
        sendError('No attendance marks provided');
    }

    if (!$controller->verifyTeacherOwnsCourse($userId, $courseId)) {
        sendError('You are not assigned to this course', 403);
    }

    $teacherId = $controller->getTeacherIdFromUserId($userId);
    if (!$teacherId) {
        sendError('Teacher record not found', 404);
    }

    require_once __DIR__ . '/../Models/Attendance.php';
    $saved = (new Attendance())->SaveSessionMarks($courseId, $teacherId, $date, $marks);

    sendJson([
        'success' => true,
        'message' => "Attendance saved for {$saved} student(s).",
        'saved'   => $saved,
    ]);
}

function handleParentGet(AttendanceController $controller): void
{
    $userId = (int) $_SESSION['user_id'];
    $parentId = $controller->getParentIdFromUserId($userId);

    if (!$parentId) {
        sendError('Parent record not found', 404);
    }

    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'children':
            sendJson([
                'success' => true,
                'data'    => $controller->getChildrenForParent($parentId),
            ]);
            break;

        case 'attendance':
            $childId = (int) ($_GET['childId'] ?? 0);
            if ($childId <= 0) {
                sendError('childId is required');
            }

            if (!$controller->verifyChildBelongsToParent($parentId, $childId)) {
                sendError('You do not have access to this child', 403);
            }

            [$year, $month] = parseYearMonth();
            $data = $controller->getChildAttendanceViewData($childId, $year, $month);

            if (empty($data)) {
                sendError('Child not found', 404);
            }

            sendJson(['success' => true, 'data' => $data]);
            break;

        default:
            sendError('Invalid action');
    }
}

function handleAdminGet(AttendanceController $controller): void
{
    $action = $_GET['action'] ?? 'overview';
    $courseId = (int) ($_GET['courseId'] ?? 0);
    $date = trim((string) ($_GET['date'] ?? date('Y-m-d')));

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        sendError('Invalid date format');
    }

    switch ($action) {
        case 'overview':
            sendJson([
                'success' => true,
                'data'    => $controller->loadAdminPageData($courseId, $date),
            ]);
            break;

        case 'courses':
            sendJson([
                'success' => true,
                'data'    => $controller->getAdminCourseOptions(),
            ]);
            break;

        default:
            sendError('Invalid action');
    }
}

try {
    $role = $_SESSION['user_role'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    if ($role === 'teacher') {
        $controller = requireSessionRole('teacher');
        if ($method === 'GET') {
            handleTeacherGet($controller);
        } elseif ($method === 'POST') {
            handleTeacherPost($controller);
        } else {
            sendError('Method not allowed', 405);
        }
    } elseif ($role === 'parent') {
        $controller = requireSessionRole('parent');
        if ($method === 'GET') {
            handleParentGet($controller);
        } else {
            sendError('Method not allowed', 405);
        }
    } elseif ($role === 'admin') {
        $controller = requireSessionRole('admin');
        if ($method === 'GET') {
            handleAdminGet($controller);
        } else {
            sendError('Method not allowed', 405);
        }
    } else {
        sendError('Unauthorized', 401);
    }
} catch (Throwable $e) {
    sendError('Server error: ' . $e->getMessage(), 500);
}
