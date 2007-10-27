<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Guild PvPLog stats
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
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

$choiceForm = '<form action="' . makelink() . '" method="get">
'.$roster->locale->act['pvplist'].':
<select name="type" onchange="window.location.href=this.options[this.selectedIndex].value">
';
foreach( $choiceArray as $item_value )
{
	if( $item_value != 'playerinfo' && $item_value != 'guildinfo' )
	{
		$display = ( isset($roster->locale->act[$item_value]) ? $roster->locale->act[$item_value] : $item_value );
		if( $type == $item_value )
		{
			$choiceForm .= '  <option value="' . makelink('guild-' . $addon['basename'] . '&amp;type=' . $item_value) . '" selected="selected">' . $display . "</option>\n";
		}
		else
		{
			$choiceForm .= '  <option value="' . makelink('guild-' . $addon['basename'] . '&amp;type=' . $item_value) . '">' . $display . "</option>\n";
		}
	}
}
$choiceForm .= '</select>
</form><br />';

print $choiceForm;


$striping_counter = 1;

$tableHeader = border('sgray','start',$roster->locale->act[$type]) . '<table width="100%" cellspacing="0" class="bodyline">';

function tableHeaderRow( $th )
{
	$acount = 0;
	$output = "\t<tr>\n";
	foreach( $th as $header )
	{
		++$acount;
		if( $acount == count($th) )
		{
			$output .= '		<th class="membersHeaderRight">' . $header . "</th>\n";
		}
		else
		{
			$output .= '		<th class="membersHeader">' . $header . "</th>\n";
		}
	}
	return $output . "\t</tr>\n";
}

$tableFooter = "</table>\n".border('sgray','end');

function rankLeft( $sc )
{
	print '		<td class="membersRow' . $sc . '">';
}
function rankMid( $sc )
{
	print '		<td class="membersRow' . $sc . '">';
}
function rankRight( $sc )
{
	print '		<td class="membersRowRight' . $sc . '">';
}

if( $type == 'guildwins' )
{
	print $tableHeader;

	$query = "SELECT `pvp`.`guild`, COUNT(`pvp`.`guild`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`guild`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "  <tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])) . '">';

		if( $row['guild'] == '' )
		{
			$guildname = '(' . $roster->locale->act['unknown'] . ')';
		}
		else
		{
			$guildname = $row['guild'];
		}

		print $guildname;
		print "</a></td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'guildlosses' )
{
	print $tableHeader;

	$query = "SELECT `pvp`.`guild`, COUNT(`pvp`.`guild`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`guild`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])) . '">';
		if( $row['guild'] == '' )
		{
			$guildname = '(' . $roster->locale->act['unknown'] . ')';
		}
		else
		{
			$guildname = $row['guild'];
		}

		print $guildname;
		print "</a></td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'enemywins' )
{
	print $tableHeader;

	print tableHeaderRow(array(
		$roster->locale->act['name'],
		$roster->locale->act['kills'],
		$roster->locale->act['guild'],
		$roster->locale->act['race'],
		$roster->locale->act['class'],
		$roster->locale->act['leveldiff'],
	));

	$query = "SELECT `pvp`.`name`, `pvp`.`guild`, `pvp`.`race`, `pvp`.`class`, `pvp`.`leveldiff`, COUNT(`pvp`.`name`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`name`"
		   . " ORDER BY countg DESC, `pvp`.`leveldiff` DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print '<tr class="membersRow' . (($striping_counter % 2) +1) . "\">\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name'])) . '">';
		print $row['name'];
		print "</a></td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		if ($row['guild'] == '')
		{
			$guildname = '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=') . '">(' . $roster->locale->act['unknown'] . ')</a>';
		}
		else
		{
			$guildname = '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])) . '">' . $row['guild'] . '</a>';
		}

		print $guildname;
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['race'];
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['class'];
		print "</td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['leveldiff'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'enemylosses' )
{
	print $tableHeader;
	print tableHeaderRow(array(
		$roster->locale->act['name'],
		$roster->locale->act['kills'],
		$roster->locale->act['guild'],
		$roster->locale->act['race'],
		$roster->locale->act['class'],
		$roster->locale->act['leveldiff'],
	));

	$query = "SELECT `pvp`.`name`, `pvp`.`guild`, `pvp`.`race`, `pvp`.`class`, `pvp`.`leveldiff`, COUNT(`pvp`.`name`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`name`"
		   . " ORDER BY countg DESC, `pvp`.`leveldiff` DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name'])) . '">';

		print $row['name'];
		print "</a></td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		if( $row['guild'] == '' )
		{
			$guildname = '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=') . '">(' . $roster->locale->act['unknown'] . ')</a>';
		}
		else
		{
			$guildname = '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])) . '">' . $row['guild'] . '</a>';
		}

		print $guildname;
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['race'];
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['class'];
		print "</td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['leveldiff'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'purgewins' )
{
	print $tableHeader;

	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['gn'] . "</a></td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'purgelosses' )
{
	print $tableHeader;

	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY countg DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['gn'] . "</a></td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'purgeavewins' )
{
	print $tableHeader;

	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '1' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY ave DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['gn'] . "</a></td>\n";
		rankMid((($striping_counter % 2) +1));
		$ave = round($row['ave'], 2);
		if( $ave > 0 )
		{
			$ave = '+'.$ave;
		}

		print $ave;
		rankRight((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'purgeavelosses' )
{
	print $tableHeader;

	$query = "SELECT `pvp`.`member_id`, `members`.`name` AS gn, AVG(`pvp`.`leveldiff`) AS ave, COUNT(`pvp`.`member_id`) AS countg"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`win` = '0' AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `pvp`.`member_id`"
		   . " ORDER BY ave DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['gn'] . "</a></td>\n";
		rankMid((($striping_counter % 2) +1));
		$ave = round($row['ave'], 2);
		if( $ave > 0 )
		{
			$ave = '+'.$ave;
		}

		print $ave;
		rankRight((($striping_counter % 2) +1));
		print $row['countg'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'pvpratio' )
{
	print '<br />'.$roster->locale->act['solo_win_loss'].'</small><br /><br />';
	print $tableHeader;

	$query = "SELECT `members`.`name`, `members`.`member_id`, IF(`pvp`.`win` = '1', 1, 0) AS win, SUM(`pvp`.`win`) AS wtotal, COUNT(`pvp`.`win`) AS btotal"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `members`.`guild_id` = '" . $roster->data['guild_id'] . "' AND `pvp`.`leveldiff` < 8 AND `pvp`.`leveldiff` > -8 AND `pvp`.`enemy` = '1'"
		   . " GROUP BY `members`.`name`"
		   . " ORDER BY wtotal DESC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['name'] . "</a></td>\n";
		rankRight((($striping_counter % 2) +1));
		$wins = $row['wtotal'];
		$battles = $row ['btotal'];
		if( $wins == $battles )
		{
			print 'Winless';
		}
		elseif( $wins == 0 )
		{
			print 'Unbeaten';
		}
		else
		{
			$ratio = round(($wins / ($battles-$wins)), 2);
			print "$ratio to 1";
		}
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print($tableFooter);
}
elseif( $type == 'playerinfo' )
{
	$player = ( isset($_GET['player']) ? stripslashes($_GET['player']) : '' );
	$sort = ( isset($_GET['s']) ? $_GET['s'] : '' );

	$first = true;
	$query = "SELECT `pvp`.*, `members`.`name` AS gn"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `pvp`.`name` = '" . $roster->db->escape($player) . "'";

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
		$url = 'guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($player);

		if( $first )
		{
			print '<br />' . sprintf($roster->locale->act['kill_lost_hist'],$player,$row['race'],$row['class'],'<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($row['guild'])) . '">' . ( !empty($row['guild']) ? $row['guild'] : '(' . $roster->locale->act['unknown'] . ')' ) . '</a>');
			print '<br /><br />';

			print $tableHeader;
			print tableHeaderRow(array(
				'<a href="' . makelink($url . '&amp;s=date') . '">' . $roster->locale->act['when'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=name') . '">' . $roster->locale->act['name'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=result') . '">' . $roster->locale->act['result'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=zone') . '">' . $roster->locale->act['zone'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=subzone') . '">' . $roster->locale->act['subzone'] . '</a>',
				'<a href="' . makelink($url . '&amp;s=diff') . '">' . $roster->locale->act['leveldiff'] . '</a>',
			));
			$first = false;
		}

		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print readbleDate($row['date']);
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['gn'] . "</a></td>\n";
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		if( $row['win'] == '1' )
		{
			$res = $roster->locale->act['win'];
		}
		else
		{
			$res = $roster->locale->act['loss'];
		}

		print $res;
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['zone'];
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		if( $row['subzone'] == '' )
		{
			$szone = '&nbsp;';
		}
		else
		{
			$szone = $row['subzone'];
		}

		print $szone;
		print "</td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['leveldiff'];
		print "</td>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
elseif( $type == 'guildinfo' )
{
	$guild = ( isset($_GET['pvpguild']) ? stripslashes($_GET['pvpguild']) : '' );
	$sort = ( isset($_GET['s']) ? $_GET['s'] : '' );

	print '<br />';
	print sprintf($roster->locale->act['kill_lost_hist_guild'],($guild ? $guild : '(' . $roster->locale->act['unknown'] . ')'));
	print '<br /><br />';

	print $tableHeader;

	$url = 'guild-' . $addon['basename'] . '&amp;type=guildinfo&amp;pvpguild=' . urlencode($guild);

	print tableHeaderRow(array(
		'<a href="' . makelink($url . '&amp;s=date') . '">' . $roster->locale->act['when'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=name') . '">Them</a>',
		'<a href="' . makelink($url . '&amp;s=name') . '">Us</a>',
		'<a href="' . makelink($url . '&amp;s=result') . '">' . $roster->locale->act['result'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=zone') . '">' . $roster->locale->act['zone'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=subzone') . '">' . $roster->locale->act['subzone'] . '</a>',
		'<a href="' . makelink($url . '&amp;s=diff') . '">' . $roster->locale->act['leveldiff'] . '</a>',
	));


	$query = "SELECT `pvp`.*, `members`.`name` AS gn"
		   . " FROM `" . $roster->db->table('pvp2',$addon['basename']) . "` AS pvp"
		   . " LEFT JOIN `" . $roster->db->table('members') . "` AS members ON `members`.`member_id` = `pvp`.`member_id`"
		   . " WHERE `pvp`.`guild` = '" . $roster->db->escape($guild) . "'";

	if( $sort == 'name' )
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

	while($row = $roster->db->fetch($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print readbleDate($row['date']);
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print '<a href="' . makelink('guild-' . $addon['basename'] . '&amp;type=playerinfo&amp;player=' . urlencode($row['name'])) . '">' . $row['name'] . '</a>';
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print '<a href="' . makelink('char-' . $addon['basename'] . '-pvp&amp;member=' . $row['member_id']) . '">' . $row['gn'] . "</a></td>\n";
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		if( $row['win'] == '1' )
		{
			$res = $roster->locale->act['win'];
		}
		else
		{
			$res = $roster->locale->act['loss'];
		}

		print $res;
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		print $row['zone'];
		print "</td>\n";
		rankMid((($striping_counter % 2) +1));
		if( $row['subzone'] == '' )
		{
			$szone = '&nbsp;';
		}
		else
		{
			$szone = $row['subzone'];
		}

		print $szone;
		print "</td>\n";
		rankRight((($striping_counter % 2) +1));
		print $row['leveldiff'];
		print "</td>\n</tr>\n";
	}
	$roster->db->free_result($result);

	print $tableFooter;
}
