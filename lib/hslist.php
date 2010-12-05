<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Honor ranking list
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * Generate the Honor List
 *
 * @return string
 */
function generateHsList()
{
	global $roster;

	//Highest Lifetime Rank
	$query = "SELECT `name`, `lifetimeRankName` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `lifetimeHighestRank`DESC, `lifetimeHK` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		$roster->tpl->assign_block_vars('hslist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-memberslist-honorlist'),
			'VALUE' => $roster->locale->act['hslist1'],
			'NAME'  => $row['name'],
			'COUNT' => ( $row['lifetimeRankName'] ? $row['lifetimeRankName'] : '&nbsp;' )
			)
		);
	}

	//Highest LifeTime HKs
	$query = "SELECT `name`, `lifetimeHK` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `lifetimeHK` DESC, `lifetimeHighestRank` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		$roster->tpl->assign_block_vars('hslist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-memberslist-honorlist'),
			'VALUE' => $roster->locale->act['hslist2'],
			'NAME'  => $row['name'],
			'COUNT' => $row['lifetimeHK']
			)
		);
	}

	//Highest honorpoints
	$query = "SELECT `name`, `honorpoints` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `honorpoints` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		$roster->tpl->assign_block_vars('hslist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-memberslist-honorlist'),
			'VALUE' => $roster->locale->act['hslist3'],
			'NAME'  => $row['name'],
			'COUNT' => $row['honorpoints']
			)
		);
	}

	//Highest arenapoints
	$query = "SELECT `name`, `arenapoints` FROM `" . $roster->db->table('players') . "` WHERE `guild_id` = '" . $roster->data['guild_id'] . "' ORDER BY `arenapoints` DESC LIMIT 0 , 1";
	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch( $result );

	if( $row )
	{
		$roster->tpl->assign_block_vars('hslist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-memberslist-honorlist'),
			'VALUE' => $roster->locale->act['hslist4'],
			'NAME'  => $row['name'],
			'COUNT' => $row['arenapoints']
			)
		);
	}
	$roster->db->free_result($result);

	$roster->tpl->set_handle('hslist', 'hslist.html');
	return $roster->tpl->fetch('hslist');
}
