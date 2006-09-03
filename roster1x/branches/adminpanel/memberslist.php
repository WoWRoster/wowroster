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

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];
$guildMOTD = $guild_info['guild_motd'];
$guildFaction = $guild_info['faction'];

if( $roster_conf['index_update_inst'] )
{
	print '            <a href="#update"><font size="4">'.$wordings[$roster_conf['roster_lang']]['update_link'].'</font></a><br /><br />';
}


if ( $roster_conf['index_motd'] == 1 )
{
	if( $roster_conf['motd_display_mode'] )
	{
		print '<img src="motd.php?motd='.urlencode($guildMOTD).'" alt="Guild Message of the Day" /><br /><br />';
	}
	else
	{
		echo '<span class="GMOTD">Guild MOTD: '.$guildMOTD.'</span><br /><br />';
	}
}

include_once (ROSTER_LIB.'menu.php');


if( $roster_conf['hspvp_list_disp'] == 'hide' )
{
	$pvp_hs_colapse='';
	$pvp_hs_full   =' style="display:none;"';
}
else
{
	$pvp_hs_colapse=' style="display:none;"';
	$pvp_hs_full   ='';
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
1) The main query is defined in the calling index file.
2) So are the fields. Field parameters are below
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

		// Javascript order field.
		// If this collumn is best sorted on something other than what is shown in the table, put the name
		// of the field to sort on in here.
		'jsort'      => 'fieldname'

		// Not currently used, required collumn
		'required'   => true,

		// Not currently used, indicates to show by default
		'default'    => true,

		// This is in case you need to use a function to modify the value for a field
		// See examples at the bottom of this file.
		// Remember the JS sorts on the innerHTML of the first node. Make it an invisible div if needed.
		'value'      => 'function_name',
	),
);
************************/

// Merge Arrays
$FIELDS = array();
foreach ($FIELD as $field )
{
	$FIELDS += $field;
}


// Print the sql string
if ( $roster_conf['sqldebug'] )
{
	echo "<!-- $mainQuery -->\n";
}

$result = $wowdb->query( $mainQuery ) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$mainQuery);


// switching the quoting around as I loathe printing ugly html, and I like readable code, so I compromise.

$cols = count( $FIELDS );

$borderTop = "<br />\n".border('syellow','start')."\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" id=\"membersData\">\n";

// header row
$tableHeaderRow = "  <thead><tr>\n";
$sortFields = "";
$sortoptions = '<option selected value="none"></option>'."\n";
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

	if ( $current_col == $cols )
	{
		$tableHeaderRow .= '    <th class="membersHeaderRight" id="'.$DATA['lang_field'].'">'.$th_text."</th>\n";
	}
	else
	{
		$tableHeaderRow .= '    <th class="membersHeader" id="'.$DATA['lang_field'].'">'.$th_text."</th>\n";
	}

	$sortoptions .= '<optgroup label="'.$th_text.'">'.
		'<option value="'.$current_col.'_asc">'.$th_text.' ASC</option>'.
		'<option value="'.$current_col.'_desc">'.$th_text.' DESC</option>'.
		'</optgroup>'."\n";


	if ($current_col > 1)
	{
		$sortFields .= '    <tr>';
	}
	$sortFields .= '<th class="membersHeader" onclick="toggleColumn('.($current_col-1).',this)" style="cursor:pointer;">'.$th_text.'</th>'.
	'<td><input type="text" id="filter_'.$current_col.'" onkeydown="enter_sort(event,6);" name="filter_'.$current_col.'">'."\n";

	$current_col++;
}
$tableHeaderRow .= "  </tr>\n";
// end header row

$borderBottom = "</table>\n".border('syellow','end');



// Build sort/filter block
echo
	'<div id="sortfilterCol" style="display:'.(($roster_conf['members_openfilter'])?'none':'inline').';">'."\n".
	border('sblue','start',"<div style=\"cursor:pointer;width:440px;\" onclick=\"swapShow('sortfilterCol','sortfilter')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"\"/>".$act_words['memberssortfilter']."</div>")."\n".
	border('sblue','end')."\n".
	'</div>'."\n".
	'<div id="sortfilter" style="display:'.(($roster_conf['members_openfilter'])?'inline':'none').';">'."\n".
	border('sblue','start',"<div style=\"cursor:pointer;width:440px;\" onclick=\"swapShow('sortfilterCol','sortfilter')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"\"/>".$act_words['memberssortfilter']."</div>")."\n".
	'<table><tr>'."\n".
	'<td class="membersHeader">'.$act_words['memberssort'].'</td>'."\n".
	'<td class="membersHeader">'.$act_words['memberscolshow'].'</td>'."\n".
	'<td class="membersHeader">'.$act_words['membersfilter'].'</td>'."\n".
	'<tr><td rowspan="'.$cols.'">'."\n";
for ($i=0; $i<4; $i++) {
	echo '<select id="sort'.$i.'" name="sort'.$i.'">'."\n".$sortoptions.'</select><br />';
}
echo
	'<button onclick="dosort(6); return false;">Go</button>'."\n".
	'<input type="hidden" id="sort4" name="sort4" value="3_desc">'.
	'<input type="hidden" id="sort5" name="sort5" value="1_asc">'.
	$sortFields.
	'</table>'."\n".
	border('sblue','end').
	'</div>'."\n";






// Counter for row striping
$striping_counter = 0;

echo $borderTop.$tableHeaderRow;

while ( $row = $wowdb->fetch_assoc( $result ) )
{
	// actual player rows
	// Increment counter so rows are colored alternately
	$stripe_counter = ( ( $striping_counter++ % 2 ) + 1 );
	echo "<tbody><tr class='membersRowColor".$stripe_counter."'>\n";
	$current_col = 1;


	// Echoing cells w/ data
	foreach ( $FIELDS as $field => $DATA )
	{
		if ( isset( $DATA['value'] ) )
		{
			$cell_value = $DATA['value']( $row );
		}
		elseif ( isset( $DATA['jsort'] ) )
		{
			$cell_value = '<div style="display:none; ">'.$row[$DATA['jsort']].'</div>'.$row[$field];
			if (empty($row[$field]))
			{
				$cell_value .= '&nbsp;';
			}
		}
		else
		{
			$cell_value = '<div>'.$row[$field].'</div>';
		}

		// IMPORTANT do not add any spaces between the td and the $cell_value or the javascript will break
		if ( $current_col == $cols ) // last col
		{
			echo "    <td class='membersRowCell'>$cell_value</td>\n";
		}
		else
		{
			echo "    <td class='membersRowRightCell'>$cell_value</td>\n";
		}
		$current_col++;
	}
	echo "  </tr>\n";
}

echo $borderBottom;


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

	echo "<!--";print_r($row);echo "-->";

	if( $roster_conf['index_member_tooltip'] )
	{
		if ( $row['RankInfo'] > 0 )
		{
			$rankname = $row['RankName'].' ';
		}

		$tooltip_h = $row['name'].' : '.$row['guild_title'];

		$tooltip .= 'Level '.$row['level'].' '.$row['class']."\n";

		if( isset($rankname) )
			$tooltip .= $rankname.' of the '.$guildFaction."\n";

		$tooltip .= $wordings[$roster_conf['roster_lang']]['lastonline'].': '.$row['last_online'].' in '.$row['zone'];
		$tooltip .= ($row['nisnull'] ? '' : "\n".$wordings[$roster_conf['roster_lang']]['note'].': '.$row['note']);

		$tooltip = '<div style="cursor:help;" '.makeOverlib($tooltip,$tooltip_h,'',1,'',',WRAP').'>';


		if ( $row['server'] )
		{
			return "<div style='display:none;'>".$row['name']."</div>".$tooltip.'<a href="char.php?name='.$row['name'].'&amp;server='.$row['server'].'">'.$row['name'].'</a></div>';
		}
		else
		{
			return "<div style='display:none;'>".$row['name']."</div>".$tooltip.$row['name'].'</div>';
		}
	}
	else
	{
		if ( $row['server'] )
		{
			return "<div style='display:none;'>".$row['name']."</div>".'<a href="char.php?name='.$row['name'].'&amp;server='.$row['server'].'">'.$row['name'].'</a>';
		}
		else
		{
			return '<div>'.$row['name'].'</div>';
		}
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
		    return "<div style='display:none;'>".$row['class']."</div>".$icon_value.$row['class'];
		}
	}
	else
	{
		return '&nbsp;';
	}
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

	// Calculate Exp if player has it
	if( !empty($row['exp']) )
	{
		list($current, $max, $rested) = explode( ':', $row['exp'] );

		if( $rested > 0 )
		{
			$rested = ' : '.$rested;
		}
		$togo = $max - $current;
		$togo .= ' XP until level '.($row['level']+1);

		$percent_exp =  round(($current/$max)*100);

		$tooltip = '<div style="white-space:nowrap;" class="levelbarParent" style="width:200px;"><div class="levelbarChild">XP '.$current.'/'.$max.$rested.'</div></div>';
		$tooltip .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="200">';
		$tooltip .= '<tr>';
		$tooltip .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$percent_exp.'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt=""></td>';
		$tooltip .= '<td width="'.(100 - $percent_exp).'%"></td>';
		$tooltip .= '</tr>';
		$tooltip .= '</table>';


		if( $row['level'] == '60' )
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
		$percentage = round(($row['level']/60)*100);

		$cell_value .= '<div '.$tooltip.' style="cursor:default;"><div class="levelbarParent" style="width:70px;"><div class="levelbarChild">'.$row['level'].'</div></div>'."\n";
		$cell_value .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="70">'."\n";
		$cell_value .= '<tr>'."\n";
		$cell_value .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$percentage.'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt=""></td>'."\n";
		$cell_value .= '<td width="'.(100 - $percentage).'%"></td>'."\n";
		$cell_value .= "</tr>\n</table>\n</div>\n";
	}
	else
	{
		$cell_value = '<div'.$tooltip.' style="cursor:default;">'.$row['level'].'</div>';
	}

	return '<div style="display:none">'.$row['level'].'</div>'.$cell_value;
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

	$toolTip = '<div class="levelbarParent" style="width:100%;"><div class="levelbarChild">'.$row['Rankexp'].'%</div></div>';
	$toolTip .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%">';
	$toolTip .= '<tr>';
	$toolTip .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$row['Rankexp'].'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt=""></td>';
	$toolTip .= '<td width="'.(100 - $row['Rankexp']).'%"></td>';
	$toolTip .= '</tr>';
	$toolTip .= '</table>';

	if ( $row['RankInfo'] > 0 )
	{
		$cell_value = "<div style='display:none;'>".$row['RankInfo']."</div><div ".makeOverlib($toolTip,$row['RankName'].' ('.$wordings[$roster_conf['roster_lang']]['rank'].' '.$row['RankInfo'].')','',2,'',',RIGHT,WRAP').">".$rankicon.' '.$row['RankName']."</div>";

		return $cell_value;
	}
	else
	{
		return '&nbsp;';
	}
}

?>
