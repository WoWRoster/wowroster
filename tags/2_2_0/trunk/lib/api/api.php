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

if( !defined('IN_ROSTER') ) {
	exit('Detected invalid access to this file!');
}

require_once ROSTER_API . 'resource/Realm.php';
require_once ROSTER_API . 'resource/Char.php';
require_once ROSTER_API . 'resource/Guild.php';
require_once ROSTER_API . 'resource/Talents.php';
require_once ROSTER_API . 'resource/Data.php';
require_once ROSTER_API . 'apiitem.php';
//require_once ROSTER_API . 'colorapiitem.php';

class WowAPI {
	/**
	 * @var \Realm Realm object instance.
	 */
	public $Realm; // realm object
	public $Char; // char object
	public $Guild; // guild Object
	public $Team; // team Object
	public $Data; // Blizzard Data Objects..
	public $Talents; // char name nyi
	public $Aitem;
	public $cItem;
	
	public $locale;// = ''.strtoupper($region).'';
	public $region;// = ''.strtoupper($region).'';
	public $_locale;
	public $api_url;
	protected $pub_key;
	protected $pri_key;
	//now to test and see if we have what we need
	
	public function __construct($region='us')
	{
		global $roster;
		// Check for required extensions
		if (!function_exists('curl_init')) {
			throw new Exception('Curl is required for api usage.');
		}

		if (!function_exists('json_decode')) {
			throw new Exception('JSON PHP extension required for api usage.');
		}
		$conf = array(
			'pub_key' => $roster->config['api_key_public'],
			'pri_key' => $roster->config['api_key_private'],
			'debug' => true,
			'region' => $roster->config['api_url_region'],// this is for the url you want to make requests from	
			'locale' => $roster->config['api_url_locale'],// thsi is the language you want the data translated in to
		);

		$this->region = $conf['locale'];
		$this->_locale = $roster->config['api_url_locale'];
		$this->locale = $conf['region'];
		$this->Settings($conf);
		
		$this->Realm = new Realm($region);
		$this->Char = new character($region);
		$this->Guild = new guild($region);
		$this->Data = new Data(strtoupper($region));
		$this->Item = new ApiItem();
	//	$this->cItem = new ApiColorItem();
		//$this->Team = new team($region);
		$this->Talents = new talents($region);
	}
	
	public function Settings($array)
	{
		$this->regionsw($array['locale']);
		define('API_DEBUG', $array['debug']);
		define('API_pub_key', $array['pub_key']);
		define('API_pri_key', $array['pri_key']);
	
	}
	
	public function regionsw($region)
	{
		$e = '';
		switch($region)
		{
			case 'en_US':
			case 'es_MX':
			case 'pt_BR':
			//define(API_URI, 'http://us.battle.net/');
			$this->api_url = 'http://us.battle.net/';
			$e = 'http://us.battle.net/';
			break;

			case 'en_GB':
			case 'es_ES':
			case 'fr_FR':
			case 'ru_RU':
			case 'de_DE':
			//define(API_URI, 'http://eu.battle.net/');
			$this->api_url = 'http://eu.battle.net/';
			$e = 'http://eu.battle.net/';
			break;

			case 'ko_kr':
			//define(API_URI, 'http://kr.battle.net/');
			$this->api_url = 'http://kr.battle.net/';
			$e = 'http://kr.battle.net/';
			break;

			case 'zh_TW':
			//define(API_URI, 'http://tw.battle.net/');
			$this->api_url = 'http://tw.battle.net/';
			$e = 'http://tw.battle.net/';
			break;

			case 'zh_CN':
			//define(API_URI, 'http://battlenet.com.cn/');
			$this->api_url = 'http://battlenet.com.cn/';
			$e = 'http://battlenet.com.cn/';
			break;
		}
		define('API_URI', $e);
	}
	
}
