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
	public function __construct ($serverAddress, $fullAPIKey) {
		if (isset($serverAddress)) {
			self::$root = $serverAddress;
		}
		if (isset($fullAPIKey)) {
			self::$fullKey = $fullAPIKey;
		}
	}
	
	/**
	 * Returns full API key
	 * 
	 * @return string
	 */
	public function getFullKey() {
		return self::$fullKey;		
	}
	
// API BLOCK [BEGIN]
	
	/**
	 * Get server status
	 *
	 * @return mixed Server status
	 */
	public function getServerStatus() {
		$response = $this->curl_get(self::$root.'server/ServerStatus.xml.aspx');
		return $response;
	}
	
/*	
<?xml version='1.0' encoding='UTF-8'?>
<eveapi version="2">
  <currentTime>2007-12-12 11:48:50</currentTime>
  <result>
    <rowset name="characters" key="characterID" columns="name,characterID,corporationName,corporationID">
      <row name="Mary" characterID="150267069" corporationName="Starbase Anchoring Corp" corporationID="150279367" />
      <row name="Marcus" characterID="150302299" corporationName="Marcus Corp" corporationID=150333466" />
      <row name="Dieniafire" characterID="150340823" corporationName="center for Advanced Studies" corporationID="1000169" />
    </rowset>
  </result>
  <cachedUntil>2007-12-12 12:48:50</cachedUntil>
</eveapi>
*/
	public function getCharacters($userId) {
		$post = array();
		$post['apiKey'] = self::getFullKey();
		$post['userID'] = $userId;		
		$response = $this->curl_get(self::$root.'account/Characters.xml.aspx', $post);
		return $response;
	}
	
	public function getCharacterWalletTransactions($userId) {
		
	}
// API BLOCK [END]

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