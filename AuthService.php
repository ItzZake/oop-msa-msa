<?php

include_once 'SessionManager.php';
include_once 'User.php';
include_once 'UserRepository.php';

class AuthService {

	function __construct() {
		// code to initialize auth service, e.g. connect to database, set up session, etc.
		SessionManager::Start();
	}

	public static function register($email, $password, $role) {
		// code to register a new user
		$user = new User(null, $email, $password, null, null, null, $role, null, null);
		$userRepository = new UserRepository();
		$userRepository->save($user);
	}

	public static function login($email, $password) {
		SessionManager::login($email, $password);
	}

	public static function logout() {
		SessionManager::Destroy();
	}
}
?>