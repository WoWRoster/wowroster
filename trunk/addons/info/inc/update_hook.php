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
 * @package    CharacterInfo
 * @subpackage UpdateHook
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Addon Update class
 * This MUST be the same name as the addon basename
 *
 * @package    CharacterInfo
 * @subpackage UpdateHook
 */
class infoUpdate
{
	var $messages = '';		// Update messages
	var $data = array();	// Addon config data automatically pulled from the addon_config table
	var $files = array();
	var $disp_defaults = array();


	/**
	 * Class instantiation
	 * The name of this function MUST be the same name as the class name
	 *
	 * @param array $data	| Addon data
	 * @return infoUpdate
	 */
	function infoUpdate( $data )
	{
		global $roster;

		$this->data = $data;

		$query = "SELECT * FROM `" . $roster->db->table('default',$this->data['basename']) . "`;";

		$result = $roster->db->query($query);
		$this->disp_defaults = $roster->db->fetch_all($result, SQL_ASSOC);
		$this->disp_defaults = $this->disp_defaults[0];
	}

	/**
	 * Char trigger: create an entry for the character processed
	 *
	 * @param array $char
	 * @param int $memberid
	 * @return bool
	 */
	function char( $char , $member_id )
	{
		global $roster;

		$query = "SELECT `member_id` FROM `" . $roster->db->table('display',$this->data['basename']) . "` WHERE `member_id` = '$member_id';";

		$result = $roster->db->query($query);

		if( !$result )
		{
			$this->messages .= 'CharacterInfo: <span style="color:red;">' . $char['Name'] . ' not updated, failed at line ' . __LINE__ . '</span><br />' . "\n";
			return false;
		}

		$update = $roster->db->num_rows($result) == 1;
		$roster->db->free_result($result);

		if( !$update )
		{
			$query = $this->disp_defaults;
			$query['member_id'] = $member_id;
			$querystr = "INSERT INTO `" . $roster->db->table('display',$this->data['basename']) . "` " . $roster->db->build_query('INSERT',$query) . ";";

			if( !$roster->db->query($querystr) )
			{
				$this->messages .= 'CharacterInfo: <span style="color:red;">Old records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
				return false;
			}
		}


		return true;
	}

	/**
	 * Guild post trigger: remove deleted members from the info table
	 *
	 * @param array $guild
	 *		CP.lua guild data
	 * @return bool
	 */
	function guild_post( $guild )
	{
		global $roster;

		$query = "DELETE `" . $roster->db->table('display',$this->data['basename']) . "`"
			   . " FROM `" . $roster->db->table('display',$this->data['basename']) . "`"
			   . " LEFT JOIN `" . $roster->db->table('players') . "` USING (`member_id`)"
			   . " WHERE `" . $roster->db->table('players') . "`.`member_id` IS NULL;";

		if( $roster->db->query($query) )
		{
			$this->messages .= 'CharacterInfo: ' . $roster->db->affected_rows() . ' records without matching member records deleted';
		}
		else
		{
			$this->messages .= 'CharacterInfo: <span style="color:red;">Old records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
			return false;
		}

		return true;
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		$this->messages = '';
	}
}
