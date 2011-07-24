<?php
function __autoload($class_name) {
	require_once './leechs/'.$class_name.'.php';	
}

/**
 * Container for all others Leech's classes (classes that get data from EVE API and push it to local database 
 * 
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class Leechs {
	/**
	 * Create API connector
	 * 
	 * @param $serverAddress Link to EVE API's server root URL (default `http://api.eve-online.com/`)
	 * @param $limitedAPIKey Limited API key
	 * @param $fullAPIKey Full API key
	 * @return LEVEAPI API connector
	 */
	public function __construct($serverAddress = 'http://api.eve-online.com/', $limitedAPIKey = null, $fullAPIKey = null) {
		$this->api = new LEVEAPI($serverAddress, $limitedAPIKey, $fullAPIKey);		
	}
	
	/**
	 * EVE API connector
	 * @var LEVEAPI
	 */
	private $api = null;
	
	/**
	 * Get EVE API
	 * 
	 * @param $serverAdress 
	 * @param $limitedKey
	 * @param $fullKey
	 * @return LEVEAPI EVE API connector
	 */
	public function getServerStatus() {
		return $this->api->getServerStatus();
	}
		
} 
?>