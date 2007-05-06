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

define('IN_SORTMEMBER',true);

include_once ($addon['dir'] . 'inc/memberslist.php');

$memberlist = new memberslist(array('group_alts'=>0));

$mainQuery =
	'SELECT *, DATE_FORMAT( `update_time`, "' . $roster->locale->act['timeformat'] . '" ) AS date, '.
	'UNIX_TIMESTAMP(`update_time`) AS date_stamp '.
	'FROM `'.ROSTER_MEMBERLOGTABLE.'` AS members '.
	'WHERE `guild_id` = "'.$roster->data['guild_id'].'"'.
	'ORDER BY ';


$FIELD['name'] = array(
	'lang_field' => 'name',
	'order'    => array( '`name` ASC' ),
	'order_d'    => array( '`name` DESC' ),
	'display' => 3,
);

$FIELD['class'] = array(
	'lang_field' => 'class',
	'order'    => array( '`class` ASC' ),
	'order_d'    => array( '`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'display' => $addon['config']['log_class'],
);

$FIELD['level'] = array(
	'lang_field' => 'level',
	'order_d'    => array( '`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'display' => $addon['config']['log_level'],
);

$FIELD['guild_title'] = array (
	'lang_field' => 'title',
	'order' => array( '`guild_rank` ASC' ),
	'order_d' => array( '`guild_rank` DESC' ),
	'jsort' => 'guild_rank',
	'display' => $addon['config']['log_gtitle'],
);

$FIELD['type'] = array (
	'lang_field' => 'type',
	'order' => array( '`type` ASC' ),
	'order_d' => array( '`type` DESC' ),
	'value' => 'type_value',
	'display' => $addon['config']['log_type'],
);

$FIELD['date'] = array (
	'lang_field' => 'date',
	'order' => array( 'date DESC' ),
	'order_d' => array( 'date ASC' ),
	'jsort' => 'date_stamp',
	'display' => $addon['config']['log_date'],
);

$FIELD['note'] = array (
	'lang_field' => 'note',
	'order' => array( 'nisnull','`note` ASC' ),
	'order_d' => array( 'nisnull','`note` DESC' ),
	'value' => 'note_value',
	'display' => $addon['config']['log_note'],
);

$FIELD['officer_note'] = array (
	'lang_field' => 'onote',
	'order' => array( 'onisnull','`note` ASC' ),
	'order_d' => array( 'onisnull','`note` DESC' ),
	'value' => 'note_value',
	'display' => $addon['config']['log_onote'],
);

$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$roster->output['html_head']  = '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';


// Start output
if( $addon['config']['log_update_inst'] )
{
	print '            <a href="#update"><font size="4">'.$roster->locale->act['update_link'].'</font></a><br /><br />';
}


if ( $addon['config']['log_motd'] == 1 )
{
	print $memberlist->makeMotd();
}

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster->output['show_menu'] = false;

echo "<table>\n  <tr>\n";

if ( $addon['config']['log_hslist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_LIB.'hslist.php');
	echo "    </td>\n";
}

if ( $addon['config']['log_pvplist'] == 1 )
{
	echo '    <td valign="top">';
	include_once( ROSTER_LIB.'pvplist.php');
	echo "    </td>\n";
}

echo "  </tr>\n</table>\n";

echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Print the update instructions
if( $addon['config']['log_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$roster->locale->act['update_instructions']);
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($roster->locale->act['update_instruct'], $roster->config['uploadapp'], $roster->locale->act['index_text_uniloader'], $roster->config['profiler'], makelink('update'), $roster->locale->act['lualocation']);

	if ($roster->config['pvp_log_allow'] == 1)
	{
		echo sprintf($roster->locale->act['update_instructpvp'], $roster->config['pvplogger']);
	}
	echo '</div>'.border('sgray','end');
}


/**
 * Controls Output of a Note Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function note_value ( $row, $field )
{
	global $roster, $addon;

	$tooltip='';
	if( !empty($row[$field]) )
	{
		$note = htmlspecialchars(nl2br($row[$field]));

		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster->config['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$roster->locale->act['note'],'',1,'',',WRAP').' alt="[]" />';
		}
	}
	else
	{
		$note = '&nbsp;';
		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster->config['img_url'].'no_note.gif" alt="[]" />';
		}
	}

	return '<div style="display:none; ">'.$row['note'].'</div>'.$note;
}


/**
 * Controls Output of a Type Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function type_value ( $row, $field )
{
	global $roster, $addon;

	if( $row['type'] == 0 )
	{
		$return = '<span class="red">' . $roster->locale->act['removed'] . '</span>';
	}
	else
	{
		$return = '<span class="green">' . $roster->locale->act['added'] . '</span>';
	}

	return '<div style="display:none; ">'.$row['type'].'</div>'.$return;
}
