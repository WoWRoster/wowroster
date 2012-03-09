<?php
/** 
 * Dev.PKComp.net WoWRoster Addon
 * 
 * LICENSE: Licensed under the Creative Commons 
 *          "Attribution-NonCommercial-ShareAlike 2.5" license 
 * 
 * @copyright  2005-2007 Pretty Kitty Development 
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5" 
 * @link       http://dev.pkcomp.net 
 * @package    user 
 * @subpackage Profile Admin
 */

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( isset($_POST['process']) && $_POST['process'] != '' )
{
	$roster_config_message = processData();
}

global $roster, $user, $addon;

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$listing = $next = $prev = '';


	$uid = $roster->auth->uid;


/**
 * Actual list
 */
$query = "SELECT "
	. " COUNT( `member_id` )"
	. " FROM `" . $roster->db->table('members') . "`"
	. " WHERE `account_id` = " . $uid . ";";

$num_members = $roster->db->query_first($query);

$guildQuery = "SELECT "
	. " `guild_id` "
	. " FROM `" . $roster->db->table('members') . "`"
	. " WHERE `account_id` = " . $uid . ";";
	
$guild_id = $roster->db->query_first($query);

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
		<th class="membersHeader">' . $roster->locale->act['user_settings']['tab2'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['tab3'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['tab4'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['tab5'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['talents'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['spellbook'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['mailbox'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['bags'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['bank'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['quests'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['recipes'] . '</th>
		<th class="membersHeader">' . $roster->locale->act['user_settings']['item_bonuses'] . "</th>\n\t</tr>\n";

	$i=0;

	$query = 'SELECT '.
	'`user`.`id`, '.
	'`members`.`member_id`, '.
	'`members`.`account_id`, '.
	'`player`.`member_id`, '.
	'`player`.`name`, '.
	'`player`.`race`, '.
	'`player`.`sex`, '.
	'`player`.`level`, '.
	'`player`.`class`, '.
	'`display`.`member_id`, '.
	'`display`.`show_money`, '.
	'`display`.`show_played`, '.
	//'`display`.`show_model`, '.
	'`display`.`show_pets`, '.
	'`display`.`show_reputation`, '.
	'`display`.`show_skills`, '.
	'`display`.`show_honor`, '.
	//'`display`.`show_currency`, '.
	'`display`.`show_talents`, '.
	//'`display`.`show_glyphs`, '.
	'`display`.`show_spellbook`, '.
	'`display`.`show_mail`, '.
	'`display`.`show_bags`, '.
	'`display`.`show_bank`, '.
	'`display`.`show_quests`, '.
	'`display`.`show_recipes`, '.
	'`display`.`show_item_bonuses` '.
	//'`display`.`show_pet_talents`, '.
	//'`display`.`show_pet_spells`, '.
	//'`display`.`show_companions`, '.
	//'`display`.`show_mounts`'. '.

	'FROM `'.$roster->db->table('user_members').'` AS user '.
	'LEFT JOIN `'.$roster->db->table('members').'` AS members ON `user`.`id` = `members`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('players').'` AS player ON `members`.`member_id` = `player`.`member_id` '.
	'LEFT JOIN `'.$roster->db->table('display', 'info').'` AS display ON `members`.`member_id` = `display`.`member_id` '.
	'WHERE `user`.`id` = "' . $uid . '" '.
	'ORDER BY `members`.`name` ASC'.
	' LIMIT ' . ($start > 0 ? $start : 0) . ', 15;';

	$result = $roster->db->query($query);

	while( $data = $roster->db->fetch($result) )
	{
		$formbody .= '	<tr>
		<td class="membersRow' . (($i%2)+1) . '"><a href="' . makelink('char-info&amp;a=c:' . $data['member_id']) . '" target="_blank">' . $data['name'] . '</a><br />
			' . $data['race'] . ':' . $data['sex'] . '<br />
			' . $data['level'] . ':' . $data['class'] . "</td>\n";

		$k=0;
		foreach( $data as $val_name => $value )
		{
			if( substr( $val_name, 0, 5 ) != 'show_' )
			{
				continue;
			}

			$formbody .= '		<td class="membersRow' . (($i%2)+1) . '">' . "\n";
			$formbody .= '			<input type="radio" id="chard_f' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="1" ' . ( $value == '1' ? 'checked="checked"' : '' ) . ' /><label for="chard_f' . $k . '_' . $data['member_id'] . '">Off</label><br />' . "\n";
			$formbody .= '			<input type="radio" id="chard_n' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="3" ' . ( $value == '3' ? 'checked="checked"' : '' ) . ' /><label for="chard_n' . $k . '_' . $data['member_id'] . '">On</label><br />' . "\n";
			$formbody .= '			<input type="radio" id="chard_g' . $k . '_' . $data['member_id'] . '" name="disp_' . $data['member_id'] . ':' . $val_name . '" value="0" ' . ( $value == '0' ? 'checked="checked"' : '' ) . ' /><label for="chard_g' . $k . '_' . $data['member_id'] . '">Global</label>' . "\n";
			$formbody .= "\t\t\t</td>\n";

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

$body = "
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('" . $roster->locale->act['confirm_config_submit'] . "');submitonce(this);\">
	$formbody
<br /><br />\n<input type=\"submit\" value=\"" . $roster->locale->act['config_submit_button'] . "\" />\n<input type=\"reset\" name=\"Reset\" value=\"" . $roster->locale->act['config_reset_button'] . "\" onclick=\"return confirm('" . $roster->locale->act['confirm_config_reset'] . "')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n
</form>";

$tab1 = explode('|',$roster->locale->act['user_settings']['set']);
$tab2 = explode('|',$roster->locale->act['user_settings']['prof']);

$menu = messagebox('
<ul class="tab_menu">
	<li class="selected"><a href="' . makelink('user-user-settings') . '" style="cursor:help;"' . makeOverlib($tab1[1],$tab1[0],'',1,'',',WRAP') . '>' . $tab1[0] . '</a></li>
	<li><a href="' . makelink('user-user-settings-profile') . '" style="cursor:help;"' . makeOverlib($tab2[1],$tab2[0],'',1,'',',WRAP') . '>' . $tab2[0] . '</a></li>
</ul>
',$roster->locale->act['user_page']['settings'],'sgray','145px');


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
					 . " FROM `" . $roster->db->table('display', 'info') . "`"
					 . " WHERE `member_id` = '$member_id';";

			$result = $roster->db->query($get_val) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$get_val);

			$config = $roster->db->fetch($result);

			if( $config[$settingName] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `" . $roster->db->table('display', 'info') . "`"
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