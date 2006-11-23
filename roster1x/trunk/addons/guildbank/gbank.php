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

// Check if we already assigned something to $content, if not, Declare it
if (!isset($content))
{
	$content = '';
}

// Check if there is a level range, if so, apply it to the Item Query
$lvlItemQuery = '';
if (isset($_POST['min']) && $_POST['min'])
{
	$min = urldecode($_POST['min']);
	$minform = urlencode($min);
}
else
{
	$min = 'none';
	$minform = '';
}
if (isset($_POST['max']) && $_POST['max'])
{
	$max = urldecode($_POST['max']);
	$maxform = urlencode($max);
}
else
{
	$max = 'none';
	$maxform = '';
}
if ($min == 'none' && $max == 'none')
{
	$content .= '<!-- There is an error in the Level Filter, Both are None, though nor max nor min were posted -->';
}
else
{
	if ($min == 'none')
	{
		$min = 0;
	}
	elseif ($max == 'none')
	{
		$max = 60;
	}
	$lvlItemQuery = ' AND `item_tooltip` REGEXP \''.$wordings[$roster_conf['roster_lang']]['reqlevel'].' (('.$min.')';
	for ($i = $min + 1;$i <= $max;$i++)
	{
		$lvlItemQuery .= '|('.$i.')';
	}
	$lvlItemQuery .= ')\'';
}
print ('<!-- MIN '.$min.', '.$minform.', MAX '.$max.', '.$maxform.' -->');
// Check if there is a filter, if so, apply it to the Item Query
$filterItemQuery = '';
if (isset($_POST['filter']) && $filter = urldecode($_POST['filter']))
{
	$filters = explode(' ', $filter);
	foreach($filters as $filterblock)
	{
		$filterItemQuery .= " AND item.item_tooltip LIKE '%".$filterblock."%'";
	}
	$filter = urlencode($filter);
}
else
{
	$filter = '';
}

// Define the Queries
//$itemQuery = "SELECT member.name as member_name, member.member_id as member_id, item.*, LEFT(item.item_id, (LOCATE(':',item.item_id)-1)) as real_itemid FROM `".ROSTER_ITEMSTABLE."` as item LEFT JOIN `".ROSTER_MEMBERSTABLE."` as member ON item.member_id=member.member_id WHERE member.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%' AND item.item_parent!='bags' AND item.item_parent!='equip'".$filterItemQuery.$lvlItemQuery." AND item.item_tooltip NOT LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_soulbound']."%' ORDER BY item.item_name";
$itemQuery = "SELECT member.name as member_name, member.member_id as member_id, item.*, LEFT(item.item_id, (LOCATE(':',item.item_id)-1)) as real_itemid FROM `".ROSTER_ITEMSTABLE."` as item LEFT JOIN `".ROSTER_MEMBERSTABLE."` as member ON item.member_id=member.member_id WHERE member.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%' AND item.item_parent!='bags' AND item.item_parent!='equip'".$filterItemQuery.$lvlItemQuery." AND (item.item_tooltip NOT LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_soulbound']."%' OR item.item_tooltip LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_boe']."%') ORDER BY item.item_name";
$muleNameQuery = "SELECT m.member_id, m.name AS member_name, m.note AS member_note, m.officer_note AS member_officer_note, p.server AS muleservername, p.money_g AS gold, p.money_s  AS silver, p.money_c AS copper FROM `".ROSTER_PLAYERSTABLE."` AS p, `".ROSTER_MEMBERSTABLE."` AS m WHERE m.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%' AND p.member_id = m.member_id ORDER BY m.name";

// If SQL Debugging is enabled, please insert the queries as commented HTML
if ($roster_conf['sqldebug'])
{
  $content .= "<!-- $itemQuery --> ";
  $content .= "<!-- $muleNameQuery --> ";
}

// Define two DB Placeholders
$muleNames = $wowdb->query($muleNameQuery);
if (!$muleNames)
{
	print($wowdb->error());
	return;
}
$DBitemArray = $wowdb->query($itemQuery);
if (!$DBitemArray)
{
	print($wowdb->error());
	return;
}

// Declare the Global variables
$money = array('gold' => 0, 'silver' => 0, 'copper' => 0);
$mule = array();

// Get all info about the Mules and fill the $money array
$mulecount = 0;
while ($muleRow = $wowdb->getrow($muleNames))
{
	if (isset($muleRow['gold']))
	{
		$money['gold'] += $muleRow['gold'];
	}
	if (isset($muleRow['silver']))
	{
		$money['silver'] += $muleRow['silver'];
	}
	if (isset($muleRow['copper']))
	{
		$money['copper'] += $muleRow['copper'];
	}

	$mule[$muleRow['member_id']]['member_name'] = $muleRow['member_name'];
	$mule[$muleRow['member_id']]['muleservername'] = $muleRow['muleservername'];
	$mule[$muleRow['member_id']]['member_gold'] = $muleRow['gold'];
	$mule[$muleRow['member_id']]['member_silver'] = $muleRow['silver'];
	$mule[$muleRow['member_id']]['member_copper'] = $muleRow['copper'];
	$mule[$muleRow['member_id']]['updatetime'] = DateCharDataUpdated($muleRow['member_id']);

	$mulecount++;
}

// Display info about mules and bankmoney
if ($roster_conf['bank_money'])
{
	$banker_columns=5;
} else {
	$banker_columns=2;
}

//// Setup a table to hold the Mule Box and the Filter Box
$content .= '<table border="0"><tr><td>';

// Set the color of the Filter and Result table border
if ($mulecount > 0)
{
	if ($roster_conf['bank_money'])
	{
		$muleborderstyle = 'syellow';
	}
	else
	{
		$muleborderstyle = 'sgray';
	}
}
else
{
	$muleborderstyle = 'sred';
}

// Begin Mule Box table with a nice Stylish border
$content .= border($muleborderstyle,'start',$wordings[$roster_conf['roster_lang']]['guildbank'].' '.$wordings[$roster_conf['roster_lang']]['character'].'s');

// Display the table inside the bordered cell
$content .= "<table colspan='".$banker_columns."' border='1' cellspacing='2' cellpadding='2'>";
$content .= "<tr><th class='membersHeader'>".$wordings[$roster_conf['roster_lang']]['character']." ".$wordings[$roster_conf['roster_lang']]['name']."</th>";
if ($roster_conf['bank_money'])
{
	$content .= "<th class='membersHeader'><img src=\"".$roster_conf['img_url']."/bagcoingold.gif\" alt=\"G\"/> </th><th class='membersHeader'><img src=\"".$roster_conf['img_url']."/bagcoinsilver.gif\" alt=\"S\"/> </th><th class='membersHeader'><img src=\"".$roster_conf['img_url']."/bagcoinbronze.gif\" alt=\"C\"/> </th>";
}
$content .= "<th class='membersHeader'>".$wordings[$roster_conf['roster_lang']]['lastupdate']."</th></tr>";
$muleRowHeader=1;
if ($mulecount > 0)
{
	foreach ($mule as $muleID => $muleArray)
	{
		$muleurl = "<a href='".$roster_conf['roster_dir']."/char.php?name=".$muleArray['member_name']."&server=".urlencode($muleArray['muleservername'])."'>";
		$content .= "<tr><td class='membersRow".$muleRowHeader."'>".$muleurl."<span style='font-size:9pt;color:#0070dd;text-decoration:underline;'>".$muleArray['member_name']."</span></a></td>";
		// Display Banker Money   style=\"color:#$color\">
		if ($roster_conf['bank_money'])
		{
			$content .= "<td class='membersRow".$muleRowHeader."' align=\"right\"><span style=\"color:#d4b857\">".$muleArray['member_gold']."</span></td>";
			$content .= "<td class='membersRow".$muleRowHeader."' align=\"right\"><span style=\"color:#6e6c6f\">".$muleArray['member_silver']."</span></td>";
			$content .= "<td class='membersRow".$muleRowHeader."' align=\"right\"><span style=\"color:#a97052\">".$muleArray['member_copper']."</span></td>";
		}
		$content .= "<td class='membersRow".$muleRowHeader."'>".$muleArray['updatetime']."</td></tr>";
		switch ($muleRowHeader)
		{
			case 1:
				$muleRowHeader = 2;
				break;
			default:
				$muleRowHeader = 1;
		}
	}
	// Display Total Money
	if ($roster_conf['bank_money'])
	{
		$money = CalcTotalMoney();
		$content .= "<tr><td class=\"membersHeader\"><b>".$wordings[$roster_conf['roster_lang']]['guildbank_totalmoney']."</b></td>";
		$content .= "<td class=\"membersHeader\" align=\"right\"><span style=\"color:#d4b857; font-weight: bold;\">".$money['gold']."</b></span></td>";
		$content .= "<td class=\"membersHeader\" align=\"right\"><span style=\"color:#6e6c6f; font-weight: bold;\">".$money['silver']."</b></span></td>";
		$content .= "<td class=\"membersHeader\" align=\"right\"><span style=\"color:#a97052; font-weight: bold;\">".$money['copper']."</b></span></td>";
		$content .= "<td class=\"membersHeader\">&nbsp;</td>";
	}
}
else
{
	$content .= '<tr><th colspan="2" class="membersHeader"><span style="color:#ff0000;font-size:12pt;">'.$wordings[$roster_conf['roster_lang']]['gbank_charsNotFound'].'</span></th></tr>';
}

$content .= "</table>";
// Close the Mule Box table with a stylish border
$content .= border($muleborderstyle,'end');

//// Close the Mule Box cell and open the Filter Box cell
$content .= '</td><td>';

// Process the results of the item-query on the database
$itemsarray = array();
while($itemArrayRow = $wowdb->getrow($DBitemArray))
{
	if ($category = CheckCategory2($itemArrayRow['item_tooltip'], $itemArrayRow['item_id']))
	{
		// The CategoryID check succeeded so this is a Wanted item (probably :)
		// Let's shove it in the array using the following array schema:
		// $items[$catagory][$realitemid] = Array with a Per Banker quantity as well
		// From the Query we get the following:
		//   member_name, member_id, item_name, item_parent, item_slot, item_color, item_id, item_texture, item_quantity, item_tooltip, item_id, real_itemid, total_quantity

//		$real_itemid = $itemArrayRow['real_itemid'];  // Changed to item_id to solve duplicate real_itemid numbers for different items
		$itemid_array = explode(':', $itemArrayRow['item_id']);
		$real_itemid = $itemid_array[0].':'.$itemid_array[1].':'.$itemid_array[2];
		$itemsarray[$category][$real_itemid]['item_name'] = $itemArrayRow['item_name'];
		$itemsarray[$category][$real_itemid]['item_color'] = $itemArrayRow['item_color'];
		$itemsarray[$category][$real_itemid]['item_id'] = $itemArrayRow['item_id'];
		$itemsarray[$category][$real_itemid]['item_texture'] = $itemArrayRow['item_texture'];
		$itemsarray[$category][$real_itemid]['item_tooltip'] = $itemArrayRow['item_tooltip'];
		$itemsarray[$category][$real_itemid]['item_parent'] = $itemArrayRow['item_parent'];
		if (!isset($itemsarray[$category][$real_itemid]['quantity']))
		{
			$itemsarray[$category][$real_itemid]['quantity'] = 0;
		}
		$itemsarray[$category][$real_itemid]['quantity'] += $itemArrayRow['item_quantity'];

		// Adding the Per Banker quantity inside the array
		if (!isset($itemsarray[$category][$real_itemid]['banker'][$itemArrayRow['member_id']]))
		{
			$itemsarray[$category][$real_itemid]['banker'][$itemArrayRow['member_id']] = 0;
		}
		$itemsarray[$category][$real_itemid]['banker'][$itemArrayRow['member_id']] += $itemArrayRow['item_quantity'];
	}
}
// Set the color of the Filter and Result table border
if (!$itemsarray)
{
	$borderstyle = 'sblue';
	if ($min != 'none' || $max != 'none' || $filter != '')
	{
		$filterstyle = 'sred';
	}
	else
	{
		$filterstyle = 'sblue';
	}
}
else
{
	$borderstyle = 'syellow';
	if ($min != 'none' || $max != 'none' || $filter != '')
	{
		$filterstyle = 'syellow';
	}
	else
	{
		$filterstyle = 'sblue';
	}
}

// Begin Filter Box table with a nice Stylish border
$content .= border($filterstyle,'start',$wordings[$roster_conf['roster_lang']]['filter'].' '.$wordings[$roster_conf['roster_lang']]['guildbank']);
// Display the table inside the bordered cell
$content .= "<table class='bodyline' cellspacing='1' cellpadding='2'>";
// Setup a form to post
$content .= "<form method='POST'>";
// Filter Items
$content .= "<tr><td class='membersHeader'><small>".$wordings[$roster_conf['roster_lang']]['lvlrange']."</small>&nbsp;&nbsp;<input type='text' size='4' value='".$minform."' name='min'>&nbsp;&nbsp;<small>-</small>&nbsp;&nbsp;<input type='text' size='4' value='".$maxform."' name='max'></td></tr>";
$content .= "<tr><td class='membersHeader'><small>".$wordings[$roster_conf['roster_lang']]['filter']."</small>&nbsp;&nbsp;<input type='text' value='".$filter."' name='filter'></td></tr>";
$content .= "<tr><td class='membersHeader'><center>&nbsp;<input type='submit' value='".$wordings[$roster_conf['roster_lang']]['tooltip_set']." ".$wordings[$roster_conf['roster_lang']]['filter']."'>";
// End Form
$content .= "</form></center>";
$content .= "</td></tr>";
$content .= "</table>";

// Close the Filter Box table with a stylish border
$content .= border($filterstyle,'end');

//// Close table which holds the Mule Box and the Filter Box
$content .= '</td></tr><tr><td></td></tr></table>';

// Begin table with a nice Stylish border
$content .= border($borderstyle,'start');

// Create the Header of the table
$content .= "<table bgcolor='#1f1e1d' colspan='".$row_columns."' border='0' cellspacing='2' cellpadding='2'>";
// Process each category sorted on $display_order from conf.php
foreach ($display_order as $CategoryID)
{
	// Check if we want to see Emtpy Categories OR if the category has items
	if ($show_empty || isset($itemsarray[$CategoryID]))
	{
		// Show the Column Header for this Category
		$content .= "<tr><th colspan='".$row_columns."' class='membersHeader'>".$wordings[$roster_conf['roster_lang']]['bankitem_'.$CategoryID]."</th></tr>";
		$count = 0;
		// Again check if we actually have items in the Category before displaying them
		if (isset($itemsarray[$CategoryID]))
		{
			$content .= "<tr class=\"membersRow1\">";
			foreach ($itemsarray[$CategoryID] as $itemid => $itemdetail)
			{
				// Display the icon for the item
				$content .= processItem($itemid, $CategoryID);
		    $count++;
		    if ($count == $row_columns)
		    {
		    	$content .= "</tr><tr>";
		    	$count = 0;
		    }
		  }
		  for ($i=$count;$i < $row_columns;$i++)
		  {
		  	$content .= "<td></td>";
		  }
		  $content .= "</tr>";
		}
	}
}

// If no items are returned in the $itemsarray
if (!$itemsarray)
{
	$content .= '<tr><th colspan="'.$row_columns.'" class="membersHeader"><span style="color:#ff0000;font-size:12pt;">'.$wordings[$roster_conf['roster_lang']]['no'].' '.$wordings[$roster_conf['roster_lang']]['items'].' in '.$wordings[$roster_conf['roster_lang']]['guildbank'];
	// If we filtered, also display the filter

	if ($filter)
	{
		$content .= ' - '.$wordings[$roster_conf['roster_lang']]['filter'].': '.$filter;
	}
	$content .= '</span></th></tr>';
}

// Close the item table
$content .= "</table>";

// Close the stylish border table
$content .= border($borderstyle,'end');


//// Functions
// Function to calculate the total of the mules money
function CalcTotalMoney()
{
	global $money;

	while ($money['copper'] > 100) {
		$money['silver']++;
		$money['copper'] = $money['copper'] - 100;
	}
	while ($money['silver'] > 100) {
		$money['gold']++;
		$money['silver'] = $money['silver'] - 100;
	}
	return $money;
}

// Function to grab the last Update Time of the member and return it in readable format
function DateCharDataUpdated($member_id)
{
    global $wowdb, $roster_conf;
    extract($GLOBALS);
    $datequery = "SELECT `dateupdatedutc` FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$member_id'";
    $dateresult = $wowdb->query($datequery);
    $date = $wowdb->getrow($dateresult);
    $dateupdatedutc = $date["dateupdatedutc"];
    $day = substr($dateupdatedutc,3,2);
    $month = substr($dateupdatedutc,0,2);
    $year = substr($dateupdatedutc,6,2);
    $hour = substr($dateupdatedutc,9,2);
    $minute = substr($dateupdatedutc,12,2);
    $second = substr($dateupdatedutc,15,2);

    $localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);

    return date($phptimeformat[$roster_conf['roster_lang']], $localtime);
}

// This function will check in which category the item falls under.
function CheckCategory2($tooltip, $itemid)
{
	global $search_order, $itemarray, $bankitem, $roster_conf;
	$found = false;
	$returnvalue = 0;


	// Omit things we don't want first
	if (!$found)
	{
		foreach ($itemarray['omit'] as $check)
		{
			foreach (explode("\n", $tooltip) as $line )
			{
				$line = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$line);
				if (preg_match("/\b".$check."\b/", $line) >= 1)
				{
					$found = true;
					$returnvalue = 0;
					break;
				}
			}
			if ($found)
			break;
		}
	}

	// Iterate through our search_array
	foreach ($search_order as $index)
	{
		foreach ($itemarray[$bankitem[$index]['arg']] as $check)
		{
			foreach (explode("\n", $tooltip) as $line )
			{
				$line = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$line);
				if (preg_match("/\b".$check."\b/i", $line) >= 1)
				{
					$bankarray[$bankitem[$index]['arg']][$itemid] =
					$found = true;
					$returnvalue = $index;
					break;
				}
			}
			if ($found)
			break;
		}
		if ($found)
		break;
	}

	// Throw everything else in Misc
	if (!$found) {
		$found = true;
		$returnvalue = 29;
	}
	return $returnvalue;
}

// This function will process the item and build up the code.
function processItem($real_itemid, $category)
{
	global $mule, $itemsarray, $color_border, $itemlink, $roster_conf, $wordings;

	$itemrow = $itemsarray[$category][$real_itemid];

	// Add the lead to the item-cell
	$returnstr = '<td><span onmouseover="return overlib(\'';

	// Generate the TOOLTIP code
	$first_line = 1;
	$tooltip = '';
	$itemrow['item_tooltip'] = stripslashes($itemrow['item_tooltip']);
	foreach (explode("\n", $itemrow['item_tooltip']) as $line )
	{
		$color = '';

		if( !empty($line) )
		{
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );
			$line = preg_replace('|\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r|','<span style="color:#$1;">$2</span>',$line);

			// Do this on the first line
			// This is performed when $caption_color is set
			if( $first_line )
			{
				if( $itemrow['item_color'] == '' )
					$itemrow['item_color'] = '9d9d9d';

				if( strlen($itemrow['item_color']) > 6 )
					$color = substr( $itemrow['item_color'], 2, 6 );
				else
					$color = $itemrow['item_color'];

				$item_color = $color;
				$color .= ';font-size:12px;font-weight:bold';

				$first_line = false;
			}
			else
			{
				if ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_use'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_requires'],$line) )
					$color = 'ff0000';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_reinforced'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_equip'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_chance'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_enchant'],$line) )
					$color = '00ff00';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_soulbound'],$line) )
					$color = '00bbff';
				elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_set'],$line) )
					$color = '00ff00';
				elseif ( preg_match('|\([a-f0-9]\).'.$wordings[$roster_conf['roster_lang']]['tooltip_set'].'|',$line) )
					$color = '666666';
				elseif ( ereg('^\\"',$line) )
					$color = 'ffd517';
			}

			// Convert tabs to a formated table
			if( strpos($line,"\t") )
			{
				$line = str_replace("\t",'</td><td align="right" class="overlib_maintext">', $line);
				$line = '<table width="100%" cellspacing="0" cellpadding="0"><tr><td class="overlib_maintext">'.$line.'</td></tr></table>';
				$tooltip .= $line;
			}
			elseif( !empty($color) )
			{
				$tooltip .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
			}
			else
			{
				$tooltip .= "$line<br />";
			}
		}
		else
		{
			$tooltip .= '<br />';
		}
	}

	if (!isset($tooltip))
		$tooltip = $tooltip_out;

	// Process a line for each Banker that holds 'quantity' of the item inside the Tooltip
	foreach ($itemrow['banker'] as $itemBankerID => $NumItemsPerBanker)
	{
		$tooltip = '<span style="color:#5c82a3;">'.$mule[$itemBankerID]['member_name'].'</span> ('.$NumItemsPerBanker.') - <span style="color:#aaaaaa; font-size:8pt;">Time:'.$mule[$itemBankerID]['updatetime'].'</span><hr />' . $tooltip;
	}
	// Normalize the tooltip string
	$tooltip = str_replace("'", "\'", $tooltip);
	$tooltip = str_replace('"','&quot;', $tooltip);

	$returnstr .= $tooltip.'\');" onmouseout="return nd();">'."\n";
	// Free Up memory by clearing the tooltip string
	$tooltip = '';

	// Get the Icon Color
	switch ($item_color)
	{
		case "ffffff":
			$item_quality = "iconwhite";
		break;
		case "1eff00":
			$item_quality = "icongreen";
		break;
		case "9d9d9d":
			$item_quality = "icongrey";
		break;
		case "0070dd":
			$item_quality = "iconblue";
		break;
		default:
			$item_quality = "iconpurple";
	}
	$returnstr .= '<div class="item">';

	// Clean the Item Texture of backslashes and replace them with forward slashes
	$item_texture=str_replace('\\','/',$itemrow['item_texture']);

	// Construct the image with a URL and put the colored border around it
	if ($color_border)
	{
		$returnstr .=  '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($itemrow['item_name'])).'" target="_blank">'."\n".'<img src="'.$roster_conf['interface_url'].$item_texture.'.'.$roster_conf['img_suffix'].'" class="'.$item_quality.'"></a>';
	}
	else
	{
		$returnstr .=  '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($itemrow['item_name'])).'" target="_blank">'."\n".'<img src="'.$roster_conf['interface_url'].$item_texture.'.'.$roster_conf['img_suffix'].'"></a>';
	}
	// If we have more than 1 in total, display the total quantity number on the image
	if (($itemrow['quantity'] > 1) && ($itemrow['item_parent'] != 'bags'))
	{
		$returnstr .= '<span class="quant">'.$itemrow['quantity'].'</span>';
	}
	// Add the trail to the item-cell
	$returnstr .= '</span></div></td>';

	// Return the complete table cell for the item
	return $returnstr;
}

?>
