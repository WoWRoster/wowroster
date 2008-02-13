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
 * @package    QuestList
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * QuestList Search
 *
 * @package    QuestList
 * @subpackage Search
 */
class questlistSearch
{
	var $options;
	var $result = array();
	var $result_count = 0;
	var $start_search;
	var $stop_search;
	var $time_search;
	var $link_next;
	var $link_prev;
	var $open_table;
	var $close_table;
	var $data = array();    // Addon data

	var $zone;
	var $levelid;
	var $search_url;

	// class constructor
	function questlistSearch()
	{
		global $roster;

		$this->zone = ( isset($_POST['zone']) ? $_POST['zone'] : ( isset($_GET['zone']) ? $_GET['zone'] : '' ) );
		$this->levelid = ( isset($_POST['levelid']) ? $_POST['levelid'] : ( isset($_GET['levelid']) ? $_GET['levelid'] : '' ) );

		// Set up next/prev search link
		$this->search_url  = ( $this->zone != '' ? '&amp;zone=' . urlencode($this->zone) : '' );
		$this->search_url .= ( $this->levelid != '' ? '&amp;levelid=' . $this->levelid : '' );

		$this->open_table = '<tr><th class="membersHeader ts_string">Lv</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['zone'] . '</th></tr>';

		$quests[0] = 'All';
		$level_list = $roster->db->query("SELECT DISTINCT `quest_level` FROM `" . $roster->db->table('quests') . "` ORDER BY `quest_level` DESC;");
		$zone_list = $roster->db->query("SELECT DISTINCT `zone` FROM `" . $roster->db->table('quests') . "` ORDER BY `zone`;");

		//advanced options for searching zones
		$this->options = $roster->locale->act['zone'] . ' <select name="zone"> ';
		$this->options .= '<option value="">----------</option>';
		while( list($zone) = $roster->db->fetch($zone_list) )
		{
			$quests[$zone] = $zone;
			$this->options .= '<option value="' . urlencode($zone) . '"' . ( $zone == stripslashes($this->zone) ? ' selected="selected"' : '' ) . '>' . $zone . "</option>\n";
		}
		$roster->db->free_result($zone_list);
		$this->options .= '</select>';


		//advanced options for searching levels
		$this->options .=  ' ' . $roster->locale->act['level'] . ' <select name="levelid"> ';
		$this->options .= '<option value="">-----</option>';
		while( list($quest_level) = $roster->db->fetch($level_list) )
		{
			$quests[$quest_level] = $quest_level;
			$this->options .= '<option value="' . $quest_level . '"' . ( $quest_level == $this->levelid ? ' selected="selected"' : '' ) . '>' . $quest_level . "</option>\n";
		}
		$roster->db->free_result($level_list);
		$this->options .= '</select>';
	}

	function search( $search , $url_search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$search_zone = ($this->zone == '') ? '' : "`q`.`zone` = '" . $this->zone . "' AND";
		$search_level = ($this->levelid == '') ? '' : "`q`.`quest_level` = '" . $this->levelid . "' AND";

		$sql = "SELECT `q`.`quest_name`, `q`.`quest_level`, `q`.`quest_tag`, `q`.`zone`, `p`.`region`, `p`.`server`"
		   . " FROM `" . $roster->db->table('quests') . "` AS q"
		   . " LEFT JOIN `" . $roster->db->table('players') . "` AS p USING (`member_id`)"
		   . " WHERE $search_zone $search_level `q`.`quest_name` LIKE '%$search%'"
		   . " GROUP BY `quest_name`"
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
				list($quest_name, $quest_level, $quest_tag, $zone, $region, $server) = $roster->db->fetch($result);

				$item['title'] = $quest_name;
				$item['image'] = 'inv_misc_note_02';
				$item['html'] = '<td class="SearchRowCell">' . $quest_level.'</td>'
							  . '<td class="SearchRowCell"><a href="' . makelink('realm-questlist&amp;a=r:' . $region . '-' . urlencode($server) . '&amp;questid=' . urlencode($quest_name)) . '"><strong>' . $quest_name . '</strong></a></td>'
							  . '<td class="SearchRowCellRight">' . $zone . '</td>';

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}
		else
		{
			while( $row = $roster->db->fetch($result) )
			{
				$this->result_count++;
			}
		}
		$roster->db->free_result($result);

		if( $page > 0 )
		{
			$this->link_prev = '<a href="' . makelink('search&amp;page=' . ($page-1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $this->data['basename'] . $this->search_url) . '"><strong>' . $roster->locale->act['search_previous_matches'] . $this->data['fullname'] . '</strong></a>';
		}
		if( $nrows > $limit )
		{
			$this->link_next = '<a href="' . makelink('search&amp;page=' . ($page+1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $this->data['basename'] . $this->search_url) . '"><strong> ' . $roster->locale->act['search_next_matches'] . $this->data['fullname'] . '</strong></a>';
		}
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
