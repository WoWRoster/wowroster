<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    GuildInfo
 * @subpackage UpdateHook
 */

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * MembersList Update Hook
 *
 * @package    MembersList
 * @subpackage UpdateHook
 */
class guildinfoUpdate
{
	// Update messages
	var $messages = '';

	// Addon data object, recieved in constructor
	var $data;

	// LUA upload files accepted. We don't use any.
	var $files = array();

	// Character data cache
	var $chars = array();

	// Officer note check. Default true, because manual update bypasses the check.
	var $passedCheck=true;
	var $assignstr = array();
	var $guild_id = '';

	/**
	 * Constructor
	 *
	 * @param array $data
	 *		Addon data object
	 */
	function guildinfoUpdate($data)
	{
		$this->data = $data;

		include_once($this->data['conf_file']);
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

		$this->messages = 'GuildInfo';
	}

	/**
	 * Resets the SQL insert/update string holder
	 */
	function reset_values()
	{
		$this->assignstr = '';
	}

	/**
	 * Guild_pre trigger, set out guild id here
	 *
	 * @param array $guild
	 * 		CP.lua guild data
	 */
	function guild_pre($guild)
	{
		global $roster;

		$this->guild_id = $guild['guild_id'];

		return true;
	}

	/**
	 * Guild trigger:
	 *
	 * @param array $char
	 *		CP.lua guild member data
	 * @param int $member_id
	 * 		Member ID
	 */
	function guild($char, $member_id)
	{
		global $roster, $update;

	
		$this->messages = '';
		
		if (isset($char['XP']))
		{
			$queryx = "SELECT `member_id` FROM `".$roster->db->table('ranks',$this->data['basename'])."` WHERE `member_id`='" . $member_id . "'";
			$resultx = $roster->db->query( $queryx );
			$update_sql = $roster->db->num_rows( $resultx );

			$update->reset_values();
			$update->add_value('member_id', $member_id);
			$update->add_ifvalue($char['XP'], 'TotalXP');
			$update->add_ifvalue($char['XP'], 'WeeklyXP');
			$update->add_ifvalue($char['XP'], 'TotalRank');
			$update->add_ifvalue($char['XP'], 'WeeklyRank');
			$update->add_ifvalue($char, 'Name', 'Member');
			$update->add_value('guild_id', $this->guild_id);

			if( $update_sql >= '1' )
			{
				$querystr = "UPDATE `".$roster->db->table('ranks',$this->data['basename'])."` SET ". $update->assignstr." WHERE `member_id` = '".$member_id."'";
			}	
			else
			{
				$querystr = "INSERT INTO `".$roster->db->table('ranks',$this->data['basename'])."` SET " .  $update->assignstr . ";";
			}
		
		
			$result = $roster->db->query($querystr);
			if ( $result )
			{
				$this->messages .= 'Contrabution Rank Updated';
			}

		}
		else
		{
			$this->messages .= 'No Contrabution Rank Data';
		}
		return true;
	}

	/**
	 * Guild_post trigger:
	 *
	 * @param array $guild
	 *		CP.lua guild data
	 */
	function guild_post( $guild )
	{
		global $roster, $update;


		if (isset($guild['News']))
		{
		
			$news = $guild['News'];
			foreach ($news as $catagory => $id)
			{

				foreach ($id as $entry => $d)
				{
					if (!empty($d))
					{
						$mat = explode("/",$d['Date']);
						$datee = $mat[2].'-'.$mat[1].'-'.$mat[0];
						$date = date_create($datee);
						$display =  date_format($date, 'D F jS');

						$queryx = "SELECT `Member`,`Achievement`,`Date` FROM `".$roster->db->table('news',$this->data['basename'])."` WHERE `Member`='" . $d['Member'] . "' AND `Achievement`='".$roster->db->escape($d['Achievement'])."' AND `Date`='".date_format($date, 'Y-m-d H:i:s')."'";
						$resultx = $roster->db->query( $queryx );
						$update_sql = $roster->db->num_rows( $resultx );

						$update->reset_values();

						$update->add_ifvalue($d, 'Achievement');
						$update->add_ifvalue($d, 'Member');
						$update->add_value('Date', date_format($date, 'Y-m-d H:i:s'));
						$update->add_ifvalue($d, 'Typpe');
						$update->add_ifvalue($d, 'Type');
						$update->add_value('guild_id', $this->guild_id);
						$update->add_value('Display_date', $display);
					
						if( $update_sql >= '1' )
						{
							$querystr = "UPDATE `".$roster->db->table('news',$this->data['basename'])."` SET ".$update->assignstr." WHERE `Member` = '".$d['Member']."' and `Achievement` = '".$roster->db->escape($d['Achievement'])."'";
						}
						else
						{
							$querystr = "INSERT INTO `".$roster->db->table('news',$this->data['basename'])."` SET " .  $update->assignstr . ";";
						}

						$result = $roster->db->query($querystr);
					}	
				}
			}
		
			$this->messages = 'Guild News Updated';
		
		}
		else
		{
			$this->messages = 'No Guild News Data';
		}

		

		return true;
	}
}
