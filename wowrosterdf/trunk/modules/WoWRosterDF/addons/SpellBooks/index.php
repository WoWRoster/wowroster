<?php
$versions['versionDate']['spellbooks'] = '$Date: 2006/10/13 $'; 
$versions['versionRev']['spellbooks'] = '$Revision: 1.7.0-2 $'; 
$versions['versionAuthor']['spellbooks'] = '$Author: Ghuda $';

if (!defined("CPG_NUKE")) { exit; }


require_once ROSTER_BASE.'lib/char.php';
require_once ROSTER_BASE.'lib/wowdb.php';

// Server (for public roster use)
$server_name=$roster_conf['server_name'];

// Class Selection
if (isset($_REQUEST["classfilter"]))
           $class = $_REQUEST["classfilter"];
$form = '';
$form .= '<table cellpadding="0" cellspacing="0" class="membersList">';
$form .= '<form action="" method=GET name=myform>';
$form .= '<input type="hidden" name="name" value="'.$module_name.'">';
$form .= '<input type="hidden" name="file" value="addon">';
$form .= '<input type="hidden" name="roster_addon_name" value="SpellBooks">';
$spells=$wordings[$roster_conf['roster_lang']]['Spells'];
$form .= "<tr><th class=\"membersRow1\">$spells</th>";

$form .= '<td class="membersRow1">';
$form .= '<select name="classfilter">';
if ($class == '') {
	$is_selected = ' selected';
} else {
	$is_selected = '';
}
$all_classes=$wordings[$roster_conf['roster_lang']]['AllClasses'];
$form .= '<option value="" '.$is_selected.'>'.$all_classes.'</option>';

//mulitlanguage support
if ($addon_conf['spellbooks']['multilanguage']){
	foreach ($roster_conf['multilanguages'] as $language){
		$tmpArray = array_keys($Book['Tier_0'][$language]);
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
$form .= '<td class="membersRow1"><input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['Submit'].'" /></td>';
$form .= '</tr></form></table>';

// Display the Tier select Form in a stylish border
echo border('syellow','start');
echo $form;
echo border('syellow','end');

echo "<br/>";

// Display the Top / left side of the Stylish Border
echo border('syellow', 'start', $wordings[$roster_conf['roster_lang']][$tier]);

// Make a table to hold the content
echo '<table cellpadding="0" cellspacing="0" class="membersList">';

// Display the header of the table
    $headerspells = array(
	$wordings[$roster_conf['roster_lang']]['Name'],
	$wordings[$roster_conf['roster_lang']]['Quest1'],
	$wordings[$roster_conf['roster_lang']]['Quest2'],
	$wordings[$roster_conf['roster_lang']]['Quest3'],
	$wordings[$roster_conf['roster_lang']]['WorldDrop1'],
	$wordings[$roster_conf['roster_lang']]['WorldDrop2'],
	$wordings[$roster_conf['roster_lang']]['WorldDrop3'],
	$wordings[$roster_conf['roster_lang']]['Drop1'],
	$wordings[$roster_conf['roster_lang']]['Drop2'],
	$wordings[$roster_conf['roster_lang']]['AQ20_1'],
	$wordings[$roster_conf['roster_lang']]['AQ20_2'],
	$wordings[$roster_conf['roster_lang']]['AQ20_3']);

echo '<tr>';
foreach ($headerspells as $headerspell) {
	$header = $headerspell;
	if ($headerspell == 'Name') {
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
	if ($addon_conf['spellbooks']['multilanguage']){
		$class_where = ' AND class in (\''. str_replace( ',', '\',\'', $class) .'\') ';
	}else{
		$class_where = ' AND class = \''.$class.'\' ';
	}
}

// Get all the members above L47 from the DB and sort by member name (Filtered by Class if selected)
$query = 'SELECT name, level, member_id, class, clientLocale FROM `'.ROSTER_PLAYERSTABLE.'` WHERE level > 47 '.$class_where.' GROUP BY name ORDER BY class ASC, name ASC';

if ($roster_conf['sqldebug'])
{
	print "<!-- $query -->\n";
}

$result = $wowdb->query($query) or die($wowdb->error());

$rownum=1;
while ($row = $wowdb->fetch_array($result)) {
	if ($row['clientLocale'] == '')
		$row['clientLocale'] = $roster_lang;
		$spells = $Book[$row['clientLocale']][$row['class']];
	
	if ($spells) {
		// Open a new Row
		echo '<tr>';

		// Display the member and set details in the first column
		echo '<td><div class="membersKeyRowLeft'.$rownum.'">';
		echo '<a href="index.php?name='.$module_name.'&file=char&amp;amp;cname='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a><br>'.$row['class'].' ('.$row['level'].')<br><span class="tooltipline" style="color:#0070dd; font-size: 10px;">'.$Book[$tier][$row['clientLocale']]['Name'][$row['class']].'</span></div>';
		echo '</td>';
		
		// Process all set's for the member_id
		foreach ($spells as $spell) {
			$spellname = explode("|", $spell);
                        $nb_searched_rank = explode ($wordings[$row['clientLocale']]['rank'], $spellname[1]);
                        // echo $rank[1];
                        // echo "SELECT * FROM `".ROSTER_SPELLTABLE."` WHERE spell_name = '".$spellname[0]."' AND member_id = '".$row['member_id']."' ";
			$iquery = "SELECT * FROM `".ROSTER_SPELLTABLE."` WHERE spell_name = '".$spellname[0]."' AND member_id = '".$row['member_id']."' ";
			$iresult = $wowdb->query($iquery);
			$idata = $wowdb->getrow($iresult);
			$spellname2=$idata['spell_name'];
			$spellrank2=$idata['spell_rank'];
                        $nb_found_rank = explode ($wordings[$row['clientLocale']]['rank'], $spellrank2);
                        // echo "$spellname2 $spellrank2 <br>";
	
			// Open a new Cell
			echo '<td class="membersKeyRow'.$rownum.'">';
			echo '<div class="bagSlot">';
	
			if ( ($idata['spell_name']) and ($nb_found_rank[1]>=$nb_searched_rank[1]) )
			{
				$spell_display['name'] = $idata['spell_name'];
				$spell_display['type'] = $idata['spell_type'];
				$spell_display['icon'] = preg_replace('|\\\\|','/', $idata['spell_texture']);
				$spell_display['rank'] = $idata['spell_rank'];

                                if ($nb_found_rank[1]==$nb_searched_rank[1])
				{
					// Parse the tooltip
					$first_line = true;
					$tooltip = '';
					foreach (explode("\n", $idata['spell_tooltip']) as $line )
					{
						if( $first_line )
						{
							$color = 'FFFFFF; font-size: 12px; font-weight: bold';
						}
						else
						{
							if( substr( $line, 0, 2 ) == '|c' )
							{
								$color = substr( $line, 4, 6 ).';';
								$line = substr( $line, 10, -2 );
							}
							else if ( strpos( $line, $wordings[$row['clientLocale']]['tooltip_use'] ) === 0 )
								$color = '00ff00;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_requires'] ) === 0 )
								$color = 'ff0000;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_reinforced'] ) === 0 )
								$color = '00ff00;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_equip'] ) === 0 )
								$color = '00ff00;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_chance'] ) === 0 )
								$color = '00ff00;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_enchant'] ) === 0 )
								$color = '00ff00;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_soulbound'] ) === 0 )
								$color = '00bbff;';
							else if ( strpos( $line, $wordings[$this->data['clientLocale']]['tooltip_set'] ) === 0 )
								$color = '00ff00;';
							elseif ( strpos( $line, '"' ) === 0 )
								$color = 'ffd517;';
							else
								$color='ffffff;';
						}
						$line = preg_replace('|\\>|','&#8250;', $line );
						$line = preg_replace('|\\<|','&#8249;', $line );

						if( strpos($line,"\t") )
						{
							$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
							$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>';
							$tooltip .= $line;
						}
						elseif( $line != '')
							$tooltip .= "<span style=\"color:#$color\">$line</span><br />";
						if( $first_line )
						{
							if ($spell_display['rank'])
							{
								$rank=$spell_display['rank'];
								$tooltip .= "$rank<br />";
							}
							$first_line=false;
						}
					}
					$tooltip = str_replace("'", "\'", $tooltip);
				}
				else
				{
					$line=$spell_display['name'];
					$color = 'FFFFFF; font-size: 12px; font-weight: bold';
					$tooltip = "<span style=\"color:#$color\">$line</span><br />";
					$line=$spellname[1];
					$color='ffffff;';
					$tooltip .= "<span style=\"color:#$color\">$line</span><br />";
				}
				$spell_display['tooltip'] = str_replace('"', '&quot;', $tooltip);
				$return_string = '
                                <center>
				<img src="'.$roster_conf['interface_url'].$spell_display['icon'].'.'.$roster_conf['img_suffix'].'" class="icon" onmouseover="overlib(\''.$spell_display['tooltip'].'\',RIGHT);" onmouseout="return nd();" alt="" />
                                </center>';

				echo $return_string;

				
			} else if ($spellname[0]){
				echo '<span style="z-index: 1000;" onMouseover="return overlib(\'<span class=&quot;tooltipheader&quot; style=&quot;color:#0070dd; font-weight: bold&quot;>'.$spellname[0].' '.$spellname[1].'</span><br><span class=&quot;tooltipline&quot; style=&quot;color:#ffffff; font-size: 10px;&quot;>'.$wordings[$row['clientLocale']]['DropsFrom'].' <b>'.$spellname[2].'</b></span>\');" onMouseout="return nd();"><center>X</center></span>';
			}else {
				echo '<center>N/A</center>';
			}
			echo '</td>';
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