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

require_once ROSTER_API . 'resource/Resource.php';

/**
 * Realm resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class Realm extends Resource {
	protected $methods_allowed = array(
		'status',
		'challenge'
	);

	
	public function getChallenge($realm) {
		$data = $this->consume('challenge', array(
			'data' => '',
			'dataa' => '',
			'server' => $realm,
			'name' => $realm,
			'header'=>"Accept-language: enUS\r\n"
		));

		return $data;
	}
	
	/**
	 * Get status results for all realms.
	 *
	 * @return array
	 */
	public function getAllRealmStatus() {
		return $this->consume('status');
	}

	/**
	 * Get status results for specified realm(s).
	 *
	 * @param mixed $realms String or array of realm(s)
	 * @return mixed
	 */
	public function getRealmStatus($realms = array()) {
		if (empty($realms)) {
			throw new ResourceException('No realms specified.');
		} elseif (!is_array($realms)) {
			$data = $this->consume('status', array(
				'data' => 'realm='.$realms
			));
		} else {
			$realm_str = '';
			foreach($realms as $key => $realm) {
				$realm_str .= (!$key ? '' : '&') . 'realm=' . urlencode($realm);
			}
			$data = $this->consume('status', array(
				'data' => $realm_str
			));
		}
		return $data;
	}
}
