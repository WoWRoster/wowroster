<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Lists quests for each character
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    QuestList
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['questlist'];

$roster->tpl->assign_vars(array(
	'S_INFO_ADDON' => active_addon('info'),
	'S_SHOW'       => false,

	'L_QUEST_LIST'      => $roster->locale->act['questlist'],
	'L_QUEST_LIST_HELP' => $roster->locale->act['questlist_help'],
	'L_SEARCH_ZONE'     => $roster->locale->act['search_by_zone'],
	'L_SEARCH_QUEST'    => $roster->locale->act['search_by_quest'],
	'L_NAME'            => $roster->locale->act['name'],
	'L_QUEST_DATA'      => $roster->locale->act['quest_data'],
	'L_COMPLETE'        => $roster->locale->act['complete'],
	'L_FAILED'          => $roster->locale->act['failed'],
	'L_DAILY'           => $roster->locale->act['daily'],
	)
);

$zoneid = ( isset($_GET['zoneid']) ? $_GET['zoneid'] : '' );
$questid = ( isset($_GET['questid']) ? $_GET['questid'] : '' );


// The next two lines call the function selectQuery and use it to populate and return the code that lists the dropboxes for quests and for zones
selectQuery('`' . $roster->db->table('quests') . "` AS quests LEFT JOIN `" . $roster->db->table('quest_data') . "` AS quest_data ON `quests`.`quest_id` = `quest_data`.`quest_id` LEFT JOIN `" . $roster->db->table('players') . "` AS players ON `players`.`member_id` = `quests`.`member_id` WHERE `players`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `quests`.`member_id` = `players`.`member_id`", 'DISTINCT `quest_data`.`zone`',       'zone',       $zoneid,  '&amp;zoneid');
selectQuery('`' . $roster->db->table('quests') . "` AS quests LEFT JOIN `" . $roster->db->table('quest_data') . "` AS quest_data ON `quests`.`quest_id` = `quest_data`.`quest_id` LEFT JOIN `" . $roster->db->table('players') . "` AS players ON `players`.`member_id` = `quests`.`member_id` WHERE `players`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `quests`.`member_id` = `players`.`member_id`", 'DISTINCT `quest_data`.`quest_name`', 'quest_name', $questid, '&amp;questid');

if( !empty($zoneid) )
{
	$sql = "SELECT DISTINCT `zone` FROM `" . $roster->db->table('quest_data') . "` WHERE `zone` = '$zoneid' ORDER BY `zone`;";
	$zone = $roster->db->query_first($sql) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$sql);

	// Set our questlink caption name
	setTooltip('questlink',$roster->locale->act['quest_links']);

	$roster->tpl->assign_vars(array(
		'S_SHOW'    => true,
		'ZONE_NAME' => $zone
		)
	);

	$qquery = "SELECT *"
			. " FROM `" . $roster->db->table('quest_data') . "`"
			. " WHERE `zone` = '" . $zoneid . "'"
			. " ORDER BY `quest_name`;";

	$qresult = $roster->db->query($qquery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qquery);

	while( $qrow = $roster->db->fetch($qresult) )
	{
		$query = "SELECT `q`.*, `p`.`name`, `p`.`server`, `p`.`member_id`, `p`.`level`"
		       . " FROM `" . $roster->db->table('quests') . "` AS q, `" . $roster->db->table('players') . "` AS p"
		       . " WHERE `p`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `q`.`quest_id` = '" . $qrow['quest_id'] . "' AND `q`.`member_id` = `p`.`member_id`"
		       . " ORDER BY `p`.`level` DESC, `p`.`name` ASC;";

		$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

		// Quest links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';
		foreach( $roster->locale->act['questlinks'] as $link )
		{
			$linktip .= '<a href="' . sprintf($link['url'],$qrow['quest_id']) . '" target="_blank">' . $link['name'] . '</a><br />';
		}
		setTooltip($num_of_tips,$linktip);

		// Set template variables
		$roster->tpl->assign_block_vars('quests',array(
			'ID'      => $qrow['quest_id'],
			'NAME'    => $qrow['quest_name'],
			'LEVEL'   => $qrow['quest_level'],
			'TAG'     => $qrow['quest_tag'],
			'GROUP'   => $qrow['group'],
			'DAILY'   => $qrow['daily'],
			'TOOLTIP' => ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"',
			)
		);

		while( $row = $roster->db->fetch($result) )
		{
			// Set template variables
			$roster->tpl->assign_block_vars('quests.members',array(
				'ROW_CLASS' => $roster->switch_row_class(),
				'LINK'      => makelink('char-info-quests&amp;a=c:' . $row['member_id']),
				'NAME'      => $row['name'],
				'LEVEL'     => $row['level'],
				'COMPLETE'  => $row['is_complete'],
				)
			);
		}
		$roster->db->free_result($result);
	}
}

if( !empty($questid) )
{
	$sql = "SELECT * FROM `" . $roster->db->table('quest_data') . "` WHERE `quest_name` = '" . $questid . "';";
	$result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$sql);

	// Set our questlink caption name
	setTooltip('questlink',$roster->locale->act['quest_links']);

	$qnrow = $roster->db->fetch($result,SQL_ASSOC);

	$roster->tpl->assign_vars(array(
		'S_SHOW'    => true,
		'ZONE_NAME' => $qnrow['zone']
		)
	);

	// Quest links
	$num_of_tips = (count($tooltips)+1);
	$linktip = '';
	foreach( $roster->locale->act['questlinks'] as $link )
	{
		$linktip .= '<a href="' . sprintf($link['url'],$qnrow['quest_id']) . '" target="_blank">' . $link['name'] . '</a><br />';
	}
	setTooltip($num_of_tips,$linktip);

	$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

	$roster->tpl->assign_block_vars('quests',array(
		'ID'      => $qnrow['quest_id'],
		'NAME'    => $qnrow['quest_name'],
		'LEVEL'   => $qnrow['quest_level'],
		'TAG'     => $qnrow['quest_tag'],
		'GROUP'   => $qnrow['group'],
		'DAILY'   => $qnrow['daily'],
		'TOOLTIP' => ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"',
		)
	);

	$query = "SELECT `q`.*, `p`.`name`, `p`.`server`, `p`.`member_id`, `p`.`level`"
	       . " FROM `" . $roster->db->table('quests') . "` AS q, `" . $roster->db->table('players') . "` AS p"
	       . " WHERE `p`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `q`.`quest_id` = '" . $qnrow['quest_id'] . "' AND `q`.`member_id` = `p`.`member_id`"
	       . " ORDER BY `p`.`level` DESC, `p`.`name` ASC;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		// Set template variables
		$roster->tpl->assign_block_vars('quests.members',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-info-quests&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['name'],
			'LEVEL'     => $row['level'],
			'COMPLETE'  => $row['is_complete'],
			)
		);
	}
	$roster->db->free_result($result);
}

$roster->tpl->set_handle('body', $addon['basename'] . '/questlist.html');
$roster->tpl->display('body');




function selectQuery( $table , $fieldtoget , $field , $current , $urltorun )
{
	global $roster;

	/**
	 * table, field, current option if matching to existing data (EG: $row['state'])
	 * and you want the drop down to be preselected on their current data, the id field from that table (EG: stateid)
	 */
	$sql = "SELECT $fieldtoget FROM $table ORDER BY `quest_data`.$field ASC;";

	// execute SQL query and get result
	$sql_result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$sql);

	// put data into drop-down list box
	while( $row = $roster->db->fetch($sql_result) )
	{
		$id = rawurlencode($row["$field"]); // must leave double quote
		$optiontocompare = $row["$field"]; // must leave double quote
		$optiontodisplay = $row["$field"]; // must leave double quote

		$roster->tpl->assign_block_vars($field . '_list',array(
			'NAME'     => $optiontodisplay,
			'VALUE'    => makelink("$urltorun=$id", true),
			'SELECTED' => ( stripslashes($current) == $optiontocompare ? true : false )
			)
		);
	}
}
