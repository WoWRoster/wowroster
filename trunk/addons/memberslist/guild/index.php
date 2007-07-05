<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: pvp3.php 897 2007-05-06 00:35:11Z Zanix $
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

include_once ($addon['dir'] . 'inc/memberslist.php');

$memberlist = new memberslist;

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`level`, '.
	'`members`.`zone`, '.
	"(UNIX_TIMESTAMP( `members`.`last_online`)*1000+".($roster->config['localtimeoffset']*3600000).") AS 'last_online_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`members`.`last_online`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_online', ".
	'`members`.`note`, '.
	'`members`.`guild_title`, '.

	'`alts`.`main_id`, '.

	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`officer_note`, '.
	"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull', ".
	'`members`.`guild_rank`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`sex`, '.
	'`players`.`exp`, '.
	'`players`.`clientLocale`, '.

	'`players`.`lifetimeRankName`, '.
	'`players`.`lifetimeHighestRank`, '.
	"IF( `players`.`lifetimeHighestRank` IS NULL OR `players`.`lifetimeHighestRank` = '0', 1, 0 ) AS 'risnull', ".
	'`players`.`hearth`, '.
	"IF( `players`.`hearth` IS NULL OR `players`.`hearth` = '', 1, 0 ) AS 'hisnull', ".
	"UNIX_TIMESTAMP( `players`.`dateupdatedutc`) AS 'last_update_stamp', ".
	"DATE_FORMAT(  DATE_ADD(`players`.`dateupdatedutc`, INTERVAL ".$roster->config['localtimeoffset']." HOUR ), '".$roster->locale->act['timeformat']."' ) AS 'last_update_format', ".
	"IF( `players`.`dateupdatedutc` IS NULL OR `players`.`dateupdatedutc` = '', 1, 0 ) AS 'luisnull', ".

	'`proftable`.`professions` '.

	'FROM `'.$roster->db->table('members').'` AS members '.
	'LEFT JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	"LEFT JOIN (SELECT `member_id` , GROUP_CONCAT( CONCAT( `skill_name` , '|', `skill_level` ) ) AS 'professions' ".
		'FROM `'.$roster->db->table('skills').'` '.
		'GROUP BY `member_id`) AS proftable ON `members`.`member_id` = `proftable`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('alts',$addon['basename']).'` AS alts ON `members`.`member_id` = `alts`.`member_id` '.
	'WHERE `members`.`guild_id` = "'.$roster->data['guild_id'].'" '.
	'ORDER BY IF(`members`.`member_id` = `alts`.`member_id`,1,0), ';

$always_sort = ' `members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array (
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['class'] = array (
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'js_type' => 'ts_string',
	'display' => $addon['config']['member_class'],
);

$FIELD['level'] = array (
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['member_level'],
);

$FIELD['guild_title'] = array (
	'lang_field' => 'title',
	'order' => array( '`members`.`guild_rank` ASC' ),
	'order_d' => array( '`members`.`guild_rank` DESC' ),
	'js_type' => 'ts_number',
	'jsort' => 'guild_rank',
	'display' => $addon['config']['member_gtitle'],
);

$FIELD['lifetimeRankName'] = array (
	'lang_field' => 'currenthonor',
	'order' => array( 'risnull', '`players`.`lifetimeHighestRank` DESC' ),
	'order_d' => array( 'risnull', '`players`.`lifetimeHighestRank` ASC' ),
	'value' => array($memberlist,'honor_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['member_hrank'],
);

$FIELD['professions'] = array (
	'lang_field' => 'professions',
	'value' => 'tradeskill_icons',
	'js_type' => '',
	'display' => $addon['config']['member_prof'],
);

$FIELD['hearth'] = array (
	'lang_field' => 'hearthed',
	'order' => array( 'hisnull', 'hearth ASC' ),
	'order_d' => array( 'hisnull', 'hearth DESC' ),
	'js_type' => 'ts_string',
	'display' => $addon['config']['member_hearth'],
);

$FIELD['zone'] = array (
	'lang_field' => 'lastzone',
	'order' => array( '`members`.`zone` ASC' ),
	'order_d' => array( '`members`.`zone` DESC' ),
	'js_type' => 'ts_string',
	'display' => $addon['config']['member_zone'],
);

$FIELD['last_online'] = array (
	'lang_field' => 'lastonline',
	'order' => array( '`members`.`last_online` DESC' ),
	'order_d' => array( '`members`.`last_online` ASC' ),
	'value' => 'last_online_value',
	'js_type' => 'ts_date',
	'display' => $addon['config']['member_online'],
);

$FIELD['last_update_format'] = array (
	'lang_field' => 'lastupdate',
	'order' => array( 'luisnull','`players`.`dateupdatedutc` DESC' ),
	'order_d' => array( 'luisnull','`players`.`dateupdatedutc` ASC' ),
	'jsort' => 'last_update_stamp',
	'js_type' => 'ts_date',
	'display' => $addon['config']['member_update'],
);

$FIELD['note'] = array (
	'lang_field' => 'note',
	'order' => array( 'nisnull','`members`.`note` ASC' ),
	'order_d' => array( 'nisnull','`members`.`note` DESC' ),
	'value' => 'note_value',
	'js_type' => 'ts_string',
	'display' => $addon['config']['member_note'],
);

$FIELD['officer_note'] = array (
	'lang_field' => 'onote',
	'order' => array( 'onisnull','`members`.`note` ASC' ),
	'order_d' => array( 'onisnull','`members`.`note` DESC' ),
	'value' => 'note_value',
	'js_type' => 'ts_string',
	'display' => $addon['config']['member_onote'],
);

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

$roster->output['html_head'] .= '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';

// Start output
if( $addon['config']['member_update_inst'] )
{
	print '            <a href="' . makelink('#update') . '"><span style="font-size:20px;">'.$roster->locale->act['update_link'].'</span></a><br /><br />';
}


if ( $addon['config']['member_motd'] == 1 )
{
	print $memberlist->makeMotd();
}

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu($roster->output['show_menu']);
$roster->output['show_menu'] = false;

if( $addon['config']['member_hslist'] == 1 || $addon['config']['member_pvplist'] == 1 )
{
	echo "<table>\n  <tr>\n";

	if ( $addon['config']['member_hslist'] == 1 )
	{
		echo '    <td valign="top">';
		include_once( ROSTER_LIB.'hslist.php');
		echo generateHsList();
		echo "    </td>\n";
	}

	if ( $addon['config']['honor_pvplist'] == 1 && active_addon('pvplog') )
	{
		echo '    <td valign="top">';
		include_once( ROSTER_ADDONS.'pvplog'.DIR_SEP.'inc'.DIR_SEP.'pvplist.php');
		echo generatePvpList();
		echo "    </td>\n";
	}

	echo "  </tr>\n</table>\n";
}

echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Print the update instructions
if( $addon['config']['member_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$roster->locale->act['update_instructions']);
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($roster->locale->act['update_instruct'], $roster->config['uploadapp'], $roster->locale->act['index_text_uniloader'], $roster->config['profiler'], makelink('update'), $roster->locale->act['lualocation']);
	echo '</div>'.border('sgray','end');
}

/**
 * Controls Output of the Tradeskill Icons Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function tradeskill_icons ( $row )
{
	global $roster, $addon;

	$cell_value ='';

	// Don't proceed for characters without data
	if ($row['clientLocale'] == '')
	{
		return '&nbsp;';
	}

	$lang = $row['clientLocale'];

	$profs = explode(',',$row['professions']);
	foreach ( $profs as $prof )
	{
		$r_prof = explode('|',$prof);
		$toolTip = (isset($r_prof[1]) ? str_replace(':','/',$r_prof[1]) : '');
		$toolTiph = $r_prof[0];

		if( $r_prof[0] == $roster->locale->wordings[$lang]['riding'] )
		{
			if( $row['class']==$roster->locale->wordings[$lang]['Paladin'] || $row['class']==$roster->locale->wordings[$lang]['Warlock'] )
			{
				$icon = $roster->locale->wordings[$lang]['ts_ridingIcon'][$row['class']];
			}
			else
			{
				$icon = $roster->locale->wordings[$lang]['ts_ridingIcon'][$row['race']];
			}
		}
		else
		{
			$icon = isset($roster->locale->wordings[$lang]['ts_iconArray'][$r_prof[0]])?$roster->locale->wordings[$lang]['ts_iconArray'][$r_prof[0]]:'';
		}

		// Don't add professions we don't have an icon for. This keeps other skills out.
		if ($icon != '')
		{
			$icon = '<img class="membersRowimg" width="'.$addon['config']['icon_size'].'" height="'.$addon['config']['icon_size'].'" src="'.$roster->config['interface_url'].'Interface/Icons/'.$icon.'.'.$roster->config['img_suffix'].'" alt="" '.makeOverlib($toolTip,$toolTiph,'',2,'',',RIGHT,WRAP').' />';

			if( active_addon('info') )
			{
				$cell_value .= '<a href="' . makelink('char-info-recipes&amp;member=' . $row['member_id'] . '#' . strtolower(str_replace(' ','',$r_prof[0]))) . '">' . $icon . '</a>';
			}
			else
			{
				$cell_value .= $icon;
			}
		}
	}
	return $cell_value;
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
 * Controls Output of the Last Online Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function last_online_value ( $row )
{
	global $roster;

	if ( $row['last_online'] != '')
	{
		$guild_time = strtotime($roster->data['update_time']);
		$update_time = strtotime($row['last_online']);

		$difference = $guild_time - $update_time;

		$realtime = '<div style="display:none;">' . $update_time . '</div>';

		if( isset($row['online']) && $row['online'] == '1' || $difference < 0 )
		{
			return $realtime . $roster->locale->act['online_at_update'];
		}

		if( $difference < 60 )
		{
			return $realtime . sprintf(($difference == '1' ? $roster->locale->act['second'] : $roster->locale->act['seconds']),$difference);
		}
		else
		{
			$difference = round($difference / 60);
			if( $difference < 60 )
			{
				return $realtime . sprintf(($difference == '1' ? $roster->locale->act['minute'] : $roster->locale->act['minutes']),$difference);
			}
			else
			{
				$difference = round($difference / 60);
				if( $difference < 24 )
				{
					return $realtime . sprintf(($difference == '1' ? $roster->locale->act['hour'] : $roster->locale->act['hours']),$difference);
				}
				else
				{
					$difference = round($difference / 24);
					if( $difference < 7 )
					{
						return $realtime . sprintf(($difference == '1' ? $roster->locale->act['day'] : $roster->locale->act['days']),$difference);
					}
					else
					{
						$difference = round($difference / 7);
						if( $difference < 4 )
						{
							return $realtime . sprintf(($difference == '1' ? $roster->locale->act['week'] : $roster->locale->act['weeks']),$difference);
						}
						else
						{
							$difference = round($difference / 4);
							if( $difference < 12 )
							{
								return $realtime . sprintf(($difference == '1' ? $roster->locale->act['month'] : $roster->locale->act['months']),$difference);
							}
							else
							{
								$difference = round($difference / 12);
								return $realtime . sprintf(($difference == '1' ? $roster->locale->act['year'] : $roster->locale->act['years']),$difference);
							}

						}
					}
				}
			}
		}
	}
	else
	{
		return '&nbsp;';
	}
}
