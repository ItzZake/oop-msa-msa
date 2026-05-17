<?php
	require_once 'Database.php';

	class UserRepository {

		function findByEmail(string $email) {
			// Code to find a user by email
			// request database, return user object
			Database::getInstance()->fetchOne("SELECT * FROM Users WHERE email = ?", [$email]);
		}

		function findById(int $userId) {
			// Code to find a user by ID
			// request database, return user object
			Database::getInstance()->fetchOne("SELECT * FROM Users WHERE userId = ?", [$userId]);
		}

		function save (User $user) {
			Database::getInstance()->query("INSERT INTO Users (userId, email) VALUES (?, ?)", [$user->getId(), $user->getEmail()]);
		}

		function create (User $user) {
			;
		}
	}
?>