<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
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

require_once( 'settings.php' );

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

$header_title = $wordings[$roster_conf['roster_lang']]['Guild_Info'];

include_once(ROSTER_BASE.'roster_header.tpl');


echo $roster_menu->makeMenu('main');


if( !empty($guild_info['guild_info_text']) )
{
	print border('syellow','start',$wordings[$roster_conf['roster_lang']]['Guild_Info']).'<div class="GuildInfoText">'.nl2br($guild_info['guild_info_text']).'</div>'.border('syellow','end');
}


include_once(ROSTER_BASE.'roster_footer.tpl');
?>