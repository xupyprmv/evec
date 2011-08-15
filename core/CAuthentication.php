<?php
class CAuthentication {
	
	public static $x = '123';
	
	/**
	 * Is user already log in?
	 * 
	 * @return bool
	 */
	public static function isAuthenticatied() {
		return (!empty($_SESSION['logged in']) && $_SESSION['logged in']=='true');
	}
	
	/**
	 * Try to login 
	 * 
	 * @param $userId EVE user identifier
	 * @param $fullAPIKey
	 * @return bool log in success
	 */
	public static function login($userId = null, $fullAPIKey = null) {
		// TODO Check that credential are valid
		if (!empty($_POST['userId']) && !empty($_POST['APIKey'])) {
			echo '2';
			$_SESSION['userId'] = $_POST['userId'];
			$_SESSION['APIKey'] = $_POST['APIKey'];
			$_SESSION['logged in'] = true;
			return true;
		} 
		return false;
	}
	
	/**
	 * Outputs login page
	 * 
	 * @return void
	 */
	public static function viewLoginPage() {
		include './templates/login.tpl';
	}
}
?>