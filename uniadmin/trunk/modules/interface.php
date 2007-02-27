<?php
/******************************
 * WoWRoster.net  UniAdmin
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

// Get Operation
$op = ( isset($_REQUEST['OPERATION']) ? $_REQUEST['OPERATION'] : 'VIEW' );

// Determine what version of UU is accessing this
$uu_patterns = $juu_patterns = array();

preg_match('|uniuploader(.+)\\(uu ([0-9].[0-9].[0-9])|i',$user->user_agent,$uu_patterns);
preg_match('|\(juu ([a-z]-[0-9]{2})|i',$user->user_agent,$juu_patterns);

if( isset($uu_patterns[2]) && version_compare($uu_patterns[2],'2.5.0','<') )
{
	define('UU_COMPAT', true);
}
elseif( isset($juu_patterns[1]) && version_compare($juu_patterns[1],'11','<') )
{
	define('UU_COMPAT', true);
}
else
{
	define('UU_COMPAT', false);
}



// Decide What To Do
switch( $op )
{
	case 'GETSETTINGS':
		update_stats($op);
		echo output_settings();
		break;

	case 'GETSETTINGSXML':
		update_stats($op);
		echo output_settings_xml();
		break;

	case 'GETADDONLIST':
		update_stats($op);
		echo output_addon_xml();
		break;

	case 'GETADDON':
		update_stats($op);
		echo output_addon_url($_REQUEST['ADDON']);
		break;

	case 'GETUAVER':
		update_stats($op);
		echo $uniadmin->config['UAVer'];
		break;

	case 'GETFILEMD5':
		echo output_logo_md5($_REQUEST['FILENAME']);
		break;

	default:
		update_stats($op);
		echo $user->lang['interface_ready'];
		break;
}
$db->close_db();






/**
 * Interface Page Functions
 */



/**
 * Adds viewer's stats for the UniAdmin stats page
 */
function update_stats( $op )
{
	global $db, $user;

	$action = ( isset($_REQUEST['ADDON']) ? $op.' - '.$_REQUEST['ADDON'] : $op );
	$remote_host = gethostbyaddr($user->ip_address);

	$sql = "INSERT INTO `".UA_TABLE_STATS."` ( `ip_addr` , `host_name` , `action` , `time` , `user_agent` ) VALUES
		( '".$db->escape($user->ip_address)."', '".$db->escape($remote_host)."', '".$db->escape($action)."', '".time()."', '".$db->escape($user->user_agent)."' );";
	$db->query($sql);
}

/**
 * Echo's all of UniAdmin's settings for UniUploader
 */
function output_settings( )
{
	global $db, $uniadmin;

	// Set delimiters correctly if UU_COMPAT is true
	if( UU_COMPAT )
	{
		$eq_sep = '=';
		$pipe_sep = '|';
	}
	else
	{
		$eq_sep = '[=]';
		$pipe_sep = '[|]';
	}

	$output_string = '';

	// logos
	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `active` = '1' ORDER BY `logo_num` ASC;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			$output_string .= 'LOGO'.$row['logo_num'].$eq_sep.$uniadmin->url_path.$uniadmin->config['logo_folder'].'/'.$row['filename'].$pipe_sep;
		}
	}
	$db->free_result($result);

	// settings
	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` WHERE `enabled` = '1' ORDER BY `set_name` ASC;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			$output_string .= $row['set_name'].$eq_sep.$row['set_value'].$pipe_sep;
		}
	}
	$db->free_result($result);

	// sv list
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."` ORDER BY `sv_name` ASC;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$output_string .= 'SVLIST'.$eq_sep;
		while( $row = $db->fetch_record($result) )
		{
			$output_string .= $row['sv_name'].$pipe_sep;
		}
	}
	$db->free_result($result);

	return $output_string;
}

/**
 * Echo's all of UniAdmin's settings for UniUploader
 * This output the settings in xml format
 */
function output_settings_xml( )
{
	global $db, $uniadmin;

	$output_string = "<uasettings>\n";

	// logos
	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `active` = '1' ORDER BY `logo_num` ASC;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$output_string .= "\t<logos>\n";
		while( $row = $db->fetch_record($result) )
		{
			$output_string .= "\t\t<logo id=\"".$row['logo_num']."\">".$uniadmin->url_path.$uniadmin->config['logo_folder'].'/'.$row['filename']."</logo>\n";
		}
		$output_string .= "\t</logos>\n";
	}
	$db->free_result($result);

	// settings
	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` WHERE `enabled` = '1' ORDER BY `set_name` ASC;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$output_string .= "\t<settings>\n";
		while( $row = $db->fetch_record($result) )
		{
			$output_string .= "\t\t<".$row['set_name'].'>'.$row['set_value'].'</'.$row['set_name'].">\n";
		}
		$output_string .= "\t</settings>\n";
	}
	$db->free_result($result);

	// sv list
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."` ORDER BY `sv_name` ASC;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$output_string .= "\t<svlist>\n";
		while( $row = $db->fetch_record($result) )
		{
			$output_string .= "\t\t<savedvariable>".$row['sv_name']."</savedvariable>\n";
		}
		$output_string .= "\t</svlist>\n";
	}
	$db->free_result($result);

	$output_string .= "</uasettings>\n";

	$output_string = str_replace('&','&amp;',$output_string);

	return $output_string;
}

/**
 * Echos XML for the addons UniAdmin provides
 */
function output_addon_xml( )
{
	global $db;

	$xml = '';

	// Don't get optional addons if UU_COMPAT is true
	if( UU_COMPAT )
	{
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `enabled` = '1' AND `required` = '1' ORDER BY `name` ASC;";
	}
	else
	{
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `enabled` = '1' ORDER BY `required` DESC, `name` ASC;";
	}
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		$xml = "<addons>\n";
		while( $row = $db->fetch_record($result) )
		{
			foreach( $row as $key => $value )
			{
				$row[$key] = str_replace('"','\\"',$value);
			}

			$xml .= "\t".'<addon name="'.$row['name'].'" version="'.$row['version'].'" required="'.$row['required'].'" homepage="'.$row['homepage'].'" filename="'.$row['file_name'].'" toc="'.$row['toc'].'" full_path="'.$row['full_path'].'" notes="'.$row['notes'].'">';

			$sql = "SELECT * FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '".$row['id']."';";
			$result2 = $db->query($sql);

			if( $db->num_rows($result2) > 0 )
			{
				while( $row2 = $db->fetch_record($result2) )
				{
					foreach( $row2 as $key => $value )
					{
						$row2[$key] = addslashes($value);
					}

					if( $row2['filename'] != 'index.htm' && $row2['filename'] != 'index.html' && $row2['filename'] != '.svn' )
					{
						$xml .= "\t\t".'<file name="'.$row2['filename'].'" md5sum="'.$row2['md5sum'].'" />'."\n";
					}
				}
			}
			$db->free_result($result2);

			$xml .= "\t</addon>\n";
		}
		$xml .= "</addons>";
	}
	$db->free_result($result);

	$xml = str_replace('&','&amp;',$xml);

	return $xml;
}

/**
 * Echos an addon's download URL
 */
function output_addon_url( $addonName )
{
	global $db, $uniadmin;

	$sql = "SELECT `name`, `file_name` FROM `".UA_TABLE_ADDONS."` WHERE `name` LIKE '".$db->escape($addonName)."';";
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		$row = $db->fetch_record($result);

		$download = $uniadmin->url_path.$uniadmin->config['addon_folder'].'/'.$row['file_name'];

		$db->free_result($result);
		return $download;
	}
	else
	{
		return '';
	}
}

/**
 * Echos a logo's md5 hash
 *
 * @param string $filename
 */
function output_logo_md5( $filename )
{
	global $db;

	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `filename` = '".$db->escape($filename)."';";
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		$row = $db->fetch_record($result);

		$db->free_result($result);
		return $row['md5'];
	}
	else
	{
		return '';
	}
}
