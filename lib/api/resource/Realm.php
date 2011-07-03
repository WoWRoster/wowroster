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
 */

require_once 'Resource.php';

/**
 * Realm resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class Realm extends Resource {
	protected $methods_allowed = array(
		'status'
	);

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
