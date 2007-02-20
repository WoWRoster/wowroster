<?php
/******************************
 * Gear Browser
 * By Rihlsul
 * www.ironcladgathering.com
 * v 1.0  (9/2/2006 2:15 PM)
 * Compatible with Roster 1.70
 ******************************/


if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

print "\n".'<!-- ********************************  BEGIN GEAR BROWSER PAGE  *********************************** -->'."\n";

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        SETUP SECTION    -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
 * This section performs a wide array of setup steps:
 *  - Includes my javascript file for multi-character panel switching
 *  - Includes the base library for recipies
 *  - Includes my custom class gbchar, which extends the base library char.php.
 *  - Get and clean any request variables from characters/servers, then fetch the left side character data
 *  - Setup an array full of members for drop down use
 *  - Setup a profession filter drop down
 * -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- 
 */
 
// As an addon, I can't tuck javascript into the header nicely without dropping Headers and Footers, so...
print '<script language="JavaScript" type="text/javascript">'."\n";
require ('gb.js');
print '</script>'."\n";

// We need the recipes library for use when viewing Made By
require_once(ROSTER_BASE.'lib'.DIR_SEP.'recipes.php');

// Include my custom character class file
require_once ('gbchar.php');


// Check for server name
$server = (isset($_GET['server']) ? $_GET['server'] : $roster_conf['server_name']);
$server_name_escape = $wowdb->escape($roster_conf['server_name']);

// Get which character is on the left side
if (isset($_REQUEST['LeftCharacter']))
{
	$selectedLeft = $_REQUEST['LeftCharacter'];
}
else
{
	$selectedLeft = "";
}

// Get which is on the right side
if (isset($_REQUEST['RightCharacter']))
{
	$selectedRight = $_REQUEST['RightCharacter'];
}
else
{
	$selectedRight = "";
}


// Slash char name and server name
if( get_magic_quotes_gpc() )
{
	$selectedLeft = stripslashes( $selectedLeft );
	$selectedRight = stripslashes( $selectedRight );
	$server = stripslashes( $server_name_escape );
}

// Get Character Info - left side
if ($selectedLeft <> "")
{
	$char1 = gbchar_get_one( $selectedLeft, $server, 'Left' );
	if( !$char1 )
	{
		die_quietly('(GBC) Sorry no data in database for &quot;'.$name.'&quot; of &quot;'.$_GET['server'].'&quot;<br /><br /><a href="./index.php?name='.$module_name.'">'.$wordings[$roster_conf['roster_lang']]['backlink'].'</a>','Character Not Found');
	}
}

// Get entire member list (ouch I know)
$querymembers = "SELECT name from `".ROSTER_PLAYERSTABLE."` order by name ASC";
if ($roster_conf['sqldebug'])
{
	print ("<!-- GEARBROWSER - MEMBER LIST: $querymembers -->\n");
}
$resultmembers = mysql_query($querymembers) or die(mysql_error());
$members = array();
while ($row2 = mysql_fetch_row($resultmembers)) {
		array_push($members,$row2[0]);
} 

// Get the Profession Filter drop down setup
$qry_prof  = "select distinct( skill_name) proff from ".ROSTER_RECIPESTABLE." where skill_name != '".$wordings[$roster_conf['roster_lang']]['First Aid']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['poisons']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['Mining']."' order by skill_name";
if ($roster_conf['sqldebug'])
{
	print ("<!-- GEARBROWSER - PROFESSION FILTER: $qry_prof -->\n");
}
$result_prof = $wowdb->query($qry_prof) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_prof);
$profSelect = '<select NAME="proffilter">';
while($row_prof = $wowdb->fetch_array($result_prof))
{
	if ($_REQUEST["proffilter"]==$row_prof["proff"])
		$profSelect .= '<option VALUE="'.$row_prof["proff"].'" selected>'.$row_prof["proff"];
	else
		$profSelect .= '<option VALUE="'.$row_prof["proff"].'">'.$row_prof["proff"];
}
$wowdb->free_result($result_prof);
$profSelect .= '</select>'."\n";

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-     END SETUP SECTION    -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */


/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-         OPTION BOX       -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */
 
$optionsPanel .= "<div align=\"center\" style=\"margin:10px;\">"."\n";

$optionsPanel .= '<form action="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name=gearbrowser').'" method="POST" name="myform">'."\n";


$optionsPanel .= border('sblue','start',$wordings[$roster_conf['roster_lang']]['gearbrowser']." ".$wordings[$roster_conf['roster_lang']]['gearbrowseroptions']);
$optionsPanel .= '<table cellspacing="0" cellpadding="0" class="bodyline" width="70%">'."\n";
$optionsPanel .= '  <!-- '.$_REQUEST['rightSideType'].' -->'."\n";
// ** Row 1 **  Left side
$optionsPanel .= '  <tr>'."\n";
$optionsPanel .= '    <td class="membersRow1">'.$wordings[$roster_conf['roster_lang']]['gearbrowser-leftSideLabel'].'</td>'."\n";
$optionsPanel .= '    <td class="membersRow1" colspan="2">'.createOptionListValue($members,$selectedLeft,"LeftCharacter").'</td>'."\n";
$optionsPanel .= '  </tr>'."\n";
// ** Row 2 **  Right type label, player radio option, player list
$optionsPanel .= '  <tr>'."\n";
$optionsPanel .= '    <td class="membersRow2">'.$wordings[$roster_conf['roster_lang']]['gearbrowser-rightsidetype'].'</td>'."\n";
$optionsPanel .= '    <td class="membersRow2">'."\n";
$optionsPanel .= '      <input type="radio" name="rightSideType" value="player"'.((isset($_REQUEST['rightSideType']) && ($_REQUEST['rightSideType']=='player')) ? ' checked="checked" ' : '' ).' /> '.$wordings[$roster_conf['roster_lang']]['gearbrowser-rightsidetypeplayer'].'</td>'."\n";
$optionsPanel .= '    <td class="membersRow2">'."\n";
$optionsPanel .= '      '.createOptionListValue($members,$selectedRight,"RightCharacter")."\n";
$optionsPanel .= '    </td>'."\n";
$optionsPanel .= '  </tr>'."\n";
// ** Row 3 **  Blank, bank radio option, filter string
$optionsPanel .= '  <tr>'."\n";
$optionsPanel .= '    <td class="membersRow1">&nbsp;</td>'."\n";
$optionsPanel .= '    <td class="membersRow1">'."\n";
$optionsPanel .= '      <input type="radio" name="rightSideType" value="bank"'.((isset($_REQUEST['rightSideType']) && ($_REQUEST['rightSideType']=='bank')) ? ' checked="checked" ' : '' ).' /> '.$wordings[$roster_conf['roster_lang']]['gearbrowser-rightsidetypeguildbank'].'</td>'."\n";
$optionsPanel .= '    </td>'."\n";
$optionsPanel .= '    <td class="membersRow1"><input name="itemfilter" type="text" '.((isset($_REQUEST['itemfilter']) && ($_REQUEST['itemfilter']=='')) ? '' : 'value="'.$_REQUEST['itemfilter'].'"' ).'/></td>'."\n";
$optionsPanel .= '  </tr>'."\n";
// ** Row 4 **  Blank, made by radio option, prof filter
$optionsPanel .= '  <tr>'."\n";
$optionsPanel .= '    <td class="membersRow2">&nbsp;</td>'."\n";
$optionsPanel .= '    <td class="membersRow2" valign="top">'."\n";
$optionsPanel .= '      <input type="radio" name="rightSideType" value="madeby"'.((isset($_REQUEST['rightSideType']) && ($_REQUEST['rightSideType']=='madeby')) ? ' checked="checked" ' : '' ).' /> '.$wordings[$roster_conf['roster_lang']]['gearbrowser-rightsidetypemadeby'].'</td>'."\n";
$optionsPanel .= '    <td class="membersRow2">'.$profSelect.'<br /><input name="profsearch" type="text" '.((isset($_REQUEST['profsearch']) && ($_REQUEST['profsearch']=='')) ? '' : 'value="'.$_REQUEST['profsearch'].'"' ).'/></td>'."\n";
$optionsPanel .= '  </tr>'."\n";

/*  Level Range filtering for Guild Bank and Made By not yet implemented.
 *
	// ** Row 5 **  Minimum level row
	$optionsPanel .= '  <tr>'."\n";
	$optionsPanel .= '    <td class="membersRow1">&nbsp;</td>'."\n";
	$optionsPanel .= '    <td class="membersRow1" valign="top">'."\n";
	$optionsPanel .= '      '.$wordings[$roster_conf['roster_lang']]['gearbrowser-minimumlevel'].'</td>'."\n";
	$optionsPanel .= '    <td class="membersRow1"><input name="minimumlevel" type="text" '.((isset($_REQUEST['minimumlevel']) && ($_REQUEST['minimumlevel']=='')) ? '' : 'value="'.$_REQUEST['minimumlevel'].'"' ).'/></td>'."\n";
	$optionsPanel .= '  </tr>'."\n";
	// ** Row 6 **  Maximum level row
	$optionsPanel .= '  <tr>'."\n";
	$optionsPanel .= '    <td class="membersRow2">&nbsp;</td>'."\n";
	$optionsPanel .= '    <td class="membersRow2" valign="top">'."\n";
	$optionsPanel .= '      '.$wordings[$roster_conf['roster_lang']]['gearbrowser-maximumlevel'].'</td>'."\n";
	$optionsPanel .= '    <td class="membersRow2"><input name="maximumlevel" type="text" '.((isset($_REQUEST['maximumlevel']) && ($_REQUEST['maximumlevel']=='')) ? '' : 'value="'.$_REQUEST['maximumlevel'].'"' ).'/></td>'."\n";
	$optionsPanel .= '  </tr>'."\n";
 */

// ** Row 7 **  Big row, right side Apply button
$optionsPanel .= '  <tr>'."\n";
$optionsPanel .= '    <td class="membersRow1" colspan="3" align="right"><div align="right"><input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['gearbrowserapply'].'" /></div></td>'."\n";
$optionsPanel .= '  </tr>'."\n";
$optionsPanel .= '</table>'."\n";
$optionsPanel .= border('sblue','end');
$optionsPanel .= '</form></div>'."\n";

print $optionsPanel;

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-      END OPTION BOX      -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */


/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        LEFT PANEL       -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

if ($selectedLeft <> "")
{
	print '<table border="0" cellpadding="5" cellspacing="0" width="100%" class="gearBrowserMainTable">'."\n";
	print '  <tr>'."\n";
	print '    <td align="left" valign="top" class="row">'."\n";
	
	$char1->out();
	
	if ($roster_conf['show_item_bonuses'])
	{
		echo cleandumpBonuses($selectedLeft, $server);
	}
	print "</td><td valign=\"top\">";
}
/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-       END LEFT PANEL    -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        RIGHT PANEL      -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */


/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        PLAYER DATA      -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

if ( isset($_REQUEST['rightSideType']) && ( $_REQUEST['rightSideType'] == 'player' ) )
{	
	/*  Player Data
	 *  -----------
	 *  
	 *  Get the Character info via subclass, call out() and then tack on the bonuses.
	 *
	 */
	 
	// Get Character Info - right
	if ($selectedRight <> "")
	{
		$char2 = gbchar_get_one( $selectedRight, $server, 'Right' );
		if( !$char2 )
		{
			die_quietly('(GBC) Sorry no data in database for &quot;'.$name.'&quot; of &quot;'.$_GET['server'].'&quot;<br /><br /><a href="'.getlinjk($module_name).'">'.$wordings[$roster_conf['roster_lang']]['backlink'].'</a>','Character Not Found');
		}
	}
	print "\n".'<!-- ********************************  RIGHT PLAYER SECTION *********************************** -->'."\n";

	// Print the character info
	$char2->out();
	
	// Print the bonuses
	if ($roster_conf['show_item_bonuses'])
	{
		echo cleandumpBonuses($selectedRight, $server);
	}
	print "\n".'<!-- ********************************  END RIGHT PLAYER SECTION *********************************** -->'."\n";

}

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-         BANK DATA       -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

if ( isset($_REQUEST['rightSideType']) && ( $_REQUEST['rightSideType'] == 'bank' ) )
{	
	/*  Bank Data
	 *  -----------
	 *  
	 *  Multi-step section
	 *  - Setup any user requested filtering
	 *  - Fetch the relevant guild bank data
	 *  - Sort out all the items into arrays based on categories
	 *  - For each category, then for each item, fetch the item's printable result string
	 *  - Finally, output the results
	 */

	$content = '';
	
	// Check if there is a filter, if so, apply it to the Item Query
	$filterItemQuery = '';
	if (isset($_REQUEST['itemfilter']) && $filter = urldecode($_REQUEST['itemfilter']))
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
	
	// Request the bank/item data from the server based on filtering
	$itemQuery = "SELECT member.name as member_name, member.member_id as member_id, item.*, LEFT(item.item_id, (LOCATE(':',item.item_id)-1)) as real_itemid FROM `".ROSTER_ITEMSTABLE."` as item LEFT JOIN `".ROSTER_MEMBERSTABLE."` as member ON item.member_id=member.member_id WHERE member.".$roster_conf['banker_fieldname']." LIKE '%".$roster_conf['banker_rankname']."%' AND item.item_parent!='bags' AND item.item_parent!='equip'".$filterItemQuery." AND item.item_tooltip NOT LIKE '%".$wordings[$roster_conf['roster_lang']]['tooltip_soulbound']."%' ORDER BY item.item_name";
	if ($roster_conf['sqldebug'])
	{
		print ("<!-- GEARBROWSER - BANK ITEMS: $itemQuery -->\n");
	}
	$DBitemArray = $wowdb->query($itemQuery);
	if (!$DBitemArray)
	{
		print($wowdb->error());
		return;
	}
	
	// Sort the items into itemsarray based on Category
	$itemsarray = array();
	$dbRowsCounties = 0;
	while($itemArrayRow = $wowdb->getrow($DBitemArray))
	{
		$dbRowsCounties++;
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
	
	// Fetch the string for each category/item, add it to our 'content' results.
	foreach ($display_order as $CategoryID)
	{
		// Check if we want to see Emtpy Categories OR if the category has items
		if ($show_empty || isset($itemsarray[$CategoryID]))
		{
			// Show the Column Header for this Category
			//$content .= "<tr><th colspan='".$row_columns."'> Debug Count of DBRows: ".$dbRowsCounties."</th></tr>\n";
			$content .= "<tr><th colspan='".$row_columns."' class='membersHeader'>".$wordings[$roster_conf['roster_lang']]['gbitem_'.$CategoryID]."</th></tr>";
			$count = 0;
			//$content .= "<tr><th colspan='".$row_columns."'> Debug Count of CategoryID(".$CategoryID."): ".count($itemsarray)."</th></tr>\n";
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
	
	// Finally, output the results.
	print "\n".'<!-- ********************************  BANK SECTION *********************************** -->'."\n";
	// Begin table with a nice Stylish border
	print border('syellow','start')."\n";
	
	// Create the Header of the table
	print "<table bgcolor='#1f1e1d' colspan='".$row_columns."' border='0' cellspacing='2' cellpadding='2'>"."\n";

	print $content."\n";

	// Close the item table
	print "</table>"."\n";
	
	// Close the stylish border table
	print border('syellow','end')."\n";
	print "\n".'<!-- ********************************  END BANK      *********************************** -->'."\n";
}

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-      END BANK DATA      -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */


/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-       MADEBY DATA       -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

if ( isset($_REQUEST['rightSideType']) && ( $_REQUEST['rightSideType'] == 'madeby' ) )
{	
	/*  Madeby Data
	 *  -----------
	 *  
	 *  Very lifted straight from the recipe add-on, only changed filter form field names.  See original for documentation
	 */

	if (isset($_REQUEST["proffilter"]))
	{
		$recipes = recipe_get_all( $_REQUEST["proffilter"],($_REQUEST["profsearch"]?$_REQUEST["profsearch"]:''), ($_REQUEST["sort"]?$_REQUEST["sort"]:'type') );
	
		if( isset( $recipes[0] ) )
		{
			$rc = 0;
	
			$recipe_type = '';
			$first_table = true;
			$madebyContent .=  "<table><tr><td valign='middle'><a id='top_menu'></a> - \n";
			$qry_recipe_type = 'select distinct r.recipe_type from '.ROSTER_RECIPESTABLE.' r where r.skill_name = "'. $_REQUEST["proffilter"].'" order by r.recipe_type asc';
			if ($roster_conf['sqldebug'])
			{
				$madebyContent .= ("<!-- GEARBROWSER - RECIPE TYPE LIST: $qry_recipe_type -->\n");
			}
			$result_recipe_type = $wowdb->query($qry_recipe_type) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_recipe_type);
			while($row_recipe_type = $wowdb->fetch_array($result_recipe_type))
			{
				$madebyContent .=  '<a href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes').'#'.$row_recipe_type['recipe_type'].'">'.$row_recipe_type['recipe_type'].'</a> - '."\n";
			}
			$madebyContent .=  "</td></tr></table>\n<br /><br />\n";
	
			foreach ($recipes as $recipe)
			{
				if ($recipe_type != $recipe->data['recipe_type'])
				{
					$recipe_type = $recipe->data['recipe_type'];
					if(!$first_table)
					{
						$madebyContent .=  '</table>'.border('syellow','end').'<br />';
					}
					$first_table = false;
	
					$madebyContent .= border('syellow','start','<a href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes').'#top_menu" id="'.$recipe_type.'">'.$recipe_type.'</a>').
						'<table class="bodyline" cellspacing="0">'."\n";
	
					//$madebyContent .= '<tr>'."\n";
					//$madebyContent .= '<td colspan="14" class="membersHeaderRight"><div align="center"></div></td>'."\n";
					//$madebyContent .= '</tr>';
					$madebyContent .= '<tr>'."\n";
	
					if ($display_recipe_icon)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['item'].'&nbsp;</th>'."\n";
					}
					if ($display_recipe_name)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['name'].'&nbsp;</th>'."\n";
					}
					if ($display_recipe_level)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['level'].'&nbsp;</th>'."\n";
					}
					if ($display_recipe_tooltip)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['itemdescription'].'&nbsp;</th>'."\n";
					}
					if ($display_recipe_type)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['type'].'&nbsp;</th>'."\n";
					}
					if ($display_recipe_reagents)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['reagents'].'&nbsp;</th>'."\n";
					}
					if ($display_recipe_makers)
					{
						$madebyContent .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['whocanmakeit'].'&nbsp;</th>'."\n";
					}
	
					$madebyContent .=  '</tr>';
				}
				$qry_users = "select m.name, r.difficulty, s.skill_level ".
					"from ".ROSTER_MEMBERSTABLE." m, ".ROSTER_RECIPESTABLE." r, ".ROSTER_SKILLSTABLE." s ".
					"where r.member_id = m.member_id and r.member_id = s.member_id and r.skill_name = s.skill_name ".
					"and recipe_name = '".addslashes($recipe->data['recipe_name'])."' order by m.name";

				if ($roster_conf['sqldebug'])
				{
					$madebyContent .= ("<!-- GEARBROWSER - MADE BY - MAKERS: $qry_users -->\n");
				}
				
				$result_users = $wowdb->query($qry_users) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_users);
				$users = '';
				$break_counter = 0;
				while($row_users = $wowdb->fetch_array($result_users))
				{
					if ($break_counter == $display_recipe_makers_count)
					{
						$users .= '<br />&nbsp;';
						$break_counter = 0;
					}
					elseif( $users != '' )
					{
						$users .= ', ';
					}
	
					if (substr($row_users['skill_level'],0,strpos($row_users['skill_level'],':')) < 300)
					{
						$users .= '<a onmouseover="return overlib(\''.$row_users['skill_level'].'\',WRAP);" onmouseout="return nd();" class="difficulty_'.$row_users['difficulty'].'" href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes').'">'.$row_users['name'].'</a>'."\n";
					}
					else
					{
						$users .= '<a onmouseover="return overlib(\''.$row_users['skill_level'].'\',WRAP);" onmouseout="return nd();" class="difficulty_1" href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes').'">'.$row_users['name'].'</a>'."\n";
					}
					$break_counter++;
				}
				$wowdb->free_result($result_users);
	
				$users = rtrim($users,', ');
	
	
				// Increment counter so rows are colored alternately
				++$rc;
	
				$table_cell_start = '<td class="membersRow'.(($rc%2)+1).'" align="center" valign="middle">';
	
	
				$thottURL='<a href="http://www.thottbot.com/index.cgi?i='.
				str_replace(' ', '+',$recipe->data['recipe_name']).'" target="_thottbot">'.
				$recipe->data['recipe_name'].'</a>';
	
				if ($display_recipe_tooltip)
				{
					$tooltip = '';
					$first_line = true;
					foreach (explode("\n", $recipe->data['recipe_tooltip']) as $line )
					{
						if( $first_line )
						{
							$color = substr( $recipe->data['item_color'], 2, 6 ) . '; font-size: 12px; font-weight: bold';
							$first_line = False;
						}
						else
						{
							if( substr( $line, 0, 2 ) == '|c' )
							{
								$color = substr( $line, 4, 6 ).';';
								$line = substr( $line, 10, -2 );
							} else if ( substr( $line, 0, 4 ) == $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) {
								$color = '00ff00;';
							} else if ( substr( $line, 0, 8 ) == $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) {
								$color = 'ff0000;';
							} else if ( substr( $line, 0, 10 ) == $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) {
								$color = '00ff00;';
							} else if ( substr( $line, 0, 6 ) == $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) {
								$color = '00ff00;';
							} else if ( substr( $line, 0, 6 ) == $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) {
								$color = '00ff00;';
							} else if ( substr( $line, 0, 8 ) == $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) {
								$color = '00ff00;';
							} else if ( substr( $line, 0, 9 ) == $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) {
								$color = '00ffff;';
							} elseif ( strpos( $line, '"' ) ) {
								$color = 'ffd517;';
							} else {
								$color='ffffff;';
							}
						}
						$line = preg_replace('|\\>|','&#8250;', $line );
						$line = preg_replace('|\\<|','&#8249;', $line );
						if( strpos($line,"\t") )
						{
							$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
							$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>'."\n";
							$tooltip .= $line;
						}
						elseif( $line != '')
						{
							$tooltip .= "<span style=\"color:#$color\">$line</span><br />\n";
						}
					}
					$users = rtrim($users,'<br>');
				}
	
				$madebyContent .=  '<tr>'."\n";
				if ($display_recipe_icon)
				{
					$madebyContent .=  $table_cell_start.'<div class="equip">';
					$madebyContent .=  $recipe->out();
					$madebyContent .=  '</div></td>';
				}
				if ($display_recipe_name)
				{
					$madebyContent .=  $table_cell_start.'&nbsp;<span style="color:#'.substr( $recipe->data['item_color'], 2, 6 ).';">'.$recipe->data['recipe_name'].'</span>&nbsp;</td>';
				}
				if ($display_recipe_level)
				{
					$madebyContent .=  $table_cell_start.'&nbsp;'.$recipe->data['level'].'&nbsp;</td>';
				}
				if ($display_recipe_tooltip)
				{
					$madebyContent .=  $table_cell_start.'<table style="width:220;white-space:normal;"><tr><td>'.$tooltip.'</td></tr></table></td>';
				}
				if ($display_recipe_type)
				{
					$madebyContent .=  $table_cell_start.'&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>';
				}
				if ($display_recipe_reagents)
				{
					$madebyContent .=  $table_cell_start.'&nbsp;'.str_replace('<br>','&nbsp;<br />&nbsp;',$recipe->data['reagents']).'</td>';
				}
				if ($display_recipe_makers)
				{
					$madebyContent .=  $table_cell_start.'&nbsp;'.$users.'&nbsp;</td>';
				}
				$madebyContent .=  '</tr>';
			}
			$madebyContent .=  '</table>'.border('syellow','end');
	
		}
		else
		{
			$madebyContent .=  $wordings[$roster_conf['roster_lang']]['dnotpopulatelist'];
		}
		print "\n".'<!-- ********************************  MADE BY SECTION *********************************** -->'."\n";
		print $madebyContent;
		print "\n".'<!-- ********************************  END MADE BY SECTION *********************************** -->'."\n";
	}
}

print "</td></tr></table>";

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-     END RIGHT PANEL     -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

print '<br />';
print '<br />';

// tiny credits, why not?
$credits .= '<hr /><div align="center">'."\n";
$credits .= '  <small>'.$wordings[$roster_conf['roster_lang']]['gearbrowser'].' '.$gbVersion.'<br />'."\n"; //  <a href="http://wowroster.net/Forums/viewforum/f=56.html" target="_new">Support</a>  <br/>'."\n";
$credits .= 'Brought to you by Rihlsul of <a href="http://www.ironcladgathering.com" target="_new">Iron Clad Gathering</a></small>'."\n";
$credits .= '</div>'."\n";

print $credits;

print "\n".'<!-- ********************************  END GEAR BROWSER PAGE  *********************************** -->'."\n";

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-     FUNCTIONS SECTION   -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
 * This section has a handful of functions used in this file:
 *   - createOptionListValue :  Used to easily build a drop-down from an array
 *   - processItem           :  Stolen from guild bank, this makes the actual item's <span> section, which includes icon, coloring,
 *                              tooltip, quantity and linkings
 *   - CheckCategory2        :  Stolen form guild bank, this function checks what 'Category' an item belongs in.  Relies HEAVILY on
 *                              the setups in conf.php, localization.php, and searcharrays.php.
 * -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- 
 */
 
/* ----[ Create an HTML option list ]-----------------------
	return string ( an html option list )
	arguments
	$values = array with $values
	$selected = what will be selected
	$select_field = what to match selected with
*/
function createOptionListValue( $values , $selected , $id )
{
	if( !empty($selected) ){
		$select_one = 1;}
	else $select_one = 0;
	

	$option_list = "\n  <select class=\"list\" name=\"{$id}\">\n    ";


	foreach( $values as $name => $value )
	{
		if( $selected == $value && $select_one )
		{
			$option_list .= "    <option value=\"{$value}\" selected=\"selected\">{$value}</option>\n";
			$select_one = 0;
		}
		else
		{
			$option_list .= "    <option value=\"{$value}\">{$value}</option>\n";
		}
	}
	$option_list .= "  </select>";

	return $option_list;
}

// This function will process the item and build up the code.
function processItem($real_itemid, $category)
{
	global $mule, $itemsarray, $color_border, $itemlink, $roster_conf, $wordings;

	$itemrow = $itemsarray[$category][$real_itemid];

	// Add the lead to the item-cell
	$returnstr = '<td><span style="z-index: 1000;" onMouseover="return overlib(\'';

	// Generate the TOOLTIP code
	$first_line = 1;
	foreach (explode("\n", $itemrow['item_tooltip']) as $line )
	{
		$line = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$line);
		$class='tooltipline';
		if( $first_line )
		{
			$item_color = substr($itemrow['item_color'], 2, 6 );
			$color = $item_color.'; font-weight: bold';
			$first_line = 0;
			$class='tooltipheader';
		}
		else
		{
			if( substr( $line, 0, 2 ) == '|c' )	{
				$color = substr( $line, 4, 6 ).'; font-size: 10px;';
				$line = substr( $line, 10, -2 );
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_use']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) === 0 )
			{
				$color = '00ff00; font-size: 10px;';
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_requires']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) === 0 )
			{
				$color = 'ff0000; font-size: 10px;';
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_reinforced']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) === 0 )
			{
				$color = '00ff00; font-size: 10px;';
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_equip']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) === 0 )
			{
				$color = '00ff00; font-size: 10px;';
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_chance']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) === 0 )
			{
				$color = '00ff00; font-size: 10px;';
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_enchant']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) === 0 )
			{
				$color = '00ff00; font-size: 10px;';
			}
			else if (isset($wordings[$roster_conf['roster_lang']]['tooltip_soulbound']) && strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) === 0 )
			{
				$color = '00ffff; font-size: 10px;';
			}
			else
			{
				$color='ffffff; font-size: 10px;';
			}
		}
		$line = str_replace(">", "&#8250;", $line);
		$line = str_replace("<", "&#8249;", $line);
		if( $line != '')
		{
			if (!isset($tooltip))
			{
				$tooltip = '';
			}
			$tooltip = $tooltip."<span class=\"".$class."\" style=\"color:#$color\">$line</span><br />";
		}
	}

	// Process a line for each Banker that holds 'quantity' of the item inside the Tooltip
	//foreach ($itemrow['banker'] as $itemBankerID => $NumItemsPerBanker)
	//{
	//	$tooltip = '<span class="'.$class.'" style="color:#5c82a3;">'.$mule[$itemBankerID]['member_name'].'</span><span class="'.$class.'" style="color:#ffffff;"> ('.$NumItemsPerBanker.')</span> - <span class="'.$class.'" style="color:#aaaaaa; font-size:8pt;">Time:'.$mule[$itemBankerID]['updatetime'].'</span><br />' . $tooltip;
	//}
	// Normalize the tooltip string
	$tooltip = str_replace("'", "\'", $tooltip);
	$tooltip = str_replace('"','&quot;', $tooltip);

	$returnstr .= $tooltip.'\');" onMouseout="return nd();">'."\n";
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
		$returnstr .=  '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($itemrow['item_name'])).'" target="_itemlink">'."\n".'<img src="'.$roster_conf['interface_url'].$item_texture.'.'.$roster_conf['img_suffix'].'" class="'.$item_quality.'"></a>';
	}
	else
	{
		$returnstr .=  '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($itemrow['item_name'])).'" target="_itemlink">'."\n".'<img src="'.$roster_conf['interface_url'].$item_texture.'.'.$roster_conf['img_suffix'].'"></a>';
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

?>
