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
