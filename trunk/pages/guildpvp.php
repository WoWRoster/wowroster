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
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$header_title = $roster->locale->act['pvplist'];
include_once (ROSTER_BASE.'roster_header.tpl');


$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');


if (isset($_GET['type']))
	$type = $_GET['type'];
else
	$type = 'guildwins';


$choiceArray = array(
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

$choiceForm = '<form action="'.makelink().'" method="get">
'.$roster->locale->act['pvplist'].':
<select name="type" onchange="window.location.href=this.options[this.selectedIndex].value">
';
foreach( $choiceArray as $item_value )
{
	if( $item_value != 'playerinfo' && $item_value != 'guildinfo' )
	{
		if( $type == $item_value )
			$choiceForm .= '  <option value="'.makelink('guildpvp&amp;type='.$item_value).'" selected="selected">'.$roster->locale->act[$item_value].'</option>'."\n";
		else
			$choiceForm .= '  <option value="'.makelink('guildpvp&amp;type='.$item_value).'">'.$roster->locale->act[$item_value].'</option>'."\n";
	}
}
$choiceForm .= '</select>
</form><br />';

print $choiceForm;


$striping_counter = 1;

$tableHeader = border('sgray','start',$roster->locale->act[$type]).'<table width="100%" cellspacing="0" class="bodyline">';

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
		print '<a href="'.makelink('guildpvp&amp;type=guildinfo&amp;guild='.urlencode($row['guild']).'">');
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
		print '<a href="'.makelink('guildpvp&amp;type=guildinfo&amp;guild='.urlencode($row['guild']).'">');
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
		$roster->locale->act['name'],
		$roster->locale->act['kills'],
		$roster->locale->act['guild'],
		$roster->locale->act['race'],
		$roster->locale->act['class'],
		$roster->locale->act['leveldiff'],
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
		print '<a href="'.makelink('guildpvp&amp;type=playerinfo&amp;player='.urlencode($row['name'])).'">';
		print($row['name']);
		print("</a></td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['guild'] == '')
			$guildname = '(unguilded)';
		else
			$guildname = '<a href="'.makelink('guildpvp&amp;type=guildinfo&amp;guild='.urlencode($row['guild'])).'">'.$row['guild'].'</a>';

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
		$roster->locale->act['name'],
		$roster->locale->act['kills'],
		$roster->locale->act['guild'],
		$roster->locale->act['race'],
		$roster->locale->act['class'],
		$roster->locale->act['leveldiff'],
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
		print '<a href="'.makelink('guildpvp&amp;type=playerinfo&amp;player='.urlencode($row['name'])).'">';

		print($row['name']);
		print("</a></td>\n");
		rankMid((($striping_counter % 2) +1));
		print($row['countg']);
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['guild'] == '')
			$guildname = '(unguilded)';
		else
			$guildname = '<a href="'.makelink('guildpvp&amp;type=guildinfo&amp;guild='.urlencode($row['guild'])).'">'.$row['guild'].'</a>';

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
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;start=0&amp;s=date').'">'.$row['gn']."</a></td>\n");
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
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;start=0&amp;s=date').'">'.$row['gn']."</a></td>\n");
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
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;start=0&amp;s=date').'">'.$row['gn']."</a></td>\n");
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
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;start=0&amp;s=date').'">'.$row['gn']."</a></td>\n");
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
	print('<br />'.$roster->locale->act['solo_win_loss'].'</small><br /><br />');
	print($tableHeader);

	$query = "SELECT members.name, IF(pvp3.win = '1', 1, 0) AS win, SUM(win) AS wtotal, COUNT(win) AS btotal FROM `".ROSTER_PVP2TABLE."` pvp3 LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = pvp3.member_id WHERE pvp3.leveldiff < 8 AND pvp3.leveldiff > -8 AND pvp3.enemy = '1' GROUP BY members.name ORDER BY wtotal DESC";
	$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

	while($row = $wowdb->fetch_array($result))
	{
		// Striping rows
		print "<tr>\n";

		// Increment counter so rows are colored alternately
		++$striping_counter;

		rankLeft((($striping_counter % 2) +1));
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;s=date').'">'.$row['name']."</a></td>\n");
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
		$url = 'guildpvp&amp;type=playerinfo&amp;player='.urlencode($player);

		if ($first)
		{
			print '<br />'.sprintf($roster->locale->act['kill_lost_hist'],$player,$row['race'],$row['class'],'<a href="'.makelink('guildpvp&amp;type=guildinfo&amp;guild='.urlencode($row['guild'])).'">'.$row['guild'].'</a>');
			print ('<br /><br />');

			print($tableHeader);
			tableHeaderRow(array(
				'<a href="'.makelink($url.'&amp;s=date').'">'.$roster->locale->act['when'].'</a>',
				'<a href="'.makelink($url.'&amp;s=name').'">'.$roster->locale->act['name'].'</a>',
				'<a href="'.makelink($url.'&amp;s=result').'">'.$roster->locale->act['result'].'</a>',
				'<a href="'.makelink($url.'&amp;s=zone').'">'.$roster->locale->act['zone2'].'</a>',
				'<a href="'.makelink($url.'&amp;s=subzone').'">'.$roster->locale->act['subzone'].'</a>',
				'<a href="'.makelink($url.'&amp;s=diff').'">'.$roster->locale->act['leveldiff'].'</a>',
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
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;s=date').'">'.$row['gn'].'</a>');
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['win'] == '1')
			$res = $roster->locale->act['win'];
		else
			$res = $roster->locale->act['loss'];

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

	print ('<br />');
	print sprintf($roster->locale->act['kill_lost_hist_guild'],$guild);
	print ('<br /><br />');

	print($tableHeader);

	$url = 'guildpvp&amp;type=guildinfo&amp;guild='.urlencode($guild);

	tableHeaderRow(array(
		'<a href="'.makelink($url.'&amp;s=date').'">'.$roster->locale->act['when'].'</a>',
		'<a href="'.makelink($url.'&amp;s=name').'">Them</a>',
		'<a href="'.makelink($url.'&amp;s=name').'">Us</a>',
		'<a href="'.makelink($url.'&amp;s=result').'">'.$roster->locale->act['result'].'</a>',
		'<a href="'.makelink($url.'&amp;s=zone').'">'.$roster->locale->act['zone2'].'</a>',
		'<a href="'.makelink($url.'&amp;s=subzone').'">'.$roster->locale->act['subzone'].'</a>',
		'<a href="'.makelink($url.'&amp;s=diff').'">'.$roster->locale->act['leveldiff'].'</a>',
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
		print('<a href="'.makelink('guildpvp&amp;type=playerinfo&amp;player='.urlencode($row['name'])).'">'.$row['name'].'</a>');
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		print('<a href="'.makelink('char-pvp&amp;member='.$row['member_id'].'&amp;s=date').'">'.$row['gn'].'</a>');
		print("</td>\n");
		rankMid((($striping_counter % 2) +1));
		if ($row['win'] == '1')
			$res = $roster->locale->act['win'];
		else
			$res = $roster->locale->act['loss'];

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

include_once (ROSTER_BASE.'roster_footer.tpl');
