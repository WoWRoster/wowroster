<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$header_title = $act_words['Guild_Info'];
include_once(ROSTER_BASE.'roster_header.tpl');



if ( $roster_conf['index_motd'] == 1 && !empty($guild_info['guild_motd']) )
{
	if( $roster_conf['motd_display_mode'] )
	{
		print '<img src="motd.php" alt="Guild message of the day" /><br /><br />';
	}
	else
	{
		echo '<span class="GMOTD">Guild MOTD: '.$guild_info['guild_motd'].'</span><br /><br />';
	}
}

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');


if( !empty($guild_info['guild_info_text']) )
{
	print messagebox('<div class="GuildInfoText">'.nl2br($guild_info['guild_info_text']).'</div>',$act_words['Guild_Info'],'syellow');
}


include_once(ROSTER_BASE.'roster_footer.tpl');
