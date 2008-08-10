<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include_once ($addon['inc_dir'] . 'memberslist.php');

$options['template'] = $addon['basename'] . '/index.html';
$options['group_alts'] = false;

$memberlist = new memberslist($options);

$mainQuery =
	'SELECT '.
	'`members`.`member_id`, '.
	'`members`.`name`, '.
	'`members`.`class`, '.
	'`members`.`classid`, '.
	'`members`.`level`, '.
	'`members`.`note`, '.
	'`members`.`guild_title`, '.

	'`guild`.`update_time`, '.

	"IF( `members`.`note` IS NULL OR `members`.`note` = '', 1, 0 ) AS 'nisnull', ".
	'`members`.`officer_note`, '.
	"IF( `members`.`officer_note` IS NULL OR `members`.`officer_note` = '', 1, 0 ) AS 'onisnull', ".
	'`members`.`guild_rank`, '.

	'`players`.`server`, '.
	'`players`.`race`, '.
	'`players`.`raceid`, '.
	'`players`.`sex`, '.
	'`players`.`sexid`, '.
	'`players`.`clientLocale`, '.

	"GROUP_CONCAT( DISTINCT CONCAT( `proftable`.`skill_name` , '|', `proftable`.`skill_level` ) ORDER BY `proftable`.`skill_order`) as professions, ".
	"GROUP_CONCAT( DISTINCT CONCAT( `talenttable`.`tree` , '|', `talenttable`.`pointsspent` , '|', `talenttable`.`background` ) ORDER BY `talenttable`.`order`) AS 'talents' ".

	'FROM `'.$roster->db->table('members').'` AS members '.
	'LEFT JOIN `'.$roster->db->table('players').'` AS players ON `members`.`member_id` = `players`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('skills').'` AS proftable ON `members`.`member_id` = `proftable`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('talenttree').'` AS talenttable ON `members`.`member_id` = `talenttable`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('guild').'` AS guild ON `members`.`guild_id` = `guild`.`guild_id` '.
	'WHERE `members`.`guild_id` = "'.$roster->data['guild_id'].'" '.
	'GROUP BY `members`.`member_id` '.
	'ORDER BY ';

$always_sort = '`members`.`level` DESC, `members`.`name` ASC';

$FIELD['name'] = array (
	'lang_field' => 'name',
	'order'    => array( '`members`.`name` ASC' ),
	'order_d'    => array( '`members`.`name` DESC' ),
	'value' => array($memberlist,'name_value'),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['level'] = array (
	'lang_field' => 'level',
	'order_d'    => array( '`members`.`level` ASC' ),
	'value' => array($memberlist,'level_value'),
	'js_type' => 'ts_number',
	'display' => $addon['config']['default_level'],
);

$FIELD['race'] = array (
	'lang_field' => 'race',
	'order'    => array( '`members`.`race` ASC' ),
	'order_d'    => array( '`members`.`race` DESC' ),
	'value' => 'race_value',
	'js_type' => 'ts_string',
	'display' => $addon['config']['default_race'],
);

$FIELD['class'] = array (
	'lang_field' => 'class',
	'order'    => array( '`members`.`class` ASC' ),
	'order_d'    => array( '`members`.`class` DESC' ),
	'value' => array($memberlist,'class_value'),
	'js_type' => 'ts_string',
	'display' => $addon['config']['default_class'],
);

$FIELD['professions'] = array (
	'lang_field' => 'professions',
	'value' => 'tradeskill_icons',
	'js_type' => '',
	'display' => $addon['config']['default_prof'],
);

$FIELD['guild_title'] = array (
	'lang_field' => 'title',
	'order' => array( '`members`.`guild_rank` ASC' ),
	'order_d' => array( '`members`.`guild_rank` DESC' ),
	'js_type' => 'ts_number',
	'jsort' => 'guild_rank',
	'display' => $addon['config']['default_gtitle'],
);

$FIELD['note'] = array (
	'lang_field' => 'note',
	'order' => array( 'nisnull','`members`.`note` ASC' ),
	'order_d' => array( 'nisnull','`members`.`note` DESC' ),
	'value' => 'note_value',
	'js_type' => 'ts_string',
	'display' => $addon['config']['default_note'],
);

$FIELD['officer_note'] = array (
	'lang_field' => 'onote',
	'order' => array( 'onisnull','`members`.`note` ASC' ),
	'order_d' => array( 'onisnull','`members`.`note` DESC' ),
	'value' => 'note_value',
	'js_type' => 'ts_string',
	'display' => $addon['config']['default_onote'],
);

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

if ( $addon['config']['default_motd'] == 1 )
{
	$roster->tpl->assign_var('MOTD',$roster->data['guild_motd']);
}

// Assign some variables
$roster->tpl->assign_vars(array(
	'S_HSLIST' => (bool)$addon['config']['default_hslist'],
	'S_PVPLOG' => ( active_addon('pvplog') && $addon['config']['honor_pvplist'] == 1 ? true : false ),

	'ML_GUILD_NAME' => $roster->data['guild_name'],
	'ML_FACTION'    => $roster->data['faction'],
	'ML_FACTION_EN' => $roster->data['factionEn'],
	'ML_REALM'      => $roster->data['server'],
	'NUM_MEMBERS'   => $roster->data['guild_num_members'],
	)
);

if( $addon['config']['default_hslist'] == 1 || $addon['config']['default_pvplist'] == 1 )
{
	if ( $addon['config']['default_hslist'] == 1 )
	{
		include_once( ROSTER_LIB . 'hslist.php');
		generateHsList(false);
	}

	if ( active_addon('pvplog') && $addon['config']['honor_pvplist'] == 1 )
	{
		include_once( ROSTER_ADDONS . 'pvplog' . DIR_SEP . 'inc' . DIR_SEP . 'pvplist.php');
		generatePvpList(false);
	}
}

$memberlist->makeToolBar('horizontal');

echo $memberlist->makeMembersList();

// Print the update instructions

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
		return '<div>&nbsp;</div>';
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
				$cell_value .= '<a href="' . makelink('char-info-recipes&amp;a=c:' . $row['member_id'] . '#' . strtolower(str_replace(' ','',$r_prof[0]))) . '">' . $icon . '</a>';
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
			$value = '<img src="'.$roster->config['theme_path'].'/images/note.gif" style="cursor:help;" '.makeOverlib($note,$roster->locale->act['note'],'',1,'',',WRAP').' alt="[]" />';
		}
		else
		{
			$value = $note;
		}
	}
	else
	{
		$note = '&nbsp;';
		if( $addon['config']['compress_note'] )
		{
			$value = '<img src="'.$roster->config['theme_path'].'/images/no_note.gif" alt="[]" />';
		}
		else
		{
			$value = $note;
		}
	}

	return '<div style="display:none;">'.$note.'</div>'.$value;
}


/**
 * Controls Output of the Class Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function race_value ( $row, $field )
{
	global $roster, $addon;

	if( $row['race'] != '' )
	{
		$icon_value = '';
		// Race Icon
		if( $addon['config']['race_icon'] >= 1 )
		{
			$icon_value .= '<img class="membersRowimg" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '" src="' . $roster->config['img_url'] . 'icons/race/' . $row['raceid'] . '-' . $row['sexid'] . '.gif" alt="" /> ';
		}

		if( $addon['config']['race_text'] == 1 )
		{
			return '<div style="display:none;">' . $row['race'] . '</div>' . $icon_value . $row['race'];
		}
		else
		{
			return ($icon_value != '' ? '<div style="display:none;">' . $row['race'] . '</div>' . $icon_value : '&nbsp;');
		}
	}
	else
	{
		return '&nbsp;';
	}
}