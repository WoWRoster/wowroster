<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays the guild information text
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['guildinfo'];

// ----[ Check log-in ]-------------------------------------
if( ! $roster->auth->getAuthorized( $addon['config']['guildinfo_access'] ) )
{
	print
	'<span class="title_text">' . $roster->locale->act['guildinfo'] . '</span><br />'.
	$roster->auth->getLoginForm();

	return;
}
// ----[ End Check log-in ]---------------------------------

$guild_info_text = empty($roster->data['guild_info_text']) ? '&nbsp;' : $roster->data['guild_info_text'];

print messagebox('<div class="infotext">' . nl2br($guild_info_text) . '</div>',$roster->locale->act['guildinfo'],'syellow');
