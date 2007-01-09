<?php
$versions['versionDate']['eventcalendar'] = '$Date: 2006/08/28 $'; 
$versions['versionRev']['eventcalendar'] = '$Revision: 1.0 $'; 
$versions['versionAuthor']['eventcalendar'] = '$Author: PoloDude $';



global $ec_wordings, $roster_conf;

require_once BASEDIR.'modules/'.$module_name.'/lib/wowdb.php';

$eventid = $_REQUEST['event'];

// Get all eventinfo
$query_eventinfo = 'SELECT * FROM `'.$db_prefix.'events` WHERE eventid="'.$eventid.'"';
if ($roster_conf['sqldebug'])
{
	print "<!-- $query_eventinfo -->\n";
}
$result_eventinfo = $wowdb->query($query_eventinfo) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_eventinfo);
$row_eventinfo = $wowdb->fetch_array($result_eventinfo);

echo '<table border="0"><tr><td valign="top">';
// Display the Top / left side of the Stylish Border
echo border('syellow', 'start', $rc_wordings[$roster_conf['roster_lang']]['Titulars']);

// Make a table to hold the content
echo '<table cellpadding="0" cellspacing="0" class="membersList">';

// Display the header of the table
echo '<tr>';
echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Name'].'</th>';
echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Guild'].'</th>';
echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Level'].'</th>';
echo '<th class="membersHeaderRight">'.$rc_wordings[$roster_conf['roster_lang']]['Note'].'</th>';
echo '</tr>';

// Get all raiders
$query = 'SELECT s.name, s.note, m.class, m.guild, m.level FROM `'.$db_prefix.'event_subscribers` AS s LEFT JOIN `'.$db_prefix.'event_members` AS m ON s.name = m.name WHERE s.status = "Y" AND s.eventid="'.$eventid.'" ORDER BY s.place ASC';
if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

$total=0;
$rownum=1;
while ($row = $wowdb->fetch_array($result)) {
$total+=1;

echo '<td class="membersRow'.$rownum.'">'.getClassIcon($row['class']);
echo checkMember($row['name']).'</td>';

echo '<td class="membersRow'.$rownum.'">';
echo $row['guild'].'</td>';

echo '<td style="text-align:center;" class="membersRow'.$rownum.'">';
echo $row['level'].'</td>';

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

if($wowdb->num_rows($result) == 0){
	echo '<tr><td colspan="4" style="text-align:center" class="membersRowRight1">'.$rc_wordings[$roster_conf['roster_lang']]['NoTitulars'].'</td></tr>';
}

// add count
$maxcount = $row_eventinfo['maxCount'];
echo '<tr><th colspan="4" class="membersHeaderRight">';
echo 'Total: '.$total.' ('.$maxcount.')';
echo '</th></tr>';

// Close table
echo '</table>';

// Display the Right side / Bottom of the Stylish Border
echo border('syellow','end');

echo '<br/>';

// Get all substitutes
$query = 'SELECT s.name, s.note, m.class, m.guild, m.level FROM `'.$db_prefix.'event_subscribers` AS s LEFT JOIN `'.$db_prefix.'event_members` AS m ON s.name = m.name WHERE s.status = "S" AND s.eventid="'.$eventid.'" ORDER BY s.place ASC';
if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

if($wowdb->num_rows($result) == 0){}else{
	// Display the Top / left side of the Stylish Border
	echo border('syellow', 'start', $rc_wordings[$roster_conf['roster_lang']]['Substitutes']);
	
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Name'].'</th>';
	echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Guild'].'</th>';
	echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Level'].'</th>';
	echo '<th class="membersHeaderRight">'.$rc_wordings[$roster_conf['roster_lang']]['Note'].'</th>';
	echo '</tr>';
	
	$total=0;
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
	$total+=1;
	
	echo '<td class="membersRow'.$rownum.'">'.getClassIcon($row['class']);
	echo checkMember($row['name']).'</td>';
	
	echo '<td class="membersRow'.$rownum.'">';
	echo $row['guild'].'</td>';
	
	echo '<td style="text-align:center;" class="membersRow'.$rownum.'">';
	echo $row['level'].'</td>';
	
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
	
	if($wowdb->num_rows($result) == 0){
		echo '<tr><td colspan="4" style="text-align:center" class="membersRowRight1"> No Substitutes </td></tr>';
	}
	
	// add count
	echo '<tr><th colspan="4" class="membersHeaderRight">';
	echo 'Total: '.$total;
	echo '</th></tr>';
	
	// Close table
	echo '</table>';
	
	// Display the Right side / Bottom of the Stylish Border
	echo border('syellow','end');
}

echo '<br/>';

// Get all replacements
$query = 'SELECT s.name, s.note, m.class, m.guild, m.level FROM `'.$db_prefix.'event_subscribers` AS s LEFT JOIN `'.$db_prefix.'event_members` AS m ON s.name = m.name WHERE s.status = "R" AND s.eventid="'.$eventid.'" ORDER BY s.place ASC';
if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

if($wowdb->num_rows($result) == 0){}else{
	// Display the Top / left side of the Stylish Border
	echo border('syellow', 'start', $rc_wordings[$roster_conf['roster_lang']]['Replacements']);
	
	// Make a table to hold the content
	echo '<table cellpadding="0" cellspacing="0" class="membersList">';
	
	// Display the header of the table
	echo '<tr>';
	echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Name'].'</th>';
	echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Guild'].'</th>';
	echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Level'].'</th>';
	echo '<th class="membersHeaderRight">'.$rc_wordings[$roster_conf['roster_lang']]['Note'].'</th>';
	echo '</tr>';
	
	$total=0;
	$rownum=1;
	while ($row = $wowdb->fetch_array($result)) {
	$total+=1;
	
	echo '<td class="membersRow'.$rownum.'">'.getClassIcon($row['icon']);
	echo checkMember($row['name']).'</td>';
	
	echo '<td class="membersRow'.$rownum.'">';
	echo $row['guild'].'</td>';
	
	echo '<td style="text-align:center;" class="membersRow'.$rownum.'">';
	echo $row['level'].'</td>';
	
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
	
	// add count
	echo '<tr><th colspan="4" class="membersHeaderRight">';
	echo 'Total: '.$total;
	echo '</th></tr>';
	
	// Close table
	echo '</table>';
	
	// Display the Right side / Bottom of the Stylish Border
	echo border('syellow','end');
}

echo '</td><td width="10px"></td><td valign="top">';

// Display the Top / left side of the Stylish Border
echo border('syellow', 'start', $rc_wordings[$roster_conf['roster_lang']]['Limits']);

// Make a table to hold the content
echo '<table cellpadding="0" cellspacing="0" class="membersList">';

// Display the header of the table
echo '<tr>';
echo '<th class="membersHeader">'.$rc_wordings[$roster_conf['roster_lang']]['Class'].'</th>';
echo '<th class="membersHeaderRight">'.$rc_wordings[$roster_conf['roster_lang']]['LimitStatus'].'</th>';
echo '</tr>';

echo '<tr>';

// Get all limits
$query_limits = 'SELECT * FROM `'.$db_prefix.'event_limits` WHERE eventid = '.$eventid.' ORDER BY class ASC';
if ($roster_conf['sqldebug'])
{
	print "<!-- $query_limits -->\n";
}
$result_limits = $wowdb->query($query_limits) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_limits);

$rownum=1;
while ($row = $wowdb->fetch_array($result_limits)) {

echo '<td class="membersRow'.$rownum.'">'.getClassIcon($row['class']);
echo $rc_wordings['enUS']['Classes'][$row['class']].'</td>';

// Get all classcount
$query_classcount = 'SELECT s.name FROM `'.$db_prefix.'event_subscribers` AS s LEFT JOIN `'.$db_prefix.'event_members` AS m ON m.name = s.name WHERE m.class = "'.$row['class'].'"  AND eventid = '.$eventid;
if ($roster_conf['sqldebug']){ print "<!-- $query_classcount -->\n"; }
$result_classcount = $wowdb->query($query_classcount) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query_classcount);
$row_classcount = $wowdb->num_rows($result_classcount);

echo '<td style="text-align:center;" class="membersRowRight'.$rownum.'">';
echo $row['min'].' / '.$row_classcount.' / '.$row['max'].'</td>';

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

if($wowdb->num_rows($result_limits) == 0){
	echo '<tr><td colspan="2" style="text-align:center" class="membersRowRight1">'.$rc_wordings[$roster_conf['roster_lang']]['NoLimits'].'</td></tr>';
}

// Close table
echo '</table>';

// Display the Right side / Bottom of the Stylish Border
echo border('syellow','end');

echo '</td></tr></table>';

?>