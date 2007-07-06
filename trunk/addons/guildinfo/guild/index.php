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
 * @version    SVN: $Id: guildinfo.php 798 2007-04-15 00:00:43Z Zanix $
 * @link       http://www.wowroster.net
 * @package    GuildInfo
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['guildinfo'];

$guild_info_text = empty($roster->data['guild_info_text']) ? '&nbsp;' : $roster->data['guild_info_text'];

print messagebox('<div class="GuildInfoText">' . nl2br($guild_info_text) . '</div>',$roster->locale->act['guildinfo'],'syellow');

