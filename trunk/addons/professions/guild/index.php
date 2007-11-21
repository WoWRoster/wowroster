<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Lists tradeskills for everyone in the guild
 * Completely rewritten by vgjunkie 2006-09-14
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    Professions
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['professions'];

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
	   . " ORDER BY `s`.`skill_type`, `s`.`skill_name`,(mid(`skill_level` FROM 1 FOR (locate(':', `skill_level`)-1)) + 0) DESC, `p`.`name`;";

$result = $roster->db->query($query) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$query);


############################### START OUTPUT ##############################

// Counter for row striping
$striping_counter = 0;
$last_value = 'some obscurely random string to keep me lazy';


if( $roster->db->num_rows($result) )
{
	$id = 0;
	while( $row = $roster->db->fetch($result) )
	{
		$skill_name = $row['skill_name'];
		$skill_image = 'Interface/Icons/' . $roster->locale->wordings[$row['clientLocale']]['ts_iconArray'][$skill_name];
		$skill_image = '<div style="display:inline;float:left;"><img width="17" height="17" src="' . $roster->config['interface_url'] . $skill_image . '.' . $roster->config['img_suffix'] . '" alt="" /></div>';

		$skill_output = '<div style="cursor:pointer;width:370px;" onclick="showHide(\'table_' . $id . '\',\'img_' . $id . '\',\'' . $roster->config['img_url'] . 'minus.gif\',\'' . $roster->config['img_url'] . 'plus.gif\');">
	' . $skill_image . '
	<div style="display:inline;float:right;"><img id="img_' . $id . '" src="' . $roster->config['img_url'] . ( $addon['config']['collapse_list'] ? 'plus' : 'minus' ) . '.gif" alt="" /></div>
' . $skill_name . '</div>';

		if( $last_value != $skill_name )
		{
			if( $striping_counter )
			{
				print '</table>';
				print border('sgray','end');
				print '<br />';
			}

			print border('sgray','start',$skill_output);
			print ('
<table border="0" cellpadding="0" cellspacing="0" class="bodyline" id="table_'.$id.'"' . ( $addon['config']['collapse_list'] ? ' style="display:none;"' : '' ) . '>
	<tr>
		<th class="membersHeader">' . $roster->locale->act['level'] . '</th>
		<th class="membersHeaderRight" width="150">' . $roster->locale->act['name'] . '</th>
	</tr>
');

			$striping_counter = 0;
			$last_value = $skill_name;
		}

		// Increment counter so rows are colored alternately
		$stripe_counter = ( ( $striping_counter++ % 2 ) + 1 );
		$stripe_class = 'membersRow' . $stripe_counter;
		$stripe_class_right =  'membersRowRight' . $stripe_counter;

		// Setup some user row data
		$level_array = explode (':',$row['skill_level']);
		$levelpct = $level_array[0] / $level_array[1] * 100 ;
		settype( $levelpct, 'integer' );

		if ( !$levelpct )
		{
			$levelpct = 1;
		}

		print '
	<tr>
		<td class="' . $stripe_class . '">
			<div class="levelbarParent" style="width:200px;">
				<div class="levelbarChild">' . $level_array[0] . '/' . $level_array[1] . '</div>
			</div>
			<table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="background-image: url(\'' . $roster->config['img_url'] . 'expbar-var2.gif\');" width="' . $levelpct . '%">
						<img src="' . $roster->config['img_url'] . 'pixel.gif" height="14" width="1" alt="" />
					</td>
					<td width="' . (100-$levelpct) . '%"></td>
				</tr>
			</table>
		</td>
		<td class="' . $stripe_class_right . '">
			' . ( active_addon('info') ? '<a href="' . makelink('char-info-recipes&amp;a=c:' . $row['member_id']) . '">' . $row['name'] . '</a>' : $row['name'] ) . '
		</td>
	</tr>
';
		$id++;
	}

	print '</table>';
	print border('sgray','end');
	print '<br />';
}
