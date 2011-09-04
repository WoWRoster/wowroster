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
			'header'=>"Accept-language: enUS\r\n"
			));
		}
		return $data;
	}
}
