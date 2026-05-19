<?php
/**
 * IRegistrationStrategy Interface
 * Defines the contract for different user registration strategies
 */

interface IRegistrationStrategy
{
    /**
     * Validate the registration data specific to this role
     * @return array - Array of errors, empty if valid
     */
    public function validate(): array;

    /**
     * Process the registration for this specific role
     * @param string $email
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @return bool - True if registration successful
     * @throws RuntimeException if registration fails
     */
    public function register(string $email, string $password, string $firstName, string $lastName): bool;

    /**
     * Get additional data to store for this role
     * @return array - Role-specific data
     */
    public function getAdditionalData(): array;

    /**
     * Get the redirect URL after successful registration
     * @return string - URL to redirect to
     */
    public function getRedirectUrl(): string;
}
?>
