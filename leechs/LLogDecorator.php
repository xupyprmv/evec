<?php
/**
 * Decorator for loggin EVE API requests and responses 
 *  
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class LLogDecorator {
	/**
	 * EVE API
	 * 
	 * @var LEVEAPI
	 */
    private $api;

    public function __construct($api) {
        $this->api = $api;
    }

    public function __call($method, $params) {
		// log request		
		$uid = Core::logAPIRequest(($this->api->getFullKey() === null)?$this->api->getFullKey():
																	($this->api->getLimitedKeyKey() === null)?$this->api->getLimitedKeyKey():null, 
							$method, 
							serialize($params));
        $response = call_user_func_array(array($this->api, $method), $params);
        // convert XML response to object
        $result = $this->api->unserializeXml ($response);
		// log response
		Core::logAPIResponse($uid, $result['@attributes']['version'], 
							 $result['currentTime'], 
							 $result['cachedUntil'], $response);
        return $result;
    }
}
?>