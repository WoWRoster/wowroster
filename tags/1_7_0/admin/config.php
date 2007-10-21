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

$subdir = '../';
require_once( $subdir.'settings.php' );

$script_filename = 'config.php';



// ----[ Build the password box ]---------------------------
$passbox = '
<!-- Begin Password Input Box -->
<form action="'.$script_filename.'" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
'.border('sred','start','Authorization Required').'
  <table class="bodyline" cellspacing="0" cellpadding="0">
    <tr>
      <td class="membersRowRight1">Password:<br />
        <input name="pass_word" type="password" size="30" maxlength="30" /></td>
    </tr>
    <tr>
      <td class="membersRowRight2" valign="bottom">
        <div align="right"><input type="submit" value="Go" /></div></td>
    </tr>
  </table>
'.border('sred','end').'
</form>
<!-- End Password Input Box -->';


// ----[ Check log-in ]-------------------------------------
if( isset( $_GET['logout'] ) && $_GET['logout'] == 'logout' )
{
	if( isset($_COOKIE['roster_pass']) )
		setcookie( 'roster_pass','',time()-86400 );
	if( isset($_COOKIE['roster_conf_tab']) )
		setcookie( 'roster_conf_tab','',time()-86400 );
	$password_message = '<span style="font-size:11px;color:red;">Logged Out</span><br />';
}
else
{
	if( !isset($_COOKIE['roster_pass']) )
	{
		if( isset($_POST['pass_word']) )
		{
			if( md5($_POST['pass_word']) == $roster_conf['roster_upd_pw'] )
			{
				setcookie( 'roster_pass',$roster_conf['roster_upd_pw'] );
				$password_message = '<span style="font-size:10px;color:red;">Logged in:</span><span style="font-size:10px;color:#FFFFFF"> [<a href="'.$script_filename.'?logout=logout">Logout</a>]</span><br />';
				$allow_login = 1;
			}
			else
			{
				$password_message = '<span style="font-size:11px;color:red;">Wrong password</span><br />';
			}
		}
	}
	else
	{
		$BigCookie = $_COOKIE['roster_pass'];

		if( $BigCookie == $roster_conf['roster_upd_pw'] )
		{
			$password_message = '<span style="font-size:10px;color:red;">Logged in:</span><span style="font-size:10px;color:#FFFFFF"> [<a href="'.$script_filename.'?logout=logout">Logout</a>]</span><br />';
			$allow_login = 1;
		}
		else
		{
			setcookie( 'roster_pass','',time()-86400 );
		}
	}
}

// Disallow viewing of the page
if( !$allow_login )
{
	include_once (ROSTER_BASE.'roster_header.tpl');
	include_once (ROSTER_BASE.'lib'.DIR_SEP.'menu.php');

	print
	'<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['roster_config'].'</span><br />'.
	$password_message.
	$passbox;

	include_once (ROSTER_BASE.'roster_footer.tpl');

	exit();
}
// ----[ End Check log-in ]---------------------------------



// ----[ Decide what to do next ]---------------------------
if( isset($_POST['process']) && $_POST['process'] != '' )
{
	switch ( $_POST['process'] )
	{
		case 'process':
			$password_message .= processData( $_POST );
			break;

		case 'change_pass';
			$ret_pass = changePassword( $_POST );
			$password_message .= $ret_pass['message'];

		default:
			break;
	}
}
// ----[ End Decide what to do next ]-----------------------



/**
 * Get the current config values
 */
$sql = "SELECT `config_name`, `config_value` FROM `".ROSTER_CONFIGTABLE."` ORDER BY `id` ASC;";
$results = $wowdb->query($sql);

if( !$results || $wowdb->num_rows($results) == 0 )
{
	die("Cannot get roster configuration from database<br />\nMySQL Said: ".$wowdb->error()."<br /><br />\nYou might not have roster installed<br />\n<a href=\"".$subdir."install.php\">INSTALL</a>");
}

/**
 * Fill the config array with values
 */
while( $row = $wowdb->fetch_assoc($results) )
{
	$roster_conf[$row['config_name']] = stripslashes($row['config_value']);
}
$wowdb->free_result($results);

$db_values = getConfigData();

$char_data = getCharData();

$conf_arrays = explode('|',$db_values['master']['config_list']['value']);


// ----[ Build the menu ]--------------------------------
$menu = '
<!-- Begin Config Menu -->
'.border('sgray','start','Config Menu').'
<div style="width:145px;">
  <ul id="tab_menu">'."\n";

foreach($conf_arrays as $type)
{
	$menu .= '    <li><a href="#" onclick="return expandcontent(\''.$type.'\',this)">'.$wordings[$roster_conf['roster_lang']]['admin'][$type].'</a></li>'."\n";
}

$menu .='
	<li><a href="#" onclick="return expandcontent(\'char_disp\',this)">'.$wordings[$roster_conf['roster_lang']]['admin']['per_character_display'].'</a></li>
	<li><a href="'.$roster_conf['roster_dir'].'/rosterdiag.php" target="_new">'.$wordings[$roster_conf['roster_lang']]['rosterdiag'].'</a></li>
    <li><a href="http://www.wowroster.net/wiki" target="_new">Documentation</a></li>
  </ul>
</div>
'.border('sgray','end').'
<!-- End Config Menu -->';



$form_start = "<form action=\"$script_filename\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"submitonce(this)\">\n";

$submit_button = "<input type=\"submit\" value=\"Save Settings\" />\n<input type=\"reset\" name=\"Reset\" value=\"Reset\" />\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n<br /><br />\n";

$form_end = "</form>\n";


// Build the page
$html = '';
foreach($conf_arrays as $type)
{
	$i = 0;
	$html .= "<div id=\"$type\" style=\"display:none;\">\n".border('sblue','start',$wordings[$roster_conf['roster_lang']]['admin'][$type])."\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

	foreach($db_values[$type] as $values)
	{
		// Here is my nifty auto form generator
		// Takes `form_type` from the db and parses it for form type values and labels
		// Any un-handled form type will cause this file to just display the current value

		// Figure out input type
		$input_field = '';
		$input_type = explode('{',$values['form_type']);

		switch ($input_type[0])
		{
			case 'text':
				$length = explode('|',$input_type[1]);
				$input_field = '<input name="config_'.$values['name'].'" type="text" value="'.$values['value'].'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
				break;

			case 'radio':
				$options = explode('|',$input_type[1]);
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= '<label class="'.( $values['value'] == $vals[1] ? 'blue' : 'white' ).'"><input class="checkBox" type="radio" name="config_'.$values['name'].'" value="'.$vals[1].'" '.( $values['value'] == $vals[1] ? 'checked="checked"' : '' ).' />'.$vals[0]."</label>\n";
				}
				break;

			case 'select':
				$options = explode('|',$input_type[1]);
				$input_field .= '<select name="config_'.$values['name'].'">'."\n";
				$select_one = 1;
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					if( $values['value'] == $vals[1] && $select_one )
					{
						$input_field .= '  <option value="'.$vals[1].'" selected="selected">&gt;'.$vals[0].'&lt;</option>'."\n";
						$select_one = 0;
					}
					else
					{
						$input_field .= '  <option value="'.$vals[1].'">'.$vals[0].'</option>'."\n";
					}
				}
				$input_field .= '</select>';
				break;

			case 'function':
				$input_field = $input_type[1]();
				break;

			case 'display':
				$input_field = $values['value'];
				break;

			default:
				$input_field = $values['value'];
				break;
		}

		$html .= '
	<tr>
		<td class="membersRow'.(($i%2)+1).'">'.createTip($values['description'],$values['tooltip'],$values['description']).'</td>
		<td class="membersRowRight'.(($i%2)+1).'"><div align="right">'.$input_field.'</div></td>
	</tr>';

		$i++;
	}
	$html .= "</table>\n".border('sblue','end')."\n</div>\n";
}


// Build the character display control
$html .= "<div id=\"char_disp\" style=\"display:none;\">\n".border('syellow','start',$wordings[$roster_conf['roster_lang']]['admin']['per_character_display'])."\n<table cellspacing=\"0\" cellpadding=\"0\" class=\"bodyline\">\n";

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

		$html .= '
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
foreach($char_data as $values)
{
		$html .= '
<tr>
	<td class="membersRow'.(($i%2)+1).'">'.$values['name'].'</td>';

	foreach( $disp_array as $val_name )
	{
		$html .= '
	<td class="membersRow'.(($i%2)+1).'">';
		$html .= '<label class="'.( $values[$val_name] == '1' ? 'blue' : 'white' ).'"><input class="checkBox" type="radio" name="disp_'.$values['member_id'].':'.$val_name.'" value="1" '.( $values[$val_name] == '1' ? 'checked="checked"' : '' ).' />off</label><br />'."\n";
		$html .= '<label class="'.( $values[$val_name] == '3' ? 'blue' : 'white' ).'"><input class="checkBox" type="radio" name="disp_'.$values['member_id'].':'.$val_name.'" value="3" '.( $values[$val_name] == '3' ? 'checked="checked"' : '' ).' />on</label>'."\n";
		$html .= '</td>';
	}
	$html .= '</tr>';
	$i++;
}
$html .= "</table>\n".border('syellow','end')."\n</div>\n";


// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );


include_once( ROSTER_BASE.'lib'.DIR_SEP.'menu.php' );


// ----[ Render the entire page ]---------------------------
print
'<script type="text/javascript" language="JavaScript">
<!--
	//Set tab to intially be selected when page loads:
	//[which tab (1=first tab), ID of tab content to display]:
	var initialtab=[1, \'main_conf\'];
	window.onload=do_onload;
	window.onunload=savetabstate;
//-->
</script>
<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['roster_config'].'</span><br />'.
$password_message.
'<br /><br />
<table width="100%">
  <tr>
    <td valign="top" align="left">
      '.$menu.'
    </td>
    <td valign="top" align="center">
      '.$form_start.$submit_button.$html.$form_end.'
    </td>
    <td valign="top" align="right">
		<!-- Begin Password Change Box -->
		<form action="'.$script_filename.'" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
		'.border('sred','start','Change Roster Password').'
		  <table class="bodyline" cellspacing="0" cellpadding="0">
		    <tr>
		      <td class="membersRow1">Old Password:</td>
		      <td class="membersRowRight1"><input type="password" name="old_password" value="" /></td>
		    </tr>
		    <tr>
		      <td class="membersRow2">New Password:</td>
		      <td class="membersRowRight2"><input type="password" name="new_password1" value="" /></td>
		    </tr>
		    <tr>
		      <td class="membersRow1">New Password<br />[ confirm ]:</td>
		      <td class="membersRowRight1"><input type="password" name="new_password2" value="" /></td>
		    </tr>
		    <tr>
		      <td colspan="2" class="membersRowRight2" valign="bottom"><div align="center">
		        <input type="hidden" name="process" value="change_pass" />
		        <input type="submit" value="Change" /></div></td>
		    </tr>
		  </table>
		'.border('sred','end').'
		</form>
		<!-- End Password Change Box -->
    </td>
  </tr>
</table>';




if( $wowdb->sqldebug )
{
	if( is_array($queries) )
	{
		print "<!--\n";
		foreach( $queries as $sql )
		{
			print "$sql\n";
		}
		print "-->\n";
	}
}


include_once( ROSTER_BASE.'roster_footer.tpl' );









/**
 * Process Data for entry to the database
 *
 * @param array $post | $_POST array
 * @param string $config_name | db id to process
 */
function processData( $post )
{
	global $wowdb, $queries;

	$wowdb->reset_values();

	// Update only the changed fields
	foreach( $post as $settingName => $settingValue )
	{
		if( substr($settingName,0,7) == 'config_' )
		{
			$settingName = str_replace('config_','',$settingName);

			// Fix directories
			if( $settingName == 'img_url' || $settingName == 'interface_url' )
			{
				// Replace back-slashes
				$settingValue = preg_replace('|\\\\|','/',$settingValue );

				// Check for directories defined with no '/' at the end
				// and with a '/' at the beginning
				if( substr($settingValue, -1, 1) != '/' )
				{
					$settingValue .= '/';
				}
				if( substr($settingValue, 0, 1) == '/' )
				{
					$settingValue = substr($settingValue, 1);
				}
			}

			// Fix roster url
			if( $settingName == 'roster_dir' )
			{
				// Replace back-slashes
				$settingValue = preg_replace('|\\\\|','/',$settingValue );

				// Check for directories defined with no '/' at the end
				// and take it off
				if( substr($settingValue, -1, 1) == '/' )
				{
					$settingValue = substr($settingValue, 0, -1);
				}
			}

			$get_val = "SELECT `config_value` FROM `".ROSTER_CONFIGTABLE."` WHERE `config_name` = '".$settingName."';";
			$result = $wowdb->query($get_val)
				or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$get_val);

			$config = $wowdb->fetch_assoc($result);

			if( $config['config_value'] != $settingValue && $settingName != 'process' )
			{
				$update_sql[] = "UPDATE `".ROSTER_CONFIGTABLE."` SET `config_value` = '".$wowdb->escape( $settingValue )."' WHERE `config_name` = '".$settingName."';";
			}
		}
		elseif( substr($settingName,0,5) == 'disp_' )
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



/**
 * Get roster config data
 *
 * @return array on success, error string on failure
 */
function getConfigData ()
{
	global $wowdb, $wordings, $roster_conf;

	$sql = "SELECT * FROM `".ROSTER_CONFIGTABLE."` ORDER BY `id` ASC;";

	// Get the current config values
	$results = $wowdb->query($sql);
	if( $results && $wowdb->num_rows($results) > 0 )
	{
		while($row = $wowdb->fetch_assoc($results))
		{
			$setitem = stripslashes($row['config_type']);
			$arrayitem = stripslashes($row['config_name']);

			$db_values[$setitem][$arrayitem]['id'] = $row['id'];
			$db_values[$setitem][$arrayitem]['name'] = stripslashes($row['config_name']);
			$db_values[$setitem][$arrayitem]['config_type'] = stripslashes($row['config_type']);
			$db_values[$setitem][$arrayitem]['value'] = stripslashes($row['config_value']);
			$db_values[$setitem][$arrayitem]['form_type'] = stripslashes($row['form_type']);

			// Get description and tooltip
			$desc_tip = explode('|',$wordings[$roster_conf['roster_lang']]['admin'][$row['config_name']]);

			$db_values[$setitem][$arrayitem]['description'] = $desc_tip[0];

			$db_val_line = '<br /><br /><span style="color:#FFFFFF;font-size:10px;">db name: <span style="color:#0099FF;font-size:10px;">'.$row['config_name'].'</span></span>';
			$db_values[$setitem][$arrayitem]['tooltip'] = $desc_tip[1].$db_val_line;

		}
		return $db_values;
	}
	else
	{
		return $wowdb->error();
	}
}


/**
 * Get character config data
 *
 * @return array on success, error string on failure
 */
function getCharData ()
{
	global $wowdb, $wordings, $roster_conf;

	$sql = "SELECT ".
		"`member_id`, ".
		"`name`, ".
		"`inv`, ".
		"`talents`, ".
		"`quests`, ".
		"`bank`, ".
		"`spellbook`, ".
		"`mail`, ".
		"`money`, ".
		"`recipes`, ".
		"`bg`, ".
		"`pvp`, ".
		"`duels`, ".
		"`item_bonuses`".
		"FROM `".ROSTER_MEMBERSTABLE."` ORDER BY `name` ASC;";

	// Get the current config values
	$results = $wowdb->query($sql);
	if( $results && $wowdb->num_rows($results) > 0 )
	{
		while($row = $wowdb->fetch_assoc($results))
		{
			// See if player has data uploaded
			$data = $wowdb->query('SELECT `name` FROM `'.ROSTER_PLAYERSTABLE.'` WHERE `member_id` = \''.$row['member_id'].'\'');
			if( $wowdb->num_rows($data) > 0 )
				$db_values[] = $row;
		}
		return $db_values;
	}
	else
	{
		return $wowdb->error();
	}
}

function changePassword( $post )
{
	global $wowdb, $script_filename;

	// Get the current password
	$sql = "SELECT `config_value` FROM `".ROSTER_CONFIGTABLE."` WHERE `config_name` = 'roster_upd_pw'";
	$result = $wowdb->query($sql);
	if( $result && $wowdb->num_rows($result) > 0 )
	{
		$row = $wowdb->fetch_assoc($result);
		$db_pass = $row['config_value'];
	}
	else
	{
		return '<span style="font-size:11px;color:red;">Could not get old password from db</span>';
	}


	// Check for blank passwords
	if( $post['new_password1'] == '' || $post['new_password2'] == '' )
	{
		return '<span style="font-size:11px;color:red;">Roster does not allow blank passwords</span>';
	}

	// Check if the submitted passwords match
	if( $post['new_password1'] == $post['new_password2'] )
	{
		// Check if the passwords match the db
		if( md5($post['old_password']) == $db_pass )
		{
			// Check if the submitted pass matches the db pass
			if ( md5($post['new_password1']) == $db_pass )
			{
				return '<span style="font-size:11px;color:red;">New password same as old password</span>';
			}
			else
			{
				if( $wowdb->query("UPDATE `".ROSTER_CONFIGTABLE."` SET `config_value`='".md5($post['new_password1'])."' WHERE `config_name`='roster_upd_pw';") )
				{
					$title = 'Roster Password changed';
					$message = '<center>Your new password is<br /><br /><span style="font-size:11px;color:red;">'.$post['new_password1'].'</span><br /><br />Remember this, do NOT lose it!<br /><br />';
					$message .= 'Click <a href="'.$script_filename.'?logout=logout">HERE</a> to continue</center>';
					die_quietly($message,$title);
				}
				else
				{
					return '<span style="font-size:11px;color:red;">Roster Password NOT changed</span><br />There was a database error<br />'.$wowdb->error();
				}
			}
		}
		else
		{
			return '<span style="font-size:11px;color:red;">Old password was incorrect, password not changed</span>';
		}
	}
	else
	{
		return '<span style="font-size:11px;color:red;">New passwords do not match</span>';
	}
}


/**
 * Create a tooltip
 *
 * @param string $disp_text | Text to hover over
 * @param string $content | Content in tooltip
 * @param string $caption | Text in the caption
 * @return string ( Overlib styled tooltip )
 */
function createTip( $disp_text , $content , $caption )
{
	$content = str_replace("'","\'", $content);
	$content = str_replace('"','&quot;', $content);

	$caption = str_replace("'","\'", $caption);
	$caption = str_replace('"','&quot;', $caption);

	$tipsettings = ",WRAP";

	if( !empty($caption) )
		$caption2 = ",CAPTION,'$caption'";

	$tip = "<div style=\"cursor:help;\" onmouseover=\"return overlib('$content'$caption2$tipsettings);\" onmouseout=\"return nd();\">$disp_text</div>";

	return $tip;
}


function rosterLangValue()
{
	global $roster_conf;

	$input_field = '<select name="config_roster_lang">'."\n";
	$select_one = 1;
	foreach( $roster_conf['multilanguages'] as $value )
	{
		if( $value == $roster_conf['roster_lang'] && $select_one )
		{
			$input_field .= '  <option value="'.$value.'" selected="selected">&gt;'.$value.'&lt;</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$value.'">'.$value.'</option>'."\n";
		}
	}
	$input_field .= '</select>';

	return $input_field;
}

?>