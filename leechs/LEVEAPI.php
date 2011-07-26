<?php
/**
 * This class used to request information from EVE API
 *
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class LEVEAPI {

	/**
	 * Link to EVE API's server root URL
	 * @var string
	 */
	private static $root;

	/**
	 * Limited API key
	 * @var string
	 */
	private static $limitedKey;

	/**
	 * Full API key
	 * @var string
	 */
	private static $fullKey;

	/**
	 * Create API connector
	 *
	 * @param $serverAddress Link to EVE API's server root URL
	 * @param $limitedAPIKey Limited API key
	 * @param $fullAPIKey Full API key
	 * @return LEVEAPI API connector
	 */
	public function __construct ($serverAddress, $limitedAPIKey, $fullAPIKey) {
		if (isset($serverAddress)) {
			self::$root = $serverAddress;
		}
		if (isset($limitedAPIKey)) {
			self::$limitedKey = $limitedAPIKey;
		}
		if (isset($fullAPIKey)) {
			self::$fullKey = $fullAPIKey;
		}
	}
	
	/**
	 * Returns limited API key
	 * 
	 * @return string
	 */
	public function getLimitedKey() {
		return self::$limitedKey;		
	}

	/**
	 * Returns full API key
	 * 
	 * @return string
	 */
	public function getFullKey() {
		return self::$fullKey;		
	}
	
	/**
	 * Get server status
	 *
	 * @return mixed Server status
	 */
	public function getServerStatus() {
		$response = $this->curl_get(self::$root.'server/ServerStatus.xml.aspx');
		return $response;
	}

	/**
	 * Parse XML to PHP object
	 * 
	 * @param $input XML-string
	 * @param $recurse 
	 * @return mixed PHP object
	 */
	public function unserializeXml($input, $recurse = false)
	{
		try {
			$data = ((!$recurse) && is_string($input))? simplexml_load_string($input): $input;
			if ($data instanceof SimpleXMLElement) $data = (array) $data;
			if (is_array($data)) foreach ($data as &$item) $item = $this->unserializeXml($item, true);
			return $data;
		} catch (Exception $e) {
			throw new Exception ('Can\'t parse response from API: '. $e->getMessage());
		}
	}
	
	/**
	 * Send a POST requst using cURL
	 * Contributed from http://www.php.net/manual/en/function.curl-exec.php
	 *
	 * @param string $url to request
	 * @param array $post values to send
	 * @param array $options for cURL
	 * @return string
	 */
	public function curl_post($url, array $post = array(), array $options = array())
	{
		$defaults = array(
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_URL => $url,
		CURLOPT_FRESH_CONNECT => 1,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FORBID_REUSE => 1,
		CURLOPT_TIMEOUT => 4,
		CURLOPT_POSTFIELDS => http_build_query($post)
		);

		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	/**
	 * Send a GET requst using cURL
	 * Contributed from http://www.php.net/manual/en/function.curl-exec.php
	 *
	 * @param string $url to request
	 * @param array $get values to send
	 * @param array $options for cURL
	 * @return string
	 */
	public function curl_get($url, array $get = array(), array $options = array())
	{
		$defaults = array(
		CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_TIMEOUT => 4
		);

		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}
}
?>