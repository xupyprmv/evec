<?php
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
		$api = new LEVEAPI($serverAddress, $limitedAPIKey, $fullAPIKey);
		$loggedAPI = new LLogDecorator($api);
		$this->api = $loggedAPI;  		
	}
	
	/**
	 * EVE API connector
	 * @var LEVEAPI
	 */
	private $api = null;
	
	/**
	 * Get status of EVE server
	 * 
	 * @return mixed result as object
	 */
	public function getServerStatus() {
		return $this->api->getServerStatus();
	}
		
} 
?>