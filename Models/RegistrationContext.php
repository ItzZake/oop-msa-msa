<?php
/**
 * RegistrationContext
 * Context class that uses registration strategies
 */

require_once __DIR__ . '/IRegistrationStrategy.php';
require_once __DIR__ . '/ParentRegistrationStrategy.php';
require_once __DIR__ . '/TeacherRegistrationStrategy.php';

class RegistrationContext
{
    private $strategy;

    /**
     * Set the registration strategy
     */
    public function setStrategy(IRegistrationStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Execute validation using the current strategy
     */
    public function validate(): array
    {
        if (!$this->strategy) {
            return ['No registration strategy selected.'];
        }
        return $this->strategy->validate();
    }

    /**
     * Execute registration using the current strategy
     */
    public function register(string $email, string $password, string $firstName, string $lastName): bool
    {
        if (!$this->strategy) {
            throw new RuntimeException('No registration strategy selected.');
        }
        return $this->strategy->register($email, $password, $firstName, $lastName);
    }

    /**
     * Get additional data from the strategy
     */
    public function getAdditionalData(): array
    {
        if (!$this->strategy) {
            return [];
        }
        return $this->strategy->getAdditionalData();
    }

    /**
     * Get redirect URL from the strategy
     */
    public function getRedirectUrl(): string
    {
        if (!$this->strategy) {
            return '../View/login.php';
        }
        return $this->strategy->getRedirectUrl();
    }

    /**
     * Create a parent registration strategy
     */
    public static function createParentStrategy($email, $password, $firstName, $lastName, $phoneNumber = null, $address = null): RegistrationContext
    {
        $context = new self();
        $context->setStrategy(new ParentRegistrationStrategy($email, $password, $firstName, $lastName, $phoneNumber, $address));
        return $context;
    }

    /**
     * Create a teacher registration strategy
     */
    public static function createTeacherStrategy($email, $password, $firstName, $lastName, $qualifications = null, $department = null): RegistrationContext
    {
        $context = new self();
        $context->setStrategy(new TeacherRegistrationStrategy($email, $password, $firstName, $lastName, $qualifications, $department));
        return $context;
    }
}
?>
