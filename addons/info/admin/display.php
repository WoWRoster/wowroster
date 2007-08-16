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
 * @package    CharacterInfo
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( isset($_POST['process']) && $_POST['process'] != '' )
{
	$roster_config_message = processData();
}

$char_data = getCharData();
//echo '<pre style="text-align:left">';print_r($char_data);echo '</pre>';

// Build the character display control
if( is_array($char_data) )
{
	$body = "<div id=\"char_disp\">\n" . border('sblue','start',$roster->locale->act['admin']['per_character_display']) . "\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

	$body .= '
	<tr>
		<th class="membersHeader">' . $roster->locale->act['name'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['money'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['tab2'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['tab3'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['tab4'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['tab5'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['talents'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['spellbook'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['mailbox'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['bags'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['bank'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['quests'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['recipes'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['item_bonuses'] . "</th>\n\t</tr>\n";

	$i=0;
	foreach($char_data as $name => $data)
	{
		$body .= '	<tr>
		<td class="membersRow' . (($i%2)+1) . '"><a href="' . makelink('char-info&amp;member=' . $data['member_id']) . '" target="_blank">' . $name . '</a><br />
			' . $data['level'] . ':' . $data['class'] . "</td>\n";

		$k=0;
		foreach( $data['values'] as $val_name => $value )
		{
			$body .= '		<td class="membersRow' . (($i%2)+1) . '">' . "\n";
			$body .= '			<input type="radio" id="chard_f' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="1" ' . ( $value == '1' ? 'checked="checked"' : '' ) . ' /><label for="chard_f' . $k . '_' . $data['member_id'] . '">off</label><br />' . "\n";
			$body .= '			<input type="radio" id="chard_n' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="3" ' . ( $value == '3' ? 'checked="checked"' : '' ) . ' /><label for="chard_n' . $k . '_' . $data['member_id'] . '">on</label>' . "\n";
			$body .= "\t\t</td>\n";

			$k++;
		}
		$body .= "\t</tr>\n";
		$i++;
	}
	$body .= "</table>\n" . border('syellow','end') . "\n</div>\n";
}
else
{
	$body = 'No Data';
}

$roster->output['body_onload'] .= 'initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$body = $roster_login->getMessage() . "<br />
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('" . $roster->locale->act['confirm_config_submit'] . "');submitonce(this);\">
	$body
<br /><br />\n<input type=\"submit\" value=\"" . $roster->locale->act['config_submit_button'] . "\" />\n<input type=\"reset\" name=\"Reset\" value=\"" . $roster->locale->act['config_reset_button'] . "\" onclick=\"return confirm('" . $roster->locale->act['confirm_config_reset'] . "')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n
</form>";

$tab1 = explode('|',$roster->locale->act['admin']['char_conf']);
$tab2 = explode('|',$roster->locale->act['admin']['char_pref']);

$menu = messagebox('
<ul class="tab_menu">
	<li><a href="' . makelink('rostercp-addon-info') . '" style="cursor:help;"' . makeOverlib($tab1[1],$tab1[0],'',1,'',',WRAP') . '>' . $tab1[0] . '</a></li>
	<li class="selected"><a href="' . makelink('rostercp-addon-info-display') . '" style="cursor:help;"' . makeOverlib($tab2[1],$tab2[0],'',1,'',',WRAP') . '>' . $tab2[0] . '</a></li>
</ul>
',$roster->locale->act['roster_config_menu'],'sgray','145px');





/**
 * Get character config data
 *
 * @return array on success, error string on failure
 */
function getCharData( )
{
	global $roster;

	$sql = "SELECT "
		 . " `member_id`, `name`,"
		 . " `level`, `class`,"
		 . " `show_money`, `show_tab2`,"
		 . " `show_tab3`, `show_tab4`,"
		 . " `show_tab5`, `show_talents`,"
		 . " `show_spellbook`, `show_mail`,"
		 . " `show_bags`, `show_bank`,"
		 . " `show_quests`, `show_recipes`,"
		 . " `show_item_bonuses`"
		 . " FROM `" . $roster->db->table('players') . "`"
		 . " ORDER BY `name` ASC;";

	// Get the current config values
	$results = $roster->db->query($sql);
	if( !$results )
	{
		die_quietly( $roster->db->error(), 'Database Error',__FILE__,__LINE__,$sql);
	}

	$db_values = false;

	while( $row = $roster->db->fetch($results,SQL_ASSOC) )
	{
		$db_values[$row['name']]['member_id'] = $row['member_id'];
		$db_values[$row['name']]['level'] = $row['level'];
		$db_values[$row['name']]['class'] = $row['class'];

		foreach( $row as $field => $value )
		{
			if( $field != 'name' && $field != 'member_id' && $field != 'level' && $field != 'class' )
			{
				$db_values[$row['name']]['values'][$field] = $value;
			}
		}
	}

	$roster->db->free_result($results);

	return $db_values;
}

/**
 * Process Data for entry to the database
 *
 * @return string Settings changed or not changed
 */
function processData( )
{
	global $roster;

	$update_sql = array();

	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		if( substr($settingName,0,5) == 'disp_' )
		{
			$settingName = str_replace('disp_','',$settingName);

			list($member_id,$settingName) = explode(':',$settingName);

			$get_val = "SELECT `$settingName`"
					 . " FROM `" . $roster->db->table('players') . "`"
					 . " WHERE `member_id` = '$member_id';";

			$result = $roster->db->query($get_val) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$get_val);

			$config = $roster->db->fetch($result);

			if( $config[$settingName] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `" . $roster->db->table('players') . "`"
							  . " SET `$settingName` = '" . $roster->db->escape( $settingValue ) . "'"
							  . " WHERE `member_id` = '$member_id';";
			}
		}
	}

	// Update DataBase
	if( !empty($update_sql) )
	{
		foreach( $update_sql as $sql )
		{
			$result = $roster->db->query($sql);
			if( !$result )
			{
				return '<span style="color:#0099FF;font-size:11px;">Error saving settings</span><br />MySQL Said:<br /><pre>' . $roster->db->error() . '</pre><br />';
			}
		}
		return '<span style="color:#0099FF;font-size:11px;">Settings have been changed</span>';
	}
	else
	{
		return '<span style="color:#0099FF;font-size:11px;">No changes have been made</span>';
	}
}
