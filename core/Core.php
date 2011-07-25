<?php
/**
 * Container for all others Core's classes (classes that used for connection to local databse, authentication, etc)
 * 
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class Core {

	public static function conectToDB() {
		$db = CDatabase::getInstance($dsn, $user, $password);
	}
	
	public static function install() {
		$db = CDatabase::getInstance();
		$db->install(); //TODO create separated user-called method
	}
	
	public static function logAPIRequest($apiKey, $requestFunction, $requestArguments) {
		$uid = uniqid();
		$db = CDatabase::getInstance();		
		$db->logAPIRequest($uid, $apiKey, $requestFunction, $requestArguments);
		return $uid;		
	}
	
	public static function logAPIResponse($uid, $version, $serverTime, $cacheTime, $response) {
		// TODO think about API versions - check version compatibility
		$db = CDatabase::getInstance(); 
		$db->logAPIResponse($uid, $serverTime, $cacheTime, $response);
	}
} 
?>