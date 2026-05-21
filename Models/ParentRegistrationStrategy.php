<?php
/**
 * ParentRegistrationStrategy
 * Handles registration logic specific to parent users
 */

require_once __DIR__ . '/IRegistrationStrategy.php';
require_once __DIR__ . '/AuthService.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/UserRepository.php';

class ParentRegistrationStrategy implements IRegistrationStrategy
{
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    private $phoneNumber;
    private $address;
    private $errors = [];

    public function __construct($email = null, $password = null, $firstName = null, $lastName = null, $phoneNumber = null, $address = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
    }

    /**
     * Set registration data
     */
    public function setData($email, $password, $firstName, $lastName, $phoneNumber = null, $address = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
    }

    /**
     * Validate parent-specific registration data
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

        // Validate email
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

        // Optional: validate phone if provided
        if ($this->phoneNumber && !filter_var($this->phoneNumber, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[0-9\s\-\+\(\)]+$/"]])) {
            $this->errors[] = "Invalid phone number format.";
        }

        return $this->errors;
    }

    /**
     * Register parent user
     */
    public function register(string $email, string $password, string $firstName, string $lastName): bool
    {
        try {
            $authService = new AuthService();
            
            // Register as parent role (creates User record with role='parent')
            $user = $authService->register($email, $password, 'parent', $firstName, $lastName);

            // Create the Parent profile row for newly registered parent users.
            $sql = "INSERT INTO Parent (userID, phone, address, notifPreferences) VALUES (?, ?, ?, ?);";
            $params = [
                $user->getId(),
                $this->phoneNumber ?: null,
                $this->address ?: null,
                null
            ];
            Database::getInstance()->query($sql, $params);
            // Step 1: Register user in user table with role='parent'
            $user = $authService->register($email, $password, 'parent', $firstName, $lastName);
            $userId = $user->getId();

            if (!$userId) {
                throw new RuntimeException("Failed to get user ID after registration.");
            }

            // Step 2: Add user to parent table with additional data
            $db = Database::getInstance();
            $notifPreferences = json_encode(['newsletter' => true]);
            
            $sql = "INSERT INTO parent (userID, phone, address, notifPreferences) 
                    VALUES (?, ?, ?, ?)";
            $params = [
                $userId,
                $this->phoneNumber ?: null,
                $this->address ?: null,
                $notifPreferences
            ];
            
            try {
                $result = $db->query($sql, $params);
                if (!$result) {
                    // Check PDO error info
                    $errorInfo = $db->getConnection()->errorInfo();
                    throw new RuntimeException("Failed to execute parent profile INSERT: " . ($errorInfo[2] ?? "Unknown error"));
                }
            } catch (PDOException $pdoe) {
                throw new RuntimeException("Database error adding parent profile: " . $pdoe->getMessage());
            }

            return true;
        } catch (PDOException $e) {
            throw new RuntimeException("Parent registration database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("Parent registration failed: " . $e->getMessage());
        }
    }

    /**
     * Get parent-specific additional data
     */
    public function getAdditionalData(): array
    {
        return [
            'role' => 'parent',
            'phoneNumber' => $this->phoneNumber,
            'address' => $this->address
        ];
    }

    /**
     * Get redirect URL after parent registration
     */
    public function getRedirectUrl(): string
    {
        return "../View/enroll.php"; // Parents redirect to enrollment page
    }
}
?>
