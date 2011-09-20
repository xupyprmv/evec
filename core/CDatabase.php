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
        $tables = explode(';', $evecSQL);
        try {
            self::$db->beginTransaction();
            foreach ($tables as $table) {
                self::$db->exec($table);
            }
            self::$db->commit();
        } catch (PDOException $e) {
            self::$db->rollBack();
            throw Exception('Can\'t install EVEC: ' . $e->getMessage());
        }
    }

    /**
     * Log EVE API request to database 
     * 
     * @param $uid unique identifier for request
     * @param $apiKey apiKey
     * @param $requestFunction EVE API method
     * @param $requestArguments request parameters
     * @return void
     */
    public function logAPIRequest($uid, $apiKey, $requestFunction, $requestArguments) {
        try {
            self::$db->beginTransaction();
            $stmt = self::$db->prepare('INSERT INTO api_request_log (uniqid, requestFunction, requestArguments, evecTime) VALUES (?, ?, ?, NOW())');
            $stmt->bindValue(1, $uid, PDO::PARAM_STR);
            $stmt->bindValue(2, $requestFunction, PDO::PARAM_STR);
            $stmt->bindValue(3, $requestArguments, PDO::PARAM_STR);
            $stmt->execute();
            self::$db->commit();
        } catch (PDOException $e) {
            self::$db->rollBack();
            throw Exception('Can\'t log EVE API request: ' . $e->getMessage());
        }
    }

    /**
     * Log EVE API response to database 
     * 
     * @param $uid unique identifier of request
     * @param $serverTime time on EVE server
     * @param $cacheTime next time request aviable
     * @param $response response string
     * @return void
     */
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
        } catch (PDOException $e) {
            self::$db->rollBack();
            throw Exception('Can\'t log EVE API response: ' . $e->getMessage());
        }
    }

    /**
     * Try to return cached EVE API request result from database 
     * 
     * - Correctly works when YOUR server time not equals EVE server time
     * 
     * @param $requestFunction EVE API method
     * @param $requestArguments request parameters
     * @return string result or null
     */
    public function getChachedAPIResponse($requestFunction, $requestArguments) {
        try {
            $stmt = self::$db->prepare('SELECT response FROM api_request_log 
                WHERE requestFunction=?
                AND requestArguments=?
                AND NOW()< TIMESTAMPADD(MINUTE, TIMESTAMPDIFF(MINUTE, serverTime, evecTime), cacheTime) LIMIT 1;');
            $stmt->bindValue(1, $requestFunction, PDO::PARAM_STR);
            $stmt->bindValue(2, $requestArguments, PDO::PARAM_STR);
            if (($stmt->execute()) && ($result=$stmt->fetch(PDO::FETCH_ASSOC))) {
                echo 'RETURN FROM CACHE';
                return $result['response'];
            } else {
                echo 'RETURN FROM SERVER';
                return null;
            }     
        } catch (PDOException $e) {
            throw Exception('Can\'t get result from cache EVEC: ' . $e->getMessage());
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