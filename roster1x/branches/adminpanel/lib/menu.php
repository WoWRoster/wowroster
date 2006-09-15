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

define('ROSTER_MENU_INC',true);

if( !empty($guild_info) )
{
	$guildId = $guild_info['guild_id'];
	$updateTimeUTC = $guild_info['guild_dateupdatedutc'];
	$updateTime = DateDataUpdated($updateTimeUTC);

	$guildstat_query="SELECT IF(`".$roster_conf['alt_location']."` LIKE '%".$roster_conf['alt_type']."%',1,0) AS 'isalt',
		`level` DIV 10 AS levelgroup,
		COUNT(`level`) AS amount,
		SUM(`level`) AS sum
		FROM `".ROSTER_MEMBERSTABLE."`
		GROUP BY isalt, levelgroup
		ORDER BY isalt ASC, levelgroup DESC";
	$result_menu = $wowdb->query($guildstat_query);

	if (!$result_menu) {
		die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$guildstat_query);
	}

	$num_non_alts = 0;
	$num_alts = 0;

	$num_lvl_60 = 0;
	$num_lvl_50_59 = 0;
	$num_lvl_40_49 = 0;
	$num_lvl_30_39 = 0;
	$num_lvl_1_29 = 0;

	$level_sum = 0;

	while ($row = $wowdb->fetch_assoc($result_menu))
	{
		if ($row['isalt'])
		{
			$num_alts += $row['amount'];
		}
		else
		{
			$num_non_alts += $row['amount'];
		}

		switch ($row['levelgroup'])
		{
			case 6:
				$num_lvl_60 += $row['amount'];
				break;
			case 5:
				$num_lvl_50_59 += $row['amount'];
				break;
			case 4:
				$num_lvl_40_49 += $row['amount'];
				break;
			case 3:
				$num_lvl_30_39 += $row['amount'];
				break;
			case 2:
			case 1:
			case 0:
				$num_lvl_1_29 += $row['amount'];
				break;
			default:
		}
		$level_sum += $row['sum'];
	}

	$result_avg = $level_sum/($num_alts + $num_non_alts);
}

// Get list of addons and their links
if( !isset($fix_menu) )
{
	$addonslist = makeAddonList();
}
else
{
	$addonslist = '';
}


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
if( $roster_conf['menu_left_pane'] && !empty($guild_info) )
{
	print '   <td rowspan="2" valign="top" class="row">';

	print $wordings[$roster_conf['roster_lang']]['members'].': '.$num_non_alts.' (+'.$num_alts.' Alts)
      <br />
      <ul>
        <li style="color:#999999;">Average Level: '.round($result_avg).'</li>
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
if( $roster_conf['menu_guild_info'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/guildinfo.php">'.$wordings[$roster_conf['roster_lang']]['Guild_Info'].'</a></li>'."\n";

if( $roster_conf['menu_stats_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexstat.php">'.$wordings[$roster_conf['roster_lang']]['menustats'].'</a></li>'."\n";

if( $roster_conf['menu_tradeskills_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/tradeskills.php">'.$wordings[$roster_conf['roster_lang']]['professions'].'</a></li>'."\n";

if( $roster_conf['menu_guildbank'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/guildbank'.$roster_conf['guildbank_ver'].'.php">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></li>'."\n";
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

if( $roster_conf['menu_memberlog'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/memberlog.php">'.$wordings[$roster_conf['roster_lang']]['memberlog'].'</a></li>'."\n";

if( $roster_conf['menu_keys_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexinst.php">'.$wordings[$roster_conf['roster_lang']]['keys'].'</a></li>'."\n";
?>
     </ul></td>
<!-- Links Column 3 -->
    <td valign="top" class="row<?php print ( ($roster_conf['menu_right_pane'] ) ? '' : 'right'); ?> links">
      <ul>
        <li><a href="<?php print $roster_conf['roster_dir']; ?>/rostercp.php"><?php print $wordings[$roster_conf['roster_lang']]['roster_config']; ?></a></li>
<?php
if( $roster_conf['menu_quests_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexquests.php">'.$wordings[$roster_conf['roster_lang']]['team'].'</a></li>'."\n";

if( $roster_conf['menu_search_page'] )
	print '        <li><a href="'.$roster_conf['roster_dir'].'/indexsearch.php">'.$wordings[$roster_conf['roster_lang']]['search'].'</a></li>'."\n";
?>

        <li><a href="<?php print $roster_conf['roster_dir']; ?>/credits.php"><?php print $wordings[$roster_conf['roster_lang']]['credit']; ?></a></li>
      </ul></td>
<?php
if( $roster_conf['menu_right_pane'] && !empty($guild_info) )
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
<?php
if( $addonslist != '' )
{
	print "<!-- Addon Links -->\n";
	print "  <tr>\n    <td colspan=\"3\" align=\"center\" valign=\"top\" class=\"row".(($roster_conf['menu_right_pane'] && !empty($guild_info)) ? '' : 'right')." addon\" style=\"width:320px;\">\n";
	print '<span style="color:#0099FF;font-weight:bold;">'.$wordings[$roster_conf['roster_lang']]['Addon'].'</span>';
	print "    <ul>\n";
	print $addonslist;
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
	global $act_words, $roster_conf, $wordings, $wowdb;

	$query = "SELECT `addon`.`dbname`,`menu`.`title`,`menu`.`url`,`addon`.`basename`,`addon`.`description`,`addon`.`fullname` FROM `".$wowdb->table('addon_menu')."` AS menu LEFT JOIN `".$wowdb->table('addon')."` AS addon ON `menu`.`addon_id` = `addon`.`addon_id` WHERE `menu`.`active` = 1 AND `addon`.`active` = 1";

	$result = $wowdb->query($query);

	if (!$result)
	{
		die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	}

	$addons = array();
	$output = '';
	while ($row = $wowdb->fetch_assoc($result))
	{
		if (!in_array($row['dbname'],$addons))
		{
			$addons[] = $row['dbname'];
			$addonDir = ROSTER_ADDONS.$row['basename'].DIR_SEP;
			$localizationFile = $addonDir.'localization.php';
			if (file_exists($localizationFile))
			{
				include_once($localizationFile);
			}
		}

		$fullQuery = "?dbname=".$row['dbname'].$row['url'];
		$query = str_replace(' ','%20',$fullQuery);
		$output .= '<li><a href="'.ROSTER_URL.'/addon.php'.$query.'" '.makeOverlib($row['description'],$row['fullname']).'>'.$act_words[$row['title']]."</a></li>\n";
	}

	return $output;
}

?>