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

if( isset($_POST['process']) && $_POST['process'] != '' )
{
	$roster_config_message = processData();
}

$char_data = getCharData();

// Build the character display control
if( is_array($char_data) )
{
	$body = "<div id=\"char_disp\">\n".border('syellow','start',$wordings[$roster_conf['roster_lang']]['admin']['per_character_display'])."\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

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
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['name'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['tab5'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['spellbook'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['mailbox'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['bags'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['money'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['bank'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['quests'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['bglog'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['pvplog'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['duellog'].'</th>
	<th class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['itembonuses2'].'</th>
';

	$i=0;
	foreach($char_data as $name => $data)
	{
		$body .= '
<tr>
	<td class="membersRow'.(($i%2)+1).'">'.$name.'</td>';

		foreach( $data as $values )
		{
			$body .= '
	<td class="membersRow'.(($i%2)+1).'">';
			$body .= $roster_login->accessConfig($values);
			$body .= '</td>';
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


$body = $roster_config_message."<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('".$act_words['confirm_config_submit']."');submitonce(this);\">
<input type=\"submit\" value=\"Save Settings\" />\n<input type=\"reset\" name=\"Reset\" value=\"Reset\" onClick=\"return confirm('".$act_words['confirm_config_reset']."')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n<br /><br />\n
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
		"`players`.`member_id`, ".
		"`characters`.`name`, ".
		"`players`.`inv`, ".
		"`players`.`talents`, ".
		"`players`.`quests`, ".
		"`players`.`bank`, ".
		"`players`.`spellbook`, ".
		"`players`.`mail`, ".
		"`players`.`money`, ".
		"`players`.`recipes`, ".
		"`players`.`bg`, ".
		"`players`.`pvp`, ".
		"`players`.`duels`, ".
		"`players`.`item_bonuses` ".
		"FROM `".ROSTER_CHARACTERSTABLE."` AS characters ".
		"INNER JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `characters`.`member_id` = `players`.`member_id` ".
		"ORDER BY `name` ASC;";

	// Get the current config values
	$results = $wowdb->query($sql);
	if( $results && $wowdb->num_rows($results) > 0 )
	{
		while($row = $wowdb->fetch_assoc($results))
		{
			foreach ($row as $field => $value)
			{
				if ($field != 'name' && $field != 'member_id')
				{
					$db_values[$row['name']][$field]['name'] = $row['name'];
					$db_values[$row['name']][$field]['value'] = $value;
				}
			}
		}
		return $db_values;
	}
	else
	{
		return $wowdb->error();
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

			$get_val = "SELECT `$settingName` FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '$member_id';";
			$result = $wowdb->query($get_val)
				or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$get_val);

			$config = $wowdb->fetch_assoc($result);

			if( $config[$settingName] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `".ROSTER_PLAYERSTABLE."` SET `$settingName` = '".$wowdb->escape( $settingValue )."' WHERE `member_id` = '$member_id';";
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

?>
