<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild_id from guild info check above
$guildId = $guild_info['guild_id'];

include_once (ROSTER_LIB.'menu.php');


if (isset($_GET['type']))
	$type = $_GET['type'];
else
	$type = 'guildwins';


$choiceArray = array(
	'guildwins' => 'Wins by Guild',
	'guildlosses' => 'Losses by Guild',
	'enemywins' => 'Wins by Enemy',
	'enemylosses' => 'Losses by Enemy',
	'purgewins' => 'Guild Member Kills',
	'purgelosses' => 'Guild Member Deaths',
	'purgeavewins' => 'Best Win/Level-Diff Average',
	'purgeavelosses' => 'Best Loss/Level-Diff Average',
	'pvpratio' => 'Solo Win/Loss Ratios',
	'playerinfo' => 'Player Info',
	'guildinfo' => 'Guild Info',
);

$choiceForm = '<form action="indexpvp.php" method="get">
'.$wordings[$roster_conf['roster_lang']]['pvplist'].':
<select name="type">
';
foreach( $choiceArray as $item_value => $item_print )
{
	if( $item_value != 'playerinfo' && $item_value != 'guildinfo' )
	{
		if( $type == $item_value )
			$choiceForm .= '<option value="'.$item_value.'" selected="selected">'.$item_print;
		else
			$choiceForm .= '<option value="'.$item_value.'">'.$item_print;
	}
}
$choiceForm .= '</select>
<input type="submit" value="Apply" />
</form><br />';

print $choiceForm;


$striping_counter = 1;

$tableHeader = border('sgray','start',$choiceArray[$type]).'<table width="100%" cellspacing="0" class="bodyline">';

function tableHeaderRow($th)
{
	$acount = 0;
	foreach ($th as $header)
	{
		++$acount;
		if ($acount == 1)
			print "  <tr>\n    <th class=\"membersHeader\">$header</th>\n";
		elseif ($acount == count($th))
			print '    <th class="membersHeaderRight">'.$header.'</th>'."\n";
		else
			print '    <th class="membersHeader">'.$header."</th>\n";
	}
}

$tableFooter = "</table>\n".border('sgray','end');

function rankLeft($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function rankMid($sc)
{
	print '    <td class="membersRow'.$sc.'">';
}
function rankRight($sc)
{
	print '    <td class="membersRowRight'.$sc.'">';
}

if ($type == 'guildwins')
{
	print($tableHeader);

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '1' AND enemy = '1' GROUP BY guild ORDER BY countg DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "  <tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="?type=guildinfo&amp;guild=');
		print urlencode($row['guild']);
		print('">');
		if ($row['guild'] == '')
			$guildname = '(unguilded)';
		else
			$guildname = $row['guild'];

		print($guildname);
		print("</a></td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if($type == 'guildlosses')
{
	print($tableHeader);

	$query = "SELECT guild, COUNT(guild) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '0' AND enemy = '1' GROUP BY guild ORDER BY countg DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="?type=guildinfo&amp;guild=');
		print urlencode($row['guild']);
		print('">');
		if ($row['guild'] == '')
			$guildname = '(unguilded)';
		else
			$guildname = $row['guild'];

		print($guildname);
		print("</a></td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'enemywins')
{
	print($tableHeader);
	tableHeaderRow(array(
		$wordings[$roster_conf['roster_lang']]['name'],
		$wordings[$roster_conf['roster_lang']]['kills'],
		$wordings[$roster_conf['roster_lang']]['guild'],
		$wordings[$roster_conf['roster_lang']]['race'],
		$wordings[$roster_conf['roster_lang']]['class'],
		$wordings[$roster_conf['roster_lang']]['leveldiff'],
	));

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) AS countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '1' AND enemy = '1' GROUP BY name ORDER BY countg DESC, leveldiff DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print('<tr class="membersRow'. (($striping_counter % 2) +1) ."\">\n");

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="?type=playerinfo&amp;player=');
		print urlencode($row['name']);
		print('">');
		print($row['name']);
		print("</a></td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['guild'] == '')
			$guildname = '(unguilded)';
		else
			$guildname = '<a href="?type=guildinfo&amp;guild='.urlencode($row['guild']).'">'.$row['guild'].'</a>';

		print($guildname);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['race']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['class']);
		print("</td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['leveldiff']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'enemylosses')
{
	print($tableHeader);
	tableHeaderRow(array(
		$wordings[$roster_conf['roster_lang']]['name'],
		$wordings[$roster_conf['roster_lang']]['kills'],
		$wordings[$roster_conf['roster_lang']]['guild'],
		$wordings[$roster_conf['roster_lang']]['race'],
		$wordings[$roster_conf['roster_lang']]['class'],
		$wordings[$roster_conf['roster_lang']]['leveldiff'],
	));

	$query = "SELECT name, guild, race, class, leveldiff, COUNT(name) as countg FROM `".ROSTER_PVP2TABLE."` WHERE win = '0' AND enemy = '1' GROUP BY name ORDER BY countg DESC, leveldiff DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="?type=playerinfo&amp;player=');
		print urlencode($row['name']);
		print('">');
		print($row['name']);
		print("</a></td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['guild'] == '')
			$guildname = '(unguilded)';
		else
			$guildname = '<a href="?type=guildinfo&amp;guild='.urlencode($row['guild']).'">'.$row['guild'].'</a>';

		print($guildname);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['race']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['class']);
		print("</td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['leveldiff']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'purgewins')
{
	print($tableHeader);

	$query = "SELECT pvp3.member_id, members.name AS gn, COUNT(pvp3.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE win = '1' AND enemy = '1' GROUP BY pvp3.member_id ORDER BY countg DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;start=0&amp;s=date">'.$row['gn']."</a></td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'purgelosses')
{
	print($tableHeader);

	$query = "SELECT pvp3.member_id, members.name AS gn, COUNT(pvp3.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE win = '0' AND enemy = '1' GROUP BY pvp3.member_id ORDER BY countg DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;start=0&amp;s=date">'.$row['gn']."</a></td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'purgeavewins')
{
	print($tableHeader);

	$query = "SELECT pvp3.member_id, members.name as gn, AVG(pvp3.`leveldiff`) as ave, COUNT(pvp3.member_id) as countg FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE win = '1' AND enemy = '1' GROUP BY pvp3.member_id ORDER BY ave DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;start=0&amp;s=date">'.$row['gn']."</a></td>\n");
		rankMid((($striping_counter % 2) +1));
		$ave = round($row['ave'], 2);
		if ($ave > 0)
			$ave = '+'.$ave;

		print($ave);
		rankRight((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'purgeavelosses')
{
	print($tableHeader);

	$query = "SELECT pvp3.member_id, members.name AS gn, AVG(pvp3.`leveldiff`) AS ave, COUNT(pvp3.member_id) AS countg FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE win = '0' AND enemy = '1' GROUP BY pvp3.member_id ORDER BY ave DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;start=0&amp;s=date">'.$row['gn']."</a></td>\n");
		rankMid((($striping_counter % 2) +1));
		$ave = round($row['ave'], 2);
		if ($ave > 0)
			$ave = '+'.$ave;

		print($ave);
		rankRight((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'pvpratio')
{
	print('<br /><small>Solo Win/Loss Ratios (Level differences -7 to +7 counted)</small><br /><br />');
	print($tableHeader);
	tableHeaderRow(array(
	$roster_conf['guild_name']." Member",
	'Ratio'
	));

	//$query = "SELECT member_id, name as gn, pvp_ratio FROM `players` WHERE 1 ORDER BY pvp_ratio DESC";
	$query = "SELECT members.name, IF(pvp3.win = '1', 1, 0) AS win, SUM(win) AS wtotal, COUNT(win) AS btotal FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE pvp3.leveldiff < 8 AND pvp3.leveldiff > -8 AND pvp3.enemy = '1' GROUP BY members.name ORDER BY wtotal DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['name'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;s=date">'.$row['name']."</a></td>\n");
		rankRight((($striping_counter % 2) +1));
		$wins = $row['wtotal'];
		$battles = $row ['btotal'];
		if ($wins == $battles)
			print 'Winless';
		elseif ($wins == 0)
			print 'Unbeaten';
		else
		{
			$ratio = round(($wins / ($battles-$wins)), 2);
			print "$ratio to 1";
		}
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'playerinfo')
{
	if (isset($_GET['player']))
		$player = $_GET['player'];
	else
		$player = '';

	if (isset($_GET['s']))
		$sort = $_GET['s'];
	else
		$sort = '';

	$first = true;
	$query = "SELECT pvp3.*, members.name AS gn FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE pvp3.name = '";
	$query = $query.$player;
	$query=$query.'\'';

	if ($sort == 'name')
		$query=$query." ORDER BY 'name', 'leveldiff' DESC, 'guild'";
	else if ($sort == 'race')
		$query=$query." ORDER BY 'race', 'name', 'leveldiff' DESC";
	else if ($sort == 'class')
		$query=$query." ORDER BY 'class', 'name', 'leveldiff' DESC";
	else if ($sort == 'diff')
		$query=$query." ORDER BY 'leveldiff' DESC, 'name' ";
	else if ($sort == 'result')
		$query=$query." ORDER BY 'win' DESC, 'name' ";
	else if ($sort == 'zone')
		$query=$query." ORDER BY 'zone', 'name' ";
	else if ($sort == 'subzone')
		$query=$query." ORDER BY 'subzone', 'name' ";
	else if ($sort == 'date')
		$query=$query." ORDER BY 'date' DESC, 'name' ";
	else
		$query=$query." ORDER BY 'date' DESC, 'name' ";


	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		$url = '<a href="?type=playerinfo&amp;player='.urlencode($player);

		if ($first)
		{
			print('<br /><small>Kill/Loss history for "');
			print ($player.'" ('.$row['race'].' '.$row['class'].') of <a href="?type=guildinfo&amp;guild='.urlencode($row['guild']).'">'.$row['guild'].'</a>');
			print ('</small><br /><br />');

			print($tableHeader);
			tableHeaderRow(array(
				$url.'&amp;s=date">'.$wordings[$roster_conf['roster_lang']]['when'].'</a>',
				$url.'&amp;s=name">'.$wordings[$roster_conf['roster_lang']]['name'].'</a>',
				$url.'&amp;s=result">'.$wordings[$roster_conf['roster_lang']]['result'].'</a>',
				$url.'&amp;s=zone">'.$wordings[$roster_conf['roster_lang']]['zone2'].'</a>',
				$url.'&amp;s=subzone">'.$wordings[$roster_conf['roster_lang']]['subzone'].'</a>',
				$url.'&amp;s=diff">'.$wordings[$roster_conf['roster_lang']]['leveldiff'].'</a>',
			));
			$first = false;
		}

		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print($row['date']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;s=date">'.$row['gn'].'</a>');
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['win'] == '1')
			$res = 'Win';
		else
			$res = 'Lose';

		print($res);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['zone']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['subzone'] == '')
			$szone = '&nbsp;';
		else
			$szone = $row['subzone'];

		print($szone);
		print("</td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['leveldiff']);
		print("</td>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
else if ($type == 'guildinfo')
{
	if (isset($_GET['guild']))
		$guild = $_GET['guild'];
	else
		$guild = '';

	if (isset($_GET['s']))
		$sort = $_GET['s'];
	else
		$sort = '';

	print('<br /><small>Kill/Loss history for Guild "');
	print ($guild);
	print ('"</small><br /><br />');
	print($tableHeader);
	$url = '<a href="?type=guildinfo&amp;guild='.urlencode($guild);
	tableHeaderRow(array(
		$url.'&amp;s=date">'.$wordings[$roster_conf['roster_lang']]['when'].'</a>',
		$url.'&amp;s=name">Them</a>',
		$url.'&amp;s=name">Us</a>',
		$url.'&amp;s=result">'.$wordings[$roster_conf['roster_lang']]['result'].'</a>',
		$url.'&amp;s=zone">'.$wordings[$roster_conf['roster_lang']]['zone2'].'</a>',
		$url.'&amp;s=subzone">'.$wordings[$roster_conf['roster_lang']]['subzone'].'</a>',
		$url.'&amp;s=diff">'.$wordings[$roster_conf['roster_lang']]['leveldiff'].'</a>',
	));


	$query = "SELECT pvp3.*, members.name AS gn FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE pvp3.guild = '";
	$query = $query.$guild;
	$query=$query.'\'';

	if ($sort == 'name')
		$query=$query." ORDER BY 'name', 'leveldiff' DESC, 'guild'";
	else if ($sort == 'race')
		$query=$query." ORDER BY 'race', 'name', 'leveldiff' DESC";
	else if ($sort == 'class')
		$query=$query." ORDER BY 'class', 'name', 'leveldiff' DESC";
	else if ($sort == 'diff')
		$query=$query." ORDER BY 'leveldiff' DESC, 'name' ";
	else if ($sort == 'result')
		$query=$query." ORDER BY 'win' DESC, 'name' ";
	else if ($sort == 'zone')
		$query=$query." ORDER BY 'zone', 'name' ";
	else if ($sort == 'subzone')
		$query=$query." ORDER BY 'subzone', 'name' ";
	else if ($sort == 'date')
		$query=$query." ORDER BY 'date' DESC, 'name' ";
	else
		$query=$query." ORDER BY 'date' DESC, 'name' ";

	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print($row['date']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print('<a href="?type=playerinfo&amp;player='.urlencode($row['name']).'">'.$row['name'].'</a>');
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print('<a href="char.php?name='.$row['gn'].'&amp;server='.$roster_conf['server_name'].'&amp;action=pvp&amp;s=date">'.$row['gn'].'</a>');
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['win'] == '1')
			$res = 'Win';
		else
			$res = 'Lose';

		print($res);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['zone']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['subzone'] == '')
			$szone = '&nbsp;';
		else
			$szone = $row['subzone'];

		print($szone);
		print("</td>\n");
		rankRight((($striping_counter % 2) +1));
		print($row['leveldiff']);
		print("</td>\n</tr>\n");
	}
	$wowdb->free_result($result);

	print($tableFooter);
}
?>