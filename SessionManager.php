<?php
class SessionManager
{


	public static function Start()
	{
		// code when the teacher start a session should work
		if (session_status() == PHP_SESSION_ACTIVE) {
			return;
		}

		session_set_cookie_params([
			'lifetime' => 0, // Session cookie will expire when the browser is closed
			'path' => '/',
			'secure' => true, // HTTPS only
			'httponly' => true, // Prevent JavaScript access to session cookie
			'samesite' => 'Strict'
		]);

		session_start();
	}

	public static function Set($key, $val)
	{
		// code when the teacher set a session
		$_SESSION[$key] = $val;
	}

	public static function Get($key)
	{
		// to get session key
		return $_SESSION[$key] ?? null;
	}

	public static function Destroy()
	{
		// to close session key
		if (!session_status() == PHP_SESSION_ACTIVE) {
			return;
		}

		session_unset();
		session_destroy();
	}

	public static function Regenerate()
	{
		// code to regenerate the session
		if (!session_status() == PHP_SESSION_ACTIVE) {
			return;
		}
		session_regenerate_id(true); // true = delete old session file
	}

	public static function IsAuthenticated()
	{
		// Check if the user is authenticated
		return isset($_SESSION['user_id']);
	}
}
?>