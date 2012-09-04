<?php
/** 
 * Dev.PKComp.net Accounts Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @author	   mdeshane, Original Author - Pleegwat
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    Accounts 
 * @subpackage Memberslist Class 
 */ 

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class memberslist
{
	var $listname = 'membersData';	// table ID for javascript
	var $fields;					// field definitions
	var $query;						// main query

	var $addon;						// SortMembers addon data

	var $tableHeaderRow;			// Column headers
	var $sortFields;				// Column names and sort input fields
	var $sortoptions;				// List of 'option' fields for the sort select boxes

	/**
	 * Constructor
	 *
	 * @param array $options
	 *		Override any of the default options, like client/server sorting, alt
	 *		grouping, or pagination.
	 * @param array $addon
	 *		If the SortMembers addon array has already been loaded, pass it here
	 *		for efficiency. if the addon array is not available, omit it, and it
	 *		will be loaded automatically.
	 */
	function memberslist($options = array(), $addon = array())
	{
		global $roster;

		$basename = 'memberslist';

		// --[ Get addon array ]--
		$this->addon = getaddon($basename);


		// Set the js in the roster header
		$roster->output['html_head'] .= '<script type="text/javascript" src="' . ROSTER_PATH . 'addons/' . $basename . '/js/sorttable.js"></script>';

		// Merge in the override options from the calling file
		if( !empty($options) )
		{
			$this->addon['config'] = array_merge($this->addon['config'], $options);
		}

		// Overwrite some options if they're specified by the client
		if( isset($_GET['style']) )
		{
			$this->addon['config']['nojs'] = ($_GET['style'] == 'server')?1:0;
		}
		if( isset($_GET['alts']) )
		{
			$this->addon['config']['group_alts'] = ($_GET['alts'] == 'open')?2:($_GET['alts'] == 'close')?1:0;
		}
	}

	/**
	 * Prepare the data to build a memberlist.
	 *
	 * @param string $query
	 *	The main SQL query for this list
	 * @param string $always_sort
	 *	The trailing end of the sort string, after the columnwise sort.
	 * @param array $fields
	 *	Array with field data. See documentation.
	 * @param string $listname
	 *	The ID used by javascript to identify this memberstable.
	 */
	function prepareData( $query, $always_sort,$where, $group, $order_first, $order_last, $fields, $listname )
	{
		global $roster;

		// Save some info
		$this->listname = $listname;
		$this->fields = $fields;
		unset($fields);
		$cols = count( $this->fields );

		$get_s = ( isset($_GET['s']) ? $_GET['s'] : '' );
		$get_d = ( isset($_GET['d']) ? $_GET['d'] : '' );
		$get_st = ( isset($_GET['st']) ? $_GET['st'] : 0 );

		if( $this->addon['config']['nojs'] && $this->addon['config']['page_size'] )
		{
			// --[ Fetch number of rows. Trim down the query a bit for speed. ]--
			$rowsqry = 'SELECT COUNT(*) '.substr($query, strpos($query,'FROM')).'1';
			$result = $roster->db->query($rowsqry);
			if( !$result )
			{
				die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$rowsqry);
			}

			$row = $roster->db->fetch($result);
			$num_rows = $row[0];
		}
		// --[ Page list ]--
		if( $this->addon['config']['nojs'] && $this->addon['config']['page_size']
			&& (1 < ($num_pages = ceil($num_rows/$this->addon['config']['page_size'])))
		)
		{
			$get_st = isset($_GET['st']) ? $_GET['st'] : 0;

			$pages = array_fill(0,$num_pages - 1, false);

			$pages[0] = true;
			$pages[1] = true;
			if( $get_st > 0 )
			{
				$pages[$get_st - 1] = true;
			}
			$pages[$get_st] = true;
			if( $get_st < $num_pages - 1 )
			{
				$pages[$get_st + 1] = true;
			}
			$pages[$num_pages - 2] = true;
			$pages[$num_pages - 1] = true;

			$params = '&amp;style='.($this->addon['config']['nojs']?'server':'client').
				'&amp;alts='.($this->addon['config']['group_alts']==2?'open':$this->addon['config']['group_alts']==1?'close':'ungroup');

			$this->tableHeaderRow = "<thead>\n  <tr>\n" . '    <th colspan="'.($cols+1).'" class="membersHeader" style="text-align:center;color:#ffffff">';

			$dots = true;
			foreach( $pages as $id => $show )
			{
				if( !$show )
				{
					if( !$dots )
					{
						$this->tableHeaderRow .= ' ... ';
					}
					$dots = 'true';
					continue;
				}
				if( !$dots )
				{
					$this->tableHeaderRow .= ' - ';
				}
				$dots = false;

				if( $id == $get_st )
				{
					$this->tableHeaderRow .= ($id+1)."\n";
				}
				else
				{
					$this->tableHeaderRow .= '<a href="'.makelink($params.'&amp;st='.($id+1)).'">'.($id+1).'</a>'."\n";
				}
			}
		}
		else
		{
			$this->tableHeaderRow = "<thead>\n";
		}

		// --[ Add sorting SQL ]--
		if( empty($get_s) && !empty($this->addon['config']['def_sort']) )
		{
		   $get_s = $this->addon['config']['def_sort'];
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
		if( !empty( $where ) )
		{
			$r = '';
			if (!empty( $search_filter ))
			{
				$r = ' AND '. implode( ' AND ', $search_filter );
			}
		
			$query .= ' WHERE (' . implode( ') AND (', $where ) . ')' . $r;
			//$query .= ' WHERE ;
		}

		// --[ Add grouping SQL ]--
		if( !empty( $group ) )
		{
			$query .= ' GROUP BY ' . implode( ',', $group );
		}
		$query .=  $always_sort;

		// --[ Add pagination SQL, if we're in server sort mode ]--
		if( $this->addon['config']['nojs']
			&& $this->addon['config']['page_size']
			&& is_numeric($get_st) )
		{
			$start = $get_st * $this->addon['config']['page_size'];
			$query .= ' LIMIT '.$start.','.$this->addon['config']['page_size'];
		}

		$this->query = $query.';';

		// If group alts is off, hide the column for it
		$style = ($this->addon['config']['group_alts']>=1)?'':' style="display:none;"';

		// header row
		$this->tableHeaderRow .= "<tr>\n".'<th class="membersHeader"'.$style.'>&nbsp;</th>'."\n";
		$this->sortFields = '';
		$this->sortoptions = '<option selected="selected" value="none">&nbsp;</option>'."\n";
		$current_col = 1;
		foreach ( $this->fields as $field => $DATA )
		{
			// If this is a force invisible field, don't do anything with it.
			if( $DATA['display'] == 0 || ($DATA['display'] == 1 && $this->addon['config']['nojs']))
			{
				unset($this->fields[$field]);
				continue;
			}

			// See if there is a lang value for the header
			if( !empty($roster->locale->act[$DATA['lang_field']]) )
			{
				$th_text = $roster->locale->act[$DATA['lang_field']];
			}
			elseif( !empty($roster->locale->act['user_charlist'][$DATA['lang_field']]) )
			{
				$th_text = $roster->locale->act['user_charlist'][$DATA['lang_field']];
			}
			elseif( !empty($roster->locale->act['user_rs'][$DATA['lang_field']]) )
			{
				$th_text = $roster->locale->act['user_rs'][$DATA['lang_field']];
			}
			else
			{
				$th_text = $DATA['lang_field'];
			}

			if( $this->addon['config']['nojs'] )
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

				$this->tableHeaderRow .= '    <th class="membersHeader"><a href="'.makelink('&amp;style=server&amp;alts='.($this->addon['config']['group_alts']==2?'open':($this->addon['config']['group_alts']==1)?'close':'ungroup').'&amp;s='.$field.$desc).'">'.$th_text."</a></th>\n";
			}
			else
			{
				if( $DATA['display'] == 1 )
				{
					$this->tableHeaderRow .= '    <th class="membersHeader '.$DATA['js_type'].'" id="'.$DATA['lang_field'].'" onclick="sortColumn('.$current_col.',6,\''.$this->listname.'\');" style="cursor:pointer;display:none;">'.$th_text."</th>\n";
				}
				else
				{
					$this->tableHeaderRow .= '    <th class="membersHeader '.$DATA['js_type'].'" id="'.$DATA['lang_field'].'" onclick="sortColumn('.$current_col.',6,\''.$this->listname.'\');" style="cursor:pointer;">'.$th_text."</th>\n";
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
		global $roster;

		if( $this->addon['config']['nojs'] )
		{
			return '';
		}

		$cols = count( $this->fields );

		$output =
			'<div id="sortfilterCol" style="display:'.(($this->addon['config']['openfilter'])?'none':'inline').';">'."\n".
			border('sblue','start',"<div style=\"cursor:pointer;width:440px;\" onclick=\"swapShow('sortfilterCol','sortfilter')\"><img src=\"".$roster->config['img_url']."plus.gif\" style=\"float:right;\" alt=\"+\"/>".$roster->locale->act['memberssortfilter']."</div>")."\n".
			border('sblue','end')."\n".
			'</div>'."\n".
			'<div id="sortfilter" style="display:'.(($this->addon['config']['openfilter'])?'inline':'none').';">'."\n".
			border('sblue','start',"<div style=\"cursor:pointer;width:440px;\" onclick=\"swapShow('sortfilterCol','sortfilter')\"><img src=\"".$roster->config['img_url']."minus.gif\" style=\"float:right;\" alt=\"-\"/>".$roster->locale->act['memberssortfilter']."</div>")."\n".
			'<table><tr>'."\n".
			'<td class="membersHeader">'.$roster->locale->act['memberssort'].'</td>'."\n".
			'<td class="membersHeader">'.$roster->locale->act['memberscolshow'].'</td>'."\n".
			'<td class="membersHeader">'.$roster->locale->act['membersfilter'].'</td>'."\n".
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
	 * Returns the additional controls
	 *
	 * @param string $dir
	 *		direction
	 */
	function makeToolBar($dir = 'horizontal')
	{
		global $roster;

		// Pre-store server get params
		$get = ( isset($_GET['s']) ? '&amp;s='.$_GET['s'] : '' );
		$get = ( isset($_GET['d']) ? '&amp;d='.$_GET['d'] : '' );

		$style = '&amp;style='.($this->addon['config']['nojs']?'server':'client');
		$alts = '&amp;alts='.($this->addon['config']['group_alts']==1?'show':'hide');

		if( $this->addon['config']['group_alts']>=1 )
		{
			$button[] = '<th class="membersHeader"><a href="#" onclick="closeAlts(\''.$this->listname.'\',\''.$roster->config['img_url'].'plus.gif\'); return false;"><img src="'.$roster->config['img_url'].'minus.gif" alt="+" />'.$roster->locale->act['closeall'].'</a></th>';
			$button[] = '<th class="membersHeader"><a href="#" onclick="openAlts(\''.$this->listname.'\',\''.$roster->config['img_url'].'minus.gif\'); return false;"><img src="'.$roster->config['img_url'].'plus.gif" alt="-" />'.$roster->locale->act['openall'].'</a></th>';
			$button[] = '<th class="membersHeader"><a href="'.makelink($style.'&amp;alts=ungroup'.$get).'">'.$roster->locale->act['ungroupalts'].'</a></th>';
		}
		elseif( $this->addon['config']['group_alts'] == 0 )
		{
			$button[] = '<th class="membersHeader"><a href="'.makelink($style.'&amp;alts=open'.$get).'">'.$roster->locale->act['openalts'].'</a></th>';
			$button[] = '<th class="membersHeader"><a href="'.makelink($style.'&amp;alts=close'.$get).'">'.$roster->locale->act['closealts'].'</a></th>';
		}
		if( $this->addon['config']['nojs'] )
		{
			$button[] = '<th class="membersHeader"><a href="'.makelink('&amp;style=client'.$alts).'">'.$roster->locale->act['clientsort'].'</a></th>';
		}
		else
		{
			$button[] = '<th class="membersHeader"><a href="'.makelink('&amp;style=server'.$alts.$get).'">'.$roster->locale->act['serversort'].'</a></th>';
		}

		if( $dir == 'horizontal' )
		{
			$output = implode("\n",$button);
		}
		else
		{
			$output = implode("</tr>\n<tr>",$button);
		}
		return messagebox('<table><tr>'.$output.'</tr></table>','','sgray');
	}

	/**
	 * Returns the actual list. (but not the border)
	 */
	function makeMembersList()
	{
		global $roster;

		$cols = count( $this->fields );

		$output  = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" id=\"".$this->listname."\">\n".$this->tableHeaderRow;

		$result = $roster->db->query( $this->query );

		if ( !$result )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$this->query);
		}

		// --[ Cache arrays for main/alt ordering ]--
		$lines = array();
		$lookup = array();

		// --[ Actual list ]--
		while ( $row = $roster->db->fetch( $result ) )
		{
			$line = '';
			$current_col = 1;

			// Echoing cells w/ data
			foreach ( $this->fields as $field => $DATA )
			{
				if (!empty($DATA['value'])) 
					{
						$cell_value = call_user_func('memberslist::'.$DATA['value'], $row, $field, (isset($DATA['passthrough']) ? $DATA['passthrough'] : array()) );
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
					if( $this->addon['config']['group_alts']<=0 || $row['main_id'] == $row['member_id'] )
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
			if( $this->addon['config']['group_alts'] <= 0 )
			{
				$lookup[] = count($lines);
				$lines[]['main'] = $line;
			}
			elseif( $row['main_id'] == $row['member_id'] )
			{
				$lookup[] = $row['member_id'];
				$lines[$row['member_id']]['main'] = $line;
			}
			else
			{
				$lines[$row['main_id']]['alts'][] = $line;
			}
		}

		$stripe_counter = 0;
		// Main/Alt block
		foreach(array_diff(array_keys($lines),$lookup) as $member_id)
		{
			$lookup[] = $member_id;
		}
		for($i=0; $i<count($lookup); $i++)
		{
			$member_id = $lookup[$i];
			$block = $lines[$member_id];
			$stripe_counter = ($stripe_counter % 2) + 1;
			$stripe_class = ' class="membersRowColor'.$stripe_counter.'"';

			// Group alts off
			if( $this->addon['config']['group_alts'] <= 0 )
			{
				$output .= '<tbody><tr'.$stripe_class.'><td class="membersRowCell" style="display:none;">&nbsp;</td>'.$block['main'].'</tr></tbody>';
				continue;
			}
			// Main, or no alt data
			if( !isset($block['alts']) || 0 == count($block['alts']) )
			{
				$output .= '<tbody><tr'.$stripe_class.'><td class="membersRowCell">&nbsp;</td>'.$block['main'].'</tr></tbody>';
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
			if( $this->addon['config']['group_alts'] == 2 )
			{
				$openimg = 'minus.gif';
				$openalt = '-';
			}
			else
			{
				$openimg = 'plus.gif';
				$openalt = '+';
			}

			$output .= '<tbody id="playerrow-'.$member_id.'"><tr'.$stripe_class.'><td class="membersRowCell">'.
				'<a href="#" onclick="toggleAlts(\'playerrow-'.$member_id.'\',\'foldout-'.$member_id.'\',\''.$roster->config['img_url'].'minus.gif\',\''.$roster->config['img_url'].'plus.gif\'); return false;">'.
				'<img src="'.$roster->config['img_url'].$openimg.'" id="foldout-'.$member_id.'" alt="'.$openalt.'" /></a></td>'.
				$block['main']."\n".'</tr>'."\n";

			$alt_counter = 0;
			foreach( $block['alts'] as $line )
			{
				$alt_counter = ($alt_counter % 2) + 1;
				$stripe_class = ' class="membersRowAltColor'.$alt_counter.'"';
				if( $this->addon['config']['group_alts'] == 1 )
				{
					$stripe_class .= ' style="display:none;"';
				}
				$output .= '<tr'.$stripe_class.'><td class="membersRowCell">&nbsp;</td>'."\n".$line."\n".'</tr>'."\n";
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
		global $roster;

		if( $roster->data['guild_motd'] != '' )
		{
			if( $roster->config['motd_display_mode'] )
			{
				return '<img src="motd.php?id=' . $roster->data['guild_id'] . '" alt="Guild MOTD: '.htmlspecialchars($roster->data['guild_motd']).'" /><br /><br />';
			}
			else
			{
				return '<table class="border_frame" cellpadding="0px" cellspacing="1px" ><tr><td class="border_colour sgoldborder motd_setup">'.htmlspecialchars($roster->data['guild_motd']).'</td></tr></table><br /><br />';
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
		global $roster;

		if( $this->addon['config']['member_tooltip'] )
		{
			$tooltip_h = $row['name'].' : '.$row['guild_title'];

			$tooltip = 'Level '.$row['level'].' '.$row['sex'].' '.$row['race'].' '.$row['class']."\n";

			$tooltip .= $roster->locale->act['lastonline'].': '.$row['last_online'].' in '.$row['zone'];
			$tooltip .= ($row['nisnull'] ? '' : "\n".$roster->locale->act['note'].': '.$row['note']);

			$tooltip = '<div style="cursor:help;" '.makeOverlib($tooltip,$tooltip_h,'',1,'',',WRAP').'>';


			if( active_addon('info') && $row['server'] )
			{
				return '<div style="display:none; ">'.$row['name'].'</div>'.$tooltip.'<a href="'.makelink('char-info&amp;a=c:'.$row['member_id']).'">'.$row['name'].'</a></div>';
			}
			else
			{
				return '<div style="display:none; ">'.$row['name'].'</div>'.$tooltip.$row['name'].'</div>';
			}
		}
		else
		{
			if ( active_addon('info') && $row['server'] )
			{
				return '<div style="display:none; ">'.$row['name'].'</div>'.'<a href="'.makelink('char-info&amp;a=c:'.$row['member_id']).'">'.$row['name'].'</a></div>';
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
	 *
	function class_value ( $row, $field )
	{
		global $roster, $addon;

		if( $row['class'] != '' )
		{
			$icon_value = '';
			// Class Icon
			if( $this->addon['config']['class_icon'] >= 1 )
			{
				foreach ($roster->multilanguages as $language)
				{
					$icon_name = isset($roster->locale->wordings[$language]['class_iconArray'][$row['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$row['class']] : '';
					if( strlen($icon_name) > 0 ) break;
				}
				$icon_name = 'class/' . $icon_name;

				$icon_value .= '<img class="membersRowimg" width="20" height="20" src="' . $roster->config['img_url'] . $icon_name . '.jpg" alt="" />';
			}

			// Don't proceed for characters without data
			if( $this->addon['config']['class_icon'] == 2 && isset($row['talents']) && !empty( $row['talents']) )
			{
				$lang = $row['clientLocale'];

				$talents = explode(',',$row['talents']);

				$spec = $specicon = '';
				$tooltip = array();
				$specpoint = 0;
				$notalent = true;
				foreach( $talents as $talent )
				{
					list($name, $points, $icon) = explode('|',$talent);
					$tooltip[] = $points;
					if( $points > $specpoint )
					{
						$specpoint = $points;
						$spec = $name;
						$specicon = $icon;
						$notalent = false;
					}
				}
				$specline = implode(' / ', $tooltip);
				if( !$notalent )
				{
					// Don't proceed for characters without data
					$specicon = $this->spec_icon($row);
					//$icon_value .= $this->spec_icon($row);
					//$specicon = '<img class="membersRowimg" width="20" height="20" src="'.$roster->config['img_url'].'spec/'.$specicon.'.'.$roster->config['img_suffix'].'" alt="" '.makeOverlib($specline,$spec,'',1,'',',RIGHT,WRAP').' />';
				}

				if( active_addon('info') )
				{
					$icon_value .= '<a href="' . makelink('char-info-talents&amp;a=c:' . $row['member_id']) . '">' . $specicon . '</a>';
				}
				else
				{
					$icon_value .= $specicon;
				}
			}

			// Talent or class text
			if( $this->addon['config']['talent_text'] && isset($specline) )
			{
				$fieldtext = $specline;
			}
			else
			{
				$fieldtext = $row['class'];
			}
			// Class name coloring
			if( $this->addon['config']['class_text'] == 2 )
			{
				foreach( $roster->multilanguages as $language )
				{
					$class_color = array_search($row['class'],$roster->locale->wordings[$language]);
					if( strlen($class_color) > 0 )
					{
						$class_color = $roster->locale->wordings['enUS'][$class_color];
						break;
					}
				}

				if( $class_color != '' )
				{
					return '<div style="display:none; ">' . $row['class'] . '</div>' . $icon_value . '<span class="class' . $class_color . 'txt">' . $fieldtext . '</span>';
				}
				else
				{
					return '<div style="display:none; ">' . $row['class'] . '</div>' . $icon_value . '<span class="class' . $row['class'] . 'txt">' . $fieldtext . '</span>';
				}
			}
			elseif( $this->addon['config']['class_text'] == 1 )
			{
				return '<div style="display:none; ">' . $row['class'] . '</div>' . $icon_value . $fieldtext;
			}
			else
			{
				return ($icon_value != '' ? '<div style="display:none; ">' . $row['class'] . '</div>' . $icon_value : '&nbsp;');
			}
		}
		else
		{
			return '&nbsp;';
		}
	}*/
	function class_value ( $row, $field )
	{
		global $roster, $addon;

		if( $row['class'] != '' )
		{
			$icon_value = '';
			// Class Icon
			if( $this->addon['config']['class_icon'] >= 1 )
			{
				foreach ($roster->multilanguages as $language)
				{
					$icon_name = isset($roster->locale->wordings[$language]['class_iconArray'][$row['class']]) ? $roster->locale->wordings[$language]['class_iconArray'][$row['class']] : '';
					if( strlen($icon_name) > 0 ) break;
				}

				$icon_value .= '<div class="item-sm"><img src="' . $roster->config['img_url'] . 'class/' . $icon_name . '.png" alt="" /><div class="mask"></div></div>';
			}

			// Don't proceed for characters without data
			if( $this->addon['config']['class_icon'] == 2 && isset($row['talents']) && !empty( $row['talents']) )
			{
				$icon_value .= $this->spec_icon($row);
			}

			// Talent or class text
			if( $this->addon['config']['talent_text'] && isset($row['talents']) && $row['talents'] != '' )
			{
				$talents = explode(',', $row['talents']);

				$_t = array();
				$notalent = true;
				foreach( $talents as $talent )
				{
					list($_b, $name, $points, $icon) = explode('|', $talent);
					$_t[$_b]['tip'][] = $points;
					if( !isset($_t[$_b]['point']) || $points > $_t[$_b]['point'] )
					{
						$_t[$_b]['point'] = $points;
						$_t[$_b]['name'] = $name;
						$notalent = false;
					}
				}

				$fieldtext = $_t[0]['name'] . ': ' . implode(' / ', $_t[0]['tip']);
			}
			else
			{
				$fieldtext = $row['class'];
			}

			// Class name coloring
			if( $this->addon['config']['class_text'] == 2 )
			{
				$class_color = ( isset($roster->locale->wordings['enUS']['id_to_class'][$row['classid']]) ? $roster->locale->wordings['enUS']['id_to_class'][$row['classid']] : $row['class'] );

				return '<div style="display:none;">' . $row['class'] . '</div>' . $icon_value . '<span class="class' . str_replace(' ','',$class_color) . 'txt">' . $fieldtext . '</span>';
			}
			elseif( $this->addon['config']['class_text'] == 1 )
			{
				return '<div style="display:none;">' . $row['class'] . '</div>' . $icon_value . $fieldtext;
			}
			else
			{
				return ($icon_value != '' ? '<div style="display:none;">' . $row['class'] . '</div>' . $icon_value : '&nbsp;');
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
		global $roster;

		$tooltip = '';
		// Configurlate exp is player has it
		if( !empty($row['exp']) )
		{
			list($current, $max, $rested) = explode( ':', $row['exp'] );

			if( $rested > 0 )
			{
				$rested = ' : '.$rested;
			}
			$togo = sprintf($roster->locale->act['user_charlist']['xp'], $max - $current, ($row['level']+1));

			$percent_exp = ($max > 0 ? round(($current/$max)*100) : 0);

			$tooltip = '<div style="white-space:nowrap;" class="levelbarParent" style="width:200px;"><div class="levelbarChild">XP '.$current.'/'.$max.$rested.'</div></div>';
			$tooltip .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="200">';
			$tooltip .= '<tr>';
			$tooltip .= '<td style="background-image: url(\''.$roster->config['img_url'].'expbar-var2.gif\');" width="'.$percent_exp.'%"><img src="'.$roster->config['img_url'].'pixel.gif" height="14" width="1" alt="" /></td>';
			$tooltip .= '<td width="'.(100 - $percent_exp).'%"></td>';
			$tooltip .= '</tr>';
			$tooltip .= '</table>';


			if( $row['level'] == ROSTER_MAXCHARLEVEL )
			{
				$tooltip = makeOverlib($roster->locale->act['max_exp'],'','',2,'',',WRAP');
			}
			else
			{
				$tooltip = makeOverlib($tooltip,$togo,'',2,'',',WRAP');
			}
		}

		if( $this->addon['config']['level_bar'] )
		{
			$percentage = round(($row['level']/ROSTER_MAXCHARLEVEL)*100);

			$cell_value = '<div '.$tooltip.' style="cursor:default;"><div class="levelbarParent" style="width:70px;"><div class="levelbarChild">'.$row['level'].'</div></div>';
			$cell_value .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="70">';
			$cell_value .= '<tr>';
			$cell_value .= '<td style="background-image: url(\''.$roster->config['img_url'].'expbar-var2.gif\');" width="'.$percentage.'%"><img src="'.$roster->config['img_url'].'pixel.gif" height="14" width="1" alt="" /></td>';
			$cell_value .= '<td width="'.(100 - $percentage).'%"></td>';
			$cell_value .= "</tr>\n</table>\n</div>\n";
		}
		else
		{
			$cell_value = '<div'.$tooltip.' style="cursor:default;">'.$row['level'].'</div>';
		}

		return '<div style="display:none; ">' . str_pad($row['level'],2,'0',STR_PAD_LEFT) . '</div>'.$cell_value;
	}

	/**
	 * Controls Output of the Honor Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function honor_value ( $row, $field )
	{
		global $roster;

		if ( $row['lifetimeHighestRank'] > 0 )
		{
			if ( $this->addon['config']['honor_icon'] )
			{
				if( $row['lifetimeHighestRank'] < 10 )
				{
					$rankicon = 'Interface/PvPRankBadges/pvprank0'.$row['lifetimeHighestRank'].'.'.$roster->config['alt_img_suffix'];
				}
				else
				{
					$rankicon = 'Interface/PvPRankBadges/pvprank'.$row['lifetimeHighestRank'].'.'.$roster->config['alt_img_suffix'];
				}
				$rankicon = $roster->config['interface_url'].$rankicon;
				$rankicon = "<img class=\"membersRowimg\" width=\"".$this->addon['config']['icon_size']."\" height=\"".$this->addon['config']['icon_size']."\" src=\"".$rankicon."\" alt=\"\" />";
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

	/**
	 * Controls Output of the Talent Spec Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function spec_icon( $row )
	{
		global $roster;
		$cell_value = '';

		// Don't proceed for characters without data
		if( !isset($row['talents']) || $row['talents'] == '' )
		{
			return '<img class="middle" width="24" height="24" src="' . $roster->config['img_url'] . 'pixel.gif" alt="" />';
		}

		$talents = explode(',',$row['talents']);
		$talent2 = explode(',',$row['talents2']);

		$_d = array();
		foreach( $talent2 as $tal )
		{
			list($name, $role, $icon) = explode('|', $tal);
				$_d[$name]['role'] = $role;
				$_d[$name]['name'] = $name;
				$_d[$name]['icon'] = $icon;
		}
		
		$_t = array();
		$specicon = '';
		$notalent = true;
		foreach( $talents as $talent )
		{
			list($_s, $name, $points, $icon, $_b) = explode('|', $talent);
			$_t[$_b]['tip'][] = $name.' - '.$points;
			if( !isset($_t[$_b]['point']) || $points > $_t[$_b]['point'] )
			{
				$_t[$_b]['point'] = $points;
				$_t[$_b]['name'] = $name;
				$_t[$_b]['icon'] = strtolower($_d[$name]['icon']);
				$notalent = false;
			}
		}
		if( !$notalent )
		{
			$tooltip = $icon = '';
			foreach( $_t as $idx => $build )
			{
				$icon = '<span class="item-sm"><img src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $build['icon'] . '.' . $roster->config['img_suffix'] . '" alt="" /><span class="mask"></span></span> ';
				$tooltip .= $icon . implode(' / ', $build['tip']) . '<br />';
			}
			$tooltip = '<div style="font-size:12px;font-weight:bold;">' . $tooltip . '</div>';
			$specicon = '<div class="item-sm" ' . makeOverlib($tooltip, $_t[1]['name'], '', 2, '', ',WRAP') . '><img src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $_t[1]['icon'] . '.' . $roster->config['img_suffix'] . '" alt="" /><div class="mask"></div></div>';
		}

		if( active_addon('info') )
		{
			$cell_value .= '<a href="' . makelink('char-info&amp;a=c:' . $row['member_id'] . '&amp;t=talents') . '">' . $specicon . '</a>';
		}
		else
		{
			$cell_value .= $specicon;
		}

		return $cell_value;
	}


	/**
	 * Controls Output of the Last Online Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function last_online_value ( $row )
	{
		global $roster;

		if ( $row['last_online'] != '')
		{
			$guild_time = strtotime($row['update_time']);
			$update_time = $row['last_online_stamp'];

			$difference = $guild_time - $update_time;

			$realtime = '<div style="display:none;">' . $update_time . '</div>';

			if( isset($row['online']) && $row['online'] == '1' || $difference < 0 )
			{
				return $realtime . $roster->locale->act['user_charlist']['online'];
			}

			if( $difference < 60 )
			{
				return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['second'] : $roster->locale->act['user_charlist']['seconds']),$difference);
			}
			else
			{
				$difference = round($difference / 60);
				if( $difference < 60 )
				{
					return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['minute'] : $roster->locale->act['user_charlist']['minutes']),$difference);
				}
				else
				{
					$difference = round($difference / 60);
					if( $difference < 24 )
					{
						return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['hour'] : $roster->locale->act['user_charlist']['hours']),$difference);
					}
					else
					{
						$difference = round($difference / 24);
						if( $difference < 7 )
						{
							return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['day'] : $roster->locale->act['user_charlist']['days']),$difference);
						}
						else
						{
							$difference = round($difference / 7);
							if( $difference < 4 )
							{
								return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['week'] : $roster->locale->act['user_charlist']['weeks']),$difference);
							}
							else
							{
								$difference = round($difference / 4);
								if( $difference < 12 )
								{
									return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['month'] : $roster->locale->act['user_charlist']['months']),$difference);
								}
								else
								{
									$difference = round($difference / 12);
									return $realtime . sprintf(($difference == '1' ? $roster->locale->act['user_charlist']['year'] : $roster->locale->act['user_charlist']['years']),$difference);
								}

							}
						}
					}
				}
			}
		}
		else
		{
			return '&nbsp;';
		}
	}

	/**
	 * Controls Output of the Tradeskill Icons Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function tradeskill_icons ( $row )
	{
		global $roster;

		//$addon = getaddon('memberslist');

		$cell_value ='';

		// Don't proceed for characters without data
		if ($row['clientLocale'] == '')
		{
			return '<div>&nbsp;</div>';
		}

		$lang = $row['clientLocale'];

		$profs = explode(',',$row['professions']);
		foreach ( $profs as $prof )
		{
			$r_prof = explode('|',$prof);
			$toolTip = (isset($r_prof[1]) ? str_replace(':','/',$r_prof[1]) : '');
			$toolTiph = $r_prof[0];

			if( $r_prof[0] == $roster->locale->wordings[$lang]['riding'] )
			{
				if( $row['class']==$roster->locale->wordings[$lang]['Paladin'] || $row['class']==$roster->locale->wordings[$lang]['Warlock'] )
				{
					$icon = $roster->locale->wordings[$lang]['ts_ridingIcon'][$row['class']];
				}
				else
				{
					$icon = $roster->locale->wordings[$lang]['ts_ridingIcon'][$row['race']];
				}
			}
			else
			{
				$icon = isset($roster->locale->wordings[$lang]['ts_iconArray'][$r_prof[0]])?$roster->locale->wordings[$lang]['ts_iconArray'][$r_prof[0]]:'';
			}

			// Don't add professions we don't have an icon for. This keeps other skills out.
			if ($icon != '')
			{
				$icon = '<img class="membersRowimg" width="20" height="20" src="'.$roster->config['interface_url'].'Interface/Icons/'.$icon.'.'.$roster->config['img_suffix'].'" alt="" '.makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP').' />';

				if( active_addon('info') )
				{
					$cell_value .= '<a href="' . makelink('char-info-recipes&amp;a=c:' . $row['member_id'] . '#' . strtolower(str_replace(' ','',$r_prof[0]))) . '">' . $icon . '</a>';
				}
				else
				{
					$cell_value .= $icon;
				}
			}
		}
		return $cell_value;
	}

	/**
	 * Controls Output of a Note Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function note_value ( $row, $field )
	{
		global $roster;

		//$addon = getaddon('memberslist');

		if( !empty($row[$field]) )
		{
			$note = htmlspecialchars(nl2br($row[$field]));

			if( $this->addon['config']['compress_note'] )
			{
				$note = '<img src="'.$roster->config['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$roster->locale->act['note'],'',1,'',',WRAP').' alt="" />';
			}
			else
			{
				$value = $note;
			}
		}
		else
		{
			$note = '&nbsp;';
			if( $this->addon['config']['compress_note'] )
			{
				$note = '<img src="'.$roster->config['img_url'].'no_note.gif" alt="No Note" />';
			}
			else
			{
				$value = $note;
			}
		}

		return '<div style="display:none; ">'.htmlentities($row[$field]).'</div><center>' . $note . '</center>';
	}

	/**
	 * Controls Output of the Guild Name Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function guild_value ( $row, $field )
	{
		global $roster;

		if( $row['guild_id'] )
		{
			return '<div style="display:none; ">' . $row['guild_name'] . '</div><center><a href="' . makelink('guild-memberslist&amp;a=g:' . $row['guild_id']) . '">' . $row['guild_name'] . '</a></center>';
		}
		else
		{
			return '<div style="display:none; ">' . $row['guild_name'] . '</div><center>' . $row['guild_name'] . '</center>';
		}
	}

	/**
	 * Controls Output of the Realm Name Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function realm_value ( $row, $field )
	{
		global $roster;

		return '<div style="display:none; ">' . $row['server_name'] . '</div><center>' . $row['server_name'] . '</center>';
	}

	/**
	 * Controls Output of the Region Name Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function region_value ( $row, $field )
	{
		global $roster;

		return '<div style="display:none; ">' . $row['server_region'] . '</div><center>' . $row['server_region'] . '</center>';
	}

	/**
	 * Controls Output of the Region Name Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function servertype_value ( $row, $field )
	{
		global $roster;

		switch ( $row['servertype'] )
		{
			case "RPPVP":
				$text = $roster->locale->act['user_rs']['rppvp'];
				$textcolor = $roster->config['rs_color_rppvp'];
				break;
			case "PVE":
				$text = $roster->locale->act['user_rs']['pve'];
				$textcolor = $roster->config['rs_color_pve'];
				break;
			case "PVP":
				$text = $roster->locale->act['user_rs']['pvp'];
				$textcolor = $roster->config['rs_color_pvp'];
				break;
			case "RP":
				$text = $roster->locale->act['user_rs']['rp'];
				$textcolor = $roster->config['rs_color_rp'];
				break;
			default:
				$text = $roster->locale->act['user_rs']['error'];
				$textcolor = $roster->config['rs_color_unknown'];
		}
		return '<div style="display:none; ">' . $row['servertype'] . '</div><center><font color="' . $textcolor . '">' . $text . '</font></center>';
	}

	/**
	 * Controls Output of the Region Name Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function serverstatus_value ( $row, $field )
	{
		global $roster;

		switch( $row['serverstatus'] )
		{
			case 'UP':
				$icon = '<img src="' . $roster->config['img_url'] . 'uparrow.gif" alt="' . $roster->locale->act['user_rs']['up'] . '"/> ';
				break;
			case 'DOWN':
				$icon = '<img src="' . $roster->config['img_url'] . 'downarrow2.gif" alt="' . $roster->locale->act['user_rs']['down'] . '"/> ';
				break;
			case 'MAINTENANCE':
				$icon = '<img src="' . $roster->config['img_url'] . 'downarrow2.gif" alt="' . $roster->locale->act['user_rs']['maintenance'] . '"/> ';
				break;
			default:
				$icon = '<img src="' . $roster->config['img_url'] . 'blue-question-mark.gif" alt="' . $roster->locale->act['user_rs']['error'] . '"/> ';
				break;
		}

		$cell_value = '<div>' . $icon . '</div>' . $row['serverstatus'];
		
		return '<div style="display:none; ">' . $row['serverstatus'] . '</div><center>' . $cell_value . '</center>';
	}

	/**
	 * Controls Output of the Region Name Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function serverpop_value ( $row, $field )
	{
		global $roster;

		switch ( $row['serverpop'] )
		{
			case "LOW":
				$text = $roster->locale->act['user_rs']['low'];
				$textcolor = $roster->config['rs_color_low'];
				break;
			case "MEDIUM":
				$text = $roster->locale->act['user_rs']['medium'];
				$textcolor = $roster->config['rs_color_medium'];
				break;
			case "HIGH":
				$text = $roster->locale->act['user_rs']['high'];
				$textcolor = $roster->config['rs_color_high'];
				break;
			case "MAX":
				$text = $roster->locale->act['user_rs']['max'];
				$textcolor = $roster->config['rs_color_max'];
				break;
			case "OFFLINE":
				$text = $roster->locale->act['user_rs']['offline'];
				$textcolor = $roster->config['rs_color_offline'];
				break;
			default:
				$text = $roster->locale->act['user_rs']['error'];
				$textcolor = $roster->config['rs_color_error'];
		}
		return '<div style="display:none; ">' . $row['serverpop'] . '</div><center><font color="' . $textcolor . '">' . $text . '</font></center>';
	}


	/**
	 * Controls Output of the Faction Column
	 *
	 * @param array $row
	 * @return string - Formatted output
	 */
	function faction_value ( $row, $field )
	{
		global $roster;

		//$addon = getaddon('memberslist');
	
		if ( $row['factionEn'] )
		{
			$faction = ( isset($row['factionEn']) ? $row['factionEn'] : '' );

			switch( substr($faction,0,1) )
			{
				case 'A':
					$icon = '<img src="' . $roster->config['img_url'] . 'icon_alliance.png" alt="" width="20" height="20"/> ';
					break;
				case 'H':
					$icon = '<img src="' . $roster->config['img_url'] . 'icon_horde.png" alt="" width="20" height="20"/> ';
					break;
				default:
					$icon = '<img src="' . $roster->config['img_url'] . 'icon_neutral.png" alt="" width="20" height="20"/> ';
					break;
			}
		}
		else
		{
			$icon = '';
		}

		$cell_value = $icon . $row['faction'];

		return '<div style="display:none; ">' . $row['faction'] . '</div><center>' . $cell_value . '</center>';
	}
}
