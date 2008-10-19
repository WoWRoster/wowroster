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
 * @package    InstanceKeys
 * @subpackage UpdateHook
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * InstanceKeys Update Hook
 *
 * @package    InstanceKeys
 * @subpackage UpdateHook
 */
class keysUpdate
{
	// Update messages
	var $messages = '';

	// Addon data object, recieved in constructor
	var $data;

	// LUA upload files accepted. We don't use any.
	var $files = array();

	// Own locale strings, since I don't understand the hassle they go through in the usual channels
	var $wordings = array();
	/**
	 * Constructor
	 *
	 * @param array $data
	 *		Addon data object
	 */
	function keysUpdate($data)
	{
		global $roster;

		$this->data = $data;

		foreach( $roster->multilanguages as $locale )
		{
			$lang = array();
			$localefile = $this->data['locale_dir'] . $locale . '.php';
			if( file_exists($localefile) )
			{
				include($localefile);
			}
			else
			{
				$enUSfile = $this->data['locale_dir'] . 'enUS.php';
				if( file_exists($enUSfile) )
				{
					include($enUSfile);
				}
			}
			$this->wordings[$locale] =& $lang;
			unset($lang);
		}
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		/**
		 * We display the addon name at the beginning of the output line. If
		 * the hook doesn't exist on this side, nothing is output. If we don't
		 * produce any output (update method off) we empty this before returning.
		 */

		$this->messages = 'InstanceKeys';
	}

	/**
	 * Char trigger
	 *
	 * @param array $char
	 *		CP.lua character data
	 * @param int $member_id
	 *		Member ID
	 */
	function char($char, $member_id)
	{
		global $roster;

		// Delete stale data
		$query = "DELETE FROM `" . $roster->db->table('keycache', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "';";
		$roster->db->query($query);
		$this->messages .= ' - Keycache cleaned';

		// Gold
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('players') . "` AS data "
			. "WHERE `stages`.`locale` = '" . $char['Locale'] . "' "
			. "AND `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'G' AND (`data`.`money_c` + `data`.`money_s` * 100 + `data`.`money_g` * 10000) >= `stages`.`count`;";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' gold stages activated';

		// Items
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `key_name`, `stage` "
			. "FROM ("
				. "SELECT `stages`.`key_name`, `stages`.`stage`, `stages`.`count` AS need_count, SUM(`data`.`item_quantity`) AS item_count "
				. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
				. "`" . $roster->db->table('items') . "` AS data "
				. "WHERE `stages`.`locale` = '" . $char['Locale'] . "' "
				. "AND `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
				. "AND (`stages`.`type` = 'In' AND `data`.`item_name` = `stages`.`value` "
				. "OR `stages`.`type` = 'Ii' AND `data`.`item_id` LIKE CONCAT(`stages`.`value`, ':%')) "
				. "GROUP BY `stages`.`key_name`, `stages`.`stage`, `stages`.`count` "
			. ") AS data_query "
			. "WHERE `item_count` >= `need_count`;";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' item stages activated';

		// Quests
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('quests') . "` AS link, "
			. "`" . $roster->db->table('quest_data') . "` AS data "
			. "WHERE `stages`.`locale` = '" . $char['Locale'] . "' "
			. "AND `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' "
			. "AND `link`.`member_id` = '" . $member_id ."' "
			. "AND `link`.`quest_id` = `data`.`quest_id` "
			. "AND `stages`.`type` = 'Q' AND `data`.`quest_name` = `stages`.`value`;";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' quest stages activated';

		// Reputation
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('reputation') . "` AS data "
			. "WHERE `stages`.`locale` = '" . $char['Locale'] . "' "
			. "AND `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'R' AND `data`.`name` = `stages`.`value` "
			. "AND `stages`.`count` <= `data`.`curr_rep` + CASE `data`.`Standing` ";
		foreach( $this->wordings[$char['Locale']]['rep2level'] as $standing => $number )
		{
			$query .= "WHEN '" . $standing . "' THEN " . (int)$number . " ";
		}
		$query .= "END;";

		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' reputation stages activated';

		// Skills
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('skills') . "` AS data "
			. "WHERE `stages`.`locale` = '" . $char['Locale'] . "' "
			. "AND `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'S' AND `data`.`skill_name` = `stages`.`value` "
			. "AND `stages`.`count` <= SUBSTRING_INDEX(`data`.`skill_level`,':',1);";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' skill stages activated';

		return true;
	}
}
