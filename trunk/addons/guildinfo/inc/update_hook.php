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

/*
		Array
		(
			[AchRank] => 2
			[Zone] => Stormwind City
			[Class] => Death Knight
			[ClassId] => 6
			[Name] => Boofis
			[Level] => 85
			[Mobile] =>
			[Title] => Royalty
			[AchPoints] => 4805
			[RankEn] => Royalty
			[LastOnline] => 0:0:0:1
			[XP] => Array
				(
					[TotalXP] => 16103407
					[WeeklyXP] => 289276
					[TotalRank] => 3
					[WeeklyRank] => 12
				)

			[Rank] => 1
		)
*/
		$this->messages = '';

		$queryx = "SELECT `member_id` FROM `".$roster->db->table('ranks',$this->data['basename'])."` WHERE `member_id`='" . $member_id . "'";
		$resultx = $roster->db->query( $queryx );
		$update_sql = $roster->db->num_rows( $resultx ) == 1;

		$this->reset_values();
		$update->add_value('member_id', $member_id);
		$update->add_ifvalue($char['XP'], 'TotalXP');
		$update->add_ifvalue($char['XP'], 'WeeklyXP');
		$update->add_ifvalue($char['XP'], 'TotalRank');
		$update->add_ifvalue($char['XP'], 'WeeklyRank');
		$update->add_ifvalue($char, 'Name', 'Member');
		$update->add_value('guild_id', $this->guild_id);

		if( $update_sql )
		{
			$querystr = "UPDATE `".$roster->db->table('ranks',$this->data['basename'])."` SET ".$this->assignstr." WHERE `member_id` = '$member_id'";
		}
		else
		{
			$querystr = "INSERT INTO `".$roster->db->table('ranks',$this->data['basename'])."` SET " . $this->assignstr . ";";
		}
		//echo $querystr.'<br>';
		$result = $roster->db->query($querystr);
		if ( $result )
		{
			$this->messages .= 'Contrabution Rank Updated';
		}
/*
		if( !$result )
		{
			$this->setError('Member Log [' . $data['name'] . '] could not be inserted',$roster->db->error());
		}
 */

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

		//echo '<pre>';
		//print_r($guild['News']);
		$news = $guild['News'];
		foreach ($news as $catagory => $id)
		{
			//echo $catagory.' - '.$id.'<br>';
			foreach ($id as $entry => $d)
			{
				if (!empty($d))
				{
					$mat = explode("/",$d['Date']);
					$datee = $mat[2].'-'.$mat[1].'-'.$mat[0];
					$date = date_create($datee);
					$display =  date_format($date, 'D F jS');

					$queryx = "SELECT `Member`,`Achievement`,`Date` FROM `".$roster->db->table('news',$this->data['basename'])."` WHERE `Member`='" . $d['Member'] . "' AND `Achievement`='".$d['Achievement']."' AND `Date`='".date_format($date, 'Y-m-d H:i:s')."'";
					$resultx = $roster->db->query( $queryx );
					$update_sql = $roster->db->num_rows( $resultx ) == 1;

					$this->reset_values();
					//$update->add_value('member_id', $member_id);
					$update->add_ifvalue($d, 'Achievement');
					$update->add_ifvalue($d, 'Member');
					$update->add_value('Date', date_format($date, 'Y-m-d H:i:s'));
					$update->add_ifvalue($d, 'Typpe');
					$update->add_ifvalue($d, 'Type');
					$update->add_value('guild_id', $this->guild_id);
					$update->add_value('Display_date', $display);
					//$querystr = "INSERT INTO `".$roster->db->table('news',$this->data['basename'])."` SET " . $this->assignstr . ";";
					if( $update_sql )
					{
						//$querystr = "UPDATE `".$roster->db->table('news',$this->data['basename'])."` SET ".$this->assignstr." WHERE `member_id` = '$member_id'";
					}
					else
					{
						$querystr = "INSERT INTO `".$roster->db->table('news',$this->data['basename'])."` SET " . $this->assignstr . ";";
					}
					//echo $querystr.'<br>';
					$result = $roster->db->query($querystr);
				}
/*
				foreach ($d as $t => $e)
				{
					echo '--'.$t.' - '.$e.'<br>';
				}
*/
			}
		}

		$this->messages = 'Guild News Updated';

		return true;
	}
}
