<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster guild scope functions
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.1.0
 * @package    WoWRoster
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

class GuildScope
{
	function set_tpl($data)
	{
		global $roster;

		/**
		 * Assigning everything this file may need to the template
		 * The only tpl vars not here are ones that need to be generated in their respective methods
		 */
		$roster->tpl->assign_vars(array(
			'SERVER' => $data['server'],
			'GUILD_NAME' => $data['guild_name'],
			'FACTION_EN' => strtolower($roster->data['factionEn']),
			'FACTION' => $roster->data['faction']
			)
		);
	}
}
