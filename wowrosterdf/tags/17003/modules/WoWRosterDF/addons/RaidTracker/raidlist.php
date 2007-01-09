<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

// Zone Selection
if (isset($_REQUEST["zonefilter"]))
           $zone = $_REQUEST["zonefilter"];

$form = '';
$form .= '<table cellpadding="0" cellspacing="0" class="membersList">';
$form .= '<form action="index.php?name='.$module_name.'&amp;file=addon" method=GET name=myform>';
$form .= '<input type="hidden" name="roster_addon_name" value="RaidTracker">';
$form .= '<tr><th class="membersRow1">'.$rt_wordings[$roster_conf['roster_lang']]['Zone'].':</th>';
$form .= '<td class="membersRow1">';
$form .= '<select name="zonefilter">';
if ($zone == '') {
	$is_selected = ' selected';
} else {
	$is_selected = '';
}
$form .= '<option value="" '.$is_selected.'>'.$rt_wordings[$roster_conf['roster_lang']]['AllZones'].'</option>';

	$query = 'SELECT distinct zone FROM `'.$db_prefix.'raids` ORDER BY zone ASC';

	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while ($row = $wowdb->fetch_array($result)) {
		$zone_stripped = stripslashes($zone);
		
		if ($zone_stripped == $row['zone']) {
			$is_selected = ' selected';
		} else {
			$is_selected = '';
		}
		$form .= '<option value="'.$row['zone'].'"'.$is_selected.'>';
		if($row['zone'] != 'RandomRaid'){
			$form .= $rt_wordings[$roster_conf['roster_lang']]['Zones'][$row['zone']];
		}else{
			$form .= $rt_wordings[$roster_conf['roster_lang']][$row['zone']];
		}
		$form .= '</option>' ;
	}

$form .= '</select></td>';
$form .= '<td class="membersRow1"><input type="submit" value="submit" /></td>';
$form .= '</tr></form></table>';

// Display the Instance select Form in a stylish border
echo border('sgray','start');
echo $form;
echo border('sgray','end');

echo "<br/>";

// Display the Top / left side of the Stylish Border
echo border('syellow', 'start', $rt_wordings[$roster_conf['roster_lang']]['RaidList']);

// Make a table to hold the content
echo '<table cellpadding="0" cellspacing="0" class="membersList">';

// Display the header of the table
echo '<tr>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Zone'].'</th>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Date'].'</th>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['BossKills'].'</th>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['LootCount'].'</th>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Attendees'].'</th>';
echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Note'].'</th>';
echo '</tr>';

// Check if we have a Zone Filter
$zone_where = '';
if ($zone != '') {
	$zone_where = ' WHERE zone = \''.$zone.'\' ';
}

// Get all raids
$query = 'SELECT raidnum, raidid, zone, note FROM `'.$db_prefix.'raids` '.$zone_where.' ORDER BY raidid DESC, zone ASC';

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

$rownum=1;
while ($row = $wowdb->fetch_array($result)) {

// If loot is 0 don't show it
$query = 'SELECT count(*) FROM `'.$db_prefix.'raiditems` WHERE raidnum = '.$row['raidnum'];

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$loot_result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$loot_row = $wowdb->fetch_array($loot_result);

if($loot_row[0] == 0){}else{
// Open a new Row
		echo '<tr>';
// Display the zone in first column
echo '<td class="membersRow'.$rownum.'"> '.getZoneIcon($row['zone']);
echo '<a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&raid='.$row['raidnum'].'">';
if($row['zone'] != 'RandomRaid'){
	echo $rt_wordings[$roster_conf['roster_lang']]['Zones'][$row['zone']].'</a></td>';
}else{
	echo $rt_wordings[$roster_conf['roster_lang']][$row['zone']].'</a></td>';
}
// Display the date in second column
echo '<td class="membersRow'.$rownum.'">';
echo date($addon_conf['RaidTracker']['DateView'], strtotime($row['raidid'])).'</td>';

// Get bosskills
$query = 'SELECT count(*) FROM `'.$db_prefix.'raidbosskills` WHERE raidnum = '.$row['raidnum'];

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$kill_result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$kill_row = $wowdb->fetch_array($kill_result);

echo '<td class="membersRow'.$rownum.'">';
echo $kill_row[0].'</td>';

// Set Lootcount
echo '<td class="membersRow'.$rownum.'">';
echo $loot_row[0].'</td>';


// Get number of raidattendees
$query = 'SELECT DISTINCT name FROM `'.$db_prefix.'raidjoins` WHERE raidnum = '.$row['raidnum'];

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$attendees_result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$attendees = 0;
while ($attendees_row = $wowdb->fetch_array($attendees_result)) {
$attendees = $attendees + 1; 
}

echo '<td class="membersRow'.$rownum.'">';
echo $attendees.'</td>';

// Set note
echo '<td style="text-align:center;" class="membersRowRight'.$rownum.'">';
echo getNoteIcon($row['note']).'</td>';


// Close the Row
		echo '</tr>';
		switch ($rownum) {
		case 1:
			$rownum=2;
			break;
		default:
			$rownum=1;
	}
	}
}
// Close the table
echo '</table>';

// Display the Right side / Bottom of the Stylish Border
echo border('syellow','end');

$wowdb->free_result($result);
?>