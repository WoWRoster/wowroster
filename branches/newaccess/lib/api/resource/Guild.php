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
 * Guild resource.
 *
 * @throws ResourceException If no methods are defined.
 */
class Guild extends Resource {
	protected $methods_allowed = array(
		'guild'
	);

	/**
	 * Get status results for specified realm(s).
	 *
	 * @param mixed $realms String or array of realm(s)
	 * @return mixed
	 */
	public function getGuildInfo($rname, $name, $fields)
	{
		if (empty($rname)) {
			throw new ResourceException('No realms specified.');
		} elseif (empty($name)) {
			throw new ResourceException('No guild name specified.');
		} else {
			
			$data = $this->consume('guild', array(
			'data' => $fields,
			'dataa' => $name.'@'.$rname,
			'server' => $rname,
			'name' => $name,
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		}
		return $data;
	}
	
	public function getGuildrewards($rname, $name, $fields)
	{
		if (empty($rname)) {
			throw new ResourceException('No realms specified.');
		} elseif (empty($name)) {
			throw new ResourceException('No guild name specified.');
		} else {
			
			$data = $this->consume('grewards', array(
			'data' => $fields,
			'dataa' => $name.'@'.$rname.'-rewards',
			'server' => $rname,
			'name' => $name,
			'type' => 'GET',
			'header'=>"Accept-language: ".$this->region."\r\n"
			));
		}
		return $data;
	}
	
}
