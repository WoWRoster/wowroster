<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Members List Search
 *
 * @package    MembersList
 * @subpackage Search
 */
class memberslistSearch
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

	// class constructor
	function memberslistSearch()
	{
		global $roster;

		$this->open_table = '<tr><th class="membersHeader ts_string">' . $roster->locale->act['level'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['class'] . '</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['title'] . '</th></tr>';
	}

	function search( $search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page * $limit;

		$sql = "SELECT `member_id`, `name`, `server`, `region`, `guild_id`, `class`, `level`, `note`, `guild_rank`,`guild_title`, `zone`, `last_online`"
			 . " FROM `" . $roster->db->table('members') . "`"
			 . " WHERE (`member_id` LIKE '%$search%'"
				. " OR `name` LIKE '%" . ucfirst($search) . "%'"
				. " OR `name` LIKE '%$search%'"
				. " OR `server` LIKE '%$search%'"
				. " OR `region` LIKE '%$search%'"
				. " OR `guild_id` LIKE '%$search%'"
				. " OR `class` LIKE '%$search%'"
				. " OR `level` LIKE '%$search%'"
				. " OR `note` LIKE '%$search%'"
				. " OR `guild_rank` LIKE '%$search%'"
				. " OR `guild_title` LIKE '%$search%'"
				. " OR `zone` LIKE '%$search%')"
			 . " GROUP BY `member_id`"
			 . ( $limit > 0 ? " LIMIT $first," . $limit : '' ) . ';';

		// calculating the search time
		$this->start_search = format_microtime();

		$result = $roster->db->query($sql);

		$this->stop_search = format_microtime();
		$this->time_search = $this->stop_search - $this->start_search;

		$nrows = $roster->db->num_rows($result);
		$crows = 0;

		$x = ($limit > $nrows) ? $nrows : ($limit > 0 ? $limit : $nrows);
		if( $nrows > 0 )
		{
			while( $x > 0 )
			{
				list($member_id, $name, $server, $region, $guild_id, $class, $level, $note, $guild_rank, $guild_title, $zone, $last_online) = $roster->db->fetch($result);

				$item['html'] = '<td class="SearchRowCell">' . $level . '</td><td class="SearchRowCell">' . $class . '</td><td class="SearchRowCell"><a href="' . makelink("char-info&amp;a=c:$member_id") . '"><strong>' . $name . '</strong></a></td><td class="SearchRowCellRight">' . $guild_title . '</td>';
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
