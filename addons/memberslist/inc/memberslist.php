<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage MemberList Class
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * MemberList generation class
 *
 * @package    MembersList
 * @subpackage MemberList Class
 */
class memberslist
{
	var $listname = 'membersData';	// table ID for javascript
	var $fields;					// field definitions
	var $query;						// main query
	var $wheres;					// where clauses
	var $orders;					// order clauses

	var $addon;						// SortMembers addon data

	/**
	 * Constructor
	 *
	 * @param array $options
	 *		Override any of the default options, like client/server sorting, alt
	 *		grouping, or pagination.
	 * @param array $addon
	 *		If the MembersList addon array has already been loaded, pass it here
	 *		for efficiency. if the addon array is not available, omit it, and it
	 *		will be loaded automatically.
	 */
	function memberslist( $options = array(), $addon = array() )
	{
		global $roster;

		$basename = basename(dirname(dirname(__FILE__)));

		// --[ Get addon array only if not passed ]--
		if( !empty($addon) )
		{
			$this->addon = $addon;
		}
		else
		{
			// Get our addon name using our file loc.
			$this->addon = getaddon($basename);
		}

		// Select the template to use, so other addons can make their own memberslist templates
		if( isset($this->addon['config']['template']) )
		{
			$roster->tpl->set_handle('memberslist', $this->addon['config']['template']);
		}
		else
		{
			$roster->tpl->set_handle('memberslist', $basename . '/memberslist.html');
		}

		// Set the js in the roster header
		$roster->output['html_head'] .= '<script type="text/javascript" src="' . ROSTER_PATH . 'addons/' . $basename . '/js/alts.js"></script>';

		// Merge in the override options from the calling file
		if( !empty($options) )
		{
			$this->addon['config'] = array_merge($this->addon['config'], $options);
		}

		// Overwrite some options if they're specified by the client
		if( isset($_GET['alts']) )
		{
			$this->addon['config']['group_alts'] = ($_GET['alts'] == 'open') ? 2 : (($_GET['alts'] == 'close') ? 1 : 0);
		}
	}

	/**
	 * Prepare the data to build a memberlist.
	 *
	 * @param string $query
	 *	The main SQL query for this list
	 * @param array $where
	 *  Default WHERE clauses. These are ANDed together
	 * @param array $group
	 *  GROUP BY columns. Should be the ID column of the listed scope (member_id on the default memberslist)
	 * @param array order_first
	 *  Initial order clauses, before any order clauses supplied by the UI
	 * @param array order_last
	 *  Trailing order clauses, after any order clauses supplied by the UI
	 * @param array $fields
	 *	Array with field data. See documentation.
	 * @param string $listname
	 *	The ID used by javascript to identify this memberstable.
	 */
	function prepareData( $query, $where, $group, $order_first, $order_last, $fields, $listname )
	{
		global $roster;

		// Save some info
		$this->listname = $listname;
		$this->fields = $fields;
		unset($fields);
		$cols = count($this->fields);

		// Pre-store server get params
		$get = ( isset($_GET['s']) ? '&amp;s=' . $_GET['s'] : '' ) . ( isset($_GET['st']) ? '&amp;st=' . $_GET['s'] : '' );
		$filter_post = $get . ( isset($_GET['alts']) ? '&amp;alts=' . $_GET['alts'] : '' );

		$get_s = ( isset($_GET['s']) ? $_GET['s'] : '' );
		$get_st = ( isset($_GET['st']) ? $_GET['st'] : 0 );

		// Extract filters form $_GET
		$get_filter = array();
		foreach( $this->fields as $name => $data )
		{
			if( isset( $_GET['filter_' . $name] ) && !empty( $_GET['filter_' . $name] ) )
			{
				$get_filter[$name] = $_GET['filter_' . $name];
				$get .= '&amp;filter_' . $name . '=' . htmlentities($get_filter[$name]);
			}
		}

		$roster->tpl->assign_vars(array(
			'U_UNGROUP_ALTS' => makelink('&amp;alts=ungroup' . $get),
			'U_OPEN_ALTS' => makelink('&amp;alts=open' . $get),
			'U_CLOSE_ALTS' => makelink('&amp;alts=close' . $get),
			'U_FILTER_FORM' => makelink($filter_post),

			'S_FILTER' => false,
			'S_HIDE_FILTER' => (bool)!$this->addon['config']['openfilter'],
			'S_GROUP_ALTS' => $this->addon['config']['group_alts'],

			'B_PAGINATION' => false,

			'COLS' => $cols+1,
			'LISTNAME' => $this->listname,
			)
		);

		// --[ Add filter SQL ]--
		foreach( $get_filter as $field => $filter )
		{
			$data = $this->fields[$field];

			if( isset( $data['filter'] ) )
			{
				$where_clause = call_user_func($data['filter'], $field, $filter, (isset($DATA['passthrough']) ? $DATA['passthrough'] : array() ) );
			}
			else
			{
				$where_clause = $this->default_filter( $field, $filter );
			}

			if( !empty( $where_clause ) )
			{
				$where[] = $where_clause;
			}
		}
		if( !empty( $where ) )
		{
			$query .= ' WHERE (' . implode( ') AND (', $where ) . ')';
		}

		// --[ Add grouping SQL ]--
		if( !empty( $group ) )
		{
			$query .= ' GROUP BY ' . implode( ',', $group );
		}

		// --[ Get number of rows ]--
		if( $this->addon['config']['page_size'] )
		{
			// --[ Fetch number of rows. Trim down the query a bit for speed. ]--
			$rowsqry = 'SELECT COUNT(*) ' . substr($query, strpos($query,'FROM'));
			$num_rows = $roster->db->query_first($rowsqry);
		}

		// --[ Add sorting SQL ]--
		$order = $order_first;
		if( empty($get_s) && !empty($this->addon['config']['def_sort']) )
		{
		   $get_s = $this->addon['config']['def_sort'];
		}

		foreach( explode( ',', $get_s ) as $sort )
		{
			if( empty($sort) )
			{
				continue;
			}

			if( strpos( $sort, ':' ) )
			{
				list($field, $dir) = explode( ':', $sort );
			}
			else
			{
				$field = $sort; $dir = 'a';
			}

			$ORDER_FIELD = $this->fields[$field];
			if( $dir == 'd' && isset( $ORDER_FIELD['order_d'] ) )
			{
				foreach ( $ORDER_FIELD['order_d'] as $order_field_sql )
				{
					$order[] = $order_field_sql;
				}
			}
			elseif( isset( $ORDER_FIELD['order']) )
			{
				foreach ( $ORDER_FIELD['order'] as $order_field_sql )
				{
					$order[] = $order_field_sql;
				}
			}
		}

		foreach( $order_last as $order_sql )
		{
				$order[] = $order_sql;
		}

		if( !empty( $order ) )
		{
			$query .= ' ORDER BY ' . implode( ',', $order );
		}

		// --[ Add pagination SQL ]--
		if( $get_st )
		{
			$query .= ' LIMIT ' . $get_st . ',' . $this->addon['config']['page_size'];
		}

		// --[ Query done, add to class vars ]--
		$this->query = $query;

		// --[ Page list ]--
		if( $this->addon['config']['page_size']
			&& (1 < ($num_pages = ceil($num_rows/$this->addon['config']['page_size'])))
		)
		{
			$params = '&amp;alts=' . ($this->addon['config']['group_alts']==2 ? 'open' : ($this->addon['config']['group_alts']==1 ? 'close' : 'ungroup'));

			paginate($params . '&amp;st=', $num_rows, $this->addon['config']['page_size'], $get_st);
		}

		// header row
		$current_col = 1;
		foreach ( $this->fields as $field => $DATA )
		{
			// If this is a force invisible field, don't do anything with it.
			if( $DATA['display'] == 0 || $DATA['display'] == 1 )
			{
				unset($this->fields[$field]);
				continue;
			}

			// See if there is a lang value for the header
			if( !empty($roster->locale->act[$DATA['lang_field']]) )
			{
				$th_text = $roster->locale->act[$DATA['lang_field']];
			}
			else
			{
				$th_text = $DATA['lang_field'];
			}

			// click a sorted field again to reverse sort it
			// Don't add it if it is detected already
			if( !empty( $get_s ) )
			{
				$sorts = explode( ',', $get_s );
			}
			else
			{
				$sorts = array();
			}

			if( false !== ($key = array_search( $field, $sorts )) || 
				false !== ($key = array_search( $field . ':a', $sorts)) )
			{
				unset( $sorts[$key] );
				array_unshift( $sorts, $field . ':d' );
			}
			else
			{
				$key = array_search( $field . ':d', $sorts );
				if( $key !== false )
				{
					unset( $sorts[$key] );
				}
				array_unshift( $sorts, $field . ':a' );
			}
			$newsort = implode( ',', $sorts );
			
			$get = '&amp;alts=' . ($this->addon['config']['group_alts']==2 ? 'open' : (($this->addon['config']['group_alts']==1) ? 'close' : 'ungroup')) . '&amp;s=' . $newsort;
			foreach( $get_filter as $key => $filter )
			{
				$get .= '&amp;filter_' . $key . '=' . htmlentities($filter);
			}

			$roster->tpl->assign_block_vars('header_cell',array(
				'LINK' => makelink($get),
				'TEXT' => $th_text,
				'ID' => false,
				'FILTER' => ( isset($get_filter[$field]) ? htmlentities($get_filter[$field]) : '' ),
				'NAME' => $field,
				)
			);

			$current_col++;
		}
		// end header row
	}

	/**
	 * Returns the actual list.
	 */
	function makeMembersList( $border=false )
	{
		global $roster;

		$roster->tpl->assign_vars(array(
			'S_ML_BORDER' => $border,
			)
		);

		$cols = count($this->fields);

		$result = $roster->db->query($this->query);

		if ( !$result )
		{
			die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$this->query);
		}

		// --[ Cache arrays for main/alt ordering ]--
		$lines = $line = array();
		$lookup = array();

		// --[ Actual list ]--
		while ( $row = $roster->db->fetch( $result ) )
		{
			$line = array();
			$current_col = 1;

			// Echoing cells w/ data
			foreach ( $this->fields as $field => $DATA )
			{
				if ( isset( $DATA['value'] ) )
				{
					$cell_value = call_user_func($DATA['value'], $row, $field, (isset($DATA['passthrough']) ? $DATA['passthrough'] : array()) );
				}
				else
				{
					$cell_value = ($row[$field] != '') ? $row[$field] : '&nbsp;';
				}


				$line[] = array(
					'cell_value' => $cell_value,
				);
				$current_col++;
			}

			// Cache lines for main/alt stuff
			if( $this->addon['config']['group_alts'] < 0 )
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
				$lookup[] = $row['member_id'];
				$lines[$row['member_id']]['amain'] = $line;
				$lines[$row['main_id']]['alts'][] = $line;
			}
		}

		// Main/Alt block
		$new_lookup = array_diff(array_keys($lines),$lookup);
		foreach($new_lookup as $member_id)
		{
			$lookup[] = $member_id;
		}
		$lookup_count = count($lookup);
		for($i=0; $i<$lookup_count; $i++)
		{
			$member_id = $lookup[$i];
			$block = $lines[$member_id];

			// Main without alts, or ungrouped
			if( $this->addon['config']['group_alts'] < 0 || !isset($block['alts']) || 0 == count($block['alts']) )
			{
				if( isset( $block['main'] ) )
				{
					$this->assignRow( 'members_row', $member_id, 'main altless', $block['main'] );
				}
				else
				{
					$this->assignRow( 'members_row', $member_id, 'amain', $block['amain'] );
				}
			}
			// Mainless alt.
			else if( !isset($block['main']) )
			{
				foreach( $block['alts'] as $id => $alt )
				{
					$this->assignRow( 'members_row', $member_id . '-' . $id, 'alt_mainless', $alt );
				}
			}
			// Main with alts
			else
			{
				$this->assignRow( 'members_row', $member_id, 'main', $block['main'] );

				foreach( $block['alts'] as $alt )
				{
					$this->assignRow( 'members_row.alt', $member_id, 'alt', $alt );
				}
			}
		}

		return $roster->tpl->fetch('memberslist');
	}

	/**
	 * Assigns a data row to $roster->tpl
	 */
	function assignRow( $class, $member_id, $type, $cells )
	{
		global $roster;

		$roster->tpl->assign_block_vars($class,array(
			'MEMBER_ID' => $member_id,
			'ROW_CLASS' => ( ( 0 !== strpos( $type, 'alt') )           ?
		       				$roster->switch_row_class()        :
						$roster->switch_alt_row_class() ),
			'CLASS'     => $type
		));
		foreach( $cells as $cell )
		{
			$roster->tpl->assign_block_vars($class . '.cell',array(
				'VALUE'   => $cell['cell_value']
			));
		}
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
				return '<img src="' . ROSTER_URL . 'motd.php?id=' . $roster->data['guild_id'] . '" alt="Guild MOTD: ' . htmlspecialchars($roster->data['guild_motd']) . '" /><br /><br />';
			}
			else
			{
				return '<table class="border_frame" cellpadding="0" cellspacing="1" ><tr><td class="border_colour sgoldborder motd_setup">' . htmlspecialchars($roster->data['guild_motd']) . '</td></tr></table><br /><br />';
			}
		}
	}

	function default_filter( $field, $filter )
	{
		if( !strncmp( $filter, '=', 1 ) )
		{
			return $field . '="' . substr( $filter, 1 ) . '"';
		}
		else if( !strncmp( $filter, '!=', 2 ) )
		{
			return $field . '!="' . substr( $filter, 2 ) . '"';
		}
		else if( !strncmp( $filter, '<=', 2 ) )
		{
			return $field . '<="' . substr( $filter, 2 ) . '"';
		}
		else if( !strncmp( $filter, '>=', 2 ) )
		{
			return $field . '>="' . substr( $filter, 2 ) . '"';
		}
		else if( !strncmp( $filter, '>', 1 ) )
		{
			return $field . '>"' . substr( $filter, 1 ) . '"';
		}
		else if( !strncmp( $filter, '<', 1 ) )
		{
			return $field . '<"' . substr( $filter, 1 ) . '"';
		}
		else
		{
			return $field . ' LIKE "%' . $filter . '%"';
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

		$tooltip = '';
		if( $this->addon['config']['member_tooltip'] )
		{
			$tooltip_h = $row['name'] . ' : ' . $row['guild_title'];

			$tooltip = 'Level ' . $row['level'] . ' ' . $row['sex'] . ' ' . $row['race'] . ' ' . $row['class'] . "\n";

			$tooltip .= $roster->locale->act['lastonline'] . ': ' . $row['last_online'] . ' in ' . $row['zone'];
			$tooltip .= ( $this->addon['config']['member_note'] == 0 || $row['nisnull'] ? '' : "\n" . $roster->locale->act['note'] . ': ' . $row['note'] );

			$tooltip = ' style="cursor:help;" ' . makeOverlib($tooltip,$tooltip_h,'',1,'',',WRAP');
		}

		if( active_addon('info') && $row['server'] )
		{
			return '<div style="display:none;">' . $row['name'] . '</div><div' . $tooltip . '><a href="' . makelink('char-info&amp;a=c:' . $row['member_id']) . '">' . $row['name'] . '</a></div>';
		}
		else
		{
			return '<div style="display:none;">' . $row['name'] . '</div><div' . $tooltip . '>' . $row['name'] . '</div>';
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

				$icon_value .= '<img class="membersRowimg" width="' . $this->addon['config']['icon_size'] . '" height="' . $this->addon['config']['icon_size'] . '" src="' . $roster->config['img_url'] . 'class/' . $icon_name . '.jpg" alt="" />';
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
					$specicon = '<img class="membersRowimg" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '" src="' . $roster->config['img_url'] . 'spec/' . $specicon . '.' . $roster->config['img_suffix'] . '" alt="" ' . makeOverlib($specline,$spec,'',1,'',',RIGHT,WRAP') . ' />';
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
					$class_color = ( isset($roster->locale->wordings[$language]['class_to_en'][$row['class']]) ? $roster->locale->wordings[$language]['class_to_en'][$row['class']] : '' );

					if( strlen($class_color) > 0 )
					{
						$class_color = $roster->locale->wordings['enUS'][$class_color];
						break;
					}
				}

				if( $class_color != '' )
				{
					return '<div style="display:none;">' . $row['class'] . '</div>' . $icon_value . '<span class="class' . $class_color . 'txt">' . $fieldtext . '</span>';
				}
				else
				{
					return '<div style="display:none;">' . $row['class'] . '</div>' . $icon_value . '<span class="class' . $row['class'] . 'txt">' . $fieldtext . '</span>';
				}
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
				$rested = ' : ' . $rested;
			}
			$togo = sprintf($roster->locale->act['xp_to_go'], $max - $current, ($row['level']+1));

			$percent_exp = ($max > 0 ? round(($current/$max)*100) : 0);

			$tooltip = '<div style="white-space:nowrap;" class="levelbarParent" style="width:200px;"><div class="levelbarChild">XP ' . $current . '/' . $max . $rested . '</div></div>';
			$tooltip .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="200">';
			$tooltip .= '<tr>';
			$tooltip .= '<td style="background-image: url(\'' . $roster->config['img_url'] . 'expbar-var2.gif\');" width="' . $percent_exp . '%"><img src="' . $roster->config['img_url'] . 'pixel.gif" height="14" width="1" alt="" /></td>';
			$tooltip .= '<td width="' . (100 - $percent_exp) . '%"></td>';
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

			$cell_value = '<div ' . $tooltip . ' style="cursor:default;"><div class="levelbarParent" style="width:70px;"><div class="levelbarChild">' . $row['level'] . '</div></div>';
			$cell_value .= '<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="70">';
			$cell_value .= '<tr>';
			$cell_value .= '<td style="background-image: url(\'' . $roster->config['img_url'] . 'expbar-var2.gif\');" width="' . $percentage . '%"><img src="' . $roster->config['img_url'] . 'pixel.gif" height="14" width="1" alt="" /></td>';
			$cell_value .= '<td width="' . (100 - $percentage) . '%"></td>';
			$cell_value .= "</tr>\n</table>\n</div>\n";
		}
		else
		{
			$cell_value = '<div ' . $tooltip . ' style="cursor:default;">' . $row['level'] . '</div>';
		}

		return '<div style="display:none;">' . str_pad($row['level'],2,'0',STR_PAD_LEFT) . '</div>' . $cell_value;
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
					$rankicon = 'Interface/PvPRankBadges/pvprank0' . $row['lifetimeHighestRank'] . '.' . $roster->config['alt_img_suffix'];
				}
				else
				{
					$rankicon = 'Interface/PvPRankBadges/pvprank' . $row['lifetimeHighestRank'] . '.' . $roster->config['alt_img_suffix'];
				}
				$rankicon = $roster->config['interface_url'] . $rankicon;
				$rankicon = "<img class=\"membersRowimg\" width=\"" . $this->addon['config']['icon_size'] . "\" height=\"" . $this->addon['config']['icon_size'] . "\" src=\"" . $rankicon . "\" alt=\"\" />";
			}
			else
			{
				$rankicon = '';
			}

			$cell_value = $rankicon . ' ' . $row['lifetimeRankName'];

			return '<div style="display:none;">' . $row['lifetimeHighestRank'] . '</div>' . $cell_value;
		}
		else
		{
			return '&nbsp;';
		}
	}

	/**
	 * Controls Output of the Guild Name Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function guild_name_value ( $row, $field )
	{
		return '<div style="display:none;">' . $row['guild_name'] . '</div><a href="' . makelink('guild-memberslist&amp;a=g:' . $row['guild_id']) . '">' . $row['guild_name'] . '</a></div>';
	}

	/**
	 * Controls Output of the Talent Spec Column
	 *
	 * @param array $row - of character data
	 * @return string - Formatted output
	 */
	function spec_icon( $row )
	{
		global $roster, $addon;

		$cell_value ='';

		// Don't proceed for characters without data
		if( !isset($row['talents']) || $row['talents'] == '' )
		{
			return '<img class="membersRowimg" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '" src="' . $roster->config['img_url'] . 'pixel.gif" alt="" />';
		}

		$lang = $row['clientLocale'];

		$talents = explode(',',$row['talents']);

		$spec = $specicon = '';
		$tooltip = array();
		$specpoint = 0;
		foreach( $talents as $talent )
		{
			list($name, $points, $icon) = explode('|',$talent);
			$tooltip[] = $points;
			if( $points > $specpoint )
			{
				$specpoint = $points;
				$spec = $name;
				$specicon = $icon;
			}
		}
		$tooltip = implode(' / ', $tooltip);

		$specicon = '<img class="membersRowimg" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '" src="' . $roster->config['img_url'] . 'spec/' . $specicon . '.' . $roster->config['img_suffix'] . '" alt="" ' . makeOverlib($tooltip,$spec,'',1,'',',RIGHT,WRAP') . ' />';

		if( active_addon('info') )
		{
			$cell_value .= '<a href="' . makelink('char-info-talents&amp;a=c:' . $row['member_id']) . '">' . $specicon . '</a>';
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
				return $realtime . $roster->locale->act['online_at_update'];
			}

			if( $difference < 60 )
			{
				return $realtime . sprintf(($difference == '1' ? $roster->locale->act['second'] : $roster->locale->act['seconds']),$difference);
			}
			else
			{
				$difference = round($difference / 60);
				if( $difference < 60 )
				{
					return $realtime . sprintf(($difference == '1' ? $roster->locale->act['minute'] : $roster->locale->act['minutes']),$difference);
				}
				else
				{
					$difference = round($difference / 60);
					if( $difference < 24 )
					{
						return $realtime . sprintf(($difference == '1' ? $roster->locale->act['hour'] : $roster->locale->act['hours']),$difference);
					}
					else
					{
						$difference = round($difference / 24);
						if( $difference < 7 )
						{
							return $realtime . sprintf(($difference == '1' ? $roster->locale->act['day'] : $roster->locale->act['days']),$difference);
						}
						else
						{
							$difference = round($difference / 7);
							if( $difference < 4 )
							{
								return $realtime . sprintf(($difference == '1' ? $roster->locale->act['week'] : $roster->locale->act['weeks']),$difference);
							}
							else
							{
								$difference = round($difference / 4);
								if( $difference < 12 )
								{
									return $realtime . sprintf(($difference == '1' ? $roster->locale->act['month'] : $roster->locale->act['months']),$difference);
								}
								else
								{
									$difference = round($difference / 12);
									return $realtime . sprintf(($difference == '1' ? $roster->locale->act['year'] : $roster->locale->act['years']),$difference);
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
}

