<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

$header_title = $act_words['quests'];
include_once (ROSTER_BASE.'roster_header.tpl');


if (isset($_GET['zoneid']))
{
	$zoneidsafe = stripslashes($_GET['zoneid']);
	$zoneidsafe = addslashes($zoneidsafe);
}

if (isset($_GET['questid']))
{
	$questidsafe = stripslashes($_GET['questid']);
	$questidsafe = addslashes($questidsafe);
}

function SelectQuery($table,$fieldtoget,$field,$current,$fieldid,$urltorun)
{
	global $wowdb;

	/*table, field, current option if matching to existing data (EG: $row['state'])
	and you want the drop down to be preselected on their current data, the id field from that table (EG: stateid)*/

	$sql = "SELECT ".$fieldtoget." FROM ".$table." ORDER BY quests.".$field." ASC";

	// Check SQL for debug only when changing
	//print $sql;

	// execute SQL query and get result
	$sql_result = $wowdb->query($sql) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$sql);

	// put data into drop-down list box
	$option_block = '';
	while ($row = $wowdb->fetch_assoc($sql_result))
	{
		$id = $row["$fieldid"];//must leave double quote
		$optiontocompare = addslashes($row["$field"]);//must leave double quote
		$optiontodisplay = $row["$field"];//must leave double quote

		if ($current == $optiontocompare)
			$option_block .= '          <option value="'.makelink("$urltorun=$id").'" selected>'.$optiontodisplay."</option>\n";
		else
			$option_block .= '          <option value="'.makelink("$urltorun=$id").'" >'.$optiontodisplay."</option>\n";
	}
	// dump out the list
	return $option_block;
}


// The next two lines call the function SelectQuery and use it to populate and return the code that lists the dropboxes for quests and for zones
$option_blockzones = selectQuery("`".ROSTER_QUESTSTABLE."` quests,`".ROSTER_MEMBERSTABLE."` members WHERE quests.member_id = members.member_id","DISTINCT quests.zone","zone",(isset($zoneidsafe) ? $zoneidsafe : ''),"zone","questlist&amp;zoneid");
$option_blockquests = selectQuery("`".ROSTER_QUESTSTABLE."` quests,`".ROSTER_MEMBERSTABLE."` members WHERE quests.member_id = members.member_id","DISTINCT quests.quest_name","quest_name",(isset($questidsafe) ? $questidsafe : ''),"quest_name","questlist&amp;questid");

// Don't forget the menu !!
include_once(ROSTER_LIB.'menu.php');
print("<br />\n");

echo "<table cellspacing=\"6\">\n  <tr>\n";
echo '    <td valign="top">';
include_once(ROSTER_LIB.'search_thot.php');
echo "    </td>\n";

echo '    <td valign="top">';
include_once(ROSTER_LIB.'search_alla.php');
echo "    </td>\n";
echo "  </tr>\n</table>\n";

print("<br />\n");

print border('sgray','start',$act_words['team']);
?>
<table bgcolor="#292929" cellspacing="0" cellpadding="4" border="0" class="bodyline">
  <tr>
    <td class="membersRow">
<?php
print $act_words['search1'];

print('<br /><br />
      <form method="post" action="'.makelink('questlist').'">
        '.$act_words['search2'].':
        <br />
        <select name="zoneid" onchange="window.location.href=this.options[this.selectedIndex].value">
          <option value="">----------</option>
'.$option_blockzones.'
        </select><br /><br />
        '.$act_words['search3'].'
        <br />
        <select name="questid" onchange="window.location.href=this.options[this.selectedIndex].value">
          <option value="">----------</option>
'.$option_blockquests.'
        </select>
      </form>');
?>
</td>
  </tr>
</table>
<?php
print border('sgray','end');

if (isset($zoneidsafe))
{
	$zquery = "SELECT DISTINCT `zone` FROM `".ROSTER_QUESTSTABLE."` WHERE `zone` = '".$zoneidsafe."' ORDER BY `zone`";

	$zresult = $wowdb->query($zquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$zquery);

	while($zrow = $wowdb->fetch_array($zresult))
	{
		print('<div class="headline_1">'.$zrow['zone']."</div>\n");

		$qquery = "SELECT DISTINCT quest_name";
		$qquery .= " FROM `".ROSTER_QUESTSTABLE."`";
		$qquery .= " WHERE zone = '" .$zoneidsafe . "'";
		$qquery .= " ORDER BY quest_name";

		$qresult = $wowdb->query($qquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qquery);

		while($qrow = $wowdb->fetch_array($qresult))
		{
			$query = "SELECT q.zone, q.quest_name, q.quest_level, p.name, p.server, p.member_id";
			$query .= " FROM `".ROSTER_QUESTSTABLE."` q, `".ROSTER_PLAYERSTABLE."` p";
			$query .= " WHERE q.zone = '" .$zoneidsafe . "' AND q.member_id = p.member_id AND q.quest_name = '" . addslashes($qrow['quest_name']) . "'";
			$query .= " ORDER BY q.zone, q.quest_name, q.quest_level, p.name";

			$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

			$tableHeader = border('syellow','start',$qrow['quest_name']).
				'<table cellpadding="0" cellspacing="0">';

			$tableHeaderRow = '  <tr>
    <th class="membersHeader">Zone</th>
    <th class="membersHeader">Quest Name</th>
    <th class="membersHeader">Quest Level</th>
    <th class="membersHeaderRight">Member</th>
  </tr>';

			$tableFooter = '</table>'.border('syellow','end').'<br />';

			print($tableHeader);
			print($tableHeaderRow);

			$striping_counter = 0;
			while($row = $wowdb->fetch_array($result))
			{
				print('<tr>');

				// Increment counter so rows are colored alternately
				++$striping_counter;

				// Echoing cells w/ data
				print('<td class="membersRow'. (($striping_counter % 2) +1) .'">');
				print($row['zone']);
				print('</td>');

				print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['quest_name'].'</td>');
				print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['quest_level'].'</td>');
				print('<td class="membersRowRight'. (($striping_counter % 2) +1) .'">');
				if ($row['server'])
				{
					print('<a href="'.makelink('char&amp;member='.$row['member_id']).'" target="_blank">'.$row['name'].'</a>');
				}
				else
					print($row['name']);

				print('</td>');
				print("</tr>\n");
			}

			print($tableFooter);
			$wowdb->free_result($result);
		}
	}
}

if (isset($questidsafe))
{
	$qnquery = "SELECT DISTINCT `quest_name` FROM `".ROSTER_QUESTSTABLE."` WHERE `quest_name` = '" .$questidsafe. "' ORDER BY `quest_name`";
	$qnresult = $wowdb->query($qnquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qnquery);

	while($qnrow = $wowdb->fetch_array($qnresult))
	{
		print('<div class="headline_1">'.$qnrow['quest_name']."</div>\n");

		$query = "SELECT q.zone, q.quest_name, q.quest_level, p.name, p.server, p.member_id";
		$query .= " FROM `".ROSTER_QUESTSTABLE."` q, `".ROSTER_PLAYERSTABLE."` p";
		$query .= " WHERE q.member_id = p.member_id AND q.quest_name = '" . addslashes($qnrow['quest_name'])  . "'";
		$query .= " ORDER BY q.zone, q.quest_name, q.quest_level, p.name";

		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

		$tableHeader = border('syellow','start').'<table cellpadding="0" cellspacing="0">';

		$tableHeaderRow = '  <tr>
    <th class="membersHeader">Member</th>
    <th class="membersHeader">Quest Level</th>
    <th class="membersHeaderRight">Zone</th>
  </tr>';

		$tableFooter = '</table>'.border('syellow','end');

		print($tableHeader);
		print($tableHeaderRow);

		$striping_counter = 0;
		while($row = $wowdb->fetch_array($result))
		{
			print('<tr>');

			// Increment counter so rows are colored alternately
			++$striping_counter;

			// Echoing cells w/ data
			print('<td class="membersRow'. (($striping_counter % 2) +1) .'">');
			if ($row['server'])
			{
				print('<a href="'.makelink('char&amp;member='.$row['member_id']).'" target="_blank">'.$row['name'].'</a>');
			}
			else
				print($row['name']);

			print('</td>');
			print('<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['quest_level'].'</td>');
			print('<td class="membersRowRight'. (($striping_counter % 2) +1) .'">');
			print($row['zone']);
			print('</td>');
			print("</tr>\n");
		}

		print($tableFooter);
		$wowdb->free_result($result);
	}
}

include_once (ROSTER_BASE.'roster_footer.tpl');
