<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2.0
 * @package    WoWRoster
 */

require_once ROSTER_API . 'tools/Curl.php';
require_once ROSTER_API . 'tools/url.php';
require_once ROSTER_API . 'tools/ResourceException.php';
require_once ROSTER_API . 'tools/HttpException.php';

/**
 * Resource skeleton
 * 
 * @throws ResourceException If no methods are defined.
 */
abstract class Resource {
	/**
	 * API uri for Wow's API
	 */
	const API_URI = 'http://%s.battle.net/';

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
		global $roster;
		$makecache = false;
		$msg = '';
		if (!in_array($method, $this->methods_allowed)) 
		{
			throw new ResourceException('Method not allowed.', 405);
		}
		// new prity url builder ... much better then befor...
		$ui = sprintf(self::API_URI, $this->region);

		// new cache system see hwo old teh file is only live update files more then X days/hours old

			$url = $this->url->BuildUrl($ui,$method,$params['server'],$params['name'],$params);
			if (isset($_GET['debug']))
			{
				echo '--[ '.$url.' ]--<br>';
			}
			$data = $this->Curl->makeRequest($url,null, $params,$url,$method);
			if ($this->Curl->errno !== CURLE_OK) 
			{
				throw new ResourceException($this->Curl->error, $this->Curl->errno);
			}
			
			// update the tracker...
			$q = "SELECT * FROM `" . $roster->db->table('api_usage') . "` WHERE `date`='".date("Y-m-d")."' AND `type` = '".$method."'";
			$y = $roster->db->query($q);
			$row = $roster->db->fetch($y);
			if (!isset($row['total']))
			{
				$query = 'INSERT INTO `' . $roster->db->table('api_usage') . '` VALUES ';
				$query .= "('','".$method."','".date("Y-m-d")."','+1'); ";
			}
			else
			{
				$query = "Update `" . $roster->db->table('api_usage') . "` SET `total`='".($row['total']+1)."' WHERE `type` = '".$method."' AND `date` = '".date("Y-m-d")."'";
			}
			$ret = $roster->db->query($query);
			
			//Battle.net returned a HTTP error code
			$x = json_decode($data['response'], true);
			if (isset($data['response_headers']) && $data['response_headers']['http_code'] != '200') 
			{
				$msg = $this->transhttpciode($data['response_headers']['http_code']);
				//throw new HttpException(json_decode($data['response'], true), $data['response_headers']['http_code']);
				$this->seterrors(array('type'=>$method,'msg'=>''.$msg.'<br>'.$url.''));
				//$this->query['result'] = false; // over ride cache and set to false no data or no url no file lol
			}
		
			//$makecache
			if (isset($x['reason']))
			{
				$this->seterrors(array('type'=>$method,'msg'=>$x['reason']));
				$this->query['result'] = false; // over ride cache and set to false no data or no url no file lol
			}
			//print_r($data['response_headers']);
			$data = json_decode($data['response'], true);
			$info = $data;//$this->utf8_array_decode($data);


		return $info;
	}
	function seterrors($errors)
	{
		$this->errors[] = $errors;
	}


	/**
	 * Returns all errors
	 *
	 * @return string
	 */
	function geterrors()
	{
		return implode("\n",$this->errors) . "\n";
	}


	/**
	 * Resets the stored errors
	 *
	 */
	function reseterrors()
	{
		$this->errors = array();
	}
	public function transhttpciode($code)
	{
		switch ($code)
		{
			case '404':
				return 'A request was made to a resource that doesn\'t exist.';
			break;
			case '500':
				return 'If at first you don\'t succeed, blow it up again';			
			break;
			case '200':
				return 'Access to this API url is Restricted';			
			break;
			case '303':
				return 'Local Cache file used.';			
			break;
			
			default:
			break;
		}	
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
