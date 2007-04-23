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

if ( !defined('IN_SORTMEMBER') )
{
	die_quietly('Detected invalid access to this file!','SortMember');
}

class memberslist
{
	var $listname = 'membersData';	// table ID for javascript
	var $fields;					// field definitions
	var $query;						// main query

	var $tableHeaderRow;			// Column headers
	var $sortFields;				// Column names and sort input fields
	var $sortoptions;				// List of 'option' fields for the sort select boxes

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
		global $wowdb, $wordings, $act_words, $roster_conf, $addon;

		$this->listname = $listname;
		$this->fields = $fields;
		unset($fields);

		// Set GET vars here, to avoid NOTICE error hell
		$get_s = ( isset($_GET['s']) ? $_GET['s'] : '' );
		$get_d = ( isset($_GET['d']) ? $_GET['d'] : '' );

		// Get default sort from roster config
		if( empty($get_s) && !empty($addon['config']['def_sort']) )
		{
		   $get_s = $addon['config']['def_sort'];
		}

		if( isset($this->fields[$get_s]) )
		{
			$ORDER_FIELD = $this->fields[$get_s];
			if( !empty($get_d) && isset( $ORDER_FIELD['order_d'] ) )
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

		$this->query = $query . ' `members`.`level` DESC, `members`.`name` ASC';

		$cols = count( $this->fields );

		// header row
		$this->tableHeaderRow = '  <thead><tr>'."\n".'<th class="membersHeader">&nbsp;</th>'."\n";
		$this->sortFields = '';
		$this->sortoptions = '<option selected="selected" value="none">&nbsp;</option>'."\n";
		$current_col = 1;
		foreach ( $this->fields as $field => $DATA )
		{
			// If this is a force invisible field, don't do anything with it.
			if( $DATA['display'] == 0 || ($DATA['display'] == 1 && $addon['config']['nojs']))
			{
				unset($this->fields[$field]);
				continue;
			}

			// See if there is a lang value for the header
			if( !empty($act_words[$DATA['lang_field']]) )
			{
				$th_text = $act_words[$DATA['lang_field']];
			}
			else
			{
				$th_text = $DATA['lang_field'];
			}

			if( $addon['config']['nojs'] )
			{
				// click a sorted field again to reverse sort it
				// Don't add it if it is detected already
				if( $get_d != 'true' )
				{
					$desc = ( $get_s == $field ) ? '&amp;d=true' : '';
				}
				else
				{
					$desc = '';
				}

				$this->tableHeaderRow .= '    <th class="membersHeader"><a href="'.makelink('&amp;s='.$field.$desc).'">'.$th_text."</a></th>\n";
			}
			else
			{
				if( $DATA['display'] == 1 )
				{
					$this->tableHeaderRow .= '    <th class="membersHeader" id="'.$DATA['lang_field'].'" onclick="sortColumn('.$current_col.',6,\''.$this->listname.'\');" style="cursor:pointer;display:none;">'.$th_text."</th>\n";
				}
				else
				{
					$this->tableHeaderRow .= '    <th class="membersHeader" id="'.$DATA['lang_field'].'" onclick="sortColumn('.$current_col.',6,\''.$this->listname.'\');" style="cursor:pointer;">'.$th_text."</th>\n";
				}
			}

			$this->sortoptions .= '<optgroup label="'.$th_text.'">'.
				'<option value="'.$current_col.'_asc">'.$th_text.' ASC</option>'.
				'<option value="'.$current_col.'_desc">'.$th_text.' DESC</option>'.
				'</optgroup>'."\n";

			if( $current_col > 1 )
			{
				$this->sortFields .= '    <tr>';
			}

			// Name in sort box toggles if this isn't a force visible field.
			if( $DATA['display'] == 3 )
			{
				$this->sortFields .= '<th class="membersHeader">'.$th_text.'</th>';
			}
			elseif( $DATA['display'] == 2 )
			{
				$this->sortFields .= '<th class="membersHeader" onclick="toggleColumn('.($current_col).',this,\''.$this->listname.'\');" style="cursor:pointer;">'.$th_text.'</th>';
			}
			else
			{
				$this->sortFields .= '<th class="membersHeader" onclick="toggleColumn('.($current_col).',this,\''.$this->listname.'\');" style="cursor:pointer; background-color:#5b5955;">'.$th_text.'</th>';
			}

			$this->sortFields .= '<td><input type="text" id="'.$this->listname.'_filter_'.$current_col.'" onkeydown="enter_sort(event,6,\''.$this->listname.'\');" name="'.$this->listname.'_filter_'.$current_col.'" /></td></tr>'."\n";

			$current_col++;
		}
		$this->tableHeaderRow .= "  </tr>\n</thead>\n";
		// end header row
	}

	/**
	 * Returns the sort/filter box
	 */
	function makeFilterBox()
	{
		global $wowdb, $wordings, $act_words, $roster_conf, $addon;

		if( $addon['config']['nojs'] )
		{
			return '';
		}

		$cols = count( $this->fields );

		$output =
			'<div id="sortfilterCol" style="display:'.(($addon['config']['openfilter'])?'none':'inline').';">'."\n".
			border('sblue','start',"<div style=\"cursor:pointer;width:440px;\" onclick=\"swapShow('sortfilterCol','sortfilter')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\"/>".$act_words['memberssortfilter']."</div>")."\n".
			border('sblue','end')."\n".
			'</div>'."\n".
			'<div id="sortfilter" style="display:'.(($addon['config']['openfilter'])?'inline':'none').';">'."\n".
			border('sblue','start',"<div style=\"cursor:pointer;width:440px;\" onclick=\"swapShow('sortfilterCol','sortfilter')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\"/>".$act_words['memberssortfilter']."</div>")."\n".
			'<table><tr>'."\n".
			'<td class="membersHeader">'.$act_words['memberssort'].'</td>'."\n".
			'<td class="membersHeader">'.$act_words['memberscolshow'].'</td>'."\n".
			'<td class="membersHeader">'.$act_words['membersfilter'].'</td>'."\n".
			'</tr>'."\n".
			'<tr><td rowspan="'.$cols.'">'."\n";
		for ($i=0; $i<4; $i++) {
			$output .= '<select id="'.$this->listname.'_sort_'.$i.'" name="'.$this->listname.'_sort_'.$i.'">'."\n".$this->sortoptions.'</select><br />';
		}
		$output .=
			'<button onclick="dosort(6,\''.$this->listname.'\'); return false;">Go</button>'."\n".
			'<input type="hidden" id="'.$this->listname.'_sort_4" name="'.$this->listname.'_sort_4" value="3_desc" />'."\n".
			'<input type="hidden" id="'.$this->listname.'_sort_5" name="'.$this->listname.'_sort_5" value="1_asc" />'."\n".
			'</td>'."\n".
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
		global $wowdb, $wordings, $act_words, $roster_conf, $addon;

		$cols = count( $this->fields );

		$output  = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" id=\"".$this->listname."\">\n";
		$output .= $this->tableHeaderRow;

		$result = $wowdb->query( $this->query );

		if ( !$result )
		{
			die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$this->query);
		}

		while ( $row = $wowdb->fetch_assoc( $result ) )
		{
			$line = '';
			$current_col = 1;

			// Echoing cells w/ data
			foreach ( $this->fields as $field => $DATA )
			{
				if ( isset( $DATA['value'] ) )
				{
					$cell_value = call_user_func($DATA['value'], $row, $field );
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
					if($row[$field] == '')
					{
						$row[$field] = '&nbsp;';
					}
					$cell_value = '<div>'.$row[$field].'</div>';
				}


				/**
				 * IMPORTANT do not add any spaces between the td and the
				 * $cell_value or the javascript will break
				 * This construct means we can't hide the first column. But
				 * that's no problem cause it's probably the name anyway which
				 * is locked on force visible.
				 */
				if( $current_col == 1 )
				{
					// Cache lines for main/alt stuff
					if( !isset($row['main_id']) || $row['main_id'] == $row['member_id'] )
					{
						$line .= '    <td class="membersRowCell">'.$cell_value.'</td>'."\n";
					}
					else
					{
						$line .= '    <td class="membersRowCell" style="padding-left:20px;">'.$cell_value.'</td>'."\n";
					}
				}
				elseif( $DATA['display'] == 1 )
				{
					$line .= '    <td class="membersRowCell" style="display:none;">'.$cell_value.'</td>'."\n";
				}
				else
				{
					$line .= '    <td class="membersRowCell">'.$cell_value.'</td>'."\n";
				}
				$current_col++;
			}

			// Cache lines for main/alt stuff
			if( !$addon['config']['group_alts'] || !isset($row['main_id']) || $row['main_id'] == $row['member_id'] )
			{
				$lines[$row['member_id']]['main'] = $line;
			}
			else
			{
				$lines[$row['main_id']]['alts'][] = $line;
			}
		}

		$stripe_counter = 0;
		// Main/Alt block
		foreach($lines as $member_id => $block)
		{
			$stripe_counter = ($stripe_counter % 2) + 1;
			$stripe_class = ' class="membersRowColor'.$stripe_counter.'"';

			// Main, or no alt data
			if( !isset($block['alts']) || 0 == count($block['alts']) )
			{
				$output .= '<tbody><tr'.$stripe_class.'><td class="membersRowCell"></td>'.$block['main'].'</tr></tbody>';
				continue;
			}
			// Mainless alt.
			if( !isset($block['main']) )
			{
				foreach( $block['alts'] as $line )
				{
					$output .= '<tbody><tr'.$stripe_class.'><td class="membersRowCell"><span class="red">MA</span></td>'.$line.'</tr></tbody>';
				}
				continue;
			}

			// Main with alts
			$openimg = 'minus.gif';

			$output .= '<tbody id="playerrow-'.$member_id.'"><tr'.$stripe_class.'><td class="membersRowCell">'."\n".
				'<a href="#" onclick="toggleAlts(\'playerrow-'.$member_id.'\',\'foldout-'.$member_id.'\',\''.$roster_conf['img_url'].'minus.gif\',\''.$roster_conf['img_url'].'plus.gif\'); return false;">'.
				'<img src="'.$roster_conf['img_url'].$openimg.'" id="foldout-'.$member_id.'" alt="" /></a></td>'.
				$block['main']."\n".'</tr>'."\n";

			$alt_counter = 0;
			foreach( $block['alts'] as $line )
			{
				$alt_counter = ($alt_counter % 2) + 1;
				$stripe_class = ' class="membersRowAltColor'.$alt_counter.'"';
				$output .= '<tr'.$stripe_class.'><td class="membersRowCell"></td>'."\n".$line."\n".'</tr>'."\n";
			}
			$output .= '</tbody>'."\n";
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
			return '<img src="motd.php" alt="Guild MOTD: '.htmlspecialchars($guild_info['guild_motd']).'" /><br /><br />';
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
function name_value ( $row, $field )
{
	global $roster_conf, $act_words, $addon;

	if( $addon['config']['member_tooltip'] )
	{
		$tooltip_h = $row['name'].' : '.$row['guild_title'];

		$tooltip = 'Level '.$row['level'].' '.$row['sex'].' '.$row['race'].' '.$row['class']."\n";

		$tooltip .= $act_words['lastonline'].': '.$row['last_online'].' in '.$row['zone'];
		$tooltip .= ($row['nisnull'] ? '' : "\n".$act_words['note'].': '.$row['note']);

		$tooltip = '<div style="cursor:help;" '.makeOverlib($tooltip,$tooltip_h,'',1,'',',WRAP').'>';


		if ( $row['server'] )
		{
			return '<div style="display:none; ">'.$row['name'].'</div>'.$tooltip.'<a href="'.makelink('char&amp;member='.$row['member_id']).'">'.$row['name'].'</a></div>';
		}
		else
		{
			return '<div style="display:none; ">'.$row['name'].'</div>'.$tooltip.$row['name'].'</div>';
		}
	}
	else
	{
		if ( $row['server'] )
		{
			return '<div style="display:none; ">'.$row['name'].'</div>'.'<a href="char.php?name='.$row['name'].'&amp;server='.$row['server'].'">'.$row['name'].'</a>';
		}
		else
		{
			return '<div style="display:none; ">'.$row['name'].'</div>'.$row['name'];
		}
	}
}

/**
 * Controls Output of the Class Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function class_value ( $row, $field )
{
	global $wordings, $roster_conf, $addon;

	if( $row['class'] != '' )
	{
		// Class Icon
		if( $addon['config']['class_icon'] == 1 )
		{
			foreach ($roster_conf['multilanguages'] as $language)
			{
				$icon_name = isset($wordings[$language]['class_iconArray'][$row['class']]) ? $wordings[$language]['class_iconArray'][$row['class']] : '';
				if( strlen($icon_name) > 0 ) break;
			}
			$icon_name = 'Interface/Icons/'.$icon_name;

			$icon_value = '<img class="membersRowimg" width="'.$addon['config']['icon_size'].'" height="'.$addon['config']['icon_size'].'" src="'.$roster_conf['interface_url'].$icon_name.'.'.$roster_conf['img_suffix'].'" alt="" />&nbsp;';
		}
		else
		{
			$icon_value = '';
		}

		// Class name coloring
		if ( $addon['config']['class_color'] == 1 )
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
				return '<div style="display:none; ">'.$row['class'].'</div>'.$icon_value.'<span class="class'.$class_color.'txt">'.$row['class'].'</span>';
			else
				return '<div style="display:none; ">'.$row['class'].'</div>'.$icon_value.'<span class="class'.$row['class'].'txt">'.$row['class'].'</span>';
		}
		else
		{
		    return '<div style="display:none; ">'.$row['class'].'</div>'.$icon_value.$row['class'];
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
function level_value ( $row, $field )
{
	global $wowdb, $roster_conf, $wordings, $act_words, $addon;

	$tooltip = '';
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
		$tooltip .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$percent_exp.'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt="" /></td>';
		$tooltip .= '<td width="'.(100 - $percent_exp).'%"></td>';
		$tooltip .= '</tr>';
		$tooltip .= '</table>';


		if( $row['level'] == ROSTER_MAXCHARLEVEL )
		{
			$tooltip = makeOverlib($act_words['max_exp'],'','',2,'',',WRAP');
		}
		else
		{
			$tooltip = makeOverlib($tooltip,$togo,'',2,'',',WRAP');
		}
	}

	if( $addon['config']['level_bar'] )
	{
		$percentage = round(($row['level']/ROSTER_MAXCHARLEVEL)*100);

		$cell_value = '<div '.$tooltip.' style="cursor:default;"><div class="levelbarParent" style="width:70px;"><div class="levelbarChild">'.$row['level'].'</div></div>';
		$cell_value .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="70">';
		$cell_value .= '<tr>';
		$cell_value .= '<td style="background-image: url(\''.$roster_conf['img_url'].'expbar-var2.gif\');" width="'.$percentage.'%"><img src="'.$roster_conf['img_url'].'pixel.gif" height="14" width="1" alt="" /></td>';
		$cell_value .= '<td width="'.(100 - $percentage).'%"></td>';
		$cell_value .= "</tr>\n</table>\n</div>\n";
	}
	else
	{
		$cell_value = '<div'.$tooltip.' style="cursor:default;">'.$row['level'].'</div>';
	}

	return '<div style="display:none; ">'.$row['level'].'</div>'.$cell_value;
}

/**
 * Controls Output of the Honor Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function honor_value ( $row, $field )
{
	global $roster_conf, $wordings, $addon;

	if ( $row['lifetimeHighestRank'] > 0 )
	{
		if ( $addon['config']['honor_icon'] )
		{
			if( $row['lifetimeHighestRank'] < 10 )
			{
				$rankicon = 'Interface/PvPRankBadges/PvPRank0'.$row['lifetimeHighestRank'].'.'.$roster_conf['alt_img_suffix'];
			}
			else
			{
				$rankicon = 'Interface/PvPRankBadges/PvPRank'.$row['lifetimeHighestRank'].'.'.$roster_conf['alt_img_suffix'];
			}
			$rankicon = $roster_conf['interface_url'].$rankicon;
			$rankicon = "<img class=\"membersRowimg\" width=\"".$addon['config']['icon_size']."\" height=\"".$addon['config']['icon_size']."\" src=\"".$rankicon."\" alt=\"\" />";
		}
		else
		{
			$rankicon = '';
		}

		$cell_value = $rankicon.' '.$row['lifetimeRankName'];

		return '<div style="display:none; ">'.$row['lifetimeHighestRank'].'</div>'.$cell_value;
	}
	else
	{
		return '&nbsp;';
	}
}
