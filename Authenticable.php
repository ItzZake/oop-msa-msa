<?php
interface Authenticable
{
	function LogIn($email, $password)
	{
		//Code for log in
	}

	function LogOut()
	{
		// Code for Log Out
	}

	function ResetPassword($token, $newPassword)
	{
		// code for password reset
	}

	function ChangePassword($old, $new)
	{
		// code for ChangePassword
	}
}
?>