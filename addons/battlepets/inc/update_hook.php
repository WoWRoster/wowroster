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
class battlepetsUpdate
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
	function battlepetsUpdate($data)
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

		$this->messages = 'Battle Pets: ';
	}

	/**
	 * Resets the SQL insert/update string holder
	 */
	function reset_values()
	{
		$this->assignstr = '';
	}

	/**
	 * Guild trigger:
	 *
	 * @param array $char
	 *		CP.lua guild member data
	 * @param int $member_id
	 * 		Member ID
	 */
	function char($char, $member_id)
	{
		global $roster, $update, $addon;
		
		$sqlquery2 = "DELETE FROM `" . $roster->db->table('pets', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "'";
		$result2 = $roster->db->query($sqlquery2);
		
		$sqlquery3 = "DELETE FROM `" . $roster->db->table('pets_spells', $this->data['basename']) . "` WHERE `member_id` = '" . $member_id . "'";
		$result3 = $roster->db->query($sqlquery3);

		$data = $char["PetBattle"];
		
		foreach ($data as $pet => $info)
		{
			
			
			$update->reset_values();
			$update->add_value('member_id', $member_id );
			$update->add_ifvalue($info, 'CName');
			$update->add_ifvalue($info, 'Level');
			$update->add_ifvalue($info, 'XP');
			$update->add_ifvalue($info, 'MaxXp');
			$update->add_ifvalue($info, 'Name');
			$update->add_value('Texture', strtolower($info['Texture']));
			$update->add_ifvalue($info, 'Tooltip');
			$update->add_ifvalue($info, 'Health');
			$update->add_ifvalue($info, 'MaxHealth');
			$update->add_ifvalue($info, 'Power');
			$update->add_ifvalue($info, 'Speed');
			$update->add_ifvalue($info, 'Rarity');
			$update->add_ifvalue($info, 'Type');
			$update->add_ifvalue($info, 'CreatureID');
			$update->add_value('SText', $this->tooltip($info['SText']));
			$update->add_ifvalue($info, 'Description');
			$update->add_ifvalue($info, 'Species');
			$update->add_ifvalue($info, 'IsWild');
			$querystr = "INSERT INTO `" . $roster->db->table('pets', $this->data['basename']) . "` SET " . $update->assignstr;
			$result = $roster->db->query($querystr);
			$this->messages .= '.';
			$petID = $roster->db->insert_id();
			foreach ($info['spells'] as $id => $spell)
			{
				$update->reset_values();
				//$update->add_value('member_id', $member_id );
				$update->add_value('bpet_id', $petID );
				$update->add_value('member_id', $member_id );
				$update->add_value('spell_level', (int)$spell['Text'] );
				$update->add_value('spell_id', $spell['SpellID'] );
				$update->add_value('spell_turns', $spell['NumTurns'] );
				$update->add_value('spell_cd', $spell['MaxCd'] );
				$update->add_value('spell_name', $spell['Name'] );
				$update->add_value('spell_strong', $spell['StrongAgainstType'] );
				$update->add_value('spell_weak', $spell['WeakAgainstType'] );
				$update->add_value('spell_texture', strtolower($spell['Icon']) );
				$update->add_value('spell_tooltip', $this->tooltip($spell['Tooltip']) );
				$querystr = "INSERT INTO `" . $roster->db->table('pets_spells', $this->data['basename']) . "` SET " . $update->assignstr;
				$result = $roster->db->query($querystr);
			}
			
		}
		return true;
		
	}
	
	function tooltip( $tipdata )
	{
		global $roster;
		$tooltip = '';
		$tipdata = preg_replace('/\|c([a-f0-9]{2})([0-9a-f]{6})(.+?)\|r/i','<span style="color:#$2;">$3</span>',$tipdata);
		//$tipdata = preg_replace('/\|c([0-9a-f]{2})([0-9a-f]{6})([^\|]+)/','<span style="color:#$2;">$3</span>',$tipdata);
		$tipdata = str_replace('|r', '', $tipdata);
		$tipdata = str_replace('|n', '<br>', $tipdata);
		$tipdata = str_replace('.BLP', '', $tipdata);
		//$tipdata = preg_replace('/\|TINTERFACE\\\\(.+?)\\\\(.+?):0\|t/ise', '<img src="Interface/'.ucfirst('$1').'/'.strtolower('$2').'.'.$roster->config['img_suffix'].'"></a> ',$tipdata);
		$tipdata = preg_replace('/\|TINTERFACE\\\\(.+?)\\\\(.+?):0\|t/e', "'<img src=\"".$roster->config['interface_url']."Interface/'.ucfirst(strtolower('$1')).'/'.strtolower('$2').'.png\" width=16 height=16></a> '",$tipdata);

		
		if( is_array($tipdata) )
		{
			$tooltip = implode("<br>",$tipdata);
		}
		else
		{
			$tooltip = $tipdata;//str_replace('<br>',"\n",$tipdata);
		}
		return $tooltip;
	}
	

}
