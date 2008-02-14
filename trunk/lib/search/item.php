<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    WoWRoster
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Item Search
 *
 * @package    WoWRoster
 * @subpackage Search
 */
class roster_itemSearch
{
	var $options;
	var $result = array();
	var $result_count = 0;
	var $start_search;
	var $stop_search;
	var $time_search;
	var $open_table;
	var $close_table;
	var $search_url;
	var $data = array();    // Addon data

	var $minlvl;
	var $maxlvl;
	var $quality;
	var $quality_sql;

	// class constructor
	function roster_itemSearch()
	{
		global $roster;

		require_once (ROSTER_LIB . 'item.php');

		$this->open_table = '<tr><th class="membersHeader ts_string">' . $roster->locale->act['item'] . '</th>'
						  . '<th class="membersHeader ts_string">Lv</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['character'] . '</th></tr>';

		$this->minlvl = isset($_POST['item_minle']) ? $_POST['item_minle'] : ( isset($_GET['item_minle']) ? $_GET['item_minle'] : '' );
		$this->maxlvl = isset($_POST['item_maxle']) ? $_POST['item_maxle'] : ( isset($_GET['item_maxle']) ? $_GET['item_maxle'] : '' );
		$this->quality = isset($_POST['item_quality']) ? $_POST['item_quality'] : ( isset($_GET['item_quality']) ? $_GET['item_quality'] : array() );

		// Set up next/prev search link
		$this->search_url  = ( $this->minlvl != '' ? '&amp;item_minle=' . $this->minlvl : '' );
		$this->search_url .= ( $this->maxlvl != '' ? '&amp;item_maxle=' . $this->maxlvl : '' );

		// Assemble sql for item quality
		if( count($this->quality) > 0 )
		{
			$i = 0;
			$this->quality_sql = array();
			foreach( $this->quality as $color )
			{
				$this->quality_sql[] = "`items`.`item_color` = '$color'";
				$this->search_url .= '&amp;item_quality[' . $i++ . ']=' . $color;
			}
			$this->quality_sql = ' AND (' . implode(' OR ',$this->quality_sql) . ')';
		}

		//advanced options for searching items
		$this->options = '
	<label for="item_minle">' . $roster->locale->act['level'] . ':</label>
	<input type="text" name="item_minle" id="item_minle" size="3" maxlength="3" value="' . $this->minlvl . '" /> -
	<input type="text" name="item_maxle" id="item_maxle" size="3" maxlength="3" value="' . $this->maxlvl . '" /><br />
	<label for="item_quality">Quality:</label><br />
	<select name="item_quality[]" id="item_quality" size="6" multiple="multiple">
		<option value="9d9d9d" style="color:#9d9d9d;"' . ( in_array('9d9d9d',$this->quality) ? ' selected="selected"' : '' ) . '>Poor</option>
		<option value="ffffff" style="color:#ffffff;"' . ( in_array('ffffff',$this->quality) ? ' selected="selected"' : '' ) . '>Common</option>
		<option value="1eff00" style="color:#1eff00;"' . ( in_array('1eff00',$this->quality) ? ' selected="selected"' : '' ) . '>Uncommon</option>
		<option value="0070dd" style="color:#0070dd;"' . ( in_array('0070dd',$this->quality) ? ' selected="selected"' : '' ) . '>Rare</option>
		<option value="a335ee" style="color:#a335ee;"' . ( in_array('a335ee',$this->quality) ? ' selected="selected"' : '' ) . '>Epic</option>
		<option value="ff8800" style="color:#ff8800;"' . ( in_array('ff8800',$this->quality) ? ' selected="selected"' : '' ) . '>Legendary</option>
	</select>';
	}

	function search( $search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$sql = "SELECT `players`.`name`, `players`.`member_id`, `players`.`server`, `players`.`region`, `items`.*"
			 . " FROM `" . $roster->db->table('items') . "` AS items,`" . $roster->db->table('players') . "` AS players"
			 . " WHERE `items`.`member_id` = `players`.`member_id`"
				. " AND (`items`.`item_name` LIKE '%$search%' OR `items`.`item_tooltip` LIKE '%$search%')"
				. ( $this->minlvl != '' ? " AND `items`.`level` >= '$this->minlvl'" : '' )
				. ( $this->maxlvl != '' ? " AND `items`.`level` <= '$this->maxlvl'" : '' )
				. $this->quality_sql
			 . " ORDER BY `items`.`item_name` ASC"
			 . ( $limit > 0 ? " LIMIT $first," . $limit : '' ) . ';';

		//calculating the search time
		$this->start_search = format_microtime();

		$result = $roster->db->query($sql);

		$this->stop_search = format_microtime();
		$this->time_search = $this->stop_search - $this->start_search;

		$nrows = $roster->db->num_rows($result);

		$x = ($limit > $nrows) ? $nrows : ($limit > 0 ? $limit : $nrows);
		if( $nrows > 0 && $limit > 0 )
		{
			while( $x > 0 )
			{
				$row = $roster->db->fetch($result);
				$icon = new item($row);

				$item['html'] = '<td class="SearchRowCell">' . $icon->out() . '</td>'
							  . '<td class="SearchRowCell">' . $icon->requires_level . '</td>'
							  . '<td class="SearchRowCell"><span style="color:#' . $icon->color . '">[' . $icon->name . ']</span></td>'
							  . '<td class="SearchRowCellRight"><a href="' . makelink('char-info&amp;a=c:' . $row['member_id']) . '"><strong>' . $row['name'] . '</strong></a></td>';

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}
		else
		{
			$this->result_count = $nrows;
		}
		$roster->db->free_result($result);
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
