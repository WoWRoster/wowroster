<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Lists quests for each character
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    QuestList
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['questlist'];

$zoneidsafe = ( isset($_GET['zoneid']) ? $roster->db->escape($_GET['zoneid']) : '' );
$questidsafe = ( isset($_GET['questid']) ? $roster->db->escape($_GET['questid']) : '' );


// The next two lines call the function selectQuery and use it to populate and return the code that lists the dropboxes for quests and for zones
$option_blockzones = selectQuery("`" . $roster->db->table('quests') . "` AS quests,`" . $roster->db->table('members') . "` AS members WHERE `members`.`server` = '" . $roster->data['server'] . "' AND `quests`.`member_id` = `members`.`member_id`","DISTINCT `quests`.`zone`",'zone',$zoneidsafe,'zone','&amp;zoneid');
$option_blockquests = selectQuery("`" . $roster->db->table('quests') . "` AS quests,`" . $roster->db->table('members') . "` AS members WHERE `members`.`server` = '" . $roster->data['server'] . "' AND `quests`.`member_id` = `members`.`member_id`","DISTINCT `quests`.`quest_name`",'quest_name',$questidsafe,'quest_name','&amp;questid');

$searchbox = $roster->locale->act['questlist_help'].'<br /><br />
	<form method="get" action="' . getFormAction() . '">
		<strong>' . $roster->locale->act['search_by_zone'] . '</strong>
		<br />
		<select name="zoneid" onchange="window.location.href=this.options[this.selectedIndex].value">
			<option value="">----------</option>
' . $option_blockzones . '
		</select>
		<br /><br />
		<strong>' . $roster->locale->act['search_by_quest'] . '</strong>
		<br />
		<select name="questid" onchange="window.location.href=this.options[this.selectedIndex].value">
			<option value="">----------</option>
' . $option_blockquests . '
		</select>
</form><br />';

print messagebox($searchbox,$roster->locale->act['questlist']);


if( !empty($zoneidsafe) )
{
	$zquery = "SELECT DISTINCT `zone` FROM `" . $roster->db->table('quests') . "` WHERE `zone` = '$zoneidsafe' ORDER BY `zone`;";
	$zresult = $roster->db->query($zquery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$zquery);

	while( $zrow = $roster->db->fetch($zresult) )
	{
		print '<div class="headline_1">' . $zrow['zone'] . "</div>\n";

		$qquery = "SELECT DISTINCT `quest_name`";
		$qquery .= " FROM `" . $roster->db->table('quests') . "`";
		$qquery .= " WHERE `zone` = '" . $zoneidsafe . "'";
		$qquery .= " ORDER BY `quest_name`;";

		$qresult = $roster->db->query($qquery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qquery);

		while( $qrow = $roster->db->fetch($qresult) )
		{
			$query = "SELECT `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `q`.`quest_tag`, `q`.`is_complete`, `p`.`name`, `p`.`server`, `p`.`member_id`, `p`.`level`"
			       . " FROM `" . $roster->db->table('quests') . "` AS q, `" . $roster->db->table('players') . "` AS p"
			       . " WHERE `p`.`server` = '" . $roster->data['server'] . "' AND `q`.`zone` = '" .$zoneidsafe . "' AND `q`.`member_id` = `p`.`member_id` AND `q`.`quest_name` = '" . addslashes($qrow['quest_name']) . "'"
			       . " ORDER BY `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `p`.`name`;";

			$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

			// Quest links
			$num_of_tips = (count($tooltips)+1);
			$linktip = '';
			foreach( $roster->locale->act['questlinks'] as $link )
			{
				$linktip .= '<a href="' . $link['url1'] . urlencode(utf8_decode($qrow['quest_name'])) . '" target="_blank">' . $link['name'] . '</a><br />';
			}
			setTooltip($num_of_tips,$linktip);
			setTooltip('questlink',$roster->locale->act['quest_links']);

			$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

			$tableHeader = border('syellow','start','<a href="#"' . $linktip . '>' . $qrow['quest_name'] . '</a>').
				'<table cellpadding="0" cellspacing="0" width="100%">';

			$tableHeaderRow = '  <tr>
    <th class="membersHeader">' . $roster->locale->act['name'] . '</th>
    <th class="membersHeader">' . $roster->locale->act['quest_data'] . '</th>
  </tr>';

			$tableFooter = '</table>' . border('syellow','end') . '<br />';

			print $tableHeader;
			print $tableHeaderRow;

			$striping_counter = 1;
			while( $row = $roster->db->fetch($result) )
			{
				print "<tr>\n";

				// Increment counter so rows are colored alternately
				++$striping_counter;

				$quest_tags = '';
				$tagstring = '&nbsp;';

				if( $row['quest_tag'] )
				{
					$quest_tags[] = $row['quest_tag'];
				}

				if( $row['is_complete'] == 1 )
				{
					$quest_tags[] = $roster->locale->act['complete'];
				}
				elseif( $row['is_complete'] == -1 )
				{
					$quest_tags[] = $roster->locale->act['failed'];
				}

				if( is_array($quest_tags) )
				{
					$tagstring = ' (' . implode(', ',$quest_tags) . ')';
				}

				// Echoing cells w/ data
				print '<td class="membersRow' . (($striping_counter % 2) +1) . '">';
				print ( active_addon('info') ? '<a href="'.makelink('char-info-quests&amp;member=' . $row['member_id']) . '" target="_blank">' . $row['level'] . ':' . $row['name'] . '</a>' : $row['level'] . ':' . $row['name'] );
				print '</td>';

				print '<td class="membersRow' . (($striping_counter % 2) +1) . '">' . $row['quest_level'] . $tagstring . '</td>';
				print "</tr>\n";
			}

			print $tableFooter;
			$roster->db->free_result($result);
		}
	}
}

if( !empty($questidsafe) )
{
	$qnquery = "SELECT DISTINCT `quest_name` FROM `" . $roster->db->table('quests') . "` WHERE `quest_name` = '" . $questidsafe . "' ORDER BY `quest_name`;";
	$qnresult = $roster->db->query($qnquery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qnquery);

	while( $qnrow = $roster->db->fetch($qnresult) )
	{
		// Quest links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';
		foreach( $roster->locale->act['questlinks'] as $link )
		{
			$linktip .= '<a href="' . $link['url1'] . urlencode(utf8_decode($qnrow['quest_name'])) . '" target="_blank">' . $link['name'] . '</a><br />';
		}
		setTooltip($num_of_tips,$linktip);
		setTooltip('questlink',$roster->locale->act['quest_links']);

		$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		print '<div class="headline_1"><a href="#"' . $linktip . '>' . $qnrow['quest_name'] . "</a></div>\n";

		$query = "SELECT `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `q`.`quest_tag`, `q`.`is_complete`, `p`.`name`, `p`.`server`, `p`.`member_id`, `p`.`level`"
		       . " FROM `" . $roster->db->table('quests') . "` AS q, `" . $roster->db->table('players') . "` AS p"
		       . " WHERE `p`.`server` = '" . $roster->data['server'] . "' AND `q`.`member_id` = `p`.`member_id` AND `q`.`quest_name` = '" . addslashes($qnrow['quest_name'])  . "'"
		       . " ORDER BY `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `p`.`name`;";

		$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

		$tableHeader = border('syellow','start') . '<table cellpadding="0" cellspacing="0">';

		$tableHeaderRow = '  <tr>
    <th class="membersHeader">' . $roster->locale->act['name'] . '</th>
    <th class="membersHeader">' . $roster->locale->act['quest_data'] . '</th>
    <th class="membersHeaderRight">' . $roster->locale->act['zone'] . '</th>
  </tr>';

		$tableFooter = '</table>' . border('syellow','end');

		print $tableHeader;
		print $tableHeaderRow;

		$striping_counter = 1;
		while( $row = $roster->db->fetch($result) )
		{
			print "<tr>\n";

			// Increment counter so rows are colored alternately
			++$striping_counter;

			$quest_tags = '';
			$tagstring = '&nbsp;';

			if( $row['quest_tag'] )
			{
				$quest_tags[] = $row['quest_tag'];
			}

			if( $row['is_complete'] == 1 )
			{
				$quest_tags[] = $roster->locale->act['complete'];
			}
			elseif( $row['is_complete'] == -1 )
			{
				$quest_tags[] = $roster->locale->act['failed'];
			}

			if( is_array($quest_tags) )
			{
				$tagstring = ' (' . implode(', ',$quest_tags) . ')';
			}

			// Echoing cells w/ data
			print '<td class="membersRow' . (($striping_counter % 2) +1) . '">';
			print ( active_addon('info') ? '<a href="'.makelink('char-info-quests&amp;member=' . $row['member_id']) . '" target="_blank">' . $row['level'] . ':' . $row['name'] . '</a>' : $row['level'] . ':' . $row['name'] );
			print '</td>';

			print '<td class="membersRow'. (($striping_counter % 2) +1) .'">' . $row['quest_level'] . $tagstring . '</td>';
			print '<td class="membersRowRight' . (($striping_counter % 2) +1) . '">';
			print $row['zone'];
			print '</td>';
			print "</tr>\n";
		}

		print $tableFooter;
		$roster->db->free_result($result);
	}
}


function selectQuery( $table , $fieldtoget , $field , $current , $fieldid , $urltorun )
{
	global $roster;

	/*table, field, current option if matching to existing data (EG: $row['state'])
	and you want the drop down to be preselected on their current data, the id field from that table (EG: stateid)*/

	$sql = "SELECT $fieldtoget FROM $table ORDER BY `quests`.$field ASC;";

	// execute SQL query and get result
	$sql_result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$sql);

	// put data into drop-down list box
	$option_block = '';
	while( $row = $roster->db->fetch($sql_result) )
	{
		$id = $row["$fieldid"]; // must leave double quote
		$optiontocompare = addslashes($row["$field"]); // must leave double quote
		$optiontodisplay = $row["$field"]; // must leave double quote

		if ($current == $optiontocompare)
		{
			$option_block .= '			<option value="' . makelink("$urltorun=$id") . '" selected="selected">' . $optiontodisplay . "</option>\n";
		}
		else
		{
			$option_block .= '			<option value="'  .makelink("$urltorun=$id") . '" >' . $optiontodisplay . "</option>\n";
		}
	}
	// dump out the list
	return $option_block;
}
