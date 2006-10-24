<?php
global $db;  
       $result = $db->sql_query("SELECT guild_motd FROM cms_wowrosterdf_roster_guild");
       while ($row = $db->sql_fetchrow($result)) {
           $guildMOTD =  $row['guild_motd'];
		  
       }
       $db->sql_freeresult($result);

$module_name= 'wowrosterdf';
if (!defined('CPG_NUKE')) { exit; }

if (is_active($module_name))
{
$content .= $guildMOTD;
    //$content .= '<center><img src="index.php?name='.$module_name.'&amp;file=motd&amp;motd='.urlencode($guildMOTD).'" alt="Guild Message of the Day" /><br /><br /></center>';
	
}
 