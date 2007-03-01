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


if( is_array($guild_info) )
{
	$updateTime = DateDataUpdated($guild_info['guild_dateupdatedutc']);

	$guildstat_query = "SELECT IF(`".$roster_conf['alt_location']."` LIKE '%".$wowdb->escape($roster_conf['alt_type'])."%',1,0) AS 'isalt',
		FLOOR(`level`/10) AS levelgroup,
		COUNT(`level`) AS amount,
		SUM(`level`) AS sum
		FROM `".ROSTER_MEMBERSTABLE."`
		GROUP BY isalt, levelgroup
		ORDER BY isalt ASC, levelgroup DESC";
	$result_menu = $wowdb->query($guildstat_query);

	if (!$result_menu)
	{
		die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$guildstat_query);
	}

	$num_non_alts = 0;
	$num_alts = 0;

	$num_lvl_70 = 0;
	$num_lvl_60_69 = 0;
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
			case 7:
				$num_lvl_70 += $row['amount'];
				break;
			case 6:
				$num_lvl_60_69 += $row['amount'];
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

if(!isset($updateTime))
{
	$updateTime = $wordings[$roster_conf['roster_lang']]['none'];
}
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
if( $roster_conf['menu_left_pane'] && is_array($guild_info) )
{
	print '   <td rowspan="2" valign="top" class="row">';

	print $wordings[$roster_conf['roster_lang']]['members'].': '.$num_non_alts.' (+'.$num_alts.' Alts)
      <br />
      <ul>
        <li style="color:#999999;">Average Level: '.round($result_avg).'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 70: '.$num_lvl_70.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 60-69: '.$num_lvl_60_69.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 50-59: '.$num_lvl_50_59.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 40-49: '.$num_lvl_40_49.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 30-39: '.$num_lvl_30_39.'</li>
        <li>'.$wordings[$roster_conf['roster_lang']]['level'].' 1-29: '.$num_lvl_1_29.'</li>
      </ul></td>';
}
?>
    <td valign="top" class="row links">
      <ul>
        <li><a href="<?php print makelink($roster_conf['default_page']); ?>"><?php print $wordings[$roster_conf['roster_lang']]['roster']; ?></a></li>
<?php

if( $roster_conf['menu_member_page'] && $roster_conf['default_page'] != 'members' )
	print '        <li><a href="'.makelink('members').'">'.$wordings[$roster_conf['roster_lang']]['members'].'</a></li>'."\n";

if( $roster_conf['menu_guild_info'] )
	print '        <li><a href="'.makelink('guildinfo').'">'.$wordings[$roster_conf['roster_lang']]['Guild_Info'].'</a></li>'."\n";

if( $roster_conf['menu_stats_page'] )
	print '        <li><a href="'.makelink('guildstats').'">'.$wordings[$roster_conf['roster_lang']]['menustats'].'</a></li>'."\n";

if( $roster_conf['menu_guildbank'] )
	print '        <li><a href="'.makelink('guildbank').'">'.$wordings[$roster_conf['roster_lang']]['guildbank'].'</a></li>'."\n";
?>
      </ul></td>
<!-- Links Column 2 -->
    <td valign="top" class="row links">
      <ul>
<?php
if( $roster_conf['menu_pvp_page'] && $roster_conf['pvp_log_allow'] )
	print '        <li><a href="'.makelink('guildpvp').'">'.$wordings[$roster_conf['roster_lang']]['pvplist'].'</a></li>'."\n";

if( $roster_conf['menu_honor_page'] )
	print '        <li><a href="'.makelink('guildhonor').'">'.$wordings[$roster_conf['roster_lang']]['menuhonor'].'</a></li>'."\n";

if( $roster_conf['menu_memberlog'] )
	print '        <li><a href="'.makelink('memberlog').'">'.$wordings[$roster_conf['roster_lang']]['memberlog'].'</a></li>'."\n";

if( $roster_conf['menu_keys_page'] )
	print '        <li><a href="'.makelink('keys').'">'.$wordings[$roster_conf['roster_lang']]['keys'].'</a></li>'."\n";

if( $roster_conf['menu_tradeskills_page'] )
	print '        <li><a href="'.makelink('tradeskills').'">'.$wordings[$roster_conf['roster_lang']]['professions'].'</a></li>'."\n";
?>
     </ul></td>
<!-- Links Column 3 -->
    <td valign="top" class="row<?php print (($roster_conf['menu_right_pane'] && is_array($guild_info)) ? '' : 'right'); ?> links">
      <ul>
<?php
if( $roster_conf['menu_update_page'] )
	print '        <li><a href="'.makelink('update').'">'.$wordings[$roster_conf['roster_lang']]['upprofile'].'</a></li>'."\n";

if( $roster_conf['menu_quests_page'] )
	print '        <li><a href="'.makelink('questlist').'">'.$wordings[$roster_conf['roster_lang']]['team'].'</a></li>'."\n";

if( $roster_conf['menu_search_page'] )
	print '        <li><a href="'.makelink('search').'">'.$wordings[$roster_conf['roster_lang']]['search'].'</a></li>'."\n";
?>
        <li><a href="<?php print makelink('admin').'">'.$wordings[$roster_conf['roster_lang']]['roster_config']; ?></a></li>
        <li><a href="<?php print makelink('credits').'">'.$wordings[$roster_conf['roster_lang']]['credit']; ?></a></li>
      </ul></td>
<?php
if( $roster_conf['menu_right_pane'] && is_array($guild_info) )
{
	print '    <td rowspan="2" valign="top" class="rowright">';

	if( $roster_conf['rs_mode'] )
	{
		print '<img alt="WoW Server Status" src="realmstatus.php" /></td>';
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
	print "  <tr>\n    <td colspan=\"3\" align=\"center\" valign=\"top\" class=\"row".(($roster_conf['menu_right_pane'] && is_array($guild_info)) ? '' : 'right')." addon\" style=\"width:320px;\">\n";
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
