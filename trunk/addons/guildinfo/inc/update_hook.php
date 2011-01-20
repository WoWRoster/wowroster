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

		$this->messages = 'memberslist';
	}
	
	/**
	 * Resets the SQL insert/update string holder
	 */
	function reset_values()
	{
		$this->assignstr = '';
	}


	/**
	 * Add a value to an INSERT or UPDATE SQL string
	 *
	 * @param string $row_name
	 * @param string $row_data
	 */
	function add_value( $row_name , $row_data )
	{
		global $roster;

		if( $this->assignstr != '' )
		{
			$this->assignstr .= ',';
		}

		// str_replace added to get rid of non breaking spaces in cp.lua tooltips
		$row_data = str_replace(chr(194) . chr(160), ' ', $row_data);
		$row_data = stripslashes($row_data);
		$row_data = $roster->db->escape($row_data);

		$this->assignstr .= " `$row_name` = '$row_data'";
	}


	/**
	 * Verifies existance of variable before attempting add_value
	 *
	 * @param array $array
	 * @param string $key
	 * @param string $field
	 * @param string $default
	 * @return boolean
	 */
	function add_ifvalue( $array , $key , $field=false , $default=false )
	{
		if( $field === false )
		{
			$field = $key;
		}

		if( isset($array[$key]) )
		{
			$this->add_value($field, $array[$key]);
			return true;
		}
		else
		{
			if( $default !== false )
			{
				$this->add_value($field, $default);
			}
			return false;
		}
	}
	
	

	/**
	 * Guild_pre trigger, set a flag if officer note data is not available
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
	 * Guild trigger, the regex-based alt detection
	 *
	 * @param array $char
	 *		CP.lua guild member data
	 * @param int $member_id
	 * 		Member ID
	 */
	function guild($char, $member_id)
	{
		global $roster;
		$update = false;
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
		$update = $roster->db->num_rows( $resultx ) == 1;
		
		$this->reset_values();
		$this->add_value('member_id', $member_id);
		$this->add_ifvalue($char['XP'], 'TotalXP');
		$this->add_ifvalue($char['XP'], 'WeeklyXP');
		$this->add_ifvalue($char['XP'], 'TotalRank');
		$this->add_ifvalue($char['XP'], 'WeeklyRank');
		$this->add_ifvalue($char, 'Name', 'Member');
		$this->add_value('guild_id', $this->guild_id);

		if( $update )
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
		//if( !$result )
		//{
		//	$this->setError('Member Log [' . $data['name'] . '] could not be inserted',$roster->db->error());
		//}
		
		
		return true;
	}

	/**
	 * Guild_post trigger: throwing away the old records
	 *
	 * @param array $guild
	 *		CP.lua guild data
	 */
	function guild_post( $guild )
	{
		global $roster;

		//echo '<pre>';
		//print_r($guild['News']);
		$news = $guild['News'];
		foreach ($news as $catagory => $id)
		{
			//echo $catagory.' - '.$id.'<br>';
			foreach ($id as $entry => $d)
			{

				if (!empty($d)) {

				
					
				$mat = explode("/",$d['Date']);
				$datee = $mat[2].'-'.$mat[1].'-'.$mat[0];
				$date = date_create($datee);
				$display =  date_format($date, 'D F jS');

				$queryx = "SELECT `Member`,`Achievement`,`Date` FROM `".$roster->db->table('news',$this->data['basename'])."` WHERE `Member`='" . $d['Member'] . "' AND `Achievement`='".$d['Achievement']."' AND `Date`='".date_format($date, 'Y-m-d H:i:s')."'";
				$resultx = $roster->db->query( $queryx );
				$update = $roster->db->num_rows( $resultx ) == 1;
				
				
				
				$this->reset_values();
				//$this->add_value('member_id', $member_id);
				$this->add_ifvalue($d, 'Achievement');
				$this->add_ifvalue($d, 'Member');
				$this->add_value('Date', date_format($date, 'Y-m-d H:i:s'));
				$this->add_ifvalue($d, 'Typpe');
				$this->add_ifvalue($d, 'Type');
				$this->add_value('guild_id', $this->guild_id);
				$this->add_value('Display_date', $display);
				//$querystr = "INSERT INTO `".$roster->db->table('news',$this->data['basename'])."` SET " . $this->assignstr . ";";
				if( $update )
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

	/**
	 * Char trigger: add the member record to the local data array
	 *
	 * @param array $char
	 *		CP.lua character data
	 * @param int $member_id
	 *		Member ID
	 *
	function char($char, $member_id)
	{
		global $roster;

		// --[ Check if this update type is enables ]--
		if(( $this->data['config']['update_type'] & 2 ) == 0 )
		{
			// prevent the addon name from being displayed
			$this->messages = '';
			return true;
		}

		// --[ Fetch full member data ]--
		$query =
			"SELECT `alt`.*, `member`.`name` ".
			"FROM `".$roster->db->table('members')."` member ".
				"LEFT JOIN `".$roster->db->table('alts',$this->data['basename'])."` alt ".
					"ON `alt`.`member_id` = `member`.`member_id` ".
			"WHERE `member`.`member_id` = '".$member_id."';";

		$result = $roster->db->query( $query );

		if ( !$result )
		{
			$this->messages .= ' - <span style="color:red;">Member with ID: '.$member_id.' not updated, failed at line '.__LINE__.'. MySQL said:<br/>'.$roster->db->error().'</span><br/>'."\n";
			return false;
		}

		if ( $row = $roster->db->fetch( $result )) {
			// Check manual record
			if ( $row['alt_type'] & 0x8 ) {
				$roster->db->free_result( $result );
				$this->messages .= " - <span style='color:yellow;'>Manual entry</span><br/>\n";
				return true;
			}
			else
			{
				$roster->db->free_result( $result );
				$member_name = $row['name'];
			}
		}
		else
		{
			$roster->db->free_result( $result );
			$this->messages .= ' - <span style="color:red;">'.$member_name.' not updated, failed at line '.__LINE__.'</span><br/>'."\n";
			return false;
		}

		// --[ Add record to the cache of chars we'll be updating ]--
		$this->chars[$member_id] = $char;
		$this->chars[$member_id]['main_id'] = $row['main_id'];
		$this->chars[$member_id]['alt_type'] = $row['alt_type'];

		$this->messages = substr($this->messages,0,-10);
		return true;
	}

	**
	 * Char_post trigger: does the actual update.
	 *
	 * @param array $chars
	 *		CP.lua characters data
	 *
	function char_post($chars)
	{
		global $roster;

		// --[ Check if this update type is enables ]--
		if(( $this->data['config']['update_type'] & 2 ) == 0 )
		{
			// prevent the addon name from being displayed
			$this->messages = '';
			return true;
		}

		if( empty($this->chars) ) { return true; }

		// Decide upon a main: Highest leveled among those with highest guild rank
		$maxrank = 11;
		$maxlevel = 0;

		foreach($this->chars as $char)
		{
			if( isset( $char['Guild'] ) && isset( $char['Guild']['Rank'] ) && $char['Guild']['Rank'] < $maxrank )
			{
				$maxrank = $char['Guild']['Rank'];
			}
		}

		foreach($this->chars as $member_id => $char)
		{
			if( $char['Guild']['Rank'] == $maxrank && $char['Level'] > $maxlevel )
			{
				$maxlevel = $char['Level'];
				$mainid = $member_id;
			}
		}

		// And the update code
		$inclause = implode(',',array_diff(array_keys($this->chars),array($mainid)));

		if( empty($inclause) )
		{
			$query = "UPDATE `".$roster->db->table('alts',$this->data['basename'])."` SET `main_id` = '".$mainid."', `alt_type` = '".ALTMONITOR_MAIN_MANUAL_NO_ALTS."' WHERE `member_id` = '".$mainid."'";
			if( $roster->db->query($query) )
			{
				$this->messages .= ' - '.$this->chars[$mainid]['Name'].' written as main without alts<br/>'."\n";
			}
			else
			{
				$this->messages .= ' - <span style="color:red;">Main not written. MySQL said: '.$roster->db->error().'</span><br/>'."\n";
				return false;
			}
		}
		else
		{
			$query = "UPDATE `".$roster->db->table('alts',$this->data['basename'])."` SET `main_id` = '".$mainid."', `alt_type` = '".ALTMONITOR_ALT_MANUAL_WITH_MAIN."' WHERE `member_id` IN (".$inclause.")";
			if( $roster->db->query($query) )
			{
				$this->messages .= ' - '.$roster->db->affected_rows().' alts added to main '.$this->chars[$mainid]['Name'];
			}
			else
			{
				$this->messages .= ' - <span style="color:red;">Alts not written. MySQL said: '.$roster->db->error().'</span><br/>'."\n";
				return false;
			}

			$query = "UPDATE `".$roster->db->table('alts',$this->data['basename'])."` SET `main_id` = '".$mainid."', `alt_type` = '".ALTMONITOR_MAIN_MANUAL_WITH_ALTS."' WHERE `member_id` = '".$mainid."'";
			if( $roster->db->query($query) )
			{
				$this->messages .= ' - Main written<br/>'."\n";
			}
			else
			{
				$this->messages .= ' - <span style="color:red;">Main not written. MySQL said: '.$roster->db->error().'</span><br/>'."\n";
				return false;
			}
		}

		return true;
	}
	*/
}
