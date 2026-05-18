<?php

require_once("PasswordHasher.php");

class User
{
    private $userId;
    private $email;

    private $firstName;
    private $lastName;
    private $password;
    private $preferredLanguage;
    private $createdAt;
    private $isActive;
    private $lastLoginAt;
    private $role;
    private PasswordHasher $hasher;

    public function __construct($userId, $email, $password, $preferredLanguage = null, $createdAt = null, $lastLoginAt = null, $role = null, $firstName = null, $lastName = null, $isActive = true)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->preferredLanguage = $preferredLanguage;
        $this->createdAt = $createdAt;
        $this->lastLoginAt = $lastLoginAt;
        $this->role = $role;
        $this->isActive = $isActive;
        $this->hasher = new PasswordHasher();
    }

    public static function fromArray(array $data)
    {
        $isActive = true;
        if (isset($data['isActive'])) {
            $isActive = (bool)$data['isActive'];
        } elseif (isset($data['IsActive'])) {
            $isActive = (bool)$data['IsActive'];
        }

        return new self(
            isset($data['userId']) ? $data['userId'] : null,
            isset($data['email']) ? $data['email'] : '',
            isset($data['password']) ? $data['password'] : '',
            isset($data['preferredLanguage']) ? $data['preferredLanguage'] : null,
            isset($data['createdAt']) ? $data['createdAt'] : null,
            isset($data['lastLoginAt']) ? $data['lastLoginAt'] : null,
            isset($data['role']) ? $data['role'] : null,
            isset($data['firstName']) ? $data['firstName'] : null,
            isset($data['lastName']) ? $data['lastName'] : null,
            $isActive
        );
    }

    public function getId()
    {
        return $this->userId;
    }

    public function setId($id)
    {
        $this->userId = $id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPreferredLanguage()
    {
        return $this->preferredLanguage;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    public function updateProfile($data)
    {
        $this->email = isset($data['email']) ? $data['email'] : $this->email;
        $this->preferredLanguage = isset($data['preferredLanguage']) ? $data['preferredLanguage'] : $this->preferredLanguage;
        $this->firstName = isset($data['firstName']) ? $data['firstName'] : $this->firstName;
        $this->lastName = isset($data['lastName']) ? $data['lastName'] : $this->lastName;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function verifyPassword($plain)
    {
        return $this->hasher->Verify($plain, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = $this->hasher->Hash($password);
    }

    public function getPasswordHash()
    {
        return $this->password;
    }

    public function markLastLogin()
    {
        $this->lastLoginAt = date('Y-m-d H:i:s');
    }

    public function changePassword($old, $new)
    {
        if (!$this->verifyPassword($old)) {
            return false;
        }

        if (empty($new) || strlen($new) < 8) {
            return false;
        }

        $this->setPassword($new);
        return true;
    }

    public function resetPassword($newPassword)
    {
        if (empty($newPassword) || strlen($newPassword) < 8) {
            return false;
        }

        $this->setPassword($newPassword);
        return true;
    }

    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'password' => $this->password,
            'preferredLanguage' => $this->preferredLanguage,
            'createdAt' => $this->createdAt,
            'lastLoginAt' => $this->lastLoginAt,
            'role' => $this->role,
            'isActive' => $this->isActive,
        ];
    }
}
?>