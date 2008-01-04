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
$roster_login = new RosterLogin();

// Disallow viewing of the page
if( $roster_login->getAuthorized() < $addon['config']['guildinfo_access'] )
{
	include_once(ROSTER_BASE . 'header.php');
	$roster_menu = new RosterMenu;
	$roster_menu->makeMenu($roster->output['show_menu']);

	print
	'<span class="title_text">' . $roster->locale->act['guildinfo'] . '</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once(ROSTER_BASE . 'footer.php');
	exit();
}
else
{
	echo $roster_login->getMessage() . '<br />';
}
// ----[ End Check log-in ]---------------------------------

$guild_info_text = empty($roster->data['guild_info_text']) ? '&nbsp;' : $roster->data['guild_info_text'];

print messagebox('<div class="GuildInfoText">' . nl2br($guild_info_text) . '</div>',$roster->locale->act['guildinfo'],'syellow');

