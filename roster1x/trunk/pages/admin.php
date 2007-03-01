<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin('admin');

// Disallow viewing of the page
if( !$roster_login->getAuthorized() )
{
	include_once (ROSTER_BASE.'roster_header.tpl');
	include_once (ROSTER_LIB.'menu.php');

	print
	'<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['roster_config'].'</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

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
			$roster_diag_message = processData();
			break;

		case 'change_pass';
			$ret_pass = changePassword();
			$roster_diag_message = $ret_pass;

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
	die("Cannot get roster configuration from database<br />\nMySQL Said: ".$wowdb->error()."<br /><br />\nYou might not have roster installed<br />\n<a href=\"install.php\">INSTALL</a>");
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
  <ul id="admin_menu" class="tab_menu">'."\n";

$first_tab = ' class="selected"';
foreach($conf_arrays as $type)
{
	$menu .= '    <li'.$first_tab.'><a href="#" rel="'.$type.'">'.$wordings[$roster_conf['roster_lang']]['admin'][$type].'</a></li>'."\n";
	$first_tab = '';
}

$menu .='
	<li><a href="#" rel="char_disp">'.$wordings[$roster_conf['roster_lang']]['admin']['per_character_display'].'</a></li>
	<li><a href="'.makelink('rosterdiag').'" target="_blank">'.$wordings[$roster_conf['roster_lang']]['rosterdiag'].'</a></li>
    <li><a href="http://www.wowroster.net/wiki" target="_blank">Documentation</a></li>
  </ul>
</div>
'.border('sgray','end').'
<!-- End Config Menu -->';



$form_start = "<form action=\"".makelink('admin')."\" method=\"post\" enctype=\"multipart/form-data\" id=\"config\" onsubmit=\"return confirm('".$wordings[$roster_conf['roster_lang']]['confirm_config_submit']."') && submitonce(this)\">\n";

$submit_button = "<input type=\"submit\" value=\"Save Settings\" />\n<input type=\"reset\" name=\"Reset\" value=\"Reset\" onClick=\"return confirm('".$wordings[$roster_conf['roster_lang']]['confirm_config_reset']."')\"/>\n<input type=\"hidden\" name=\"process\" value=\"process\" />\n<br /><br />\n";

$form_end = "</form>\n";


// Build the page
$html = '';
$k = 0;
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

				if( $length[1] < 20 )
					$text_class = '64';
				elseif( $length[1] < 30 )
					$text_class = '128';
				elseif( $length[1] < 40 )
					$text_class = '192';
				else
					$text_class = '';

				$input_field = '<input class="wowinput'.$text_class.'" name="config_'.$values['name'].'" type="text" value="'.$values['value'].'" size="'.$length[1].'" maxlength="'.$length[0].'" />';
				break;

			case 'radio':
				$options = explode('|',$input_type[1]);
				$rad=0;
				foreach( $options as $value )
				{
					$vals = explode('^',$value);
					$input_field .= '<input type="radio" id="rad'.$k.'_'.$i.'_'.$rad.'" name="config_'.$values['name'].'" value="'.$vals[1].'"'.( $values['value'] == $vals[1] ? ' checked="checked"' : '' ).' /><label for="rad'.$k.'_'.$i.'_'.$rad.'">'.$vals[0]."</label>\n";
					$rad++;
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
						$input_field .= '  <option value="'.$vals[1].'" selected="selected">-'.$vals[0].'-</option>'."\n";
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
	$k++;
}


// Build the character display control
if( is_array($char_data) )
{
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

		$k=0;
		foreach( $disp_array as $val_name )
		{
			$html .= '
	<td class="membersRow'.(($i%2)+1).'">';
			$html .= '<input type="radio" id="chard_f'.$k.'_'.$values['member_id'].'" name="disp_'.$values['member_id'].':'.$val_name.'" value="1" '.( $values[$val_name] == '1' ? 'checked="checked"' : '' ).' /><label for="chard_f'.$k.'_'.$values['member_id'].'">off</label><br />'."\n";
			$html .= '<input type="radio" id="chard_n'.$k.'_'.$values['member_id'].'" name="disp_'.$values['member_id'].':'.$val_name.'" value="3" '.( $values[$val_name] == '3' ? 'checked="checked"' : '' ).' /><label for="chard_n'.$k.'_'.$values['member_id'].'">on</label>'."\n";
			$html .= '</td>';

			$k++;
		}
		$html .= '</tr>';
		$i++;
	}
	$html .= "</table>\n".border('syellow','end')."\n</div>\n";
}
else
{
	$html .= "<div id=\"char_disp\" style=\"display:none;\">\n</div>\n";
}


// ----[ Render the page ]----------------------------------
$body_action = 'onLoad="initARC(\'config\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');"';
include_once( ROSTER_BASE.'roster_header.tpl' );


include_once( ROSTER_LIB.'menu.php' );


// ----[ Render the entire page ]---------------------------
print
'
<span class="title_text">'.$wordings[$roster_conf['roster_lang']]['roster_config'].'</span><br />'.
$roster_login->getMessage().
$roster_diag_message.
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
		<form action="'.makelink('admin').'" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="submitonce(this)">
		'.border('sred','start','Change Roster Password').'
		  <table class="bodyline" cellspacing="0" cellpadding="0">
		    <tr>
		      <td class="membersRow1">Old Password:</td>
		      <td class="membersRowRight1"><input class="wowinput128" type="password" name="old_password" value="" /></td>
		    </tr>
		    <tr>
		      <td class="membersRow2">New Password:</td>
		      <td class="membersRowRight2"><input class="wowinput128" type="password" name="new_password1" value="" /></td>
		    </tr>
		    <tr>
		      <td class="membersRow1">New Password<br />[ confirm ]:</td>
		      <td class="membersRowRight1"><input class="wowinput128" type="password" name="new_password2" value="" /></td>
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
</table>
<script type="text/javascript" language="JavaScript">
<!--
	initializetabcontent("admin_menu");
//-->
</script>';




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
 * @return string Settings changed or not changed
 */
function processData( )
{
	global $wowdb, $queries;

	$wowdb->reset_values();

	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		// Strip those nasty slashes
		$settingValue = stripslashes($settingValue);

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
function getConfigData( )
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

			$db_val_line = '<br /><br />db name: <span style="color:#0099FF;">'.$row['config_name'].'</span>';
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
function getCharData( )
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

function changePassword( )
{
	global $wowdb;

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
	if( $_POST['new_password1'] == '' || $_POST['new_password2'] == '' )
	{
		return '<span style="font-size:11px;color:red;">Roster does not allow blank passwords</span>';
	}

	// Check if the submitted passwords match
	if( $_POST['new_password1'] == $_POST['new_password2'] )
	{
		// Check if the passwords match the db
		if( md5($_POST['old_password']) == $db_pass )
		{
			// Check if the submitted pass matches the db pass
			if ( md5($_POST['new_password1']) == $db_pass )
			{
				return '<span style="font-size:11px;color:red;">New password same as old password</span>';
			}
			else
			{
				if( $wowdb->query("UPDATE `".ROSTER_CONFIGTABLE."` SET `config_value`='".md5($_POST['new_password1'])."' WHERE `config_name`='roster_upd_pw';") )
				{
					$title = 'Roster Password changed';
					$message = '<div style="width=100%" align="center">Your new password is<br /><br /><span style="font-size:11px;color:red;">'.$_POST['new_password1'].'</span><br /><br />Remember this, do NOT lose it!<br /><br />';
					$message .= 'Click <form style="display:inline;" name="roster_logout" action="'.makelink('admin').'" method="post"><input type="hidden" name="logout" value="1" />[<a href="javascript: document.roster_logout.submit();">HERE</a>]</form> to continue</div>';
					roster_die($message,$title);
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
	$tipsettings = ",WRAP";

	$tip = "<div style=\"cursor:help;\" ".makeOverlib($content,$caption,'',2,'',$tipsettings).">$disp_text</div>";

	return $tip;
}


function rosterLangValue( )
{
	global $roster_conf;

	$input_field = '<select name="config_roster_lang">'."\n";
	$select_one = 1;
	foreach( $roster_conf['multilanguages'] as $value )
	{
		if( $value == $roster_conf['roster_lang'] && $select_one )
		{
			$input_field .= '  <option value="'.$value.'" selected="selected">-'.$value.'-</option>'."\n";
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

function pageNames( )
{
	global $roster_conf;

	/**
	 * Scan the pages directory to generate a list of available pages
	 */
	if( $handle = opendir(ROSTER_PAGES) )
	{
		$roster_conf['roster_pages'] = array();
		while( false !== ($file = readdir($handle)) )
		{
			if( !is_dir(ROSTER_PAGES.$file) && $file != '.' && $file != '..' && $file != 'addon.php' && !preg_match('/[^a-zA-Z0-9_.]/', $file) && get_file_ext($file) == 'php' )
			{
				$pages[] = array(substr($file,0,strpos($file,'.')),substr($file,0,strpos($file,'.')));
			}
		}
	}
	
	$addonlist = makeAddonList(2);
	
	$pages = array_merge($pages, $addonlist);

	$input_field = '<select name="config_default_page">'."\n";
	$select_one = 1;
	
	foreach( $pages as $value )
	{
		if( $value[0] == $roster_conf['default_page'] && $select_one )
		{
			$input_field .= '  <option value="'.$value[0].'" selected="selected">-'.$value[1].'-</option>'."\n";
			$select_one = 0;
		}
		else
		{
			$input_field .= '  <option value="'.$value[0].'">'.$value[1].'</option>'."\n";
		}
	}
	$input_field .= '</select>';

	return $input_field;
}
