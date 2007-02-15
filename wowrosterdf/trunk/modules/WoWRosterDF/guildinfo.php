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

 require(BASEDIR . 'modules/' . $module_name . '/settings.php');


//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	message_die( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

// Get guild info from guild info check above
$GuildInfo = $guild_info['guild_info_text'];
$guildMOTD = $guild_info['guild_motd'];

$header_title = $wordings[$roster_conf['roster_lang']]['Guild_Info'];

include_once(ROSTER_BASE.'roster_header.tpl');



if ( $roster_conf['index_motd'] == 1 && !empty($guildMOTD) )
{
	if( $roster_conf['motd_display_mode'] )
	{
		print '<img src="'.getlink($module_name.'&amp;file=motd').'" alt="'.$guildMOTD.'" /><br /><br />';
	}
	else
	{
		echo '<span class="GMOTD">Guild MOTD: '.$guildMOTD.'</span><br /><br />';
	}
}

include_once (ROSTER_BASE.'lib/menu.php');


if( !empty($GuildInfo) )
{
	print border('syellow','start',$wordings[$roster_conf['roster_lang']]['Guild_Info']).'<div class="GuildInfoText">'.nl2br($GuildInfo).'</div>'.border('syellow','end');
}


include_once(ROSTER_BASE.'roster_footer.tpl');
?>