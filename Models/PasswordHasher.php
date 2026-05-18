<?php
class PasswordHasher
{
    private $algorithm;

    public function __construct($algorithm = PASSWORD_DEFAULT)
    {
        $this->algorithm = $algorithm;
    }

    function Hash($plain): string // return string
    {
        // code for Hashing the password
        $hashed = password_hash($plain, $this->algorithm);

        if ($hashed === false) {
            throw new Exception("Password hashing failed.");
        }

        return $hashed;
    }

    function Verify($plain, $hash): bool
    {
        // code for vertifying the password of the user
        return password_verify($plain, $hash);
    }

    function NeedsReHash($hash): bool
    {
        // code if it needs to re-hash
        return password_needs_rehash($hash, $this->algorithm);
    }
}
?>