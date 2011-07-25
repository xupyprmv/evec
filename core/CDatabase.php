<?php
/**
 * Database connector as singleton.
 *  
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class CDatabase {
	
	/**
	 * Database connection
	 * @var PDO 
	 */
	private static $db;
	
	/**
	 * CDatabase instance
	 * @var CDatabase
	 */
	private static $instance;
	
	/**
	 * Create CDatabase instance
	 * 
	 * @param $dsn data source name for PDO
	 * @param $user DB username
	 * @param $password DB password
	 * @return CDatabase
	 */
	public static function getInstance($dsn='mysql:dbname=evec;host=127.0.0.1', $user = 'evec', $password = 'evec') {
		if (!isset(self::$instance)) {
			try {			
	    		self::$db = new PDO($dsn, $user, $password);
	    		self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
	    		$className = __CLASS__;
            	self::$instance = new $className;
			} catch (PDOException $e) {
				throw Exception('Connection failed: ' . $e->getMessage());
			}
		}
		return self::$instance;		
	}
	
	/**
	 * Install EVEC : create all necessary tables
	 * 
	 * @return void
	 */
	public function install() {
		$evecSQL = file_get_contents('./core/evec.sql');
		$tables = explode(';',$evecSQL);		
		try {
        	self::$db->beginTransaction();
        	foreach ($tables as $table) {
        		self::$db->exec($table);
        	}
        	self::$db->commit();
    	} catch(PDOException $e) {
    		self::$db->rollBack();
    		throw Exception('Can\'t install EVEC: '. $e->getMessage());    	
    	}
	} 
	
	public function logAPIRequest($uid, $apiKey, $requestFunction, $requestArguments) {
		try {
			self::$db->beginTransaction();
			$stmt = self::$db->prepare('INSERT INTO api_request_log (uniqid, apiKey, requestFunction, requestArguments, evecTime) VALUES (?, ?, ?, ?, NOW())');
			$stmt->bindValue(1, $uid, PDO::PARAM_STR);
            $stmt->bindValue(2, $apiKey, PDO::PARAM_STR);
            $stmt->bindValue(3, $requestFunction, PDO::PARAM_STR);
            $stmt->bindValue(4, $requestArguments, PDO::PARAM_STR);
            $stmt->execute();
       		self::$db->commit();		
    	} catch(PDOException $e) {
    		self::$db->rollBack();
    		throw Exception('Can\'t install EVEC: '. $e->getMessage());    	
    	}		
	}
	
	public function logAPIResponse($uid, $serverTime, $cacheTime, $response) {
		try {
			self::$db->beginTransaction();
			$stmt = self::$db->prepare('UPDATE api_request_log SET serverTime = ?, cacheTime = ?, response =? WHERE uniqid = ?');
			$stmt->bindValue(1, $serverTime, PDO::PARAM_STR);
            $stmt->bindValue(2, $cacheTime, PDO::PARAM_STR);
            $stmt->bindValue(3, $response, PDO::PARAM_STR);
            $stmt->bindValue(4, $uid, PDO::PARAM_STR);
            $stmt->execute();
       		self::$db->commit();		
    	} catch(PDOException $e) {
    		self::$db->rollBack();
    		throw Exception('Can\'t install EVEC: '. $e->getMessage());    	
    	}				
	}
	
	// restrict access to PHP magic methods
	private function __construct() {	
	}
	private function __clone() {	
	}
	private function __wakeup() {	
	}
}
?>