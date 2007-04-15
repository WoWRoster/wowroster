<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character display configuration
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

if( isset($_POST['process']) && $_POST['process'] != '' )
{
	$roster_config_message = processData();
}

$char_data = getCharData();

// Build the character display control
if( is_array($char_data) )
{
	$body = "<div id=\"char_disp\">\n".border('syellow','start',$act_words['admin']['per_character_display'])."\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

	$disp_array = array(
		'talents',
		'spellbook',
		'mail',
		'inv',
		'money',
		'bank',
		'recipes',
		'quests',
		'bg',
		'pvp',
		'duels',
		'item_bonuses',
	);

	$body .= '
<tr>
	<th class="membersHeader">'.$act_words['name'].'</th>
	<th class="membersHeader">'.$act_words['talents'].'</th>
	<th class="membersHeader">'.$act_words['spellbook'].'</th>
	<th class="membersHeader">'.$act_words['mailbox'].'</th>
	<th class="membersHeader">'.$act_words['bags'].'</th>
	<th class="membersHeader">'.$act_words['money'].'</th>
	<th class="membersHeader">'.$act_words['bank'].'</th>
	<th class="membersHeader">'.$act_words['recipes'].'</th>
	<th class="membersHeader">'.$act_words['quests'].'</th>
	<th class="membersHeader">'.$act_words['bglog'].'</th>
	<th class="membersHeader">'.$act_words['pvplog'].'</th>
	<th class="membersHeader">'.$act_words['duellog'].'</th>
	<th class="membersHeader">'.$act_words['itembonuses2'].'</th>
</tr>
';

	$i=0;
	foreach($char_data as $name => $data)
	{
		$body .= '
<tr>
	<td class="membersRow'.(($i%2)+1).'">'.$name.'</td>';

		$k=0;
		foreach( $data as $val_name => $values )
		{
			$body .= '
	<td class="membersRow'.(($i%2)+1).'">';
			$body .= '<input type="radio" id="chard_f'.$k.'_'.$values['member_id'].'" name="disp_'.$values['member_id'].':'.$val_name.'" value="1" '.( $values['value'] == '1' ? 'checked="checked"' : '' ).' /><label for="chard_f'.$k.'_'.$values['member_id'].'">off</label><br />'."\n";
			$body .= '<input type="radio" id="chard_n'.$k.'_'.$values['member_id'].'" name="disp_'.$values['member_id'].':'.$val_name.'" value="3" '.( $values['value'] == '3' ? 'checked="checked"' : '' ).' /><label for="chard_n'.$k.'_'.$values['member_id'].'">on</label>'."\n";
			$body .= '</td>';

			$k++;
		}
		$body .= '</tr>';
		$i++;
	}
	$body .= "</table>\n".border('syellow','end')."\n</div>\n";
}
else
{
	$body = 'No Data';
}

$body_action = 'onload="initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');"';
$body = $roster_login->getMessage()."<br />
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('".$act_words['confirm_config_submit']."');submitonce(this);\">
<input type=\"submit\" value=\"".$act_words['config_submit_button']."\" />\n<input type=\"reset\" name=\"Reset\" value=\"".$act_words['config_reset_button']."\" onclick=\"return confirm('".$act_words['confirm_config_reset']."')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n<br /><br />\n
	$body
</form>";




/**
 * Get character config data
 *
 * @return array on success, error string on failure
 */
function getCharData( )
{
	global $wowdb, $wordings, $roster_conf;

	$sql = "SELECT ".
		"`members`.`member_id`, ".
		"`members`.`name`, ".
		"`members`.`inv`, ".
		"`members`.`talents`, ".
		"`members`.`quests`, ".
		"`members`.`bank`, ".
		"`members`.`spellbook`, ".
		"`members`.`mail`, ".
		"`members`.`money`, ".
		"`members`.`recipes`, ".
		"`members`.`bg`, ".
		"`members`.`pvp`, ".
		"`members`.`duels`, ".
		"`members`.`item_bonuses` ".
		"FROM `".ROSTER_MEMBERSTABLE."` AS members ".
		"INNER JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `members`.`member_id` = `players`.`member_id` ".
		"ORDER BY `name` ASC;";

	// Get the current config values
	$results = $wowdb->query($sql);
	if( !$results )
	{
		die_quietly( $wowdb->error(), 'Database Error',basename(__FILE__),__LINE__,$sql);
	}

	if( $wowdb->num_rows($results) > 0 )
	{
		while($row = $wowdb->fetch_assoc($results))
		{
			foreach ($row as $field => $value)
			{
				if ($field != 'name' && $field != 'member_id')
				{
					$db_values[$row['name']][$field]['name'] = $row['name'];
					$db_values[$row['name']][$field]['member_id'] = $row['member_id'];
					$db_values[$row['name']][$field]['value'] = $value;
				}
			}
		}
		return $db_values;
	}
}

/**
 * Process Data for entry to the database
 *
 * @return string Settings changed or not changed
 */
function processData( )
{
	global $wowdb, $queries;

	$wowdb->reset_values();

	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		if( substr($settingName,0,5) == 'disp_' )
		{
			$settingName = str_replace('disp_','',$settingName);

			list($member_id,$settingName) = explode(':',$settingName);

			$get_val = "SELECT `$settingName` FROM `".ROSTER_MEMBERSTABLE."` WHERE `member_id` = '$member_id';";
			$result = $wowdb->query($get_val)
				or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$get_val);

			$config = $wowdb->fetch_assoc($result);

			if( $config[$settingName] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `".ROSTER_MEMBERSTABLE."` SET `$settingName` = '".$wowdb->escape( $settingValue )."' WHERE `member_id` = '$member_id';";
			}
		}
	}

	// Update DataBase
	if( is_array($update_sql) )
	{
		foreach( $update_sql as $sql )
		{
			$queries[] = $sql;

			$result = $wowdb->query($sql);
			if( !$result )
			{
				return '<span style="color:#0099FF;font-size:11px;">Error saving settings</span><br />MySQL Said:<br /><pre>'.$wowdb->error().'</pre><br />';
			}
		}
		return '<span style="color:#0099FF;font-size:11px;">Settings have been changed</span>';
	}
	else
	{
		return '<span style="color:#0099FF;font-size:11px;">No changes have been made</span>';
	}
}
