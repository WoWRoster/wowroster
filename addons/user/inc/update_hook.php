<?php
/**
 * WoWRoster.net WoWRoster
 *
 * User Update hook
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.2
 * @package    WoWRoster
 * @subpackage User
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}


class userUpdate
{
	// Update messages
	var $messages = '';

	// Addon data object, recieved in constructor
	var $data;

	// LUA upload files accepted. We don't use any.
	var $files = array();

	// Character data cache
	var $chars = array();

	/**
	 * Constructor
	 *
	 * @param array $data
	 *		Addon data object
	 */
	function userUpdate($data)
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

		$this->messages = '<strong>User:</strong><ul>';
	}
	
	/**
	 * Char trigger: add the member record to the local data array
	 *
	 * @param array $char
	 *		CP.lua character data
	 * @param int $member_id
	 *		Member ID
	 */ 
	function char( $data , $member_id )
	{
		global $roster, $addon;
		
		// --[ We will allways try and keep user info up todate if some one is login ]--

		$this->messages .= '<li><span style="color:yellow">Getting Character Information...</span></li><br />' . "\n";
		
		// --[ Fetch full member data ]--
		$query = "SELECT `name`, `guild_id`, `server`, `region` FROM `" . $roster->db->table('players') . "` WHERE `member_id` = '" . $member_id . "';";
		$result = $roster->db->query( $query );

		if ( !$result )
		{
			$this->messages .= ' - <span style="color:red;">Character not updated, failed at line ' . __LINE__ . '. MySQL said:<br/>' . $roster->db->error() . '</span><br/>' . "\n";
			return false;
		}

		if ( $row = $roster->db->fetch( $result ))
		{	
				$roster->db->free_result( $result );
				$member_name = $row['name'];
				$guild_id = $row['guild_id'];
				$realm = $row['server'];
				$region = $row['region'];
		}
		else
		{
			$roster->db->free_result( $result );
			$this->messages .= ' - <span style="color:red;">' . $member_name . ' not updated, failed at line ' . __LINE__ . '</span><br/>'."\n";
			return false;
		}

		if (isset($roster->auth->uid) && $roster->auth->uid != '')
		{
			// And the update code
			$u_id = $roster->auth->uid;
			$sql = "SELECT `uid` FROM `" . $roster->db->table('user_link', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "';";
			$result = $roster->db->query( $sql );
			$row = $roster->db->fetch($result);

			if($row == 0)
			{
				$query = "INSERT INTO `" . $roster->db->table('user_link',$this->data['basename']) . "` SET `uid` = '" . $u_id . "', `member_id` = '" . $member_id . "', `guild_id` = '" . $guild_id . "', `group_id` = '1', `realm` = '" . $realm . "', `region` = '" . $region ."';";

				if( $roster->db->query($query) )
				{
					$this->messages .= ' - <span style="color:green;"><img src="realmstatus.php?r=' . $region . '-' . $realm . '" height=0 width=0 />' . $member_name . ' has been saved.</span><br/>';
					$query2 = "UPDATE `" . $roster->db->table('members') . "` SET `account_id` = '".$u_id."' WHERE `member_id` = '" . $member_id . "';";
					$roster->db->query($query2);
				}
				else
				{
					$this->messages .= ' - <span style="color:red;">' . $member_name . ' has not been saved. MySQL said: ' . $roster->db->error() . '</span><br/>' . "\n";
					return false;
				}
			}
			else
			{
				$query = "UPDATE `" . $roster->db->table('user_link',$this->data['basename']) . "` SET `guild_id` = '" . $guild_id . "', `group_id` = '1', `realm` = '" . $realm . "', `region` = '" . $region ."' WHERE `member_id` = '" . $member_id . "';";

				if( $roster->db->query($query) )
				{
					$this->messages .= ' - <span style="color:green;"><img src="realmstatus.php?r=' . $region . '-' . $realm . '" height=0 width=0 />' . $member_name . ' has been updated.</span><br/>';
					$query2 = "UPDATE `" . $roster->db->table('members') . "` SET `account_id` = '".$u_id."' WHERE `member_id` = '" . $member_id . "';";
					$roster->db->query($query2);
				}
				else
				{
					$this->messages .= ' - <span style="color:red;">' . $member_name . ' has not been updated. MySQL said: ' . $roster->db->error() . '</span><br/>' . "\n";
					return false;
				}
			}
		}
		$this->messages .= "</ul>";
		return true;
	}

}
