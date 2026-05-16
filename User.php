<?php

require_once("PasswordHasher.php");
require_once("SessionManager.php");

abstract class User implements Authenticable
{
    private $userId;
    private $email;
    private $password;
    private $preferredLanguage;
    private $createdAt;
    private $IsActive;
    private $lastLoginAt;
    private $role;
    private PasswordHasher $hasher;

    public function __construct($userId, $email, $password, $preferredLanguage, $createdAt, $lastLoginAt, $role)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
        $this->preferredLanguage = $preferredLanguage;
        $this->createdAt = $createdAt;
        $this->lastLoginAt = $lastLoginAt;
        $this->role = $role;
        $this->hasher = new PasswordHasher();
    }

    public function UpdateProfile($data)
    {
        // Code to update user profile
        $this->email = isset($data['email']) ? $data['email'] : $this->email;
        $this->preferredLanguage = isset($data['preferredLanguage']) ? $data['preferredLanguage'] : $this->preferredLanguage;
    }

    public function GetRole()
    {
        return $this->role;
    }

    public function SetRole($role)
    {
        $this->role = $role;
    }

    public function VerifyPassword($plain, $hash)
    {
        // Code to verify password
    }

    public function LogIn($email, $password): bool
    {
        // Code for log in

        if (!$this->IsActive)
            return false;
        if ($this->email != $email)
            return false;
        if (!this->hasher->verify($password, $this->password))
            return false;
        if (this->hasher->NeedsReHash($this->password)) {
            $this->password = this->hasher->Hash($password);
        }

        SessionManager::start();
        SessionManager::Regenerate();

        $this->lastLoginAt = date("Y-m-d H:i:s");

        return true;
    }

    public function Logout(): bool
    {
        // Code for Log Out
        SessionManager::Destroy();
        return true;
    }

    public function ResetPassword($token, $newPassword): bool
    {
        if (empty($newPassword) || strlen($newPassword) < 8) {
            return false;
        }

        $this->password = $this->hasher->Hash($newPassword);

        return true;
    }

    public function ChangePassword($old, $new): bool
    {
        // code for ChangePassword
        if (!$this->hasher->Verify($old, $this->password)) {
            return false;
        }

        if (empty($new) || strlen($new) < 8) {
            return false;
        }

        $this->password = $this->hasher->Hash($new);
        return true;
    }
}
?>