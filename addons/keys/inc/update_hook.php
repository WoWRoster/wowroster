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

	/**
	 * Constructor
	 *
	 * @param array $data
	 *		Addon data object
	 */
	function keysUpdate($data)
	{
		$this->data = $data;
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
			. "WHERE `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'G' AND (`data`.`money_c` + `data`.`money_s` * 100 + `data`.`money_g` * 10000) > `stages`.`count`;";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' gold stages activated';

		// Items
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('items') . "` AS data "
			. "WHERE `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND (`stages`.`type` = 'In' AND `data`.`item_name` = `stages`.`value` "
			. "OR `stages`.`type` = 'Ii' AND `data`.`item_id` LIKE CONCAT(`stages`.`value`, ':%')) "
			. "GROUP BY `stages`.`key_name`, `stages`.`stage`, `stages`.`count` "
			. "HAVING SUM(`data`.`item_quantity`) > `stages`.`count`;";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' item stages activated';

		// Quests
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('quests') . "` AS data "
			. "WHERE `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'Q' AND `data`.`quest_name` = `stages`.`value`;";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' quest stages activated';

		// Reputation
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('reputation') . "` AS data "
			. "WHERE `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'R' AND `data`.`name` = `stages`.`value` "
			. "AND `stages`.`count` < FIND_IN_SET(`data`.`Standing`, '" . implode(',', array_keys($roster->locale->wordings[$char['Locale']]['rep2level'])) . "') ";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' reputation stages activated';

		// Skills
		$query = "INSERT INTO `" . $roster->db->table('keycache', $this->data['basename']) . "` (`member_id`, `key_name`, `stage`) "
			. "SELECT '" . $member_id . "', `stages`.`key_name`, `stages`.`stage` "
			. "FROM `" . $roster->db->table('stages', $this->data['basename']) . "` AS stages, "
			. "`" . $roster->db->table('skills') . "` AS data "
			. "WHERE `stages`.`faction` = '" . substr($char['Faction'],0,1) . "' AND `data`.`member_id` = '" . $member_id ."' "
			. "AND `stages`.`type` = 'S' AND `data`.`skill_name` = `stages`.`value` "
			. "AND `stages`.`count` < SUBSTRING_INDEX(`data`.`skill_level`,':',1);";
		$roster->db->query($query);
		$this->messages .= ' - ' . $roster->db->affected_rows() . ' skill stages activated';

		return true;
	}
}
