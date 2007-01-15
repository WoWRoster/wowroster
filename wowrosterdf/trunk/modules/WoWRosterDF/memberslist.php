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

//DF security
if (!defined('CPG_NUKE')) { exit; }
//Roster security
/*
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}*/

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	message_die( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];
$guildMOTD = $guild_info['guild_motd'];
$guildFaction = $guild_info['faction'];



if( $roster_conf['index_update_inst'] )
{
	print '            <a href="'.getlink($module_name.'#update').'"><font size="4">'.$wordings[$roster_conf['roster_lang']]['update_link'].'</font></a><br /><br />';
}


if ( $roster_conf['index_motd'] == 1 && !empty($guildMOTD) )
{
	if( $roster_conf['motd_display_mode'] )
	{
		print '<img src="'.getlink($module_name.'&amp;file=motd').'" alt="'.$guildMOTD.'" /><br /><br />';
	}
	else
	{
		echo '<span class="GMOTD">Guild MOTD: '.$guildMOTD.'</span><br /><br />';
	}
}

include_once (ROSTER_LIB.'menu.php');


if( $roster_conf['hspvp_list_disp'] == 'hide' )
{
	$pvp_hs_colapse=' style="display:none;"';
	$pvp_hs_image='plus';
}
else
{
	$pvp_hs_colapse='';
	$pvp_hs_image='minus';
}

echo "<table>\n  <tr>\n";

if ( $roster_conf['index_hslist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_BASE.'hslist.php');
	echo "    </td>\n";
}

if ( $roster_conf['index_pvplist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_BASE.'pvplist.php');
	echo "    </td>\n";
}

echo "  </tr>\n</table>\n";



/*
this section drives the entire output.
Later I'll probably write some query string handling for either/or:
1) fieldsets : given a get request, load a specific set of fields to display (note, will have to add that get string setting to all column sorting headers)
2) customisation by end user: create a list of individual fields, and load them in based off a checkbox-driven form (probably with defaults) for which columns they wish to see.
3) optomisation of query: ie, add to the fields here the section of query to add for it, so it only queries what it has to show.
- at present you need to ensure all fields we will ever display are in the query, the reason I havent done this yet is because of the joins, it makes it narky.
*/


/************************
Example FIELD[] parameters
*************************
// Start the array like this
$FIELD[] = array (

	 // the field name, taken from the query below
	 // if you add options, be sure the query returns those fields or they'll be blank
	 // This is the only required part
	'name' => array(

		// This is for the column header, check the lang files for whatever they need to be called
		// If this is not found, this table header will show what is placed here
		'lang_field' => 'lang_key_name',

		// An ordering array
		// Some sorts benifit from multiple order fields, name the fields here as you would in the query
		'order'    => array( '`table`.`field` SORT' ),

		// An ordering descending array
		// This is the same as above, but this is used when the field sort is reversed
		// Some sorts benifit from multiple order fields, name the fields here as you would in the query
		'order_d'    => array( '`table`.`field` SORT' ),

		// This will let you divide out the table on the sections
		'divider' => true,

		// Prefix this text before the the name of the divider
		'divider_prefix' => 'Level ',

		// This is in case you need to use a function to modify the value for a divider
		// See examples at the bottom of this file
		'divider_value' => 'function_name',

		// Not currently used, but left as a reminder to me that we want to always have at least the name...
		'required' => true,

		// Not currently used, indicates to show by default
		'default'  => true,

		// This is in case you need to use a function to modify the value for a field
		// See examples at the bottom of this file
		'value' => 'function_name',
	),
);
************************/

// Merge Arrays
$FIELDS = array();
foreach ($FIELD as $field )
{
	$FIELDS += $field;
}


/************************
 * Set up the default secondary sorting
 * This is what everything will be sorted by after the first sort specified in the arrays
 * DO NOT leave this blank, at least one sort must be here
************************/
$always_sort = '`members`.`level` DESC, `members`.`name` ASC';



// Join the tables. These are small tables thankfully

// remember that any fields added above need to have a column pulled here
$query =
	'SELECT '.
// we limit down what it actually pulls
// The query is a lot simpler than it looks...

// Fields to get from the members table
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`note`, '.
	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`level`, '.
	'`members`.`guild_rank`, '.
	'`members`.`guild_title`, '.
	'`members`.`zone`, '.
	"DATE_FORMAT( `members`.`last_online`, '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'last_online', ".

// Fields to get from the players table
	'`players`.`race`, '.
	'`players`.`RankName`, '.
	'`players`.`RankInfo`, '.
	"IF( `players`.`RankInfo` IS NULL OR `players`.`RankInfo` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`exp`, '.
	'`players`.`server`, '.
	'`players`.`clientLocale` ';

// Get additional SQL from files
	if( isset($additional_sql) )
	{
		$query .= ', ';
		foreach( $additional_sql as $more_sql )
		{
			$query .= $more_sql;
		}
	}

// Continue with JOINS
$query .=
	"FROM `".ROSTER_MEMBERSTABLE."` AS members ".

// All those people asking about guild searching, here's a spot!  and here's the simple alteration to stop guild filtering in this particular place
	"LEFT JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `members`.`member_id` = `players`.`member_id` AND `members`.`guild_id` = '$guildId' ".
	"ORDER BY ";


// Add custom primary and secondary ORDER BY definitions
// removed all the switchstring jazz as it wasn't needed

// Get default sort from roster config
if( !isset($_GET['s']) && !empty($roster_conf['index_sort']) )
{
   $_GET['s'] = $roster_conf['index_sort'];
}

if ( $ORDER_FIELD = $FIELDS[$_GET['s']] )
{
	$order_field = $_GET['s'];

	if( $_GET['d'] && isset( $ORDER_FIELD['order_d'] ) )
	{
		foreach ( $ORDER_FIELD['order_d'] as $order_field_sql )
		{
			$query .= $order_field_sql.', ';
		}
	}
	elseif( isset( $ORDER_FIELD['order']) )
	{
		foreach ( $ORDER_FIELD['order'] as $order_field_sql )
		{
			$query .= $order_field_sql.', ';
		}
	}
}
$query .= $always_sort;


// Print the sql string
if ( $roster_conf['sqldebug'] )
{
	echo "<!-- $query -->\n";
}

$result = $wowdb->query( $query ) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);


// switching the quoting around as I loathe printing ugly html, and I like readable code, so I compromise.

$cols = count( $FIELDS );

$tableHeader = "<table>\n  <tr>\n    <td>\n";

function borderTop( $text='' )
{
	return "<br />\n".border('syellow','start',$text)."\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
}


// header row
$tableHeaderRow = "  <tr>\n";
$current_col = 1;
foreach ( $FIELDS as $field => $DATA )
{
	// See if there is a lang value for the header
	if( !empty($wordings[$roster_conf['roster_lang']][$DATA['lang_field']]) )
	{
		$th_text = $wordings[$roster_conf['roster_lang']][$DATA['lang_field']];
	}
	else
	{
		$th_text = $DATA['lang_field'];
	}

	// click a sorted field again to reverse sort it
	// Don't add it if it is detected already
	if( $_REQUEST['d'] != 'true' )
	{
		$desc = ( $order_field == $field ) ? '&amp;d=true' : '';
	}

	if ( $current_col == $cols )
	{
		$tableHeaderRow .= '    <td class="membersHeaderRight"><a href="'.getlink($module_name.( isset($_GET['file']) ? '&amp;file='.$_GET['file'] : '' ).'&amp;s='.$field.$desc).'">'.$th_text."</a></td>\n";
	}
	else
	{
		$tableHeaderRow .= '    <td class="membersHeader"><a href="'.getlink($module_name.( isset($_GET['file']) ? '&amp;file='.$_GET['file'] : '' ).'&amp;s='.$field.$desc).'">'.$th_text."</a></td>\n";
	}

	$current_col++;
}
$tableHeaderRow .= "  </tr>\n";
// end header row

$borderBottom = "</table>\n".border('syellow','end');

$tableFooter = '</td></tr></table>';


print($tableHeader);


// Counter for row striping
$striping_counter = 0;

$last_value = 'some obscurely random string because i\'m too lazy to do this a better way.';

while ( $row = $wowdb->fetch_assoc( $result ) )
{
	// Adding grouping dividers
	if ( $ORDER_FIELD['divider'] )
	{
		if ( $last_value != $row[$order_field] )
		{
			if ( $roster_conf['sqldebug'] )
			{
				echo "<!-- $order_field :: $last_value != $row[$order_field] -->\n";
			}
			if ( $striping_counter )
			{
				echo $borderBottom;
			}

			if( isset($ORDER_FIELD['divider_value']) )
			{
				$divider_text = $ORDER_FIELD['divider_value']( $ORDER_FIELD['divider_prefix'].$row[$order_field] );
			}
			else
			{
				$divider_text = '<div class="membersGroup">'.$ORDER_FIELD['divider_prefix'].$row[$order_field]."</div>\n";
			}

			echo
			borderTop($divider_text).
			$tableHeaderRow;

			$striping_counter = 0;
			$last_value = $row[$order_field];
		}
	}
	else if ( $striping_counter == 0 )
	{
		echo borderTop().
			$tableHeaderRow;
	}

	// actual player rows
	echo "  <tr>\n";


	// Increment counter so rows are colored alternately
	$stripe_counter = ( ( $striping_counter++ % 2 ) + 1 );
	$stripe_class = 'membersRow'.$stripe_counter;
	$stripe_class_right =  'membersRowRight'.$stripe_counter;
	$current_col = 1;


	// Echoing cells w/ data
	foreach ( $FIELDS as $field => $DATA )
	{
		if ( isset( $DATA['value'] ) )
		{
			$cell_value = $DATA['value']( $row );
		}
		else
		{
			$cell_value = ( ($row[$field] == '') ? '&nbsp;' : $row[$field]);
		}

		//---[ Adding trade skills images ]---------------
		if ( $roster_conf['index_tradeskill_icon'] == 1 && $field == $roster_conf['index_tradeskill_loc'] )
		{
			$cell_value .= tradeskill_icons($row);
		}

		if ( $current_col == $cols ) // last col
		{
			echo "    <td class=\"$stripe_class_right\">$cell_value</td>\n";
		}
		else
		{
			echo "    <td class=\"$stripe_class\">$cell_value</td>\n";
		}
		$current_col++;
	}
	echo "  </tr>\n";
}

echo $borderBottom;

print($tableFooter);



// Print the update instructions
if( $roster_conf['index_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$wordings[$roster_conf['roster_lang']]['update_instructions']);
	echo '<div align="left" style="font-size:10px;">'.$wordings[$roster_conf['roster_lang']]['update_instruct'];

	if ($roster_conf['pvp_log_allow'] == 1)
	{
		echo $wordings[$roster_conf['roster_lang']]['update_instructpvp'];
	}
	echo '</div>'.border('sgray','end');
}



/*********************************************************************
 function(s) to return a value from a row with some logic applied.
*********************************************************************/


/**
 * Controls Output of the Name Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function name_value ( $row )
{
	global $wordings, $roster_conf, $guildFaction;

	if( $roster_conf['index_member_tooltip'] )
	{
		if ( $row['RankInfo'] > 0 )
		{
			$rankname = $row['RankName'].' ';
		}

		$tooltip_h = $row['name'].' : '.$row['guild_title'];

		$tooltip .= 'Level '.$row['level'].' '.$row['class']."\n";

		$tooltip .= $wordings[$roster_conf['roster_lang']]['lastonline'].': '.$row['last_online'].' in '.$row['zone'];
		$tooltip .= ($row['nisnull'] ? '' : "\n".$wordings[$roster_conf['roster_lang']]['note'].': '.$row['note']);

		$tooltip = '<div style="cursor:help;" '.makeOverlib($tooltip,$tooltip_h,'',1,'',',WRAP').'>';


		if ( $row['server'] )
		{
			return $tooltip.'<a href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row['name'].'&amp;server='.$row['server']).'">'.$row['name'].'</a></div>';
		}
		else
		{
			return $tooltip.$row['name'].'</div>';
		}
	}
	else
	{
		if ( $row['server'] )
		{
			return '<a href="'.getlink($module_name.'&amp;file=char&amp;cname='.$row['name'].'&amp;server='.$row['server']).'">'.$row['name'].'</a>';
		}
		else
		{
			return $row['name'];
		}
	}
}


/**
 * Controls Output of the Honor Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function honor_value ( $row )
{
	global $roster_conf, $wordings;

	if ( $roster_conf['index_honoricon'] )
	{
		$rankicon = $roster_conf['interface_url'].$row['RankIcon'].'.'.$roster_conf['alt_img_suffix'];
		$rankicon = "<img class=\"membersRowimg\" width=\"".$roster_conf['index_iconsize']."\" height=\"".$roster_conf['index_iconsize']."\" src=\"".$rankicon."\" alt=\"\" />";
	}
	else
	{
		$rankicon = '';
	}

	if ( $row['RankInfo'] > 0 )
	{
		$cell_value = $rankicon.' '.$row['RankName'];

		return $cell_value;
	}
	else
	{
		return '&nbsp;';
	}
}


/**
 * Controls Output of the Last Updated Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function last_up_value ( $row )
{
	global $roster_conf, $phptimeformat;

	if ( $row['last_update'] != '')
	{
		$cell_value = $row['last_update'];

		list($month,$day,$year,$hour,$minute,$second) = sscanf($cell_value,"%d/%d/%d %d:%d:%d");

		$localtime = mktime($hour+$roster_conf['localtimeoffset'] ,$minute, $second, $month, $day, $year, -1);
		return date($phptimeformat[$roster_conf['roster_lang']], $localtime);
	}
	else
	{
		return '&nbsp;';
	}
}


/**
 * Controls Output of the Class Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function class_value ( $row )
{
	global $wordings, $roster_conf;

	if( $row['class'] != '' )
	{
		// Class Icon
		if( $roster_conf['index_classicon'] == 1 )
		{
			foreach ($roster_conf['multilanguages'] as $language)
			{
				$icon_name = $wordings[$language]['class_iconArray'][$row['class']];
				if( strlen($icon_name) > 0 ) break;
			}
			$icon_name = 'Interface/Icons/'.$icon_name;

			$icon_value = '<img class="membersRowimg" width="'.$roster_conf['index_iconsize'].'" height="'.$roster_conf['index_iconsize'].'" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';
		}
		else
		{
			$icon_value = '';
		}

		// Class name coloring
		if ( $roster_conf['index_class_color'] == 1 )
		{
			foreach( $roster_conf['multilanguages'] as $language )
			{
				$class_color = array_search($row['class'],$wordings[$language]);
				if( strlen($class_color) > 0 )
				{
					$class_color = $wordings['enUS'][$class_color];
					break;
				}
			}

			if( $class_color != '' )
				return $icon_value.'<span class="class'.$class_color.'txt">'.$row['class'].'</span>';
			else
				return $icon_value.'<span class="class'.$row['class'].'txt">'.$row['class'].'</span>';
		}
		else
		{
		    return $icon_value.$row['class'];
		}
	}
	else
	{
		return '&nbsp;';
	}
}


/**
 * Controls Output of the Class Dividers
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function class_divider ( $text )
{
	global $wordings, $roster_conf;

	// Class Icon
	foreach ($roster_conf['multilanguages'] as $language)
	{
		$icon_name = $wordings[$language]['class_iconArray'][$text];
		if( strlen($icon_name) > 0 ) break;
	}
	$icon_name = 'Interface/Icons/'.$icon_name;

	$icon_value = '<a id="'.$text.'" /><img class="membersRowimg" width="16" height="16" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';

	return '<div class="membersGroup">'.$icon_value.$text.'</div>';

}


/**
 * Controls Output of the Tradeskill Icons Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function tradeskill_icons ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	if( $row['clientLocale'] != '' )
	{
		$lang = $row['clientLocale'];

		$SQL_prof = $wowdb->query( "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` = '".$row['member_id']."' AND (`skill_type` = '".$wordings[$lang]['professions']."' OR `skill_type` = '".$wordings[$lang]['secondary']."') ORDER BY `skill_order` ASC" );

		$cell_value = '';
		while ( $r_prof = $wowdb->fetch_assoc( $SQL_prof ) )
		{
			$toolTip = str_replace(':','/',$r_prof['skill_level']);
			$toolTiph = $r_prof['skill_name'];

			if( $r_prof['skill_name'] == $wordings[$lang]['riding'] )
			{
				if( $row['class']==$wordings[$lang]['Paladin'] || $row['class']==$wordings[$lang]['Warlock'] )
				{
					$skill_image = 'Interface/Icons/'.$wordings[$lang]['ts_ridingIcon'][$row['class']];
				}
				else
				{
					$skill_image = 'Interface/Icons/'.$wordings[$lang]['ts_ridingIcon'][$row['race']];
				}
			}
			else
			{
				$skill_image = 'Interface/Icons/'.$wordings[$lang]['ts_iconArray'][$r_prof['skill_name']];
			}

			$cell_value .= "<img class=\"membersRowimg\" width=\"".$roster_conf['index_iconsize']."\" height=\"".$roster_conf['index_iconsize']."\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" ".makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP')." />\n";
		}
	}
	else
	{
		$cell_value = '&nbsp;';
	}
	return $cell_value;
}


/**
 * Controls Output of the Level Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function level_value ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	// Configurlate exp is player has it
	if( !empty($row['exp']) )
	{
		list($current, $max, $rested) = explode( ':', $row['exp'] );

		if( $rested > 0 )
		{
			$rested = ' : '.$rested;
		}
		$togo = $max - $current;
		$togo .= ' XP until level '.($row['level']+1);

		$percent_exp = ($max > 0 ? round(($current/$max)*100) : 0);

		$tooltip = '<div style="white-space:nowrap;" class="levelbarParent" style="width:200px;"><div class="levelbarChild">XP '.$current.'/'.$max.$rested.'</div></div>';
		$tooltip .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="200">';
		$tooltip .= '<tr>';
		$tooltip .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$percent_exp.'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt=""></td>';
		$tooltip .= '<td width="'.(100 - $percent_exp).'%"></td>';
		$tooltip .= '</tr>';
		$tooltip .= '</table>';


		if( $row['level'] == '70' )
		{
			$tooltip = makeOverlib($wordings[$roster_conf['roster_lang']]['max_exp'],'','',2,'',',WRAP');
		}
		else
		{
			$tooltip = makeOverlib($tooltip,$togo,'',2,'',',WRAP');
		}
	}

	if( $roster_conf['index_level_bar'] )
	{
		$percentage = round(($row['level']/70)*100);

		$cell_value .= '<div '.$tooltip.' style="cursor:default;"><div class="levelbarParent" style="width:70px;"><div class="levelbarChild">'.$row['level'].'</div></div>';
		$cell_value .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="70">';
		$cell_value .= '<tr>';
		$cell_value .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$percentage.'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt=""></td>';
		$cell_value .= '<td width="'.(100 - $percentage).'%"></td>';
		$cell_value .= "</tr>\n</table>\n</div>\n";
	}
	else
	{
		$cell_value = '<div'.$tooltip.' style="cursor:default;">'.$row['level'].'</div>';
	}

	return $cell_value;
}


/**
 * Controls Output of the Armor Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function armor_value ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	$cell_value = '&nbsp;';

	if( !empty($row['clientLocale']) )
		$lang = $row['clientLocale'];
	else
		$lang = $roster_conf['roster_lang'];

	// Configurlate armor is player has it
	if( $row['stat_armor_c'] )
	{
		$base = $row['stat_armor'];
		$current = $row['stat_armor_c'];
		$buff = $row['stat_armor_b'];
		$debuff = $row['stat_armor_d'];

		if( $buff == 0 )
		{
			$color = 'white';
			$mod_symbol = '';
		}
		else if( $buff < 0 )
		{
			$color = 'purple';
			$mod_symbol = '-';
		}
		else
		{
			$color = 'green';
			$mod_symbol = '+';
		}


		$name = $wordings[$lang]['armor'];
		if( !empty($row['mitigation']) )
			$tooltip = '<span class="red">'.$wordings[$lang]['tooltip_damage_reduction'].': '.$row['mitigation'].'%</span>';

		if( $mod_symbol == '' )
		{
			$tooltipheader = $name.' '.$current;
		}
		else
		{
			$tooltipheader = "$name $current ($base <span class=\"$color\">$mod_symbol $buff</span>)";
		}

		$line = '<span style="color:#ffffff;font-size:12px;font-weight:bold;">'.$tooltipheader.'</span><br />';
		$line .= '<span style="color:#DFB801;">'.$tooltip.'</span>';

		$cell_value  = '<span style="cursor:help;" '.makeOverlib($line,'','',2).'>';
		$cell_value .= '<strong class="'.$color.'">'.$current.'</strong>';
		$cell_value .= '</span>';
	}
	return $cell_value;
}


/**
 * Controls Output of the Money Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function money_value ( $row )
{
	global $wowdb, $roster_conf, $wordings;

	// Configurlate money if player has it
	$cell_value = '<div class="money">';
	$return = '';

	if( !empty($row['money_g']) )
		$return .= $row['money_g'].' <img src="'.$roster_conf['img_url'].'bagcoingold.gif" alt="g"/> ';
	if( !empty($row['money_s']) )
		$return .= $row['money_s'].' <img src="'.$roster_conf['img_url'].'bagcoinsilver.gif" alt="s"/> ';
	if( !empty($row['money_c']) )
		$return .= $row['money_c'].' <img src="'.$roster_conf['img_url'].'bagcoinbronze.gif" alt="c"/> ';

	if( !empty($return) )
		$cell_value .= $return.'</div>';
	else
		$cell_value =  '&nbsp;';

	return $cell_value;
}


/**
 * Controls Output of a Note Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function note_value ( $row )
{
	global $roster_conf, $wordings;

	$tooltip='';
	if( !empty($row['note']) )
	{
		$prg_find = array('/"/','/&/','|\\>|','|\\<|',"/\\n/");
		$prg_rep  = array('&quot;','&amp;','&gt;','&lt;','<br />');

		$note = preg_replace($prg_find, $prg_rep, $row['note']);

		if( $roster_conf['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$wordings[$roster_conf['roster_lang']]['note'],'',1,'',',WRAP').' alt="[]" />';
		}
	}
	else
	{
		$note = '&nbsp;';
		if( $roster_conf['compress_note'] )
		{
			$note = '<img src="'.$roster_conf['img_url'].'no_note.gif" alt="[]" />';
		}
	}

	return $note;
}
?>