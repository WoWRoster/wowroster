<?php
/******************************
 * WoWRoster.net  UniAdmin
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

$interface = true;
$uniadmin = '';

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');

// Get Operation
$op = ( isset($_REQUEST['OPERATION']) ? $_REQUEST['OPERATION'] : '' );

// Determine what version of UU is accessing this
$ua_compat_mode = false;
$patterns = array();
preg_match('|UniUploader 2.0 \\(UU ([0-9].[0-9].[0-9]);|',$user->user_agent,$patterns);

if( isset($patterns[1]) && version_compare($patterns[1],'2.5.0','<') )
{
	$ua_compat_mode = true;
}


// Decide What To Do
switch( $op )
{
	case 'GETADDON':
		add_stat($op);
		output_url($_REQUEST['ADDON']);
		break;

	case 'GETADDONLIST':
		add_stat($op);
		output_xml();
		break;

	case 'GETSETTINGS':
		add_stat($op);
		output_settings();
		break;

	case 'GETUAVER':
		add_stat($op);
		echo $uniadmin->config['UAVer'];
		break;

	case 'GETFILEMD5':
		output_logo_md5($_REQUEST['FILENAME']);
		break;

	default:
		$op = 'VIEW';
		add_stat($op);
		echo $user->lang['interface_ready'];
		break;
}
$db->close_db();






/**
 * Interface Page Functions
 */


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
		echo $row['md5'];
	}
	$db->free_result($result);
}

/**
 * Echo's all of UniAdmin's settings for UniUploader
 */
function output_settings( )
{
	global $db, $ua_compat_mode;

	if( $ua_compat_mode )
	{
		$eq_sep = '=';
		$pipe_sep = '|';
	}
	else
	{
		$eq_sep = '[=]';
		$pipe_sep = '[|]';
	}

	// logos
	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `active` = '1';";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			echo 'LOGO'.$row['logo_num'].$eq_sep.$row['download_url'].$pipe_sep;
		}
	}
	$db->free_result($result);

	// settings
	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` WHERE `enabled` = '1';";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			echo $row['set_name'].$eq_sep.$row['set_value'].$pipe_sep;
		}
	}
	$db->free_result($result);

	// sv list
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."`;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		echo 'SVLIST'.$eq_sep;
		while( $row = $db->fetch_record($result) )
		{
			echo $row['sv_name'].':';
		}
	}
	$db->free_result($result);
}

/**
 * Adds viewer's stats for the UniAdmin stats page
 */
function add_stat( $op )
{
	global $db, $user;

	$action = ( isset($_REQUEST['ADDON']) ? $op.' - '.$_REQUEST['ADDON'] : $op );
	$remote_host = gethostbyaddr($user->ip_address);

	$sql = "INSERT INTO `".UA_TABLE_STATS."` ( `ip_addr` , `host_name` , `action` , `time` , `user_agent` ) VALUES
		( '".$db->escape($user->ip_address)."', '".$db->escape($remote_host)."', '".$db->escape($action)."', '".time()."', '".$db->escape($user->user_agent)."' );";
	$db->query($sql);
}

/**
 * Echos XML for the addons UniAdmin provides
 */
function output_xml( )
{
	global $db, $ua_compat_mode;

	$xml = '<addons>';

	// Don't get optional addons is UU is lower than 2.5.0
	if( $ua_compat_mode )
	{
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `enabled` = '1' AND `required` = '1';";
	}
	else
	{
		$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `enabled` = '1';";
	}
	$result = $db->query($sql);

	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			$id = $row['id'];
			$name = addslashes($row['name']);
			$version = addslashes($row['version']);
			$required = addslashes($row['required']);
			$toc = addslashes($row['toc']);

			$xml .= "\n\t<addon name=\"$name\" version=\"$version\" required=\"$required\" toc=\"$toc\">";

			$sql = "SELECT * FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$id';";
			$result2 = $db->query($sql);

			if( $db->num_rows($result2) > 0 )
			{
				while( $row2 = $db->fetch_record($result2) )
				{
					$filename = addslashes($row2['filename']);
					$md5 = addslashes($row2['md5sum']);
					if ($filename != 'index.htm' && $filename != 'index.html' && $filename != '.svn')
					{
						$xml .= "\n\t\t<file name=\"$filename\" md5sum=\"$md5\" />";
					}
				}
			}
			$db->free_result($result2);

			$xml .= "\n\t</addon>";
		}
	}
	$db->free_result($result);

	$xml .= "\n</addons>";

	echo $xml;
}

/**
 * Echos an addon's download URL
 */
function output_url( $addonName )
{
	global $db;

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `name` = '".$db->escape($addonName)."';";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		$row = $db->fetch_record($result);
		echo $row['dl_url'];
	}
	$db->free_result($result);
}

?>