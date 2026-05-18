<?php

require_once 'Authenticable.php';
require_once 'SessionManager.php';
require_once 'userRepository.php';
require_once 'PasswordHasher.php';
require_once 'User.php';

class AuthService implements Authenticable
{
	private UserRepository $userRepository;
	private PasswordHasher $hasher;

	public function __construct()
	{
		SessionManager::Start();
		$this->userRepository = new UserRepository();
		$this->hasher = new PasswordHasher();
	}

	public function register(string $email, string $password, string $role, ?string $firstName = null, ?string $lastName = null, ?string $preferredLanguage = null): User
	{
		$email = trim($email);
		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException('Valid email required.');
		}

		if (empty($password) || strlen($password) < 8) {
			throw new InvalidArgumentException('Password must be at least 8 characters.');
		}

		if ($this->userRepository->findByEmail($email)) {
			throw new RuntimeException('Email is already registered.');
		}

		$user = new User(
			null,
			$email,
			'',
			$preferredLanguage,
			date('Y-m-d H:i:s'),
			null,
			$role,
			$firstName,
			$lastName,
			true
		);
		$user->setPassword($password);

		if (!$this->userRepository->save($user)) {
			throw new RuntimeException('Unable to save new user.');
		}

		return $user;
	}

	public function login(string $email, string $password): ?User
	{
		$user = $this->userRepository->findByEmail(trim($email));
		if (!$user || !$user->isActive()) {
			return null;
		}

		if (!$user->verifyPassword($password)) {
			return null;
		}

		if ($this->hasher->NeedsReHash($user->getPasswordHash())) {
			$user->setPassword($password);
		}

		$user->markLastLogin();
		$this->userRepository->save($user);

		SessionManager::Regenerate();
		SessionManager::Set('user_id', $user->getId());
		SessionManager::Set('user_role', $user->getRole());

		return $user;
	}

	public function logout(): bool
	{
		SessionManager::Destroy();
		return true;
	}

	public function resetPassword(string $newPassword): bool
	{
		if (empty($newPassword) || strlen($newPassword) < 8) {
			return false;
		}

		$userId = SessionManager::Get('user_id');
		if (!$userId) {
			return false;
		}

		$user = $this->userRepository->findById((int) $userId);
		if (!$user) {
			return false;
		}

		if (!$user->resetPassword($newPassword)) {
			return false;
		}

		return $this->userRepository->save($user);
	}

	public function changePassword(string $oldPassword, string $newPassword): bool
	{
		$userId = SessionManager::Get('user_id');
		if (!$userId) {
			return false;
		}

		$user = $this->userRepository->findById((int) $userId);
		if (!$user) {
			return false;
		}

		if (!$user->changePassword($oldPassword, $newPassword)) {
			return false;
		}

		return $this->userRepository->save($user);
	}

	public function getAuthenticatedUser(): ?User
	{
		$userId = SessionManager::Get('user_id');
		if (!$userId) {
			return null;
		}

		return $this->userRepository->findById((int) $userId);
	}
}
?>