<?php

if (!defined('CPG_NUKE')) { exit; }

global $db, $prefix;

# Get the name of the module from the filename
# Filename should be in the format:
#   block-$module_name[-center][-scroll][-extra_info].php
#
# Where:
#   $module_name is the name of the module
#   -center is added if this is a center block
#   -scroll is added if this is a scrolling block
#   -extra_info is added if there is extra information for this block (ie. Last 5 Topics, Last 5 Posts, Hottest 5 Topics, etc)
$block_file_info = explode('-', substr(basename(__FILE__), 6, (basename(__FILE__) - 4)));

$module_name= $block_file_info[0];

if (is_active($module_name))
{
	$result = $db->sql_query('SELECT guild_motd FROM '.$prefix.'_'.$module_name.'_roster_guild');

	while( $row = $db->sql_fetchrow($result) )
	{
	   $guildMOTD = $row['guild_motd'];
	}

	$db->sql_freeresult($result);

	$content .= '<center>'.$guildMOTD.'</center><br /> ';
}
