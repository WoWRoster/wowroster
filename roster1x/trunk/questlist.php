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
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];


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
	global $wowdb, $zoneidsafe, $questidsafe;

	/*table, field, current option if matching to existing data (EG: $row['state'])
	and you want the drop down to be preselected on their current data, the id field from that table (EG: stateid)*/

	$sql = "SELECT ".$fieldtoget." FROM ".$table." ORDER BY quests.".$field." ASC";

	// Check SQL for debug only when changing
	//print $sql;

	// execute SQL query and get result
	$sql_result = $wowdb->query($sql) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$sql);

	// put data into drop-down list box
	while ($row = $wowdb->fetch_assoc($sql_result))
	{
		$id = $row["$fieldid"];//must leave double quote
		$optiontocompare = addslashes($row["$field"]);//must leave double quote
		$optiontodisplay = $row["$field"];//must leave double quote

		if ($current == $optiontocompare)
			$option_block .= "          <option value=\"$urltorun=$id\" selected>$optiontodisplay</option>\n";
		else
			$option_block .= "          <option value=\"$urltorun=$id\" >$optiontodisplay</option>\n";
	}
	// dump out the list
	return $option_block;
}


// The next two lines call the function SelectQuery and use it to populate and return the code that lists the dropboxes for quests and for zones
$option_blockzones = selectQuery("`".ROSTER_QUESTSTABLE."` quests,`".ROSTER_MEMBERSTABLE."` members WHERE quests.member_id = members.member_id","DISTINCT quests.zone","zone",$zoneidsafe,"zone","indexquests.php?zoneid");
$option_blockquests = selectQuery("`".ROSTER_QUESTSTABLE."` quests,`".ROSTER_MEMBERSTABLE."` members WHERE quests.member_id = members.member_id","DISTINCT quests.quest_name","quest_name",$questidsafe,"quest_name","indexquests.php?questid");

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

print border('sgray','start');
?>
<table bgcolor="#292929" cellspacing="0" cellpadding="4" border="0" class="bodyline">
  <tr>
    <td class="membersRow">
<?php
print $wordings[$roster_conf['roster_lang']]['search1'];

print('<br /><br />
      <form method="post" action="indexquests.php">
        '.$wordings[$roster_conf['roster_lang']]['search2'].':
        <br />
        <select name="zoneid" onchange="top.location.href=this.options[this.selectedIndex].value">
          <option value="">Not Selected....</option>
'.$option_blockzones.'
        </select><br /><br />
        '.$wordings[$roster_conf['roster_lang']]['search3'].'
        <br />
        <select name="questid" onchange="top.location.href=this.options[this.selectedIndex].value">
          <option value="">Not Selected....</option>
'.$option_blockquests.'
        </select>
      </form>');
?>
</td>
  </tr>
</table>
<?php
print border('sgray','end');

if (isset($zoneidsafe) or isset($questidsafe))
{
	$zquery = "SELECT DISTINCT `zone` FROM `".ROSTER_QUESTSTABLE."` WHERE `zone` = '".$zoneidsafe."' ORDER BY `zone`";

	$zresult = $wowdb->query($zquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$zquery);
	if ($roster_conf['sqldebug'])
		print ("<!--$query-->");

	while($zrow = $wowdb->fetch_array($zresult))
	{
		print('<h1>'.$zrow['zone']."</h1>\n");

		$qquery = "SELECT DISTINCT quest_name";
		$qquery .= " FROM `".ROSTER_QUESTSTABLE."`";
		$qquery .= " WHERE zone = '" .$zoneidsafe . "'";
		$qquery .= " ORDER BY quest_name";

		$qresult = $wowdb->query($qquery) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qquery);
		if ($roster_conf['sqldebug'])
		print ("<!--$query-->");

		while($qrow = $wowdb->fetch_array($qresult))
		{
			$query = "SELECT q.zone, q.quest_name, q.quest_level, p.name, p.server";
			$query .= " FROM `".ROSTER_QUESTSTABLE."` q, `".ROSTER_PLAYERSTABLE."` p";
			$query .= " WHERE q.zone = '" .$zoneidsafe . "' AND q.member_id = p.member_id AND q.quest_name = '" . addslashes($qrow['quest_name']) . "'";
			$query .= " ORDER BY q.zone, q.quest_name, q.quest_level, p.name";

			$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
			if ($roster_conf['sqldebug'])
				print ("<!--$query-->");

			$tableHeader = border('syellow','start','<h2>'.$qrow['quest_name'].'</h2>').
				'<table cellpadding="0" cellspacing="0" class="membersList">';

			$tableHeaderRow = '  <tr>
    <th class="membersHeader">Zone</th>
    <th class="membersHeader">Quest Name</th>
    <th class="membersHeader">Quest Level</th>
    <th class="membersHeaderRight">Member</th>
  </tr>';

			$tableFooter = '</table>'.border('syellow','end').'<br />';

			print($tableHeader);
			print($tableHeaderRow);

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
					print('<a href="javascript:void(0)"'.
						'onclick="w = window.open(\'char.php?name='.$row['name'].'&amp;server='.$row['server'].'&amp;action=character\',\'PopUp\',\'width=830,height=750,left=100,top=100,screenX=100,screenY=100,fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,directories=no,location=no\'); return true;"'.
						'>'.$row['name'].'</a>');
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

	if ($roster_conf['sqldebug'])
		print ("<!--$query-->");

	while($qnrow = $wowdb->fetch_array($qnresult))
	{
		print('<h1>'.$qnrow['quest_name'].'</h1><br />');

		$query = "SELECT q.zone, q.quest_name, q.quest_level, p.name, p.server";
		$query .= " FROM `".ROSTER_QUESTSTABLE."` q, `".ROSTER_PLAYERSTABLE."` p";
		$query .= " WHERE q.member_id = p.member_id AND q.quest_name = '" . addslashes($qnrow['quest_name'])  . "'";
		$query .= " ORDER BY q.zone, q.quest_name, q.quest_level, p.name";

		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		if ($roster_conf['sqldebug'])
			print ("<!--$query-->");

		$tableHeader = border('syellow','start').'<table cellpadding="0" cellspacing="0" class="membersList">';

		$tableHeaderRow = '  <tr>
    <th class="membersHeader">Member</th>
    <th class="membersHeader">Quest Level</th>
    <th class="membersHeaderRight">Zone</th>
  </tr>';

		$tableFooter = '</table>'.border('syellow','end');

		print($tableHeader);
		print($tableHeaderRow);

		while($row = $wowdb->fetch_array($result))
		{
			print('<tr>');

			// Increment counter so rows are colored alternately
			++$striping_counter;

			// Echoing cells w/ data
			print('<td class="membersRow'. (($striping_counter % 2) +1) .'">');
			if ($row['server'])
			{
				print('<a href="javascript:void(0)"'.
					'onClick="w = window.open(\'char.php?name='.$row['name'].'&amp;server='.$row['server'].'&amp;action=character\',\'PopUp\',\'width=650,height=750,left=200,top=200,screenX=200,screenY=200,fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,directories=no,location=no\'); return true;"'.
					'>'.$row['name'].'</a>');
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

?>