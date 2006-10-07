<?php

$interface = true;

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');

// Get Operation
$op = ( isset($_REQUEST['OPERATION']) ? $_REQUEST['OPERATION'] : '' );

// Decide What To Do
switch( $op )
{
	case 'GETADDON':
		addStat();
		outputUrl();
		break;

	case 'GETADDONLIST':
		addStat();
		outputXml();
		break;

	case 'GETSETTINGS':
		addStat();
		outputSettings();
		break;

	case 'GETUAVER':
		addStat();
		echo $uniadmin->config['UAVer'];
		break;

	case 'GETFILEMD5':
		outputLogoMd5($_REQUEST['FILENAME']);
		break;

	default:
		$op = 'TEST';
		addStat();
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
function outputLogoMd5( $filename )
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
function outputSettings( )
{
	global $db;

	// logos
	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `active` = '1';";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		while( $row = $db->fetch_record($result) )
		{
			echo 'LOGO'.$row['logo_num'].'[=]'.$row['download_url'].'[|]';
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
			echo $row['set_name'].'[=]'.$row['set_value'].'[|]';
		}
	}
	$db->free_result($result);

	// sv list
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."`;";
	$result = $db->query($sql);
	if( $db->num_rows($result) > 0 )
	{
		echo 'SVLIST[=]';
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
function addStat( )
{
	global $db, $op;

	$action = ( isset($_REQUEST['ADDON']) ? $op.' - '.$_REQUEST['ADDON'] : $op );
	$remote_address = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '' );
	$remote_host = gethostbyaddr($remote_address);
	$user_agent = ( isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '' );

	$sql = "INSERT INTO `".UA_TABLE_STATS."` ( `ip_addr` , `host_name` , `action` , `time` , `user_agent` ) VALUES
		( '".$db->escape($remote_address)."', '".$db->escape($remote_host)."', '".$db->escape($action)."', '".time()."', '".$db->escape($user_agent)."' );";
	$db->query($sql);
}

/**
 * Echos XML for the addons UniAdmin provides
 */
function outputXml( )
{
	global $db;

	$xml = '<addons>';

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `enabled` = '1';";
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
function outputUrl( )
{
	global $db;

	$addonName = $_REQUEST['ADDON'];

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