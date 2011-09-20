<?php

/**
 * Decorator that add cache abilities to EVE API client
 * Returns EVE API results as objects 
 *  
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class EVEAPICacheDecorator {

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
     *   - Check request for already cached
     *   - Execute request if result not cached or cache is out-of-date   
     *   - Return cached result otherwise
     *    
     * @param $method EVEAPI method
     * @param $params EVEAPI method arguments
     * @return mixed response as object
     */
    public function __call($method, $params) {
        // try get from cache		
        if ($response=Core::getChachedAPIResponse($method, serialize($params))) {
            // get from cache
            $result = $this->api->unserializeXml($response);
        } else {
            // get from EVE API
            echo 'GOTO EVE API';
            $result = call_user_func_array(array($this->api, $method), $params);
        }
        return $result;
    }

}

?>