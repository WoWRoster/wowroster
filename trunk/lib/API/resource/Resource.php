<?php

require_once dirname(__FILE__) . '/../tools/Curl.php';
require_once dirname(__FILE__) . '/../tools/url.php';
require_once dirname(__FILE__) . '/../tools/ResourceException.php';
require_once dirname(__FILE__) . '/../tools/HttpException.php';

/**
 * Resource skeleton
 * 
 * @throws ResourceException If no methods are defined.
 */
abstract class Resource {
	/**
	 * API uri for Wow's API
	 */
	const API_URI = 'http://%s.battle.net/api/wow/';
	const API_URI2 = 'http://%s.battle.net/wow/';

	/**
	 * @var string Serve region(`us` or `eu`)
	 */
	protected $region;

	/**
	 * Methods allowed by this resource (or available).
	 *
	 * @var array
	 */
	protected $methods_allowed;

	/**
	 * Curl object instance.
	 *
	 * @var \Curl
	 */
	protected $Curl;

	/**
	 * @throws ResourceException If no methods are allowed
	 * @param string $region Server region(`us` or `eu`)
	 */
	public function __construct($region='us') 
	{
		if (empty($this->methods_allowed)) 
		{
			throw new ResourceException('No methods defined in this resource.');
		}
		$this->region = $region;
		$this->Curl = new Curl();
		$this->url = new url();
	}

	/**
	 * Consumes the resource by method and returns the results of the request.
	 *
	 * @param string $method Request method
	 * @param array $params Parameters
	 * @throws ResourceException If request method is not allowed
	 * @return array Request data
	 */
	public function consume($method, $params=array()) 
	{
		if (!in_array($method, $this->methods_allowed)) 
		{
			throw new ResourceException('Method not allowed.', 405);
		}
		// new prity url builder ... much better then befor...
		$ui = sprintf(self::API_URI, $this->region);
		if ($method == 'talents')
		{
			$ui2 = sprintf(self::API_URI2, $this->region);
			$data = $this->Curl->makeRequest($this->url->BuildUrl($ui2,$method,$params[server],$params[name],$params));
		}
		else
		{
			$data = $this->Curl->makeRequest($this->url->BuildUrl($ui,$method,$params[server],$params[name],$params));
		}
		//cURL returned an error code
		
		if ($this->Curl->errno !== CURLE_OK) 
		{
			throw new ResourceException($this->Curl->error, $this->Curl->errno);
		}
		//Battle.net returned a HTTP error code
		if (!isset($data['response_headers']['http_code']) || $data['response_headers']['http_code'] !== 200) 
		{
			throw new HttpException(json_decode($data['response'], true), $data['response_headers']['http_code']);
		}
		
		return json_decode($data['response'], true);
	}

	/**
	 * Returns the URI for use with the request object
	 *
	 * @param string $method
	 * @return string API URI
	 *
	private function getResourceUri($method) {
		return sprintf(self::API_URI, $this->region) . strtolower(get_class($this)) . '/' . $method;
	}
	*/
}
