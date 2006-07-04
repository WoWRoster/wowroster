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
$subdir = '../../';
require_once($subdir.'settings.php');

$guild_name_escaped = $wowdb->escape($roster_conf['guild_name']);
$server_name_escaped = $wowdb->escape($roster_conf['server_name']);
$query = "SELECT `guild_id`, `guild_dateupdatedutc` FROM `".ROSTER_GUILDTABLE."` WHERE `guild_name` = '$guild_name_escaped' AND `server` ='$server_name_escaped'";

$guild_data = $wowdb->query($query);
$guild_data_rows = $wowdb->num_rows($guild_data);

if( $guild_data && $guild_data_rows > 0 )
{
	if ($row = $wowdb->fetch_assoc($guild_data))
	{
		$guildId = $row['guild_id'];
		$updateTimeUTC = $row['guild_dateupdatedutc'];
		$updateTime = DateDataUpdated($updateTimeUTC);
	}
	else
	{
		die_quietly($wowdb->error(),'Could not connect to database',basename(__FILE__),__LINE__,$query);
	}

	$result_menu = $wowdb->query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND ".$roster_conf['alt_location']." NOT LIKE '%".$roster_conf['alt_type']."%'") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_non_alts = $wowdb->num_rows($result_menu);

	$result_menu = $wowdb->query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND ".$roster_conf['alt_location']." LIKE '%".$roster_conf['alt_type']."%'") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_alts = $wowdb->num_rows($result_menu);

	$result_menu = $wowdb->query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND `level` = 60") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_lvl_60 = $wowdb->num_rows($result_menu);

	$result_menu = $wowdb->query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND `level` > 49 and `level` < 60") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_lvl_50_59 = $wowdb->num_rows($result_menu);

	$result_menu = $wowdb->query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND `level` > 39 and `level` < 50") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_lvl_40_49 = $wowdb->num_rows($result_menu);

	$result_menu = $wowdb->query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND `level` > 29 and `level` < 40") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_lvl_30_39 = $wowdb->num_rows($result_menu);

	$result_menu = $wowdb->query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE `guild_id` = $guildId AND `level` > 0 and `level` < 30") or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
	$num_lvl_1_29 = $wowdb->num_rows($result_menu);

	$result_avg = $wowdb->fetch_array($wowdb->query("SELECT AVG(level) FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId")) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__);
}

?>
<!-- Begin WoWRoster Menu -->

<table cellspacing="0" cellpadding="4" border="1" style="font:11px Arial, Helvetica, sans-serif;">
  <tr>
<?php
print
'    <td colspan="2" align="left" valign="top" height="15">
      <span style="font-size:16px;"><a href="'.$roster_conf['website_address'].'">'.$roster_conf['guild_name'].'</a></span>
      <span style="font-size:10px;"> @ '.$roster_conf['server_name'].' ('.$roster_conf['server_type'].')</span><br />
      <span style="font-size:10px;">'.$wordings[$roster_conf['roster_lang']]['update'].': '.$updateTime;

if ($roster_conf['timezone'])
	print ' ('.$roster_conf['timezone'].')';

print '</span></td>';

?>

  </tr>
  <tr>
<!-- Links Column 1 -->
    <td valign="top" width="50%">
      <ul style="margin:0;padding-left:15px;padding-right:15px;">
        <li><a href="<?php print $roster_conf['roster_dir']; ?>/index.php"><?php print $wordings[$roster_conf['roster_lang']]['roster']; ?></a></li>
<?php
if( $roster_conf['menu_byclass'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/index.php?s=class">'.$wordings[$roster_conf['roster_lang']]['byclass'].'</a></li>'."\n";

if( $roster_conf['menu_alt_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexalt.php">'.$wordings[$roster_conf['roster_lang']]['alternate'].'</a></li>'."\n";

if( $roster_conf['menu_guild_info'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/guildinfo.php">'.$wordings[$roster_conf['roster_lang']]['Guild_Info'].'</a></li>'."\n";

if( $roster_conf['menu_stats_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexstat.php">'.$wordings[$roster_conf['roster_lang']]['menustats'].'</a></li>'."\n";

if( $roster_conf['menu_pvp_page'] && $roster_conf['pvp_log_allow'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexpvp.php">'.$wordings[$roster_conf['roster_lang']]['pvplist'].'</a></li>'."\n";

if( $roster_conf['menu_honor_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexhonor.php">'.$wordings[$roster_conf['roster_lang']]['menuhonor'].'</a></li>'."\n";

if( $roster_conf['menu_guildbank'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/guildbank'.$roster_conf['guildbank_ver'].'.php">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></li>'."\n";
?>
      </ul></td>
<!-- Links Column 2 -->
    <td valign="top" width="50%">
      <ul style="margin:0;padding-left:15px;padding-right:15px;">
<?php
if( $roster_conf['menu_keys_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexinst.php">'.$wordings[$roster_conf['roster_lang']]['keys'].'</a></li>'."\n";

if( $roster_conf['menu_tradeskills_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/tradeskills.php">'.$wordings[$roster_conf['roster_lang']]['professions'].'</a></li>'."\n";

if( $roster_conf['menu_update_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/admin/update.php">'.$wordings[$roster_conf['roster_lang']]['upprofile'].'</a></li>'."\n";

if( $roster_conf['menu_quests_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexquests.php">'.$wordings[$roster_conf['roster_lang']]['team'].'</a></li>'."\n";

if( $roster_conf['menu_search_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexsearch.php">'.$wordings[$roster_conf['roster_lang']]['search'].'</a></li>'."\n";
?>
        <li><a href="<?php print $roster_conf['roster_dir']; ?>/admin/config.php"><?php print $wordings[$roster_conf['roster_lang']]['roster_config']; ?></a></li>
        <li><a href="<?php print $roster_conf['roster_dir']; ?>/credits.php"><?php print $wordings[$roster_conf['roster_lang']]['credit']; ?></a></li>
      </ul></td>
  </tr>
</table>

<br />
<!-- End WoWRoster Menu -->

<?php


/**
 * Calculates the last updated value
 *
 * @param string $updateTimeUTC dateupdatedutc
 * @return string formatted date string
 */
function DateDataUpdated($updateTimeUTC)
{
	global $roster_conf, $phptimeformat;

	$day = substr($updateTimeUTC,3,2);
	$month = substr($updateTimeUTC,0,2);
	$year = substr($updateTimeUTC,6,2);
	$hour = substr($updateTimeUTC,9,2);
	$minute = substr($updateTimeUTC,12,2);
	$second = substr($updateTimeUTC,15,2);

	$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);

	return date($phptimeformat[$roster_conf['roster_lang']], $localtime);
}

?>