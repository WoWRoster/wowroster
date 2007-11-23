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

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

// Change scope to guild, and rerun detection to load default
$roster->scope = 'guild';
$roster->get_scope_data();

$listing = $next = $prev = '';

/**
 * Actual list
 */
$query = "SELECT "
	. " COUNT( `member_id` )"
	. " FROM `" . $roster->db->table('players') . "`"
	. " WHERE `guild_id` = " . ( isset($roster->data['guild_id']) ? $roster->data['guild_id'] : 0 ) . ";";

$num_members = $roster->db->query_first($query);


if( $num_members > 0 )
{
	// Draw the header line
	if ($start > 0)
	{
		$prev = '<a href="' . makelink('&amp;start=0') . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;start=' . ($start - 15)) . '">&lt;</a> ';
	}
	else
	{
		$prev = '';
	}

	if (($start+15) < $num_members)
	{
		$listing = ' <small>[' . $start . ' - ' . ($start+15) . '] of ' . $num_members . '</small>';
		$next = ' <a href="' . makelink('&amp;start=' . ($start+15)) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;start=' . ( floor( $num_members / 15) * 15 )) . '">&gt;&gt;|</a>';
	}
	else
	{
		$listing = ' <small>[' . $start . ' - ' . ($num_members) . '] of ' . $num_members . '</small>';
		$next = '';
	}

	$formbody = "<br /><div id=\"char_disp\">\n" . border('sblue','start',$prev . $roster->locale->act['admin']['per_character_display'] . $listing . $next) . "\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

	$formbody .= '
	<tr>
		<th class="membersHeader">' . $roster->locale->act['name'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['money'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['timeplayed'] . '</th>
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

	$query = "SELECT "
		. " `member_id`, `name`,"
		. " `level`, `class`,"
		. " `show_money`, `show_played`,"
		. " `show_tab2`, `show_tab3`,"
		. " `show_tab4`, `show_tab5`,"
		. " `show_talents`, `show_spellbook`,"
		. " `show_mail`, `show_bags`,"
		. " `show_bank`, `show_quests`,"
		. " `show_recipes`, `show_item_bonuses`"
		. " FROM `" . $roster->db->table('players') . "`"
		. " WHERE `guild_id` = " . $roster->data['guild_id']
		. " ORDER BY `name` ASC"
		. " LIMIT " . ($start > 0 ? $start : 0) . ", 15;";

	$result = $roster->db->query($query);

	while( $data = $roster->db->fetch($result) )
	{
		$formbody .= '	<tr>
		<td class="membersRow' . (($i%2)+1) . '"><a href="' . makelink('char-info&amp;member=' . $data['member_id']) . '" target="_blank">' . $data['name'] . '</a><br />
			' . $data['level'] . ':' . $data['class'] . "</td>\n";

		$k=0;
		foreach( $data as $val_name => $value )
		{
			if( substr( $val_name, 0, 5 ) != 'show_' )
			{
				continue;
			}

			$formbody .= '		<td class="membersRow' . (($i%2)+1) . '">' . "\n";
			$formbody .= '			<input type="radio" id="chard_f' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="1" ' . ( $value == '1' ? 'checked="checked"' : '' ) . ' /><label for="chard_f' . $k . '_' . $data['member_id'] . '">off</label><br />' . "\n";
			$formbody .= '			<input type="radio" id="chard_n' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="3" ' . ( $value == '3' ? 'checked="checked"' : '' ) . ' /><label for="chard_n' . $k . '_' . $data['member_id'] . '">on</label>' . "\n";
			$formbody .= "\t\t</td>\n";

			$k++;
		}
		$formbody .= "\t</tr>\n";
		$i++;
	}
	$formbody .= "</table>\n" . border('syellow','end') . "\n</div>\n";

	$formbody .= $prev . $listing . $next;
}
else
{
	$formbody = 'No Data';
}

$roster->output['body_onload'] .= 'initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$body .= "
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('" . $roster->locale->act['confirm_config_submit'] . "');submitonce(this);\">
	$formbody
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
