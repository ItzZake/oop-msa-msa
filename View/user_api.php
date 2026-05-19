<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

ob_start();
require_once __DIR__ . '/../Models/userRepository.php';
ob_end_clean();

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

function normalizeRole(string $role): string
{
    $role = trim(strtolower($role));
    return in_array($role, ['teacher', 'admin', 'parent', 'child'], true) ? $role : 'teacher';
}

function normalizeStatus(string $status): bool
{
    return strtolower(trim($status)) === 'active';
}

function userToApi(User $user): array
{
    return [
        'id' => $user->getId(),
        'first' => $user->getFirstName() ?? '',
        'last' => $user->getLastName() ?? '',
        'email' => $user->getEmail() ?? '',
        'role' => strtolower($user->getRole() ?? 'teacher'),
        'status' => $user->isActive() ? 'active' : 'pending',
        'cls' => '',
    ];
}

function fetchAllUserRows(): array
{
    $queries = [
        'SELECT userID, email, firstname, Lastname, Role, isActive FROM User ORDER BY firstname, Lastname',
        'SELECT userId, email, firstname, Lastname, Role, isActive FROM Users ORDER BY firstname, Lastname',
    ];

    foreach ($queries as $sql) {
        try {
            $rows = Database::getInstance()->fetchAll($sql);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (Throwable $e) {
            continue;
        }
    }

    return [];
}

function deleteUserById(int $id): bool
{
    $queries = [
        ['DELETE FROM User WHERE userID = ?', [$id]],
        ['DELETE FROM Users WHERE userId = ?', [$id]],
    ];

    foreach ($queries as [$sql, $params]) {
        try {
            $stmt = Database::getInstance()->query($sql, $params);
            if ($stmt !== false) {
                return true;
            }
        } catch (Throwable $e) {
            continue;
        }
    }

    return false;
}

$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    $body = $_POST;
}

$userRepo = new UserRepository();

try {
    if ($method === 'GET') {
        $rows = fetchAllUserRows();
        $users = [];
        foreach ($rows as $row) {
            $users[] = [
                'userId' => $row['userID'] ?? $row['userId'] ?? $row['id'] ?? null,
                'firstName' => $row['firstname'] ?? $row['firstName'] ?? '',
                'lastName' => $row['Lastname'] ?? $row['lastName'] ?? '',
                'email' => $row['email'] ?? '',
                'role' => strtolower($row['Role'] ?? $row['role'] ?? 'teacher'),
                'isActive' => (bool) ($row['isActive'] ?? $row['IsActive'] ?? 0),
            ];
        }

        sendJson(['success' => true, 'users' => $users]);
    }

    if ($method === 'POST') {
        $first = trim((string) ($body['first'] ?? ''));
        $last = trim((string) ($body['last'] ?? ''));
        $email = trim((string) ($body['email'] ?? ''));
        $password = trim((string) ($body['password'] ?? ''));
        $role = normalizeRole((string) ($body['role'] ?? 'teacher'));
        $status = normalizeStatus((string) ($body['status'] ?? 'active'));

        if ($first === '' || $last === '') {
            sendError('First and last name are required.', 400);
        }

        if ($role !== 'child' && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendError('A valid email address is required.', 400);
        }

        if ($password !== '' && strlen($password) < 8) {
            sendError('Password must be at least 8 characters.', 400);
        }

        if ($password === '') {
            $password = bin2hex(random_bytes(8));
        }

        $createdAt = date('Y-m-d H:i:s');
        $user = new User(null, $email, null, 'EN', $createdAt, null, ucfirst($role), $first, $last, $status);
        $user->setPassword($password);

        if (!$userRepo->save($user)) {
            sendError('Unable to create user.', 500);
        }

        sendJson(['success' => true, 'user' => userToApi($user)], 201);
    }

    if ($method === 'PUT') {
        $id = (int) ($body['id'] ?? 0);
        if ($id <= 0) {
            sendError('User ID is required for updates.', 400);
        }

        $user = $userRepo->findById($id);
        if (!$user) {
            sendError('User not found.', 404);
        }

        $first = trim((string) ($body['first'] ?? $user->getFirstName()));
        $last = trim((string) ($body['last'] ?? $user->getLastName()));
        $email = trim((string) ($body['email'] ?? $user->getEmail()));
        $role = normalizeRole((string) ($body['role'] ?? $user->getRole()));
        $status = normalizeStatus((string) ($body['status'] ?? ($user->isActive() ? 'active' : 'pending')));
        $password = trim((string) ($body['password'] ?? ''));

        if ($first === '' || $last === '') {
            sendError('First and last name are required.', 400);
        }

        if ($role !== 'child' && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendError('A valid email address is required.', 400);
        }

        if ($password !== '' && strlen($password) < 8) {
            sendError('Password must be at least 8 characters.', 400);
        }

        $user->updateProfile(['email' => $email, 'firstName' => $first, 'lastName' => $last]);
        if ($password !== '') {
            $user->setPassword($password);
        }
        $user->setRole(ucfirst($role));
        $user->setActive($status);

        if (!$userRepo->save($user)) {
            sendError('Unable to update user.', 500);
        }

        sendJson(['success' => true, 'user' => userToApi($user)]);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? $body['id'] ?? 0);
        if ($id <= 0) {
            sendError('User ID is required for deletion.', 400);
        }

        if (!deleteUserById($id)) {
            sendError('Unable to delete user.', 500);
        }

        sendJson(['success' => true]);
    }

    sendError('Unsupported request method.', 405);
} catch (Throwable $e) {
    sendError($e->getMessage(), 500);
}
