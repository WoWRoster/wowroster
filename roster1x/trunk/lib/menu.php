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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

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

// Get list of addons and their links
$addons = makeAddonList();


?>
<!-- Begin WoWRoster Menu -->

<?php print border('syellow','start'); ?>

<table cellspacing="0" cellpadding="4" border="0" class="main_roster_menu">
  <tr>
<?php
print '
    <td colspan="5" align="center" valign="top" class="header">
      <span style="font-size:18px;"><a href="'.$roster_conf['website_address'].'">'.$roster_conf['guild_name'].'</a></span>
      <span style="font-size:11px;"> @ '.$roster_conf['server_name'].' ('.$roster_conf['server_type'].')</span><br />';
print $wordings[$roster_conf['roster_lang']]['update'].': <span style="color:#0099FF;">'.$updateTime;

if( !empty($roster_conf['timezone']) )
	print ' ('.$roster_conf['timezone'].')';

?>
</span></td>
  </tr>
  <tr>
    <td colspan="5" class="simpleborderbot syellowborderbot"></td>
  </tr>
  <tr>
<!-- Links Column 1 -->
<?php
if( $roster_conf['menu_left_pane'] && $guild_data_rows > 0 )
{
	print '   <td rowspan="2" valign="top" class="row">';

	print $wordings[$roster_conf['roster_lang']]['members'].': '.$num_non_alts.' (+'.$num_alts.' Alts)
      <br />
      <ul>
        <li style="color:#999999;">Average Level: '.round($result_avg[0]).'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 60: '.$num_lvl_60.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 50-59: '.$num_lvl_50_59.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 40-49: '.$num_lvl_40_49.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 30-39: '.$num_lvl_30_39.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 1-29: '.$num_lvl_1_29.'</li>
      </ul></td>';
}
?>
    <td valign="top" class="row links">
      <ul>
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
?>
      </ul></td>
<!-- Links Column 2 -->
    <td valign="top" class="row links">
      <ul>
<?php
if( $roster_conf['menu_pvp_page'] && $roster_conf['pvp_log_allow'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexpvp.php">'.$wordings[$roster_conf['roster_lang']]['pvplist'].'</a></li>'."\n";

if( $roster_conf['menu_honor_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexhonor.php">'.$wordings[$roster_conf['roster_lang']]['menuhonor'].'</a></li>'."\n";

if( $roster_conf['menu_guildbank'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/guildbank'.$roster_conf['guildbank_ver'].'.php">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></li>'."\n";

if( $roster_conf['menu_keys_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexinst.php">'.$wordings[$roster_conf['roster_lang']]['keys'].'</a></li>'."\n";

if( $roster_conf['menu_tradeskills_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/tradeskills.php">'.$wordings[$roster_conf['roster_lang']]['professions'].'</a></li>'."\n";
?>
     </ul></td>
<!-- Links Column 3 -->
    <td valign="top" class="row<?php print (($roster_conf['menu_right_pane'] && $guild_data_rows > 0) ? '' : 'right'); ?> links">
      <ul>
<?php
if( $roster_conf['menu_update_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/update.php">'.$wordings[$roster_conf['roster_lang']]['upprofile'].'</a></li>'."\n";

if( $roster_conf['menu_quests_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexquests.php">'.$wordings[$roster_conf['roster_lang']]['team'].'</a></li>'."\n";

if( $roster_conf['menu_search_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexsearch.php">'.$wordings[$roster_conf['roster_lang']]['search'].'</a></li>'."\n";
?>
        <li><a href="<?php print $roster_conf['roster_dir']; ?>/admin.php"><?php print $wordings[$roster_conf['roster_lang']]['roster_config']; ?></a></li>
        <li><a href="<?php print $roster_conf['roster_dir']; ?>/credits.php"><?php print $wordings[$roster_conf['roster_lang']]['credit']; ?></a></li>
      </ul></td>
<?php
if( $roster_conf['menu_right_pane'] && $guild_data_rows > 0 )
{
	print '    <td rowspan="2" valign="top" class="rowright">';

	if( $roster_conf['rs_mode'] )
	{
		print '<img alt="WoW Server Status" src="'.$roster_conf['roster_dir'].'/realmstatus.php" /></td>';
	}
	elseif( file_exists(ROSTER_BASE.'realmstatus.php') )
	{
		include_once (ROSTER_BASE.'realmstatus.php');
	}
	else
	{
		print '&nbsp;</td>';
	}
}
?>
  </tr>
<!-- Addon Links -->
<?php
if( $addons != '' )
{
	print "  <tr>\n    <td colspan=\"3\" align=\"center\" valign=\"top\" class=\"row".(($roster_conf['menu_right_pane'] && $guild_data_rows > 0) ? '' : 'right')." addon\" style=\"width:320px;\">\n";
	print '<span style="color:#0099FF;font-weight:bold;">'.$wordings[$roster_conf['roster_lang']]['Addon'].'</span>';
	print "    <ul>\n";
	print $addons;
	print "    </ul></td>\n  </tr>\n";
}
?>
</table>

<?php print border('syellow','end'); ?>
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

	list($month,$day,$year,$hour,$minute,$second) = sscanf($updateTimeUTC,"%d/%d/%d %d:%d:%d");
	$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);

	return date($phptimeformat[$roster_conf['roster_lang']], $localtime);
}


/**
 * Gets the list of currently installed roster addons
 *
 * @return string formatted list of addons
 */
function makeAddonList()
{
	global $roster_conf, $wordings;

	$addonsPath = ROSTER_BASE.'addons';

	// Initialize output
	$output = '';

	if ($handle = opendir($addonsPath))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..')
			{
				$addons[] = $file;
			}
		}
	}

	$aCount = 0; //addon count
	$lCount = 0; //link count

	if(count($addons) != '')
	{
		foreach ($addons as $addon)
		{
			$menufile = $addonsPath.DIR_SEP.$addon.DIR_SEP.'menu.php';
			if (file_exists($menufile))
			{
				$localizationFile = $addonsPath.DIR_SEP.$addon.DIR_SEP.'localization.php';
				if (file_exists($localizationFile))
				{
					include_once($localizationFile);
				}

				include_once($menufile);

				if (0 >= $config['menu_min_user_level']) //modify this line for user level / authentication stuff (show the link for user level whatever for this addon)  you understand :P
				{
					if (isset($config['menu_index_file'][0]))
					{
						//$config['menu_index_file'] is the new array type
						foreach ($config['menu_index_file'] as $addonLink)
						{
							$fullQuery = "?roster_addon_name=$addon" . $addonLink[0];
							$query = str_replace(' ','%20',$fullQuery);
							$output .= '<li><a href="'.$roster_conf['roster_dir'].'/addon.php'.$query.'">' . $addonLink[1]."</a></li>\n";
							$lCount++;
						}
					}
				}
			}
		}
	}

	if ($lCount < 1)
	{
		return '';
	}

	return $output;
}

?>