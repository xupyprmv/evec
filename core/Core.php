<?php
/**
 * Container for all others Core's classes (classes that used for connection to local databse, authentication, etc)
 * 
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class Core {
	
	/**
	 * EVE API connector
	 * @var EVEAPI
	 */
	private static $api = null;
	
	/**
	 * Create API connector
	 * 
	 * @param $serverAddress Link to EVE API's server root URL (default `http://api.eve-online.com/`)
	 * @param $limitedAPIKey Limited API key
	 * @param $fullAPIKey Full API key
	 * @return EVEAPI API connector
	 */
	public function getEVEAPI($fullAPIKey = null) {
		if (!empty($fullAPIKey) || empty(self::$api)) {
			$api = new EVEAPI($fullAPIKey);		
			$loggedAPI = new EVEAPILogDecorator($api);
			// TODO cache decorator
			self::$api = $loggedAPI;
		}		
		return self::$api;
	}	
	
	/**
	 * Connect to database
	 * 
	 * @param $dsn data source name for PDO
	 * @param $user DB username
	 * @param $password DB password
	 * @return void
	 */
	public static function conectToDB($dsn, $user, $password) {
		$db = CDatabase::getInstance($dsn, $user, $password);
	}
	
	/**
	 * Install EVEC (prepare all tables)
	 * 
	 * @return void
	 */
	public static function install() {
		$db = CDatabase::getInstance();
		$db->install();
	}
	
	/**
	 * Log EVE API request to database 
	 * 
	 * @param $apiKey apiKey
	 * @param $requestFunction EVE API method
	 * @param $requestArguments request parameters
	 * @return string unique identifier of request
	 */
	public static function logAPIRequest($apiKey, $requestFunction, $requestArguments) {
		$uid = uniqid();
		$db = CDatabase::getInstance();		
		$db->logAPIRequest($uid, $apiKey, $requestFunction, $requestArguments);
		return $uid;		
	}
	
	/**
	 * Log EVE API response to database 
	 * 
	 * @param $uid unique identifier of request
	 * @param $version version of EVE API method
	 * @param $serverTime time on EVE server
	 * @param $cacheTime next time request aviable
	 * @param $response response string
	 * @return void
	 */	
	public static function logAPIResponse($uid, $version, $serverTime, $cacheTime, $response) {
		// TODO think about API versions - check version compatibility
		$db = CDatabase::getInstance(); 
		$db->logAPIResponse($uid, $serverTime, $cacheTime, $response);
	}
	
	/**
	 * Outputs main page
	 * 
	 * @return void
	 */
	public static function viewMainPage() {
		$characters = Core::getEVEAPI($_SESSION['APIKey'])->getCharacters($_SESSION['userId']);
		include './templates/main.tpl';
	}
	
	/**
	 * Outputs error message
	 * 
	 * @return void
	 */
	public static function viewErrorPage($errorMessage) {
		include './templates/error.tpl';
	}
} 
?>