<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Lists quests for each character
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: index.php 1791 2008-06-15 16:59:24Z Zanix $
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
	)
);

$zoneid = ( isset($_GET['zoneid']) ? $_GET['zoneid'] : '' );
$questid = ( isset($_GET['questid']) ? $_GET['questid'] : '' );


// The next two lines call the function selectQuery and use it to populate and return the code that lists the dropboxes for quests and for zones
selectQuery('`' . $roster->db->table('quests') . '` AS quests,`' . $roster->db->table('members') . "` AS members WHERE `members`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `quests`.`member_id` = `members`.`member_id`", 'DISTINCT `quests`.`zone`',       'zone',       $zoneid,  '&amp;zoneid');
selectQuery('`' . $roster->db->table('quests') . '` AS quests,`' . $roster->db->table('members') . "` AS members WHERE `members`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `quests`.`member_id` = `members`.`member_id`", 'DISTINCT `quests`.`quest_name`', 'quest_name', $questid, '&amp;questid');


if( !empty($zoneid) )
{
	$sql = "SELECT DISTINCT `zone` FROM `" . $roster->db->table('quests') . "` WHERE `zone` = '$zoneid' ORDER BY `zone`;";
	$zone = $roster->db->query_first($sql) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$sql);

	// Set our questlink caption name
	setTooltip('questlink',$roster->locale->act['quest_links']);

	$roster->tpl->assign_vars(array(
		'S_SHOW'    => true,
		'ZONE_NAME' => $zone
		)
	);

	$qquery = "SELECT DISTINCT `quest_name`, `quest_level`"
			. " FROM `" . $roster->db->table('quests') . "`"
			. " WHERE `zone` = '" . $zoneid . "'"
			. " ORDER BY `quest_name`;";

	$qresult = $roster->db->query($qquery) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qquery);

	while( $qrow = $roster->db->fetch($qresult) )
	{
		$query = "SELECT `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `q`.`quest_tag`, `q`.`is_complete`, `p`.`name`, `p`.`server`, `p`.`member_id`, `p`.`level`"
		       . " FROM `" . $roster->db->table('quests') . "` AS q, `" . $roster->db->table('players') . "` AS p"
		       . " WHERE `p`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `q`.`zone` = '" .$zoneid . "' AND `q`.`member_id` = `p`.`member_id` AND `q`.`quest_name` = '" . addslashes($qrow['quest_name']) . "'"
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

		// Set template variables
		$roster->tpl->assign_block_vars('quests',array(
			'NAME'    => $qrow['quest_name'],
			'LEVEL'   => $qrow['quest_level'],
			'TOOLTIP' => ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"',
			)
		);

		while( $row = $roster->db->fetch($result) )
		{
			$quest_tags = $tagstring = '';
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

			// Set template variables
			$roster->tpl->assign_block_vars('quests.members',array(
				'ROW_CLASS' => $roster->switch_row_class(),
				'LINK'      => makelink('char-info-quests&amp;a=c:' . $row['member_id']),
				'NAME'      => $row['name'],
				'LEVEL'     => $row['level'],
				'TAGS'      => $tagstring
				)
			);
		}
		$roster->db->free_result($result);
	}
}

if( !empty($questid) )
{
	$sql = "SELECT DISTINCT `quest_name`, `quest_level`, `zone` FROM `" . $roster->db->table('quests') . "` WHERE `quest_name` = '" . $questid . "' ORDER BY `quest_name`;";
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
		$linktip .= '<a href="' . $link['url1'] . urlencode(utf8_decode($qnrow['quest_name'])) . '" target="_blank">' . $link['name'] . '</a><br />';
	}
	setTooltip($num_of_tips,$linktip);

	$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

	$roster->tpl->assign_block_vars('quests',array(
		'NAME'    => $qnrow['quest_name'],
		'LEVEL'   => $qnrow['quest_level'],
		'TOOLTIP' => ' onclick="return overlib(overlib_' . $num_of_tips . ',CAPTION,overlib_questlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"',
		)
	);

	$query = "SELECT `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `q`.`quest_tag`, `q`.`is_complete`, `p`.`name`, `p`.`server`, `p`.`member_id`, `p`.`level`"
	       . " FROM `" . $roster->db->table('quests') . "` AS q, `" . $roster->db->table('players') . "` AS p"
	       . " WHERE `p`.`server` = '" . $roster->db->escape($roster->data['server']) . "' AND `q`.`member_id` = `p`.`member_id` AND `q`.`quest_name` = '" . addslashes($qnrow['quest_name'])  . "'"
	       . " ORDER BY `q`.`zone`, `q`.`quest_name`, `q`.`quest_level`, `p`.`name`;";

	$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);

	while( $row = $roster->db->fetch($result) )
	{
		$quest_tags = $tagstring = '';
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

		// Set template variables
		$roster->tpl->assign_block_vars('quests.members',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'LINK'      => makelink('char-info-quests&amp;a=c:' . $row['member_id']),
			'NAME'      => $row['name'],
			'LEVEL'     => $row['level'],
			'TAGS'      => $tagstring
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
	$sql = "SELECT $fieldtoget FROM $table ORDER BY `quests`.$field ASC;";

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
			'VALUE'    => makelink("$urltorun=$id",true),
			'SELECTED' => ( stripslashes($current) == $optiontocompare ? true : false )
			)
		);
	}
}
