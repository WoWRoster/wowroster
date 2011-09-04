<?php
/**
 * Battle.net WoW API PHP SDK
 *
 * This software is not affiliated with Battle.net, and all references
 * to Battle.net and World of Warcraft are copyrighted by Blizzard Entertainment.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   WoWAPI-PHP-SDK
 * @author	  Chris Saylor
 * @author	  Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright Copyright (c) 2011, Chris Saylor
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link	  https://github.com/cjsaylor/WoWAPI-PHP-SDK
 * @version    SVN: $Id$
 */

require_once ROSTER_API . 'resource/Resource.php';

/**
 * Realm resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class character extends Resource {
	protected $methods_allowed = array(
		'character',
	);
	var $x = '';
/*
	var $items = true;
	var $stats = true;
	var $reputation = true;
	var $skills = true;
	var $achievements = true;
	var $statistics = true;
	var $talents = true;
	var $appearance = true;
	var $titles = true;
	var $mounts = true;
	var $companions = true;
	var $pets = true;
	var $PvP = true;
*/
	function build_fields($data)
	{
		$fds = explode(":",$data);
		
		$this->x='';
		foreach ($fds as $fd => $s)
		{
			switch($s)
			{
				case '1':
					$this->x.= ',items';
				break;
				case '2':
					$this->x.= ',stats';
				break;
				case '3':
					$this->x.= ',reputation';
				break;
				case '4':
					$this->x.= ',skills';
				break;
				case '5':
					$this->x.= ',achievements';
				break;
				case '6':
					$this->x.= ',statistics';
				break;
				case '7':
					$this->x.= ',talents';
				break;
				case '8':
					$this->x.= ',titles';
				break;
				case '9':
					$this->x.= ',mounts';
				break;
				case '10':
					$this->x.= ',companions';
				break;
				case '11':
					$this->x.= ',pets';
				break;
				case '12':
					$this->x.= ',PvP';
				break;
			}
		
		}
		echo $this->x.'<br>';
		return $this->x;
	}
	/**
	 * Get status results for specified realm(s).
	 *
	 * @param $char charactername, $realm realm name
	 * @return mixed
	 */
	public function getCharInfo($realm, $char, $fields) {
		$this->charn = $char;
		$this->realmn = $realm;
		if (empty($realm)) {
			throw new ResourceException('No realms specified.');
		} elseif (empty($char)) {
			throw new ResourceException('No char name specified.');
		} else {
			if ($fields !='')
			{
				$fd ='?fields='.$this->build_fields($fields);
			}
			$realm_str = $realm.'/'.$char;

			$data = $this->consume('character', array(
			'data' => $fd,
			'dataa' => $realm.'/'.$char,
			'server' => $realm,
			'name' => $char,
			'header'=>"Accept-language: enUS\r\n"
			));
		}
		return $data;
	}
}
