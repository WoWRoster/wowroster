<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Raid Progresion info
 *
 *

 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://ulminia.zenutech.com
 * @package    Achievements
*/

class achievementsUpdate
{
	var $messages = '';			// Update messages
	var $data = array();		// Addon config data automatically pulled from the addon_config table
	var $files = array();
	var $evid = '';
	var $evt = '0';
	var $debuging_flag = '0';
	var $a = Array();
	var $cfg = array();
	var $achnum = '';
	var $armory;
	var $base_url;
	var $guild_id = '';

	var $order = '0';

	function achievementsUpdate( $data )
	{
		$this->data = $data;
	}


	/**
	 * Standalone Update Hook
	 *
	 * @return bool
	 */
	function update( )
	{
		global $roster;
		return true;
	}

	function char($char, $member_id)
	{
		global $roster, $update, $addon;

		$char = $roster->api->Char->getCharInfo($char['Server'],$char['Name'],'12');
			$rx = 0;
		$achi = $char['achievements'];
		$a = true;
		$sqlquery2 = "DELETE FROM `" . $roster->db->table('achievements', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "'";
		$result2 = $roster->db->query($sqlquery2);

		foreach ($achi['achievementsCompleted'] as $var => $info)
		{
			$update->reset_values();
			$update->add_value('achie_id', $info );
			$update->add_value('achie_date', $char['achievements']['achievementsCompletedTimestamp'][''.$var.''] );
			$update->add_value('member_id', $member_id);
			$querystr = "INSERT INTO `" . $roster->db->table('achievements', $this->data['basename']) . "` SET " . $update->assignstr;
			$rx++;
		
			$result = $roster->db->query($querystr);
		}

		$achi = $char['achievements'];
		$a = true;
		///*
		$sqlquery2 = "DELETE FROM `" . $roster->db->table('criteria', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "'";
		$result2 = $roster->db->query($sqlquery2);

		//we are not gona use criteria yet so much data to process it really slows roster
		foreach ($achi['criteria'] as $var => $info)
		{
			$update->reset_values();
			$update->add_value('member_id', $member_id );
			$update->add_value('crit_id', $info );
			$update->add_value('crit_date', $char['achievements']['criteriaTimestamp'][''.$var.'']);
			$update->add_value('crit_value', $char['achievements']['criteriaQuantity'][''.$var.'']);
			$querystr = "INSERT INTO `" . $roster->db->table('criteria', $this->data['basename']) . "` SET " . $update->assignstr;
			$result = $roster->db->query($querystr);
		}
		//*/
		
		$this->messages .= '<li>Updating Achievements: ';
		$this->messages .= $rx.'</li>';

		return true;
	}
	
	
	function guild_pre($guild)
	{
		global $roster, $update, $addon;

		$this->guild_id = $guild['guild_id'];
		include_once(ROSTER_LIB . 'update.lib.php');
		$update = new update;
		
		$char = $roster->api->Guild->getGuildInfo($guild['Server'],$guild['GuildName'],'2');
		$rx = 0;
		$achi = $char['achievements'];
		$a = true;
		$sqlquery2 = "DELETE FROM `" . $roster->db->table('g_achievements', $this->data['basename']) . "` WHERE `member_id` = '" . $this->guild_id . "'";
		$result2 = $roster->db->query($sqlquery2);

		foreach ($achi['achievementsCompleted'] as $var => $info)
		{
			$update->reset_values();
			$update->add_value('achie_id', $info );
			$update->add_value('achie_date', $char['achievements']['achievementsCompletedTimestamp'][''.$var.''] );
			$update->add_value('member_id', $this->guild_id);
			$querystr = "INSERT INTO `" . $roster->db->table('g_achievements', $this->data['basename']) . "` SET " . $update->assignstr;
			$rx++;
		
			$result = $roster->db->query($querystr);
		}

		$achi = $char['achievements'];
		$a = true;
		///*
		$sqlquery2 = "DELETE FROM `" . $roster->db->table('g_criteria', $this->data['basename']) . "` WHERE `member_id` = '" . $this->guild_id . "'";
		$result2 = $roster->db->query($sqlquery2);

		//we are not gona use criteria yet so much data to process it really slows roster
		foreach ($achi['criteria'] as $var => $info)
		{
			$update->reset_values();
			$update->add_value('member_id', $this->guild_id );
			$update->add_value('crit_id', $info );
			$update->add_value('crit_date', $char['achievements']['criteriaTimestamp'][''.$var.'']);
			$update->add_value('crit_value', $char['achievements']['criteriaQuantity'][''.$var.'']);
			$querystr = "INSERT INTO `" . $roster->db->table('g_criteria', $this->data['basename']) . "` SET " . $update->assignstr;
			$result = $roster->db->query($querystr);
		}
		//*/
		
		$this->messages .= '<li>Updating Achievements: ';
		$this->messages .= $rx.'</li>';

		return true;
	}
	
	
	function  char_delete($inClause)
	{
		global $roster, $addon;
		
		$querystr = "DELETE FROM `" . $roster->db->table('criteria', $this->data['basename']) . "` WHERE `member_id` IN ($inClause)";
		if( !$roster->db->query($querystr) )
		{
			$this->setError('Player criteria Data could not be deleted',$roster->db->error());
		}
		$querystr = "DELETE FROM `" . $roster->db->table('achievements', $this->data['basename']) . "` WHERE `member_id` IN ($inClause)";
		if( !$roster->db->query($querystr) )
		{
			$this->setError('Player achievements Data could not be deleted',$roster->db->error());
		}

		
		$query = "DELETE `" . $roster->db->table('achievements',$this->data['basename']) . "`"
			   . " FROM `" . $roster->db->table('achievements',$this->data['basename']) . "`"
			   . " LEFT JOIN `" . $roster->db->table('players') . "` USING (`member_id`)"
			   . " WHERE `" . $roster->db->table('players') . "`.`member_id` IS NULL;";

		if( $roster->db->query($query) )
		{
			$this->messages .= 'Achievements: ' . $roster->db->affected_rows() . ' records without matching member records deleted';
		}
		else
		{
			$this->messages .= 'Achievements: <span style="color:red;">Old records not deleted. MySQL said: ' . $roster->db->error() . "</span><br />\n";
			return false;
		}
		return true;
		
	}

	function reset_messages()
	{
		$this->messages = '';
	}

}
