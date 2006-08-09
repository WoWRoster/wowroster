<?php
$interface = true;

include('set_env.php');

if( isset($_REQUEST['OPERATION']) )
	$op = $_REQUEST['OPERATION'];
else
	$op = '';

switch( $op )
{
	case 'GETADDON':
		OutPutUrl();
		AddStat();
		break;

	case 'GETADDONLIST':
		OutPutXmL();
		AddStat();
		break;

	case 'GETSETTINGS':
		OutPutSettings();
		AddStat();
		break;

	case 'GETFILEMD5':
		outputLogoMd5($_REQUEST['FILENAME']);
		break;

	default:
		echo 'UniUploader Update Interface Ready...';
		AddStat();
		break;
}



function outputLogoMd5($filename)
{
	global $dblink, $config;

	$sql = "SELECT * FROM `".$config['db_tables_logos']."` WHERE `filename` = '$filename'";
	$result = mysql_query($sql,$dblink);
	$row = mysql_fetch_assoc($result);
	echo $row['md5'];
	//echo $filename;
}

function OutPutSettings()
{
	global $dblink, $config;

	//logos
	$sql = "SELECT * FROM `".$config['db_tables_logos']."` WHERE `active` = '1'";
	$result = mysql_query($sql,$dblink);
	while ($row = mysql_fetch_assoc($result))
	{
		echo "LOGO".$row['logo_num']."=".$row['download_url']."|";
	}
	//settings
	$sql = "SELECT * FROM `".$config['db_tables_settings']."` WHERE `enabled` = '1'";
	$result = mysql_query($sql,$dblink);
	while ($row = mysql_fetch_assoc($result))
	{
		echo $row['set_name']."=".$row['set_value']."|";
	}
	//sv list
	$sql = "SELECT * FROM `".$config['db_tables_svlist']."`";
	$result = mysql_query($sql,$dblink);
	echo "SVLIST=";
	while ($row = mysql_fetch_assoc($result))
	{
		echo $row['sv_name'].":";
	}

}

function AddStat()
{
	global $dblink, $config, $op;

	if (isset($_REQUEST['ADDON']))
	{
		$action = addslashes($op." - ".$_REQUEST['ADDON']);
	}
	else
	{
		$action = addslashes($op);
	}
	$sql = "INSERT INTO `".$config['db_tables_stats']."` ( `id` , `ip_addr` , `host_name` , `action` , `time` , `user_agent` ) VALUES
		( '', '".$_SERVER['REMOTE_ADDR']."', '".addslashes(gethostbyaddr($_SERVER['REMOTE_ADDR']))."', '$action', '".time()."', '".addslashes($_SERVER["HTTP_USER_AGENT"])."' );";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
}


function OutPutXmL()
{
	global $dblink, $config;

	$xml = "<addons>";

	$sql = "SELECT * FROM `".$config['db_tables_addons']."`";
	$result = mysql_query($sql,$dblink);

	while ($row = mysql_fetch_assoc($result))
	{
		if ($row['enabled']=="1")
		{
			$name = $row['name'];
			$version = $row['version'];
			$xml .= "
	<addon name=\"$name\" version=\"$version\" >";
			$sql = "SELECT * FROM `".$config['db_tables_files']."` WHERE `addon_name` = '".addslashes($name)."'";
			$result2 = mysql_query($sql);
			while ($row2 = mysql_fetch_assoc($result2))
			{
				$filename = $row2['filename'];
				$md5 = $row2['md5sum'];
				if ($filename != "index.htm" && $filename != "index.html")
				{
					$xml .="
		<file name=\"$filename\" md5sum=\"$md5\" />";
				}

			}
			$xml .= "
	</addon>";
		}
	}
	$xml .= "
</addons>";

	echo $xml;
}

function OutPutUrl()
{
	global $dblink, $config;

	$addonName = $_REQUEST['ADDON'];
	$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `name` = '".addslashes($addonName)."'";
	$result = mysql_query($sql,$dblink);
	$row = mysql_fetch_assoc($result);
	echo $row['dl_url'];
}

?>