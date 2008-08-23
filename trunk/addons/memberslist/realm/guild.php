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

$memberlist = new memberslist(array('group_alts'=>-1));

$mainQuery =
	'SELECT '.
	'`guild`.`guild_name`, '.
	'`guild`.`guild_id`, '.
	'`guild`.`faction`, '.
	'`guild`.`factionEn`, '.
	'`guild`.`guild_num_members`, '.
	'`guild`.`guild_num_accounts`, '.
	'`guild`.`guild_motd` '.

	'FROM `'.$roster->db->table('guild').'` AS guild '.
	'WHERE `guild`.`server` = "'.$roster->db->escape($roster->data['server']).'" '.
	'ORDER BY ';

$always_sort = ' `guild`.`guild_name` ASC';

$FIELD['guild_name'] = array (
	'lang_field' => 'guild',
	'order'      => array( '`guild`.`guild_name` ASC' ),
	'order_d'    => array( '`guild`.`guild_name` DESC' ),
	'value'      => 'guild_value',
	'js_type'    => 'ts_string',
	'display'    => 3,
);

$FIELD['faction'] = array (
	'lang_field' => 'faction',
	'order'      => array( '`guild`.`faction` ASC' ),
	'order_d'    => array( '`guild`.`faction` DESC' ),
	'value'      => 'faction_value',
	'js_type'    => 'ts_string',
	'display'    => 2,
);

$FIELD['guild_num_members'] = array (
	'lang_field' => 'members',
	'order'      => array( '`guild`.`guild_num_members` ASC' ),
	'order_d'    => array( '`guild`.`guild_num_members` DESC' ),
	'js_type'    => 'ts_number',
	'display'    => 2,
);

$FIELD['guild_num_accounts'] = array (
	'lang_field' => 'accounts',
	'order'      => array( '`guild`.`guild_num_accounts` ASC' ),
	'order_d'    => array( '`guild`.`guild_num_accounts` DESC' ),
	'js_type'    => 'ts_number',
	'display'    => 2,
);

$FIELD['guild_motd'] = array (
	'lang_field' => 'motd',
	'order'      => array( '`guild`.`guild_motd` ASC' ),
	'order_d'    => array( '`guild`.`guild_motd` DESC' ),
	'value'      => 'note_value',
	'js_type'    => 'ts_string',
	'display'    => 2,
);

$memberlist->prepareData($mainQuery, $always_sort, $FIELD, 'memberslist');

$menu = '';
// Start output
if( $addon['config']['member_update_inst'] )
{
	$roster->output['before_menu'] .= '<a href="' . makelink('#update') . '"><span style="font-size:20px;">'.$roster->locale->act['update_link'].'</span></a><br /><br />';
}

echo $memberlist->makeMembersList('syellow');

// Print the update instructions
if( $addon['config']['member_update_inst'] )
{
	echo "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$roster->locale->act['update_instructions']);
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($roster->locale->act['update_instruct'], $roster->config['uploadapp'], $roster->locale->act['index_text_uniloader'], $roster->config['profiler'], makelink('update'), $roster->locale->act['lualocation']);
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

	if( !empty($row[$field]) )
	{
		$note = htmlspecialchars(nl2br($row[$field]));

		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster->config['theme_path'].'/images/note.gif" style="cursor:help;" '.makeOverlib($note,$roster->locale->act['note'],'',1,'',',WRAP').' alt="[]" />';
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
			$note = '<img src="'.$roster->config['theme_path'].'/images/no_note.gif" alt="[]" />';
		}
		else
		{
			$value = $note;
		}
	}

	return '<div style="display:none;">'.htmlentities($row[$field]).'</div>'.$note;
}

/**
 * Controls Output of the Guild Name Column
 *
 * @param array $row
 * @return string - Formatted output
 */
function guild_value ( $row, $field )
{
	global $roster;

	if( $row['guild_id'] )
	{
		return '<div style="display:none;">' . $row['guild_name'] . '</div><a href="' . makelink('guild-memberslist&amp;a=g:' . $row['guild_id']) . '">' . $row['guild_name'] . '</a></div>';
	}
	else
	{
		return '<div style="display:none;">' . $row['guild_name'] . '</div>' . $row['guild_name'];
	}
}

/**
 * Controls Output of the Faction Column
 *
 * @param array $row
 * @return string - Formatted output
 */
function faction_value ( $row, $field )
{
	global $roster, $addon;

	if ( $row['factionEn'] )
	{
		$faction = ( isset($row['factionEn']) ? $row['factionEn'] : '' );

		switch( substr($faction,0,1) )
		{
			case 'A':
				$icon = '<img src="' . $roster->config['img_url'] . 'icon_alliance.png" alt="" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '"/> ';
				break;
			case 'H':
				$icon = '<img src="' . $roster->config['img_url'] . 'icon_horde.png" alt="" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '"/> ';
				break;
			default:
				$icon = '<img src="' . $roster->config['img_url'] . 'icon_neutral.png" alt="" width="' . $addon['config']['icon_size'] . '" height="' . $addon['config']['icon_size'] . '"/> ';
				break;
		}
	}
	else
	{
		$icon = '';
	}

	$cell_value = $icon . $row['faction'];

	return '<div style="display:none;">' . $row['faction'] . '</div>' . $cell_value;
}
