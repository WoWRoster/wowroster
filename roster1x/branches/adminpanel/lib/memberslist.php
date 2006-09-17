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

class memberslist {

	var $listname = 'membersData';	// table ID for javascript
	var $fields;			// field definitions
	var $query;			// main query

	var $tableHeaderRow;		// Column headers
	var $sortFields;		// Column names and sort input fields
	var $sortoptions;		// List of 'option' fields for the sort select boxes

	/**
	 * Prepare the data to build a memberlist.
	 *
	 * @param string $query
	 *	The main SQL query for this list
	 * @param array $fields
	 *	Array with field data. See documentation.
	 * @param string $listname
	 *	The ID used by javascript to identify this memberstable.
	 */
	function prepareData($query, $fields, $listname)
	{
		global $wowdb, $wordings, $act_words, $roster_conf;

		$this->listname = $listname;
		$this->fields = $fields;
		$this->query = $query;

		$cols = count( $fields );

		// header row
		$this->tableHeaderRow = "  <thead><tr>\n";
		$this->sortFields = "";
		$this->sortoptions = '<option selected value="none"></option>'."\n";
		$current_col = 1;
		foreach ( $fields as $field => $DATA )
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
				$this->tableHeaderRow .= '    <th class="membersHeaderRight" id="'.$DATA['lang_field'].'" onclick="sortColumn('.$current_col.',6,\''.$this->listname.'\');" style="cursor:pointer;">'.$th_text."</th>\n";
			}
			else
			{
				$this->tableHeaderRow .= '    <th class="membersHeader" id="'.$DATA['lang_field'].'" onclick="sortColumn('.$current_col.',6,\''.$this->listname.'\');" style="cursor:pointer;">'.$th_text."</th>\n";
			}

			$this->sortoptions .= '<optgroup label="'.$th_text.'">'.
				'<option value="'.$current_col.'_asc">'.$th_text.' ASC</option>'.
				'<option value="'.$current_col.'_desc">'.$th_text.' DESC</option>'.
				'</optgroup>'."\n";


			if ($current_col > 1)
			{
				$this->sortFields .= '    <tr>';
			}
			$this->sortFields .= '<th class="membersHeader" onclick="toggleColumn('.($current_col-1).',this,\''.$this->listname.'\');" style="cursor:pointer;">'.$th_text.'</th>'.
			'<td><input type="text" id="'.$this->listname.'_filter_'.$current_col.'" onkeydown="enter_sort(event,6,\''.$this->listname.'\');" name="'.$this->listname.'_filter_'.$current_col.'">'."\n";

			$current_col++;
		}
		$this->tableHeaderRow .= "  </tr>\n";
		// end header row
	}

	/**
	 * Returns the sort/filter box
	 */
	function makeFilterBox()
	{
		global $wowdb, $wordings, $act_words, $roster_conf;

		$cols = count( $this->fields );


		$output .=
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
			$output .= '<select id="'.$this->listname.'_sort_'.$i.'" name="'.$this->listname.'_sort_'.$i.'">'."\n".$this->sortoptions.'</select><br />';
		}
		$output .=
			'<button onclick="dosort(6,\''.$this->listname.'\'); return false;">Go</button>'."\n".
			'<input type="hidden" id="'.$this->listname.'_sort_4" name="'.$this->listname.'_sort_4" value="3_desc">'.
			'<input type="hidden" id="'.$this->listname.'_sort_5" name="'.$this->listname.'_sort_5" value="1_asc">'.
			$this->sortFields.
			'</table>'."\n".
			border('sblue','end').
			'</div>'."\n";

		return $output;
	}

	/**
	 * Returns the actual list. (but not the border)
	 */
	function makeMembersList()
	{
		global $wowdb, $wordings, $act_words, $roster_conf;


		$output  = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" id=\"".$this->listname."\">\n";
		$output .= $this->tableHeaderRow;

		$result = $wowdb->query( $this->query );

		if ( !$result )
		{
			die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$mainQuery);
		}

		$striping_counter = 0;

		while ( $row = $wowdb->fetch_assoc( $result ) )
		{
			// actual player rows
			// Increment counter so rows are colored alternately
			$stripe_counter = ( ( $striping_counter++ % 2 ) + 1 );
			$output .= "<tbody><tr class='membersRowColor".$stripe_counter."'>\n";
			$current_col = 1;


			// Echoing cells w/ data
			foreach ( $this->fields as $field => $DATA )
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
					$output .= "    <td class='membersRowCell'>$cell_value</td>\n";
				}
				else
				{
					$output .= "    <td class='membersRowRightCell'>$cell_value</td>\n";
				}
				$current_col++;
			}
			$output .= "  </tr>\n";
		}

		$output .= "</table>\n";

		return $output;
	}


	/**
	 * Returns the MOTD
	 *
	 * @return string | either image or text version of motd
	 */
	function makeMotd()
	{
		global $roster_conf, $guild_info;

		if( $roster_conf['motd_display_mode'] )
		{
			return '<img src="motd.php" alt="Guild Message of the Day" /><br /><br />';
		}
		else
		{
			return '<span class="GMOTD">Guild MOTD: '.htmlspecialchars($guild_info['guild_motd']).'</span><br /><br />';
		}
	}
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
	global $wordings, $roster_conf;

	if( $roster_conf['index_member_tooltip'] )
	{
		if ( $row['RankInfo'] > 0 )
		{
			$rankname = $row['RankName'].' ';
		}

		$tooltip_h = $row['name'].' : '.$row['guild_title'];
		if( isset($rankname) )
			$tooltip_h = $rankname.' '.$tooltip_h;

		$tooltip .= $wordings[$roster_conf['roster_lang']]['level'].' '.$row['level'].' '.$row['class']."\n";


		$tooltip .= $wordings[$roster_conf['roster_lang']]['lastonline'].': '.$row['last_online'].' in '.$row['zone'];
		$tooltip .= ($row['status'] == '' ? '' : "\n".'Current Status: '.$row['status']);
		$tooltip .= ($row['luisnull'] ? '' : "\n".$wordings[$roster_conf['roster_lang']]['lastupdate'].': '.$row['last_update_format']);
		$tooltip .= ($row['nisnull'] ? '' : "\n".$wordings[$roster_conf['roster_lang']]['note'].': '.$row['note']);

		$tooltip = '<div style="cursor:help;" '.makeOverlib($tooltip,$tooltip_h,'',1,'',',WRAP').'>';


		if ( $row['server'] )
		{
			return "<div style='display:none;'>".$row['name']."</div>".$tooltip.'<a href="char.php?member='.$row['member_id'].'">'.$row['name'].'</a></div>';
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
			return "<div style='display:none;'>".$row['name']."</div>".'<a href="char.php?member='.$row['member_id'].'">'.$row['name'].'</a>';
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
				return "<div style='display:none;'>".$row['class']."</div>".$icon_value.'<span class="class'.$class_color.'txt">'.$row['class'].'</span>';
			else
				return "<div style='display:none;'>".$row['class']."</div>".$icon_value.'<span class="class'.$row['class'].'txt">'.$row['class'].'</span>';
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