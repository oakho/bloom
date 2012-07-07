<?php

namespace Bloom\Auth;

use Bloom\Database\Query;

class Auth
{	
 	const AUTH_SALT = "H!7W|2lD";

	public static function login($email, $password)
	{
		$query = new Query("users");
		$user = $query->select("*")
					  ->where("email", "=", $email)
					  ->andWhere("password", "=", \Bloom\Utils\Hash::password($password, static::AUTH_SALT))
					  ->exec();
		
		if($user = $user->fetchObject("\\Application\\Models\\User")) {
			$_SESSION['user'] = serialize($user);
			return true;
		}

		return false;
	}

	public static function logout()
	{
		unset($_SESSION['user']);

		if(!isset($SESSION['user'])) {
			return true;
		}
		
		return false;
	}

	public static function isLogged()
	{
		return isset($_SESSION['user']) && (unserialize($_SESSION['user']) instanceof \Application\Models\User);
	}
}