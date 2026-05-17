<?php
interface Authenticable
{
    public function register(string $email, string $password, string $role, ?string $firstName = null, ?string $lastName = null, ?string $preferredLanguage = null);
    public function login(string $email, string $password);
    public function logout();
    public function resetPassword(string $newPassword);
    public function changePassword(string $oldPassword, string $newPassword);
}
?>