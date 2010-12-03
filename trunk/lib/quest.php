<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Quest class and functions
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Quest
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Quest class and functions
 *
 * @package    WoWRoster
 * @subpackage Quest
 */
class quest
{
	var $data;

	function quest( $data )
	{
		$this->data = $data;
	}

	function get( $field )
	{
		return $this->data[$field];
	}
}

function quest_get_one( $quest_name )
{
	global $roster;

	$query = "SELECT * FROM `" . $roster->db->table('quest_data') . "` WHERE `quest_name` LIKE '" . $roster->db->escape($quest_name) . "';";
	$result = $roster->db->query($query);
	$data = $roster->db->fetch($result,SQL_ASSOC);

	return new quest($data);
}

function quest_get_one_id( $quest_id, $locale='enUS' )
{
	global $roster;

	if( $locale != '' )
	{
		$locale = " AND `locale` = " . $roster->db->escape($locale);
	}

	$query = "SELECT * FROM `" . $roster->db->table('quest_data') . "` WHERE `quest_id` LIKE '$quest_id'$locale;";
	$result = $roster->db->query($query);
	$data = $roster->db->fetch($result,SQL_ASSOC);

	return new quest($data);
}

function quest_get_many( $member_id, $search='' )
{
	global $roster;

	$query  = "SELECT `quest_data`.*, `quest`.*"
			. " FROM `" . $roster->db->table('quests') . "` AS quest"
			. " LEFT JOIN `" . $roster->db->table('quest_data') . "` AS quest_data"
				. " ON `quest`.`quest_id` = `quest_data`.`quest_id`"
			. " WHERE `quest`.`member_id` = " . $member_id
			. " ORDER BY `quest`.`quest_index` ASC;";

	$result = $roster->db->query($query);

	$quests = array();
	while( $data = $roster->db->fetch($result,SQL_ASSOC) )
	{
		$quest = new quest($data);
		$quests[] = $quest;
	}
	return $quests;
}
