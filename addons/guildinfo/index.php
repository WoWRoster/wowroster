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
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$header_title = $act_words['Guild_Info'];


if( !empty($guild_info['guild_info_text']) )
{
	print messagebox('<div class="GuildInfoText">'.nl2br($guild_info['guild_info_text']).'</div>',$act_words['Guild_Info'],'syellow');
}
