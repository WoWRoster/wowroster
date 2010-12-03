<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Lists tradeskills for everyone in the guild
 * Completely rewritten by vgjunkie 2006-09-14
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Professions
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['professions'];

$roster->tpl->assign_vars(array(
	'S_HIDE'       => $addon['config']['collapse_list'],
	'S_INFO_ADDON' => active_addon('info'),

	'L_PROFESSIONS' => $roster->locale->act['professions'],
	'L_LEVEL'       => $roster->locale->act['level'],
	'L_NAME'        => $roster->locale->act['name'],
	)
);

// Build a list of "Skills" to look for
$inClause = "'";
foreach( $roster->multilanguages as $lang )
{
	$inClause .= implode("', '",$roster->locale->wordings[$lang]['tsArray']);
	$inClause .= "', '";
}
$inClause .= "'";

// If we don't want to show skills with a "1" value, uncomment this line (make option in config?)
$showNewSkill = ( $addon['config']['show_new_skills'] ? " AND SUBSTRING_INDEX(`s`.`skill_level`, ':', 1 ) > 1 " : '' );

// Gather a list of players that have the skills we are looking for
$query = "SELECT `s`.*, `p`.`name`, `p`.`clientLocale`, `p`.`member_id` FROM `" . $roster->db->table('skills') . "` AS s, `" . $roster->db->table('players') . "` AS p"
	   . " WHERE `p`.`member_id` = `s`.`member_id`"
	   . " AND `p`.`guild_id` = '" . $roster->data['guild_id'] . "'"
	   . $showNewSkill
	   . " AND `skill_name` IN ($inClause)"
	   . " ORDER BY `s`.`skill_type`, (mid(`skill_level` FROM 1 FOR (locate(':', `skill_level`)-1)) + 0) DESC, `s`.`skill_name`, `p`.`name`;";

$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);


if( $roster->db->num_rows($result) )
{
	while( $row = $roster->db->fetch($result) )
	{
		$skill_name = $row['skill_name'];
		foreach( $roster->multilanguages as $lang )
		{
			if( $key = array_search($skill_name,$roster->locale->wordings[$lang]) )
			{
				break;
			}
		}
		$skill_name = $roster->locale->act[$key];
		$member_id = $row['member_id'];
		$skills_array[$skill_name][] = array(
			'member_id' => $row['member_id'],
			'name'      => $row['name'],
			'level'     => $row['skill_level']
		);
	}

	$id = 0;
	foreach( $skills_array as $skill_name => $skills )
	{
		$max_level = ( $skill_name == $roster->locale->act['Poisons'] ? '350' : ROSTER_MAXSKILLLEVEL );

		$roster->tpl->assign_block_vars('profession',array(
			'ID'        => $id,
			'ICON'      => $roster->locale->act['ts_iconArray'][$skill_name],
			'NAME'      => $skill_name,
			'MAX_LEVEL' => $max_level
			)
		);

		foreach( $skills as $skill )
		{
			$level_array = explode (':',$skill['level']);
			$levelpct = $level_array[0] / ( $max_level > $level_array[1] ? $max_level : $level_array[1] ) * 100;
			settype( $levelpct, 'integer' );

			if( !$levelpct )
			{
				$levelpct = 1;
			}
                        if ($levelpct >= 100)
                        {
                        	$levelpct = '100';
                        }

			$roster->tpl->assign_block_vars('profession.skills',array(
				'ROW_CLASS' => $roster->switch_row_class(),
				'NAME'      => $skill['name'],
				'LINK'      => makelink('char-info-recipes&amp;a=c:' . $skill['member_id']),
				'LEVEL'     => $level_array[0],
				'MAX'       => $level_array[1],
				'PERCENT'   => $levelpct,
				'REMAIN'    => (100-$levelpct)
				)
			);
		}
		$id++;
	}
}

$roster->tpl->set_handle('body', $addon['basename'] . '/professions.html');
$roster->tpl->display('body');
