<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
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
	var $open_table;
	var $close_table;
	var $search_url;
	var $data = array();    // Addon data

	var $zone;
	var $levelid;

	// class constructor
	function questlistSearch()
	{
		global $roster;

		$this->zone = ( isset($_POST['zone']) ? ($_POST['zone']) : ( isset($_GET['zone']) ? ($_GET['zone']) : '' ) );
		$this->levelid = ( isset($_POST['levelid']) ? $_POST['levelid'] : ( isset($_GET['levelid']) ? $_GET['levelid'] : '' ) );

		// Set up next/prev search link
		$this->search_url  = ( $this->zone != '' ? '&amp;zone=' . htmlentities($this->zone) : '' );
		$this->search_url .= ( $this->levelid != '' ? '&amp;levelid=' . $this->levelid : '' );

		$this->open_table = '<tr><th class="membersHeader ts_string">' . $roster->locale->act['level'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['tag'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['zone'] . '</th></tr>';

		$quests[0] = 'All';
		$level_list = $roster->db->query("SELECT DISTINCT `quest_level` FROM `" . $roster->db->table('quest_data') . "` ORDER BY `quest_level` DESC;");
		$zone_list = $roster->db->query("SELECT DISTINCT `zone` FROM `" . $roster->db->table('quest_data') . "` ORDER BY `zone`;");

		//advanced options for searching zones
		$this->options = $roster->locale->act['zone'] . ' <select name="zone"> ';
		$this->options .= '<option value="">----------</option>';
		while( list($zone) = $roster->db->fetch($zone_list) )
		{
			$quests[$zone] = $zone;
			$this->options .= '<option value="' . htmlentities($zone) . '"' . ( $zone == stripslashes($this->zone) ? ' selected="selected"' : '' ) . '>' . $zone . "</option>\n";
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

	function search( $search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$search_zone = ($this->zone == '') ? '' : "`qd`.`zone` = '" . $this->zone . "' AND";
		$search_level = ($this->levelid == '') ? '' : "`qd`.`quest_level` = '" . $this->levelid . "' AND";

		$sql = "SELECT `qd`.`quest_name`, `qd`.`quest_level`, `qd`.`quest_tag`, `qd`.`zone`, `qd`.`group`, `p`.`region`, `p`.`server`"
		     . " FROM `" . $roster->db->table('quest_data') . "` AS qd"
		     . " LEFT JOIN `" . $roster->db->table('quests') . "` AS q USING (`quest_id`)"
		     . " LEFT JOIN `" . $roster->db->table('players') . "` AS p USING (`member_id`)"
		     . " WHERE $search_zone $search_level `qd`.`quest_name` LIKE '%$search%'"
		     . " GROUP BY `qd`.`quest_name`"
			 . ( $limit > 0 ? " LIMIT $first," . $limit : '' ) . ';';

		//calculating the search time
		$this->start_search = format_microtime();

		$result = $roster->db->query($sql);

		$this->stop_search = format_microtime();
		$this->time_search = $this->stop_search - $this->start_search;

		$nrows = $roster->db->num_rows($result);

		$x = ($limit > $nrows) ? $nrows : ($limit > 0 ? $limit : $nrows);
		if( $nrows > 0 )
		{
			while( $x > 0 )
			{
				list($quest_name, $quest_level, $quest_tag, $zone, $group, $region, $server) = $roster->db->fetch($result);

				if( $group )
				{
					$quest_tag .= ", $group";
				}

				$item['html'] = '<td class="SearchRowCell">' . $quest_level.'</td>'
							  . '<td class="SearchRowCell"><a href="' . makelink('realm-questlist&amp;a=r:' . $region . '-' . urlencode($server) . '&amp;questid=' . urlencode($quest_name)) . '">' . $quest_name . '</a></td>'
							  . '<td class="SearchRowCell">' . $quest_tag . '</td>'
							  . '<td class="SearchRowCellRight">' . $zone . '</td>';

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}
		$roster->db->free_result($result);
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
