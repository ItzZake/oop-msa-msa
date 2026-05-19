<?php
/**
 * EditProfileController
 * Handles user profile creation, update, and deletion operations
 * Routes requests to appropriate Model methods
 */

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/userRepository.php';

header('Content-Type: application/json; charset=utf-8');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

class EditProfileController
{
    private $userRepository;
    private $request;
    private $requestBody;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->request = $_SERVER['REQUEST_METHOD'];
        $this->requestBody = $this->getRequestBody();
    }

    /**
     * Get request body from JSON or POST data
     */
    private function getRequestBody(): array
    {
        $body = json_decode(file_get_contents('php://input'), true);
        return is_array($body) ? $body : $_POST;
    }

    /**
     * Send JSON response
     */
    private function sendJson(array $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Send error response
     */
    private function sendError(string $message, int $status = 400): void
    {
        $this->sendJson(['success' => false, 'error' => $message], $status);
    }

    /**
     * Normalize role input
     */
    private function normalizeRole(string $role): string
    {
        $role = strtolower(trim($role));
        return in_array($role, ['teacher', 'admin', 'parent', 'child'], true) ? $role : 'teacher';
    }

    /**
     * Convert User object to API format
     */
    private function userToApi(User $user): array
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

    /**
     * Handle GET request - Fetch all users
     */
    private function handleGet(): void
    {
        try {
            // Query all users from database
            $rows = Database::getInstance()->fetchAll(
                "SELECT userID, email, firstname, Lastname, Role, isActive FROM User ORDER BY firstname, Lastname"
            );
            
            if (!$rows) {
                $rows = Database::getInstance()->fetchAll(
                    "SELECT userId, email, firstname, Lastname, Role, isActive FROM Users ORDER BY firstname, Lastname"
                );
            }

            $users = array_map(function ($row) {
                $user = User::fromArray($row);
                return $this->userToApi($user);
            }, $rows ?? []);

            $this->sendJson(['success' => true, 'users' => $users]);
        } catch (Exception $e) {
            $this->sendError('Failed to fetch users: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Handle POST request - Create new user
     */
    private function handlePost(): void
    {
        $first = $this->requestBody['first'] ?? '';
        $last = $this->requestBody['last'] ?? '';
        $email = $this->requestBody['email'] ?? '';
        $password = $this->requestBody['password'] ?? '';
        $role = $this->requestBody['role'] ?? 'teacher';
        $status = ($this->requestBody['status'] ?? 'pending') === 'active';

        // Validate
        if (!$first || !$last) {
            $this->sendError('First and last name are required');
            return;
        }
        if (!$password || strlen($password) < 8) {
            $this->sendError('Password must be at least 8 characters');
            return;
        }
        if ($role !== 'child' && $email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendError('Invalid email format');
            return;
        }

        try {
            $role = $this->normalizeRole($role);
            
            // Create new user without password initially
            $user = new User(
                null,
                $email,
                null,
                'en',
                date('Y-m-d H:i:s'),
                null,
                $role,
                $first,
                $last,
                $status
            );

            // Set password using User model's hashing
            $user->setPassword($password);

            // Save to database
            if (!$this->userRepository->create($user)) {
                $this->sendError('Failed to create user in database');
                return;
            }

            // Retrieve the created user (ID was set by repository during insert)
            $userId = $user->getId();
            if (!$userId) {
                $this->sendError('User created but ID not set');
                return;
            }

            $createdUser = $this->userRepository->findById($userId);
            if (!$createdUser) {
                $this->sendError('User created but could not be retrieved');
                return;
            }

            $this->sendJson([
                'success' => true,
                'user' => $this->userToApi($createdUser),
                'message' => 'User created successfully'
            ], 201);
        } catch (Exception $e) {
            $this->sendError('Error creating user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Handle PUT request - Update existing user
     */
    private function handlePut(): void
    {
        $id = $this->requestBody['id'] ?? null;
        $first = $this->requestBody['first'] ?? '';
        $last = $this->requestBody['last'] ?? '';
        $email = $this->requestBody['email'] ?? '';
        $password = $this->requestBody['password'] ?? '';
        $role = $this->requestBody['role'] ?? '';
        $status = ($this->requestBody['status'] ?? 'pending') === 'active';

        // Validate
        if (!$id) {
            $this->sendError('User ID is required');
            return;
        }

        try {
            // Retrieve existing user
            $user = $this->userRepository->findById($id);
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }

            // Update fields if provided
            $updateData = [];
            if ($first) {
                $updateData['firstName'] = $first;
            }
            if ($last) {
                $updateData['lastName'] = $last;
            }
            if ($email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->sendError('Invalid email format');
                    return;
                }
                $updateData['email'] = $email;
            }
            
            // Update profile with new data
            if (!empty($updateData)) {
                $user->updateProfile($updateData);
            }
            
            if ($password) {
                if (strlen($password) < 8) {
                    $this->sendError('Password must be at least 8 characters');
                    return;
                }
                $user->setPassword($password);
            }
            if ($role) {
                $role = $this->normalizeRole($role);
                $user->setRole($role);
            }

            // Update in database
            if (!$this->userRepository->save($user)) {
                $this->sendError('Failed to update user');
                return;
            }

            // Retrieve updated user
            $updatedUser = $this->userRepository->findById($id);
            if (!$updatedUser) {
                $this->sendError('User updated but could not be retrieved');
                return;
            }

            $this->sendJson([
                'success' => true,
                'user' => $this->userToApi($updatedUser),
                'message' => 'User updated successfully'
            ]);
        } catch (Exception $e) {
            $this->sendError('Error updating user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Handle DELETE request - Delete user
     */
    private function handleDelete(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->sendError('User ID is required');
            return;
        }

        try {
            // Verify user exists
            $user = $this->userRepository->findById($id);
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }

            // Delete from database
            $deleted = Database::getInstance()->query("DELETE FROM User WHERE userID = ?", [$id]);
            if (!$deleted) {
                // Try alternate table name
                $deleted = Database::getInstance()->query("DELETE FROM Users WHERE userId = ?", [$id]);
            }

            if (!$deleted) {
                $this->sendError('Failed to delete user');
                return;
            }

            $this->sendJson([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (Exception $e) {
            $this->sendError('Error deleting user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Route request to appropriate handler
     */
    public function handleRequest(): void
    {
        try {
            match ($this->request) {
                'GET' => $this->handleGet(),
                'POST' => $this->handlePost(),
                'PUT' => $this->handlePut(),
                'DELETE' => $this->handleDelete(),
                default => $this->sendError('Method not allowed', 405),
            };
        } catch (Exception $e) {
            $this->sendError('Internal server error: ' . $e->getMessage(), 500);
        }
    }
}

// Instantiate and handle request
$controller = new EditProfileController();
$controller->handleRequest();
?>
