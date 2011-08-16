<?php
/**
 * Decorator for logging EVE API requests and responses
 * Returns EVE API results as objects 
 *  
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class EVEAPILogDecorator {
	/**
	 * EVE API
	 * 
	 * @var EVEAPI
	 */
    private $api;

    public function __construct($api) {
        $this->api = $api;
    }

    /**
     * Intercept API method execution. 
     *   - Log request
     *   - Execute request   
     *   - Log response
     *   - Return unserialized response
     *    
     * @param $method EVEAPI method
     * @param $params EVEAPI method arguments
     * @return mixed response as object
     */
    public function __call($method, $params) {
		// log request		
		$uid = Core::logAPIRequest($this->api->getFullKey(), $method, serialize($params));
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