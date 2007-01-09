<?php
$versions['versionDate']['raidtracker'] = '$Date: 2006/08/13 $'; 
$versions['versionRev']['raidtracker'] = '$Revision: 1.1 $';
$versions['versionAuthor']['raidtracker'] = '$Author: PoloDude $';

if (!defined("CPG_NUKE")) { exit; }

global $rt_wordings, $roster_conf;

require_once './modules/'.$module_name.'/lib/item.php';
require_once './modules/'.$module_name.'/lib/wowdb.php';

// Server (for public roster use)
$server_name=$roster_conf['server_name'];

$raidnum = $_REQUEST["raid"];

// Loot type Selection
if (isset($_REQUEST["lootfilter"]))
	$loot = $_REQUEST["lootfilter"];

$form = '';
$form .= '<table cellpadding="0" cellspacing="0" class="membersList">';
$form .= '<form action="index.php?name='.$module_name.'&amp;file=addon&amp;" method=GET name=myform>';
$form .= '<input type="hidden" name="roster_addon_name" value="RaidTracker">';
$form .= '<input type="hidden" name="raid" value="'.$raidnum.'">';
$form .= '<tr><th class="membersRow1">'.$rt_wordings[$roster_conf['roster_lang']]['LootType'].':</th>';
$form .= '<td class="membersRow1">';
$form .= '<select name="lootfilter">';
if ($loot == '') {
	$is_selected = ' selected';
} else {
	$is_selected = '';
}
$form .= '<option value="" '.$is_selected.'>'.$rt_wordings[$roster_conf['roster_lang']]['AllTypes'].'</option>';

	foreach($rt_wordings[$roster_conf['roster_lang']]['LootTypes'] as $color => $name) {
		$query = 'SELECT color FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\' AND raidnum = '.$raidnum.' '.$loot_where;
		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		$row = $wowdb->fetch_array($result);
		
		if($row){
			if ($loot == $color) {
				$is_selected = ' selected';
			} else {
				$is_selected = '';
		}
		$form .= '<option style="background-color:#000000;color:#'.substr($color, 2, 6).'" value="'.$color.'"'.$is_selected.'>'.$name.'</option>';
		}
	}

$form .= '</select></td>';
$form .= '<td class="membersRow1"><input type="submit" value="submit" /></td>';
$form .= '</tr></form></table>';

// Display the Instance select Form in a stylish border
echo border('sgray','start');
echo $form;
echo border('sgray','end');

echo "<br/>";

// Display all loottype tables next to each other
echo '<table cellpadding="0" cellspacing="0" class="membersList"><tr style="vertical-align:top;">';
echo '<td>';

if(!$addon_conf['RaidTracker']['SortByUser']){

		// Check if color is in db
		foreach($rt_wordings[$roster_conf['roster_lang']]['LootTypes'] as $color => $name) {
			$query = 'SELECT color FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\' AND raidnum = '.$raidnum.' '.$loot_where;;
			$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
			$row = $wowdb->fetch_array($result);
			
			if($row){
				if($loot == '')
					getLoot($raidnum,$color);
				if($color == $loot)
					getLoot($raidnum,$color);
			}
		}
}else{
// Display Loot

// Check if we have a Loot Filter
$loot_where = '';
if ($loot != '') {
	$loot_where = ' AND color = \''.$loot.'\' ';
}

// Get winners
$wquery = 'SELECT DISTINCT name FROM `'.$db_prefix.'raiditems` WHERE raidnum = '.$raidnum.' '.$loot_where.' ORDER BY name ASC';
if ($roster_conf['sqldebug'])
{
	print "<!-- $wquery -->\n";
}
$wresult = $wowdb->query($wquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$wquery);
while($wrow = $wowdb->fetch_array($wresult)) {
	$title = '<div style="text-align:left;">'.getClass($wrow['name']) . $wrow['name'].'</div>';
	echo border('syellow', 'start', $title);
		
		// Make a table to hold the content
		echo '<table cellpadding="0" cellspacing="0" width="250px" class="membersList">';
		
		// Display the header of the table
		echo '<tr>';
		echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Looted'].'</th>';
		echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Note'].'</th>';
		echo '</tr>';
		
		// Check if color is in db
		foreach($rt_wordings[$roster_conf['roster_lang']]['LootTypes'] as $color => $name) {
			$query = 'SELECT color FROM `'.$db_prefix.'raiditems` WHERE color = \''.$color.'\' AND raidnum = '.$raidnum.' '.$loot_where;
			$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
			$row = $wowdb->fetch_array($result);
			
			if($row){
				if($loot == '')
					getLootByName($raidnum,$color,$wrow['name']);
				if($color == $loot)
					getLootByName($raidnum,$color,$wrow['name']);
			}
		}
	
	// Close the table
		echo '</table>';
		
	echo border('syellow','end');
	echo '<br/>';
}
}
echo '</td><td style="width:10px"></td><td>';

// Display bosskills if there are any
$query = 'SELECT count(*) FROM `'.$db_prefix.'raidbosskills` WHERE raidnum = '.$raidnum;
	
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$count = $wowdb->fetch_array($result);
if($count[0] != 0){
	// Display the Top / left side of the Stylish Border
	echo border('syellow', 'start', $rt_wordings[$roster_conf['roster_lang']]['BossKill']);
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Boss'].'</th>';
	echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['KillTime'].'</th>';
	echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['KillCount'].'</th>';
	echo '</tr>';
	
	// Get bosses
	$query = 'SELECT boss, time FROM `'.$db_prefix.'raidbosskills` WHERE raidnum = '.$raidnum.' ORDER BY time ASC';
	
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}
	
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	$i = 0;
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
	$i = $i + 1;
	// Open a new Row
			echo '<tr>';
	
	// Display the boss in first column
	echo '<td class="membersRow'.$rownum.'">';
	foreach($rt_wordings[$roster_conf['roster_lang']]['Bosses'] as $zone){
		$boss = $zone[$row['boss']];
		if($boss != NULL)
			echo ' <a href="index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=RaidTracker&display=history&bossfilter='.addslashes($row['boss']).'">'.$boss.'</a></td>';
	}
	
	// Display the killtime
	$killtime = date("G:i:s", strtotime($row['time']));
	echo '<td class="membersRow'.$rownum.'">';
			echo ' '.$killtime.'</td>';
	
	// Display the killcount
	$kquery = 'SELECT count(*) FROM `'.$db_prefix.'raidbosskills` WHERE boss = \''.addslashes($row['boss']).'\'';
	
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $kquery -->\n";
	}
	
	$kresult = $wowdb->query($kquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$kquery);
	$krow = $wowdb->fetch_array($kresult);
	echo '<td class="membersRowRight'.$rownum.'">';
			echo ' '.$krow[0].'</td>';
	
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
	
	// Add total kills at bottom
		echo '<tr><th colspan="3" class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['TotalKills'].': '.$i.'</th></tr>';
	
	// Close the table
			echo '</table>';
			
		echo border('syellow','end');
		echo '<br/>';
	echo '</td><td style="width:10px"></td><td>';
}



// Display attendees
// Display the Top / left side of the Stylish Border
echo border('syellow', 'start', $rt_wordings[$roster_conf['roster_lang']]['Raiders']);

// Make a table to hold the content
echo '<table cellpadding="0" cellspacing="0" class="membersList">';

// Display the header of the table
echo '<tr>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Name'].'</th>';
echo '<th class="membersHeader">'.$rt_wordings[$roster_conf['roster_lang']]['Joined'].'</th>';
echo '<th class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['Left'].'</th>';
echo '</tr>';

// Get all attendees
$query = 'SELECT DISTINCT name FROM `'.$db_prefix.'raidjoins` WHERE raidnum = '.$raidnum.' ORDER BY name ASC';

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
$i = 0;
$rownum=1;
while ($row = $wowdb->fetch_array($result)) {
$i = $i + 1;
// Open a new Row
		echo '<tr>';

$icon_value = getClass($row['name']);

// Display the name in first column
echo '<td class="membersRow'.$rownum.'">' . $icon_value;
	
	// Check if char is in guild
	$gquery = 'SELECT member_id FROM '.ROSTER_MEMBERSTABLE.' WHERE name= \''.$row['name'].'\'';
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $gquery -->\n";
	}
	$gid_result = $wowdb->query($gquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$gquery);
	$gid = $wowdb->fetch_array($gid_result);
	if($gid[0] != ''){
		// Check if charinfo exists
		$query = 'SELECT member_id FROM '.ROSTER_PLAYERSTABLE.' WHERE name= \''.$row['name'].'\'';
		if ($roster_conf['sqldebug'])
		{
			print "<!-- $query -->\n";
		}
		$id_result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		$id = $wowdb->fetch_array($id_result);
		if($id[0] != ''){
			echo ' <a href="index.php?name='.$module_name.'char&amp;name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a></td>';
		}else{
			echo ' '.$row['name'].'</td>';
		}
	}else{
		echo ' <span style="color:#999999;">'.$row['name'].'</span></td>';
	}

// Display first joined
	$jquery = 'SELECT datejoin FROM `'.$db_prefix.'raidjoins` WHERE raidnum = '.$raidnum.' AND name = \''.$row['name'].'\'';
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $jquery -->\n";
	}
	$jresult = $wowdb->query($jquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$jquery);
	$jrow = $wowdb->fetch_array($jresult);
	$timejoined = date("G:i:s", strtotime($jrow[0]));
	echo '<td class="membersRow'.$rownum.'">'.$timejoined.'</td>';
// Display last left
	$lquery = 'SELECT dateleft FROM `'.$db_prefix.'raidleaves` WHERE raidnum = '.$raidnum.' AND name = \''.$row['name'].'\'';
	if ($roster_conf['sqldebug'])
	{
		print "<!-- $lquery -->\n";
	}
	$lresult = $wowdb->query($lquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$lquery);
	$lrow = $wowdb->fetch_array($lresult);
	$timeleft = date("G:i:s", strtotime($lrow[0]));
	echo '<td class="membersRowRight'.$rownum.'">'.$timeleft.'</td>';
// Display time in raid
//	$totaltime = gmmktime($lrow[0]);
//	echo '<td class="membersRow'.$rownum.'">'.$lt[0].'</td>';


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

// Add total attendees at bottom
	echo '<tr><th colspan="3" class="membersHeaderRight">'.$rt_wordings[$roster_conf['roster_lang']]['TotalRaiders'].': '.$i.'</th></tr>';

// Close the table
echo '</table>';

// Display the Right side / Bottom of the Stylish Border
echo border('syellow','end');

echo '</td></tr></table>';

$wowdb->free_result($result);
?>