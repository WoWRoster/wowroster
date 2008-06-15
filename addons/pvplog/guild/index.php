<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Guild PvPLog stats
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

$roster->output['title'] = $roster->locale->act['pvplist'];

$type = ( isset($_GET['type']) ? $_GET['type'] : 'guildwins' );

$choiceArray = array(
	'---------',
	'guildwins',
	'guildlosses',
	'enemywins',
	'enemylosses',
	'purgewins',
	'purgelosses',
	'purgeavewins',
	'purgeavelosses',
	'pvpratio',
	'playerinfo',
	'guildinfo'
);

$roster->tpl->assign_vars(array(
	'S_HEADER' => false,
	'S_TYPE'   => $type,

	'INFO_TEXT' => false,

	'L_PVP_LIST'      => $roster->locale->act['pvplist'],
	'L_TYPE'          => $roster->locale->act[$type],
	'L_UNKNOWN'       => $roster->locale->act['unknown'],
	'L_WIN'           => $roster->locale->act['win'],
	'L_LOSS'          => $res = $roster->locale->act['loss']
	)
);


foreach( $choiceArray as $item_value )
{
	if( $item_value != 'playerinfo' && $item_value != 'guildinfo' )
	{
		$roster->tpl->assign_block_vars('choice',array(
			'VALUE'    => makelink('guild-' . $addon['basename'] . '&amp;type=' . $item_value,true),
			'NAME'     => ( isset($roster->locale->act[$item_value]) ? $roster->locale->act[$item_value] : $item_value ),
			'SELECTED' => ( $type == $item_value ? true : false )
			)
		);
	}
}


function tableHeaderRow( $th )
{
	global $roster;

	$roster->tpl->assign_var('S_HEADER', true);

	$acount = 0;
	foreach( $th as $header )
	{
		++$acount;

		$roster->tpl->assign_block_vars('header',array(
			'NAME'  => $header,
			'RIGHT' => ( $acount == count($th) ? true : false )
			)
		);
	}
}


if( $type == 'guildwins' )
{
	$query = "SELECT `pvp`.`guild`, COUNT(`pvp`.`guild`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`guild`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])),
			'NAME'      => $row['guild'],
			'COUNT'     => $row['countg']
			)
		);
	}

	$roster->db->free_result($result);
}
elseif( $type == 'guildlosses' )
{
	$query = "SELECT `pvp`.`guild`, COUNT(`pvp`.`guild`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`guild`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])),
			'NAME'      => $row['guild'],
			'COUNT'     => $row['countg']
			)
		);
	}
	$roster->db->free_result($result);

}
elseif( $type == 'enemywins' )
{
	tableHeaderRow(array(
		$roster->locale->act['name'],
		$roster->locale->act['kills'],
		$roster->locale->act['guild'],
		$roster->locale->act['race'],
		$roster->locale->act['class'],
		$roster->locale->act['leveldiff'],
		)
	);

	$query = "SELECT `pvp`.`name`, `pvp`.`guild`, `pvp`.`race`, `pvp`.`class`, `pvp`.`leveldiff`, COUNT(`pvp`.`name`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`name`"
		   . " ORDER BY countg DESC, `pvp`.`leveldiff` DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name'])),
			'NAME'      => $row['name'],
			'COUNT'     => $row['countg'],
			'GLINK'     => makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])),
			'GNAME'     => $row['guild'],
			'RACE'      => $row['race'],
			'CLASS'     => $row['class'],
			'LEVELDIFF' => $row['leveldiff'],
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'enemylosses' )
{
	tableHeaderRow(array(
		$roster->locale->act['name'],
		$roster->locale->act['kills'],
		$roster->locale->act['guild'],
		$roster->locale->act['race'],
		$roster->locale->act['class'],
		$roster->locale->act['leveldiff'],
		)
	);

	$query = "SELECT `pvp`.`name`, `pvp`.`guild`, `pvp`.`race`, `pvp`.`class`, `pvp`.`leveldiff`, COUNT(`pvp`.`name`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`name`"
		   . " ORDER BY countg DESC, `pvp`.`leveldiff` DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name'])),
			'NAME'      => $row['name'],
			'COUNT'     => $row['countg'],
			'GLINK'     => makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])),
			'GNAME'     => $row['guild'],
			'RACE'      => $row['race'],
			'CLASS'     => $row['class'],
			'LEVELDIFF' => $row['leveldiff'],
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'purgewins' )
{
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['gn'],
			'COUNT'     => $row['countg']
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'purgelosses' )
{
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['gn'],
			'COUNT'     => $row['countg']
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'purgeavewins' )
{
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY ave DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$ave = round($row['ave'], 2);
		if( $ave > 0 )
		{
			$ave = '+' . $ave;
		}

		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['gn'],
			'AVE'       => $ave,
			'COUNT'     => $row['countg']
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'purgeavelosses' )
{
	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY ave DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$ave = round($row['ave'], 2);
		if( $ave > 0 )
		{
			$ave = '+' . $ave;
		}

		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['gn'],
			'AVE'       => $ave,
			'COUNT'     => $row['countg']
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'pvpratio' )
{
	$roster->tpl->assign_var('INFO_TEXT', $roster->locale->act['solo_win_loss']);

	$query = "SELECT `members`.`name`, `members`.`member_id`, IF(`pvp`.`win` = '1', 1, 0) AS win, SUM(`pvp`.`win`) AS wtotal, COUNT(`pvp`.`win`) AS btotal"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`leveldiff` < 8 AND `pvp`.`leveldiff` > -8 AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `members`.`name`"
		   . " ORDER BY wtotal DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		if( $row['wtotal'] == $row['btotal'] )
		{
			$ratio = $roster->locale->act['winless'];
		}
		elseif( $row['wtotal'] == 0 )
		{
			$ratio = $roster->locale->act['Unbeaten'];
		}
		else
		{
			$ratio = round(($row['wtotal'] / ($row['btotal']-$row['wtotal'])), 2) . ' to 1';
		}

		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['name'],
			'WINS'      => $row['wtotal'],
			'BATTLES'   => $row['btotal'],
			'RATIO'     => $ratio,
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'playerinfo' )
{
	$player = ( isset($_GET['player']) ? $_GET['player'] : '' );
	$sort = ( isset($_GET['s']) ? $_GET['s'] : '' );

	$first = true;
	$query = "SELECT `pvp`.*, `members`.`name` AS gn"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `pvp`.`name` = '" . $player . "'";

	if ($sort == 'name')
	{
		$query .= ' ORDER BY `name`, `leveldiff` DESC, `guild`;';
	}
	elseif( $sort == 'race' )
	{
		$query .= ' ORDER BY `race`, `name`, `leveldiff` DESC;';
	}
	elseif( $sort == 'class' )
	{
		$query .= ' ORDER BY `class`, `name`, `leveldiff` DESC;';
	}
	elseif( $sort == 'diff' )
	{
		$query .= ' ORDER BY `leveldiff` DESC, `name`;';
	}
	elseif( $sort == 'result' )
	{
		$query .= ' ORDER BY `win` DESC, `name`;';
	}
	elseif( $sort == 'zone' )
	{
		$query .= ' ORDER BY `zone`, `name`;';
	}
	elseif( $sort == 'subzone' )
	{
		$query .= ' ORDER BY `subzone`, `name`;';
	}
	elseif( $sort == 'date' )
	{
		$query .= ' ORDER BY `date` DESC, `name`;';
	}
	else
	{
		$query .= ' ORDER BY `date` DESC, `name`;';
	}

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$url = 'guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name']);

		if( $first )
		{
			$roster->tpl->assign_var('INFO_TEXT', sprintf($roster->locale->act['kill_lost_hist'],$row['name'],$row['race'],$row['class'],'<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])) . '">' . ( !empty($row['guild']) ? $row['guild'] : '(' . $roster->locale->act['unknown'] . ')' ) . '</a>') );

			tableHeaderRow(array(
				'<a href="' . makelink($url . '&amp;s=date') . '">' . $roster->locale->act['when'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=name') . '">' . $roster->locale->act['name'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=result') . '">' . $roster->locale->act['result'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=zone') . '">' . $roster->locale->act['zone'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=subzone') . '">' . $roster->locale->act['subzone'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=diff') . '">' . $roster->locale->act['leveldiff'] . '</a>',
				)
			);
			$first = false;
		}

		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'DATE'      => readbleDate($row['date']),
			'LINK'      => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['gn'],
			'WIN'       => ( $row['win'] == '1' ? true : false ),
			'ZONE'      => $row['zone'],
			'SUBZONE'   => $row['subzone'],
			'LEVELDIFF' => $row['leveldiff']
			)
		);
	}
	$roster->db->free_result($result);
}
elseif( $type == 'guildinfo' )
{
	$guild = ( isset($_GET['pvpguild']) ? $_GET['pvpguild'] : '' );
	$sort = ( isset($_GET['s']) ? $_GET['s'] : '' );

	$roster->tpl->assign_var('INFO_TEXT', sprintf($roster->locale->act['kill_lost_hist_guild'],($guild != '' ? stripslashes($guild) : '(' . $roster->locale->act['unknown'] . ')')) );

	$url = 'guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode(stripslashes($guild));

	tableHeaderRow(array(
		'<a href="' . makelink($url . '&amp;s=date') . '">' . $roster->locale->act['when'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=name') . '">' . $roster->locale->act['them'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=gn') . '">' . $roster->locale->act['us'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=result') . '">' . $roster->locale->act['result'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=zone') . '">' . $roster->locale->act['zone'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=subzone') . '">' . $roster->locale->act['subzone'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=diff') . '">' . $roster->locale->act['leveldiff'] . '</a>',
		)
	);

	$query = "SELECT `pvp`.*, `members`.`name` AS gn"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `pvp`.`guild` = '" . $guild . "'";

	if( $sort == 'name' )
	{
		$query .= ' ORDER BY `name`, `leveldiff` DESC, `guild`;';
	}
	elseif( $sort == 'gn' )
	{
		$query .= ' ORDER BY `gn`, `leveldiff` DESC;';
	}
	elseif( $sort == 'race' )
	{
		$query .= ' ORDER BY `race`, `name`, `leveldiff` DESC;';
	}
	elseif( $sort == 'class' )
	{
		$query .= ' ORDER BY `class`, `name`, `leveldiff` DESC;';
	}
	elseif( $sort == 'diff' )
	{
		$query .= ' ORDER BY `leveldiff` DESC, `name`;';
	}
	elseif( $sort == 'result' )
	{
		$query .= ' ORDER BY `win` DESC, `name`;';
	}
	elseif( $sort == 'zone' )
	{
		$query .= ' ORDER BY `zone`, `name`;';
	}
	elseif( $sort == 'subzone' )
	{
		$query .= ' ORDER BY `subzone`, `name`;';
	}
	elseif( $sort == 'date' )
	{
		$query .= ' ORDER BY `date` DESC, `name`;';
	}
	else
	{
		$query .= ' ORDER BY `date` DESC, `name`;';
	}

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while($row = $roster->db->fetch($result))
	{
		$roster->tpl->assign_block_vars('rows',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'DATE'      => readbleDate($row['date']),
			'LINK'      => makelink('guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name'])),
			'NAME'      => $row['name'],
			'GLINK'     => makelink('char-' . $addon['basename'] . '-pvp&amp;a=c:' . $row['member_id']),
			'GNAME'     => $row['gn'],
			'WIN'       => ( $row['win'] == '1' ? true : false ),
			'ZONE'      => $row['zone'],
			'SUBZONE'   => $row['subzone'],
			'LEVELDIFF' => $row['leveldiff']
			)
		);
	}
	$roster->db->free_result($result);
}

$roster->tpl->set_handle('body', $addon['basename'] . '/guild.html');
$roster->tpl->display('body');
