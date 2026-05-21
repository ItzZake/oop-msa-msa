<?php
/**
 * TeacherRegistrationStrategy
 * Handles registration logic specific to teacher users
 */

require_once __DIR__ . '/IRegistrationStrategy.php';
require_once __DIR__ . '/AuthService.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';

class TeacherRegistrationStrategy implements IRegistrationStrategy
{
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    private $qualifications;
    private $department;
    private $errors = [];

    public function __construct($email = null, $password = null, $firstName = null, $lastName = null, $qualifications = null, $department = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->qualifications = $qualifications;
        $this->department = $department;
    }

    /**
     * Set registration data
     */
    public function setData($email, $password, $firstName, $lastName, $qualifications = null, $department = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->qualifications = $qualifications;
        $this->department = $department;
    }

    /**
     * Validate teacher-specific registration data
     */
    public function validate(): array
    {
        $this->errors = [];

        // Validate first name
        if (empty($this->firstName)) {
            $this->errors[] = "First name is required.";
        } elseif (!filter_var($this->firstName, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z\s]+$/"]])) {
            $this->errors[] = "First name may only contain letters.";
        } elseif (strlen($this->firstName) < 2) {
            $this->errors[] = "First name must be at least 2 characters.";
        }

        // Validate last name
        if (empty($this->lastName)) {
            $this->errors[] = "Last name is required.";
        } elseif (!filter_var($this->lastName, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[a-zA-Z\s]+$/"]])) {
            $this->errors[] = "Last name may only contain letters.";
        }

        // Validate email - teachers need valid institutional email patterns (optional stricter validation)
        if (empty($this->email)) {
            $this->errors[] = "Email is required.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Please enter a valid email address.";
        }

        // Validate password
        if (empty($this->password)) {
            $this->errors[] = "Password is required.";
        } elseif (strlen($this->password) < 8) {
            $this->errors[] = "Password must be at least 8 characters.";
        }

        // Qualifications validation (required for teachers)
        if (empty($this->qualifications)) {
            $this->errors[] = "Please provide your educational qualifications.";
        } elseif (strlen($this->qualifications) < 5) {
            $this->errors[] = "Qualifications description must be at least 5 characters.";
        }

        return $this->errors;
    }

    /**
     * Register teacher user
     */
    public function register(string $email, string $password, string $firstName, string $lastName): bool
    {
        try {
            $authService = new AuthService();
            
            // Step 1: Register user in user table with role='teacher'
            $user = $authService->register($email, $password, 'teacher', $firstName, $lastName);
            $userId = $user->getId();

            if (!$userId) {
                throw new RuntimeException("Failed to get user ID after registration.");
            }

            // Step 2: Add user to teacher table with additional data
            $db = Database::getInstance();
            
            $sql = "INSERT INTO teacher (userID, phone) 
                    VALUES (?, ?)";
            $params = [
                $userId,
                null  // Phone not required at registration; can be updated later
            ];
            
            try {
                $result = $db->query($sql, $params);
                if (!$result) {
                    // Check PDO error info
                    $errorInfo = $db->getConnection()->errorInfo();
                    throw new RuntimeException("Failed to execute teacher profile INSERT: " . ($errorInfo[2] ?? "Unknown error"));
                }
            } catch (PDOException $pdoe) {
                throw new RuntimeException("Database error adding teacher profile: " . $pdoe->getMessage());
            }

            return true;
        } catch (PDOException $e) {
            throw new RuntimeException("Teacher registration database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("Teacher registration failed: " . $e->getMessage());
        }
    }

    /**
     * Get teacher-specific additional data
     */
    public function getAdditionalData(): array
    {
        return [
            'role' => 'teacher',
            'qualifications' => $this->qualifications,
            'department' => $this->department
        ];
    }

    /**
     * Get redirect URL after teacher registration
     */
    public function getRedirectUrl(): string
    {
        return "../View/login.php"; // Teachers redirect back to login (pending approval)
    }
}
?>
