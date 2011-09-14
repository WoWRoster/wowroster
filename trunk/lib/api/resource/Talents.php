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
class talents extends Resource {
	protected $methods_allowed = array(
		'talents',
	);

	
	/**
	 * Get status results for specified realm(s).
	 *
	 * @param $char talentsname, $realm realm name
	 * @return mixed
	 */
	public function getTalentInfo($class) {
		
		if (empty($class))
		{
			throw new ResourceException('No Class Given.');
		} 
		else
		{
			$data = $this->consume('talents', array(
			'data' => $fd,
			'dataa' => $realm.'/'.$char,
			'server' => $realm,
			'name' => $class,
			'header'=>"Accept-language: enUS\r\n"
			));
		}
		return $data;
	}
}
