<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.1.1
 * @package    WoWRoster
 */

if( !defined('IN_ROSTER') ) {
	exit('Detected invalid access to this file!');
}

require_once ROSTER_API . 'resource/Realm.php';
require_once ROSTER_API . 'resource/Char.php';
require_once ROSTER_API . 'resource/Guild.php';
require_once ROSTER_API . 'resource/Talents.php';

class WowAPI {
	/**
	 * @var \Realm Realm object instance.
	 */
	public $Realm; // realm object
	public $Char; // char object
	public $Guild; // guild Object
	public $Team; // team Object
	public $Talents; // char name nyi
	public $realmn; // realm name nyi
	public $guildn; // guild name nyi
	
	//now to test and see if we have what we need
	
	public function __construct($region='us') {
		// Check for required extensions
		if (!function_exists('curl_init')) {
			throw new Exception('Curl is required for api usage.');
		}

		if (!function_exists('json_decode')) {
			throw new Exception('JSON PHP extension required for api usage.');
		}

		$this->Realm = new Realm($region);
		$this->Char = new character($region);
		$this->Guild = new guild($region);
		//$this->Team = new team($region);
		$this->Talents = new talents($region);
	}
}
