<?php
/**
 * WoWRoster.net WoWRoster
 *
 * PvPLog ranking list
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    PvPLog
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * Generate the PvP stats List
 *
 * @return string
 */
function generatePvpList()
{
	global $roster;

	$roster->tpl->assign_var('L_PVPLIST', $roster->locale->get_string('pvplist','pvplog'));

	// Guild that suffered most at our hands
	$query = "SELECT `pvp`.`guild`, COUNT(`pvp`.`guild`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`guild` != '' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`guild` ORDER BY countg DESC";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=guildwins'),
			'VALUE' => $roster->locale->get_string('pvplist1','pvplog'),
			'NAME'  => ( $row['guild'] == '' ? '(unguilded)' : $row['guild'] ),
			'COUNT' => $row['countg']
			)
		);
	}


	// Guild that killed us the most
	$query = "SELECT `pvp`.`guild`, COUNT(`pvp`.`guild`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`guild` != '' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`guild` ORDER BY countg DESC";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=guildlosses'),
			'VALUE' => $roster->locale->get_string('pvplist2','pvplog'),
			'NAME'  => ( $row['guild'] == '' ? '(unguilded)' : $row['guild'] ),
			'COUNT' => $row['countg']
			)
		);
	}


	// Player who we killed the most
	$query = "SELECT `pvp`.`name`, COUNT(`pvp`.`name`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`guild` != '' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`name` ORDER BY countg DESC";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=enemywins'),
			'VALUE' => $roster->locale->get_string('pvplist3','pvplog'),
			'NAME'  => $row['name'],
			'COUNT' => $row['countg']
			)
		);
	}


	// Player who killed us the most
	$query = "SELECT `pvp`.`name`, COUNT(`pvp`.`name`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`name` ORDER BY countg DESC";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=enemylosses'),
			'VALUE' => $roster->locale->get_string('pvplist4','pvplog'),
			'NAME'  => $row['name'],
			'COUNT' => $row['countg']
			)
		);
	}


	// Member with the most kills
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id` ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=purgewins'),
			'VALUE' => $roster->locale->get_string('pvplist5','pvplog'),
			'NAME'  => $row['gn'],
			'COUNT' => $row['countg']
			)
		);
	}


	// Member who has died the most
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id` ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=purgelosses'),
			'VALUE' => $roster->locale->get_string('pvplist6','pvplog'),
			'NAME'  => $row['gn'],
			'COUNT' => $row['countg']
			)
		);
	}


	// Member with best kill average
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id` ORDER BY ave DESC";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$ave = round($row['ave'], 2);

		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=purgeavewins'),
			'VALUE' => $roster->locale->get_string('pvplist7','pvplog'),
			'NAME'  => $row['gn'],
			'COUNT' => ( $ave > 0 ? '+' : '' ) . $ave
			)
		);
	}


	// Member with best loss average
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2','pvplog') . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id` ORDER BY ave DESC";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);
	$row = $roster->db->fetch($result,SQL_ASSOC);

	if( $row )
	{
		$ave = round($row['ave'], 2);

		$roster->tpl->assign_block_vars('pvplist',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'  => makelink('guild-pvplog&amp;type=purgeavelosses'),
			'VALUE' => $roster->locale->get_string('pvplist8','pvplog'),
			'NAME'  => $row['gn'],
			'COUNT' => ( $ave > 0 ? '+' : '' ) . $ave
			)
		);
	}

	$roster->db->free_result($result);

	$roster->tpl->set_handle('pvplist', 'pvplog/pvplist.html');
	return $roster->tpl->fetch('pvplist');
}
