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
		if (!empty($_POST['userId']) && !empty($_POST['APIKey'])) {		
			$_SESSION['userId'] = $_POST['userId'];
			$_SESSION['APIKey'] = $_POST['APIKey'];
			
			// Check that server is on
			$status = Core::getEVEAPI()->getServerStatus();
			if (strtolower($status['result']['serverOpen'])!='true') {
				Core::viewErrorPage('EVE Server is offline');
			} else {			
				// Check that credential are valid
				$characters = Core::getEVEAPI($_SESSION['APIKey'])->getCharacters($_SESSION['userId']);
				if (!empty($characters['error'])) {
					// TODO Ban user IP after 3 unsuccessfull tries for 1 hour
					Core::viewErrorPage($characters['error']);				
				} else {
					$_SESSION['logged in'] = true;
					return true;
				}
			}					
		}
		CAuthentication::viewLoginPage();	
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