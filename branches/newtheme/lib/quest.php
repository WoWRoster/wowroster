<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Quest class and functions
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: quest.php 1791 2008-06-15 16:59:24Z Zanix $
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

function quest_get_many( $member_id, $search='' )
{
	global $roster;

	$query= "SELECT * FROM `" . $roster->db->table('quests') . "` WHERE `member_id` = '$member_id' ORDER BY `zone` ASC, `quest_level` ASC;";

	$result = $roster->db->query($query);

	$quests = array();
	while( $data = $roster->db->fetch($result) )
	{
		$quest = new quest($data);
		$quests[] = $quest;
	}
	return $quests;
}
