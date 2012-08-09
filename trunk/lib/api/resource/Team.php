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
require_once 'Resource.php';

/**
 * Realm resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class Team extends Resource {

	protected $region;
	
	protected $methods_allowed = array(
		'teams',
		'ladder',
	);
	var $x = '';

	public function getTeamInfo($realm, $name, $size) {

		if (empty($realm)) {
			throw new ResourceException('No realms specified.');
		} elseif (empty($name)) {
			throw new ResourceException('No team name specified.');
		}
		
		$data = $this->consume('teams', array(
			'data' => $fd,
			'dataa' => $realm.'/'.$name,
			'server' => $realm,
			'name' => $name,
			'size' => $size,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		
		return $data;
	}
	
	public function getLadderInfo($battlegroup, $size) {

		if (empty($battlegroup)) {
			throw new ResourceException('No battlegroup specified.');
		} elseif (empty($size)) {
			throw new ResourceException('No team size specified.');
		}

			$data = $this->consume('ladder', array(
			'data' => $fd,
			'dataa' => $battlegroup.'/'.$size,
			'server' => $battlegroup,
			'size' => $size,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		
		return $data;
	}
	
}
