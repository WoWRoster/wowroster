<?php
/**
 * WoWRoster.net WoWRoster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    PvPLog
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
 * @package    PvPLog
 * @subpackage UpdateHook
 */
class pvplogUpdate
{
	var $messages = '';		// Update messages
	var $data = array();	// Addon config data automatically pulled from the addon_config table
	var $files = array();


	/**
	 * Class instantiation
	 * The name of this function MUST be the same name as the class name
	 *
	 * @param array $data	| Addon data
	 * @return pvplog
	 */
	function pvplogUpdate( $data )
	{
		$this->data = $data;
		$this->files[] = 'pvplog';
	}

	/**
	 * Standalone Update Hook
	 *
	 * @return bool
	 */
	function update( )
	{
		global $roster, $update;

		$pvpdata = $update->uploadData['pvplog'];
		$this->reset_messages();

		foreach ($pvpdata['PurgeLogData'] as $realm_name => $realm)
		{
			foreach ($realm as $char_name => $char)
			{
				$query = "SELECT `guild_id`, `region` FROM `" . $roster->db->table('players') . "` WHERE `name` = '" . addslashes($char_name) . "' AND `server` = '" . addslashes($realm_name) . "';";
				$result = $roster->db->query( $query );
				if ($roster->db->num_rows($result) > 0)
				{
					$row = $roster->db->fetch( $result );
					$guild_id = $row['guild_id'];
					$region = $row['region'];
					$battles = $char['battles'];
					if( version_compare($char['version'], $this->data['config']['minPvPLogver'], '>=') )
					{
						$this->messages .= '<strong>' . sprintf($roster->locale->act['upload_data'],'PvPLog',$char_name,$realm_name,$region) . "</strong>\n";

						$this->messages .= "<ul>\n";
						$this->update_pvp($guild_id, $char_name, $battles);
						$this->messages .= "</ul>\n";
					}
					else // PvPLog version not high enough
					{
						$this->messages .= '<span class="red">' . sprintf($roster->locale->act['not_updating'],'PvPLog',$char_name,$char['version']) . "</span><br />\n";
						$this->messages .= sprintf($roster->locale->act['PvPLogver_err'], $this->data['config']['minPvPLogver']) . "\n";
					}
				}
			}
		}
		return true;
	}

	/**
	 * Guild post trigger: remove deleted members from the pvp2 table
	 *
	 * @param array $guild
	 *		CP.lua guild data
	 */
	function guild_post( $guild )
	{
		global $roster;

		$query = "DELETE `" . $roster->db->table('pvp2',$this->data['basename']) . "`"
			   . " FROM `" . $roster->db->table('pvp2',$this->data['basename']) . "`"
			   . " LEFT JOIN `" . $roster->db->table('members') . "` USING (`member_id`)"
			   . " WHERE `" . $roster->db->table('members') . "`.`member_id` IS NULL;";

		if( $roster->db->query($query) )
		{
			$this->messages .= 'PvPLog: ' . $roster->db->affected_rows() . ' records without matching member records deleted';
		}
		else
		{
			$this->messages .= 'PvPLog: <span style="color:red;">Old records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
			return false;
		}

		return true;
	}

	/**
	 * Updates pvp table
	 *
	 * @param int $guildId
	 * @param string $name
	 * @param array $data
	 */
	function update_pvp( $guildId , $name , $data )
	{
		global $roster, $update;

		$name_escape = $roster->db->escape( $name );

		$querystr = "SELECT `member_id` FROM `" . $roster->db->table('members') . "` WHERE `name` = '$name_escape' AND `guild_id` = '$guildId';";
		$result = $roster->db->query($querystr);
		if( !$result )
		{
			$this->messages .= 'Member could not be selected for update';
			return;
		}

		$memberInfo = $roster->db->fetch( $result );
		$roster->db->free_result($result);
		if ($memberInfo)
		{
			$memberId = $memberInfo['member_id'];
		}
		else
		{
			$this->messages .= '<li>' . $name . ' is not in the list of guild members so PvPLog info will not be inserted</li>';
			return;
		}
		// process pvp
		$this->messages .= '<li>Updating PvP data</li>';
		// loop through each index fought
		foreach( array_keys($data) as $index)
		{
			$playerInfo = $data[$index];
			$playerName = $playerInfo['name'];
			$playerDate = date('Y-m-d G:i:s', strtotime($playerInfo['date']));
			$playerRealm = ( isset($playerInfo['realm']) ? $playerInfo['realm'] : '' );
			$playerRace = ( isset($playerInfo['race']) ? $playerInfo['race'] : '' );

			// skip if entry already there
			$querystr = "SELECT `guild` FROM `" . $roster->db->table('pvp2',$this->data['basename']) . "` WHERE `index` = '$index' AND `member_id` = '$memberId' AND `name` = '" . $roster->db->escape( $playerName ) . "' AND `date` = '" . $roster->db->escape( $playerDate ) . "'" . ( !empty($playerRealm) ? " AND `realm` = '" . $roster->db->escape( $playerRealm ) . "';" : ';' );

			$result = $roster->db->query($querystr);
			if( !$result )
			{
				$this->messages .= 'PvPLog cannot update';
				return;
			}

			$memberInfo = $roster->db->fetch( $result );
			$roster->db->free_result($result);
			if (!$memberInfo)
			{
				$this->messages .= '<li>Adding PvPLog data for [' . $playerInfo['name'] . ']</li>';

				$update->reset_values();
				$update->add_value('member_id', $memberId);
				$update->add_value('index', $index);
				$this->add_pvp2time('date', $playerInfo['date']);
				$update->add_value('name', $playerInfo['name']);
				$update->add_value('guild', $playerInfo['guild']);
				$update->add_value('realm', $playerRealm);
				$update->add_value('race', $playerRace);
				$update->add_value('class', $playerInfo['class']);
				$update->add_value('zone', $playerInfo['zone']);
				$update->add_value('subzone', $playerInfo['subzone']);
				$update->add_value('leveldiff', $playerInfo['lvlDiff']);
				$update->add_value('enemy', $playerInfo['enemy']);
				$update->add_value('win', $playerInfo['win']);
				$update->add_value('bg', $playerInfo['bg']);
				$update->add_value('rank', $playerInfo['rank']);
				$update->add_value('honor', $playerInfo['honor']);

				$querystr = "INSERT INTO `" . $roster->db->table('pvp2',$this->data['basename']) . "` SET " . $update->assignstr . ';';
				$result = $roster->db->query($querystr);
				if( !$result )
				{
					$this->messages .= 'PvPLog Data could not be inserted';
				}
			}
		}

		// now calculate ratio
		$wins = 0;
		$querystr = "SELECT COUNT(`win`) AS wins FROM `" . $roster->db->table('pvp2',$this->data['basename']) . "` WHERE `win` = '1' AND `member_id` = '$memberId' GROUP BY `win`;";
		$result = $roster->db->query($querystr);
		if( !$result )
		{
			$this->messages .= 'PvPLog cannot select wins';
			return;
		}
		$memberInfo = $roster->db->fetch( $result );
		$roster->db->free_result($result);
		if ($memberInfo)
			$wins = $memberInfo['wins'];
		$this->messages .= '<li>Wins: ' . $wins . '</li>';


		$losses = 0;
		$querystr = "SELECT COUNT(`win`) AS losses FROM `" . $roster->db->table('pvp2',$this->data['basename']) . "` WHERE `win` = '0' AND `member_id` = '$memberId' GROUP BY `win`;";
		$result = $roster->db->query($querystr);
		if( !$result )
		{
			$this->messages .= 'PvPLog cannot select losses';
			return;
		}
		$memberInfo = $roster->db->fetch( $result );
		$roster->db->free_result($result);
		if ($memberInfo)
			$losses = $memberInfo['losses'];
		$this->messages .= '<li>Losses: ' . $losses . '</li>';


		if ($losses == 0 || $wins == 0)
		{
			if ($losses == 0 && $wins == 0)
			{
				$ratio = 0;
			}
			else
			{
				$ratio = 99999;
			}

		}
		else
		{
			$ratio = $wins / $losses;
		}

		$querystr = "UPDATE `" . $roster->db->table('players') . "` SET `pvp_ratio` = ".$ratio." WHERE `member_id` = '$memberId';";
		$result = $roster->db->query($querystr);
		if( !$result )
		{
			$this->messages .= 'PvPLog ratio could not be updated';
		}

		$this->messages .= '<li>Set PvP ratio to ' . $ratio . '</li>';
	}

	/**
	 * Add a time value to an INSERT or UPDATE SQL string for PVP table
	 *
	 * @param string $row_name
	 * @param string $date
	 */
	function add_pvp2time( $row_name , $date )
	{
		global $update;

		$date_str = strtotime($date);
		$p2newdate = date('Y-m-d H:i:s',$date_str);
		$update->add_value($row_name, $p2newdate);
	}

	/**
	 * Resets addon messages
	 */
	function reset_messages()
	{
		$this->messages = '';
	}
}
