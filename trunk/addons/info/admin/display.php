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

$sel_guild = ( $roster->atype == 'guild' ? $roster->anchor : false);

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

$menu_select = array();

// Get the scope select data
$query = "SELECT `guild_name`, CONCAT(`region`,'-',`server`), `guild_id` FROM `" . $roster->db->table('guild') . "`"
	   . " ORDER BY `region` ASC, `server` ASC, `guild_name` ASC;";

$result = $roster->db->query($query);

if( !$result )
{
    die_quietly($roster->db->error(),'Database error',__FILE__,__LINE__,$query);
}

while( $data = $roster->db->fetch($result,SQL_NUM) )
{
	$menu_select[$data[1]][$data[2]] = $data[0];
}

$options='';

if( $roster->db->num_rows($result) > 0 )
{
	foreach( $menu_select as $realm => $guild )
	{
		$options .= '		<optgroup label="' . $realm . '">'. "\n";
		foreach( $guild as $id => $name )
		{
			$options .= '			<option value="' . makelink("&amp;a=g:$id",true) . '"' . ( ( isset($sel_guild) && $id == $sel_guild) ? ' selected="selected"' : '' ) . '>' . $name . '</option>' . "\n";
		}
		$options .= '		</optgroup>';
	}
}

$roster->db->free_result($result);

$body = '<form action="' . makelink() . '" name="realm_select" method="post">
	<select name="guild" onchange="window.location.href=this.options[this.selectedIndex].value;">
		<option value="' . makelink() . '">----------</option>
' . $options . '
	</select>
</form>';

$body = messagebox($body,$roster->locale->act['select_guild'],'sgreen');

$listing = $next = $prev = '';

$char_data = getCharData();


// Build the character display control
if( is_array($char_data) && count($char_data) > 0 )
{
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
	foreach($char_data as $name => $data)
	{
		$formbody .= '	<tr>
		<td class="membersRow' . (($i%2)+1) . '"><a href="' . makelink('char-info&amp;member=' . $data['member_id']) . '" target="_blank">' . $name . '</a><br />
			' . $data['level'] . ':' . $data['class'] . "</td>\n";

		$k=0;
		foreach( $data['values'] as $val_name => $value )
		{
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

$body .= $roster_login->getMessage() . "<br />
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
 * Get character config data
 *
 * @return array on success, error string on failure
 */
function getCharData()
{
	global $roster, $sel_guild, $start, $listing, $next, $prev;

	if( !$sel_guild )
	{
		return;
	}
	$sql = "SELECT "
		 . " `member_id`"
		 . " FROM `" . $roster->db->table('players') . "`"
		 . " WHERE `guild_id` = " . $sel_guild . ";";

	// Get the number of rows
	$results = $roster->db->query($sql);

	$max = $roster->db->num_rows();

	if ($start > 0)
	{
		$prev = '<a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=0') . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . ($start-15)) . '">&lt;</a> ';
	}
	else
	{
		$prev = '';
	}

	if (($start+15) < $max)
	{
		$listing = ' <small>[' . $start . ' - ' . ($start+15) . '] of ' . $max . '</small>';
		$next = ' <a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . ($start+15)) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;a=g:' . $sel_guild . '&amp;start=' . ($max-15)) . '">&gt;&gt;|</a>';
	}
	else
	{
		$listing = ' <small>[' . $start . ' - ' . ($max) . '] of ' . $max . '</small>';
		$next = '';
	}

	$sql = "SELECT "
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
		 . " WHERE `guild_id` = " . $sel_guild
		 . " ORDER BY `name` ASC";


	if( $start != -1 )
	{
		$sql .= ' LIMIT ' . $start . ', 15;';
	}
	else
	{
		$sql .= ' LIMIT 0, 15;';
	}

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
