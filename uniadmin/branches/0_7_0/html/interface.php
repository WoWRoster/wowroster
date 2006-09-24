<?php
$interface = true;

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'set_env.php');

if( isset($_REQUEST['OPERATION']) )
{
	$op = $_REQUEST['OPERATION'];
}
else
{
	$op = '';
}

switch( $op )
{
	case 'GETADDON':
		AddStat();
		OutPutUrl();
		break;

	case 'GETADDONLIST':
		AddStat();
		OutPutXmL();
		break;

	case 'GETSETTINGS':
		AddStat();
		OutPutSettings();
		break;

	case 'GETUAVER':
		AddStat();
		echo $uniadmin->config['UAVer'];
		break;

	case 'GETFILEMD5':
		outputLogoMd5($_REQUEST['FILENAME']);
		break;

	default:
		AddStat();
		echo 'UniUploader Update Interface Ready...';
		break;
}



function outputLogoMd5($filename)
{
	global $db;

	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `filename` = '".$db->escape($filename)."';";
	$result = $db->query($sql);
	$row = $db->fetch_record($result);
	echo $row['md5'];
}

function OutPutSettings()
{
	global $db;

	//logos
	$sql = "SELECT * FROM `".UA_TABLE_LOGOS."` WHERE `active` = '1';";
	$result = $db->query($sql);
	while ($row = $db->fetch_record($result))
	{
		echo 'LOGO'.$row['logo_num'].'[=]'.$row['download_url'].'[|]';
	}

	//settings
	$sql = "SELECT * FROM `".UA_TABLE_SETTINGS."` WHERE `enabled` = '1';";
	$result = $db->query($sql);
	while ($row = $db->fetch_record($result))
	{
		echo $row['set_name'].'[=]'.$row['set_value'].'[|]';
	}

	//sv list
	$sql = "SELECT * FROM `".UA_TABLE_SVLIST."`;";
	$result = $db->query($sql);
	echo 'SVLIST[=]';
	while ($row = $db->fetch_record($result))
	{
		echo $row['sv_name'].':';
	}
}

function AddStat()
{
	global $db, $op;

	if (isset($_REQUEST['ADDON']))
	{
		$action = $db->escape($op.' - '.$_REQUEST['ADDON']);
	}
	else
	{
		$action = $db->escape($op);
	}
	$sql = "INSERT INTO `".UA_TABLE_STATS."` ( `ip_addr` , `host_name` , `action` , `time` , `user_agent` ) VALUES
		( '".$_SERVER['REMOTE_ADDR']."', '".$db->escape(gethostbyaddr($_SERVER['REMOTE_ADDR']))."', '$action', '".time()."', '".$db->escape($_SERVER['HTTP_USER_AGENT'])."' );";
	$db->query($sql);
}


function OutPutXmL()
{
	global $db;

	$xml = '<addons>';

	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `enabled` = '1';";
	$result = $db->query($sql);

	while ($row = $db->fetch_record($result))
	{
		$id = $row['id'];
		$name = addslashes($row['name']);
		$version = addslashes($row['version']);
		$required = addslashes($row['required']);
		$toc = addslashes($row['toc']);

		$xml .= "\n\t<addon name=\"$name\" version=\"$version\" required=\"$required\" toc=\"$toc\">";

		$sql = "SELECT * FROM `".UA_TABLE_FILES."` WHERE `addon_id` = '$id';";
		$result2 = $db->query($sql);

		while ($row2 = $db->fetch_record($result2))
		{
			$filename = addslashes($row2['filename']);
			$md5 = addslashes($row2['md5sum']);
			if ($filename != 'index.htm' && $filename != 'index.html' && $filename != '.svn')
			{
				$xml .= "\n\t\t<file name=\"$filename\" md5sum=\"$md5\" />";
			}
		}
		$xml .= "\n\t</addon>";
	}
	$xml .= "\n</addons>";

	echo $xml;
}

function OutPutUrl()
{
	global $db;

	$addonName = $_REQUEST['ADDON'];
	$sql = "SELECT * FROM `".UA_TABLE_ADDONS."` WHERE `name` = '".$db->escape($addonName)."';";
	$result = $db->query($sql);
	$row = $db->fetch_record($result);
	echo $row['dl_url'];
}

?>