<?php
$versions['versionDate']['itemsets'] = '$Date: 2006/08/29 $'; 
$versions['versionRev']['itemsets'] = '$Revision: 1.7.3 $'; 
$versions['versionAuthor']['itemsets'] = '$Author: Gorgar, PoloDude, Zeryl $';

if (!defined('CPG_NUKE')) { exit; }

require_once ROSTER_BASE.'lib/item.php';
require_once ROSTER_BASE.'lib/wowdb.php';

// Server (for public roster use)
$server_name=$roster_conf['server_name'];

//Faction Selection
$query = "SELECT `faction` FROM `".ROSTER_GUILDTABLE."` where `guild_name` = '".$roster_conf['guild_name']."' and `server` ='".addslashes($roster_conf['server_name'])."'"; 

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$result = $wowdb->query($query) or die($wowdb->error());
if ($row = $wowdb->fetch_array($result)) 
{ 
$guildFaction = substr($row['faction'],0,1); 
} 
else 
{ 
die( $nodata[$roster_conf['roster_lang']] ); 
}

// Tier Selection
if (isset($_REQUEST["tierselect"]))
           $tier = $_REQUEST["tierselect"];
if (isset($_REQUEST["classfilter"]))
           $class = $_REQUEST["classfilter"];

//Added for ony stats Default Selection
if ($tier == '') $tier = 'Tier_1';

$all_sets = array('Tier_0','Tier_0.5','Tier_1','Tier_2','Tier_3','ZG','AQ20',
'AQ40','PVP_Rare','PVP_Epic','Onyxia');

$form = '';
$form .= '<table cellpadding="0" cellspacing="0" class="wowroster">';
$form .= '<form action="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name=ItemSets').'" method=POST name=myform>';
//$form .= '<input type="hidden" name="name" value="'.$module_name.'">';
//$form .= '<input type="hidden" name="file" value="addon">';
//$form .= '<input type="hidden" name="roster_addon_name" value="ItemSets">';
$form .= '<tr><th class="membersRow1">Tier:</th>';
$form .= '<td class="membersRow1">';
$form .= '<select name="tierselect" size="1">';
for ($i = 0; $i < sizeof($all_sets); $i++) { 
	if ($tier == $all_sets[$i]) {
       $is_selected = 'selected';
	} else {
		$is_selected = '';
	}
	$form .= '<option value="'.$all_sets[$i].'"'.$is_selected.'>'.$wordings[$roster_conf['roster_lang']][$all_sets[$i]].'</option>'; 
}
$form .= '</select></td>';

$form .= '<td class="membersRow1">';
$form .= '<select name="classfilter">';
if ($class == '') {
	$is_selected = ' selected';
} else {
	$is_selected = '';
}
$form .= '<option value="" '.$is_selected.'>All Classes</option>';

//mulitlanguage support
if ($addon_conf['itemsets']['multilanguage']){
	foreach ($roster_conf['multilanguages'] as $language){
		$tmpArray = array_keys($SetT['Tier_0'][$language]);
		$tmpC = 0;
	foreach ($tmpArray as $tmpClassname){
	if ($tmpClassname != 'Name')
	{
		if (strlen($classArray[$tmpC]) > 0) $classArray[$tmpC] = $classArray[$tmpC] . ',';
		$classArray[$tmpC] = $classArray[$tmpC] . $tmpClassname;
		$tmpC++;
		}
	}
}
foreach ($classArray as $tmpClassname){
if ($class == $tmpClassname) {
$is_selected = ' selected';
} else {
$is_selected = '';
}
$form .= '<option value="'.$tmpClassname.'"'.$is_selected.'>'.$tmpClassname.'</option>';
}
}else{
	$query = 'SELECT distinct class FROM `'.ROSTER_PLAYERSTABLE.'` ORDER BY class ASC';

	if ($roster_conf['sqldebug'])
	{
		print "<!-- $query -->\n";
	}

	$result = $wowdb->query($query) or die($wowdb->error());

	while ($row = $wowdb->fetch_array($result)) { 
		if ($class == $row['class']) {
			$is_selected = ' selected';
		} else {
			$is_selected = '';
		}
		$form .= '<option value="'.$row['class'].'"'.$is_selected.'>'.$row['class'].'</option>'; 
	}
}

$form .= '</select></td>';
$form .= '<td class="membersRow1"><input type="submit" value="submit" /></td>';
$form .= '</tr></form></table>';

// Display the Tier select Form in a stylish border
echo border('syellow','start');
echo $form;
echo border('syellow','end');

echo "<br/>";

// Display the Top / left side of the Stylish Border
//if($tier==''){
//	$tier = 'Tier_0';
//}
echo border('syellow', 'start', $wordings[$roster_conf['roster_lang']][$tier]);

// Make a table to hold the content
echo '<table cellpadding="0" cellspacing="0" class="membersList">';

// Display the header of the table
if ($tier == 'ZG'){
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Waist'],
	$wordings[$roster_conf['roster_lang']]['Wrists'],
	$wordings[$roster_conf['roster_lang']]['Chest'],
	$wordings[$roster_conf['roster_lang']]['Should.'],
	$wordings[$roster_conf['roster_lang']]['Neck'],
	$wordings[$roster_conf['roster_lang']]['Trinket']);
} elseif ($tier == 'AQ20'){
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Back'],
	$wordings[$roster_conf['roster_lang']]['Finger'],
	$wordings[$roster_conf['roster_lang']]['Mainhand']);
} elseif ($tier == 'AQ40'){
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Feet'],
	$wordings[$roster_conf['roster_lang']]['Chest'],
	$wordings[$roster_conf['roster_lang']]['Head'],
	$wordings[$roster_conf['roster_lang']]['Legs'],
	$wordings[$roster_conf['roster_lang']]['Should.']);
} elseif ($tier == 'Tier_3'){
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Waist'],
	$wordings[$roster_conf['roster_lang']]['Feet'],
	$wordings[$roster_conf['roster_lang']]['Wrists'],
	$wordings[$roster_conf['roster_lang']]['Chest'],
	$wordings[$roster_conf['roster_lang']]['Hands'],
	$wordings[$roster_conf['roster_lang']]['Head'],
	$wordings[$roster_conf['roster_lang']]['Legs'],
	$wordings[$roster_conf['roster_lang']]['Should.'],
	$wordings[$roster_conf['roster_lang']]['Finger']);
} elseif ($tier == 'PVP_Rare' || $tier == 'PVP_Epic'){
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Feet'],
	$wordings[$roster_conf['roster_lang']]['Chest'],
	$wordings[$roster_conf['roster_lang']]['Hands'],
	$wordings[$roster_conf['roster_lang']]['Head'],
	$wordings[$roster_conf['roster_lang']]['Legs'],
	$wordings[$roster_conf['roster_lang']]['Should.']);
} elseif ($tier == 'Onyxia'){
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Back'],
	$wordings[$roster_conf['roster_lang']]['Trinket'],
	$wordings[$roster_conf['roster_lang']]['Finger'],
	$wordings[$roster_conf['roster_lang']]['Neck'],
	$wordings[$roster_conf['roster_lang']]['Bag']);
} else {
    $headeritems = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Waist'],
	$wordings[$roster_conf['roster_lang']]['Feet'],
	$wordings[$roster_conf['roster_lang']]['Wrists'],
	$wordings[$roster_conf['roster_lang']]['Chest'],
	$wordings[$roster_conf['roster_lang']]['Hands'],
	$wordings[$roster_conf['roster_lang']]['Head'],
	$wordings[$roster_conf['roster_lang']]['Legs'],
	$wordings[$roster_conf['roster_lang']]['Should.']);
}
echo '<tr>';
foreach ($headeritems as $headeritem) {
	if($items[$headeritem]) {
		list($iname, $thottnum) = explode('|', $items[$headeritem][$headeritem]);
		$header = '<a href="'.$itemlink.urlencode($iname).'" target="_thottbot">'.$headeritem.'</a>';
	} else {
		$header = $headeritem;
	}
	if ($headeritem == 'Name') {
		echo '<th class="membersHeader">'.$header.'</th>';
	} else {
		echo '<th class="membersHeader"><center>'.$header.'</center></th>';
	}
}
echo '</tr>';

// Check if we have a Class Filter
$class_where = '';
if ($class != '') {
	//mulitlanguage support
	if ($addon_conf['itemsets']['multilanguage']){
		$class_where = ' AND class in (\''. str_replace( ',', '\',\'', $class) .'\') ';
	}else{
		$class_where = ' AND class = \''.$class.'\' ';
	}
}

// Get all the members above L50 from the DB and sort by member name (Filtered by Class if selected)
$query = 'SELECT name, level, member_id, class, clientLocale FROM `'.ROSTER_PLAYERSTABLE.'` WHERE level > 50 '.$class_where.' GROUP BY name ORDER BY class ASC, name ASC';

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$result = $wowdb->query($query) or die($wowdb->error());

$rownum=1;
while ($row = $wowdb->fetch_array($result)) {
	if ($row['clientLocale'] == '')
		$row['clientLocale'] = $roster_lang;
	if($tier=='PVP_Rare' || $tier=='PVP_Epic'){
		$items = $SetT[$tier][$row['clientLocale']][$guildFaction][$row['class']];
	}
	else{
		$items = $SetT[$tier][$row['clientLocale']][$row['class']];
	}
	
	if ($items) {
		// Open a new Row
		echo '<tr>';

		// Display the member and set details in the first column
		echo '<td><div class="membersKeyRowLeft'.$rownum.'">';
		if($tier=='PVP_Rare' || $tier=='PVP_Epic'){
		echo '<a href="index.php?name='.$module_name.'&amp;file=char&amp;name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a><br>'.$row['class'].' ('.$row['level'].')<br><span class="tooltipline" style="color:#0070dd; font-size: 10px;">'.$SetT[$tier][$row['clientLocale']][$guildFaction]['Name'][$row['class']].'</span></div>';
		}
		else{
		echo '<a href="index.php?name='.$module_name.'&amp;file=char&amp;name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a><br>'.$row['class'].' ('.$row['level'].')<br><span class="tooltipline" style="color:#0070dd; font-size: 10px;">'.$SetT[$tier][$row['clientLocale']]['Name'][$row['class']].'</span></div>';
		}
		echo '</td>';
		
		// Process all set's for the member_id
		foreach ($items as $setpiece) {
			$setpiecename = explode("|", $setpiece);
			// Modification for french localization, thx to Ansgar
			if (isset($SetAlternateName[$row['clientLocale']][$setpiecename[0]])) {
				$iquery = "SELECT * FROM `".ROSTER_ITEMSTABLE."` WHERE (item_name = '".$setpiecename[0]."' OR item_name = '".$SetAlternateName[$row[		'clientLocale']][$setpiecename[0]]."')  AND member_id = '".$row['member_id']."'";
			} else {
				$iquery = "SELECT * FROM `".ROSTER_ITEMSTABLE."` WHERE item_name = '".$setpiecename[0]."' AND member_id = '".$row['member_id']."'";
			}
			$iresult = $wowdb->query($iquery);
			$idata = $wowdb->getrow($iresult);
			$item = new item($idata);
	
			// Open a new Cell
			echo '<td class="membersKeyRow'.$rownum.'">';
			echo '<div class="bagSlot">';
	
			if($item->data['item_name']){
				print $item->out($setpiecename);
			} else {
				echo '<span style="z-index: 1000;" onMouseover="return overlib(\'<span class=&quot;tooltipheader&quot; style=&quot;color:#0070dd; font-weight: bold&quot;>'.$setpiecename[0].'</span><br><span class=&quot;tooltipline&quot; style=&quot;color:#ffffff; font-size: 10px;&quot;>'.$wordings[$row['clientLocale']]['DropsFrom'].' <b>'.$setpiecename[1].'</b></span><br><span class=&quot;tooltipline&quot; style=&quot;color:#ffffff; font-size: 10px;&quot;>'.$wordings[$row['clientLocale']]['DropsIn'].' <b>'.$setpiecename[2].'</b></span>\');" onMouseout="return nd();"><center>X</center></span>';
			}
			echo '</td>';
			echo '</div>';
			echo '</div>';
		}
		// Close the Row
		echo '</tr>';
	}
	
	switch ($rownum) {
		case 1:
			$rownum=2;
			break;
		default:
			$rownum=1;
	}
}

// Close the table
echo '</table>';

// Display the Right side / Bottom of the Stylish Border
echo border('syellow','end');

$wowdb->free_result($result);

?>
