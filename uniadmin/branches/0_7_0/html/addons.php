<?php

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

function Main()
{
	global $dblink, $config, $url;

	$addonInputForm ="
<form method='post' enctype='multipart/form-data' action='".UA_FORMACTION."addons'>
	<table class='uuTABLE' border='0'>
		<tr>
			<th class='tableHeader' colspan='2'>Add / Update Addon</th>
		</tr>
		<tr>
			<td>Required Addon?</td>
			<td><input type='checkbox' name='required' checked='checked'></td>
		</tr>
		<tr>
			<td>Select file:</td>
			<td><input class='file' type='file' name='file'></td>
		</tr>
		<tr>
			<td>Version:</td>
			<td><input class='input' type='textbox' name='version'></td>
		</tr>
		<tr>
			<td>Homepage:</td>
			<td><input class='input' type='textbox' name='homepage'></td>
		</tr>
		<tr>
			<td colspan='2'><input class='submit' type='submit' value='Add / Update Addon'></td>
		</tr>
	</table>
	<input type='hidden' value='PROCESSUPLOAD' name='OPERATION'>
</form>
    	";

	$AddonPanel = "
		<table class='uuTABLE' border='0'>
			<tr>
				<th class='tableHeader' colspan='10'>Addon Management</th>
			</tr>
			<tr>
				<td class='dataHeader'>Name</td>
				<td class='dataHeader'>TOC</td>
				<td class='dataHeader'>Required</td>
				<td class='dataHeader'>Version</td>
				<td class='dataHeader'>Uploaded</td>
				<td class='dataHeader'>Enabled</td>
				<td class='dataHeader'>Files</td>
				<td class='dataHeader'>URL</td>
				<td class='dataHeader'>Delete</td>
				<td class='dataHeader'>Disable / Enable</td>
			</tr>";

	$sql = "SELECT * FROM `".$config['db_tables_addons']."` ORDER BY `name`";
	$result = mysql_query($sql,$dblink);

	$i=0;
	while ($row = mysql_fetch_assoc($result))
	{
		$sql = "SELECT * FROM `".$config['db_tables_files']."` WHERE `addon_name` = '".addslashes($row['name'])."'";
		$result2 = mysql_query($sql,$dblink);
		$numFiles = mysql_num_rows($result2);
		$AddonName = $row['name'];
		$homepage = $row['homepage'];
		$version = $row['version'];
		$time = date('M jS y H:i',$row['time_uploaded']);
		$url = $row['dl_url'];
		$addonID = $row['id'];
		if ($row['enabled'] == '1')
		{
			$enabled = "<span style='color:green;font-weight:bold;'>yes</span>";
			$disableHREF = "<a href='".UA_FORMACTION."addons&amp;OPERATION=DISABLEADDON&amp;ADDONID=$addonID'>Disable</a>";
		}
		else
		{
			$enabled="<span style='color:red;font-weight:bold;'>no</span>"; $disableHREF = "<a href='".UA_FORMACTION."addons&amp;OPERATION=ENABLEADDON&amp;ADDONID=$addonID'>Enable</a>";
		}
		if ($row['homepage'] == '')
		{
			$homepage = './';
		}

		if ($row['required'] == 1)
			$required = '<span style="color:red;font-weight:bold;">yes</span>';
		else
			$required = '<span style="color:green;font-weight:bold;">no</span>';
		$toc = $row['toc'];

		if($i % 2)
		{
			$tdClass = 'data2';
		}
		else
		{
			$tdClass = 'data1';
		}

		$AddonPanel .="
		<tr>
			<td class='$tdClass'><a target='_blank' href=\"$homepage\">$AddonName</a></td>
			<td class='$tdClass'>$toc</td>
			<td class='$tdClass'>$required</td>
			<td class='$tdClass'>$version</td>
			<td class='$tdClass'>$time</td>
			<td class='$tdClass'>$enabled</td>
			<td class='$tdClass'>$numFiles</td>
			<td class='$tdClass'><a href='$url'>Check</a></td>
			<td class='$tdClass'><a href='".UA_FORMACTION."addons&amp;OPERATION=DELADDON&amp;ADDONID=$addonID'>Delete!</a></td>
			<td class='$tdClass'>$disableHREF</td>
		</tr>
		";
		$i++;
	}
	$AddonPanel .= '</table>';




	EchoPage("
			$AddonPanel
			<br />
			<br />
			<br />
			$addonInputForm",'Addons');
}

function DisableAddon()
{
	global $dblink, $config, $url;

	$id = $_REQUEST['ADDONID'];
	$sql = "UPDATE `".$config['db_tables_addons']."` SET `enabled` = '0' WHERE `id` = '$id' LIMIT 1 ;";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
}

function EnableAddon()
{
	global $dblink, $config, $url;

	$id = $_REQUEST['ADDONID'];
	$sql = "UPDATE `".$config['db_tables_addons']."` SET `enabled` = '1' WHERE `id` = '$id' LIMIT 1 ;";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
}

function DeleteAddon()
{
	global $dblink, $config, $url;

	$sep = DIRECTORY_SEPARATOR;
	$id = $_REQUEST['ADDONID'];
	$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `id` = '$id'";
	$result = mysql_query($sql, $dblink);
	$row = mysql_fetch_assoc($result);
	$name = $row['name'];
	$AddonUrl = $row['dl_url'];
	$k = explode('/',$AddonUrl);
	$fileName = $k[count($k) - 1];

	$LocalPath = UA_BASEDIR.$config['addon_folder'].$sep.$fileName;
	unlink($LocalPath);
	$sql = "DELETE FROM `".$config['db_tables_addons']."` WHERE `id` = '$id'";
	mysql_query($sql,$dblink);
	$sql = "DELETE FROM `".$config['db_tables_files']."` WHERE `addon_name` LIKE '".addslashes($name)."';";
	mysql_query($sql,$dblink);
}

function unzipUsingPCLZIP($file, $path)
{
	require_once('pclzip.lib.php');
	$archive = new PclZip($file);
	//$list = $archive->extract(PCLZIP_OPT_PATH, $path, PCLZIP_OPT_REMOVE_ALL_PATH);
	//$archive->extract(PCLZIP_OPT_PATH, $path, PCLZIP_OPT_REMOVE_ALL_PATH);
	$archive->extract(PCLZIP_OPT_PATH, $path); //removed PCLZIP_OPT_REMOVE_ALL_PATH to preserve file structure
}

function unzip($file, $path)
{
	$sep = DIRECTORY_SEPARATOR;
	$zip = zip_open($file);
	if ($zip)
	{
		while ($zip_entry = zip_read($zip))
		{
			if (zip_entry_filesize($zip_entry) > 0)
			{
				// str_replace must be used under windows to convert "/" into "\"
				$complete_path = $path.str_replace('/',$sep,dirname(zip_entry_name($zip_entry)));
				$complete_name = $path.str_replace ('/',$sep,zip_entry_name($zip_entry));
				if(!file_exists($complete_path))
				{
					$tmp = '';
					foreach(explode($sep,$complete_path) AS $k)
					{
						$tmp .= $k.$sep;
						if(!file_exists($tmp))
						{
							mkdir($tmp, 0777);
							chmod($tmp,0777);
						}
					}
				}
				if (zip_entry_open($zip, $zip_entry, 'r'))
				{
					$fd = fopen($complete_name, 'w');
					fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
					fclose($fd);
					zip_entry_close($zip_entry);
				}
			}
		}
		zip_close($zip);
	}
}

function ls($dir, $array)
{
	$sep = DIRECTORY_SEPARATOR;
	$handle = opendir($dir);
	for(;(false !== ($readdir = readdir($handle)));)
	{
		if($readdir != '.' && $readdir != '..' && $readdir != 'index.htm' && $readdir != 'index.html')
		{
			$path = $dir.$sep.$readdir;
			if(is_dir($path))
				$array =  ls($path, $array);
			if(is_file($path))
			{
				$array[count($array)] = $path;
			}
		}
	}
	closedir($handle);
	return $array;
}

function processUploadedAddon()
{
	$sep = DIRECTORY_SEPARATOR;
	global $dblink, $config, $url;

	$tempFilename = $_FILES['file']['tmp_name'];
	$url = $config['URL'];
	$fileName = str_replace(' ','_',$_FILES['file']['name']);
	$addonFolder = UA_BASEDIR.$config['addon_folder'];
	$tempFolder = UA_BASEDIR.$config['temp_analyze_folder'];
	$version = $_POST['version'];
	$addonName = substr($fileName,0,count($fileName) -5);
	$homepage = $_POST['homepage'];
	if ($_POST['required'] == 'on')
		$required = 1;
	else
		$required = 0;

	if ($homepage == '')
	{
		$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `name` LIKE '".addslashes($addonName)."';";
		$result = mysql_query($sql,$dblink);
		$row = mysql_fetch_assoc($result);
		$homepage = $row['homepage'];
	}
	if ($version == '')
	{
		$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `name` LIKE '".addslashes($addonName)."';";
		$result = mysql_query($sql,$dblink);
		$row = mysql_fetch_assoc($result);
		$version = $row['version'];
	}


	$downloadLocation = $url.$config['addon_folder'].'/'.$fileName;

	$sql = "DELETE FROM `".$config['db_tables_addons']."` WHERE `name` LIKE '".addslashes($addonName)."';";
	mysql_query($sql,$dblink);
	$sql = "DELETE FROM `".$config['db_tables_files']."` WHERE `addon_name` LIKE '".addslashes($addonName)."';";
	mysql_query($sql,$dblink);





	@unlink($addonFolder.$sep.$fileName);//delete if exists
	move_uploaded_file($tempFilename,$addonFolder.$sep.$fileName);
	chmod($addonFolder.$sep.$fileName,0777);
	if ($config['ziplibsupport'])
	{
		unzip($addonFolder.$sep.$fileName,$tempFolder.$sep);
	}
	else
	{
		unzipUsingPCLZIP($addonFolder.$sep.$fileName,$tempFolder.$sep);
	}
	$files = ls($tempFolder,array());



	$toc = '00000';
	foreach ($files as $file)
	{
		if (getFileExtention($file) == 'toc')
		{
			$toc = getToc($file);

			$k = explode($sep,$file);
			$tocFileName = $k[count($k) - 1];
			$trueAddonName = substr($tocFileName,0,count($tocFileName) -5);
		}
	}

	foreach ($files as $file)
	{
		$md5 = md5_file($file);
		$k = explode($sep,$file);
		$pos_t = strpos($file,'addon_temp');
		$fileName = substr($file,$pos_t + 10);


		if ($fileName != 'index.htm' && $fileName != 'index.html')
		{
			$sql = "INSERT INTO `".$config['db_tables_files']."` ( `id` , `addon_name` , `filename` , `md5sum` )VALUES (
		'', '".addslashes($addonName)."', '".addslashes($fileName)."', '".addslashes($md5)."');";
			mysql_query($sql,$dblink);
			unlink($file);//we have obtained the md5 and inserted the row into the database, now delete the temp file
		}
	}
	//now delete the temp folders
	foreach ($files as $file)
	{
		$dir = explode($sep,$file);
		for($i=0;$i < count($dir);$i++)
		{
			array_pop($dir);
			if ($dir[count($dir) - 1] == $config['temp_analyze_folder'])
				break;
			if (is_dir(implode($sep,$dir)))
				@rmdir(implode($sep,$dir));
		}
	}


	$sql = "INSERT INTO `".$config['db_tables_addons']."` ( `id` , `time_uploaded` , `version` , `enabled` , `name`, `dl_url`, `homepage`, `toc`, `required` )VALUES (
        '', '".time()."', '".addslashes($version)."', '1', '".addslashes($addonName)."', '".addslashes($downloadLocation)."', '".addslashes($homepage)."', $toc, $required);";
	mysql_query($sql,$dblink);

}

function getToc($file)
{
	$lines = file($file);
	foreach ($lines as $line)
	{
		$IntPos = strpos(strtoupper($line),strtoupper('Interface:'));
		if ( $IntPos !== false )
		{
			return substr($line, $IntPos + 10);
		}
	}
}

function getFileExtention($filename){
	return strtolower(ltrim(strrchr($filename,'.'),'.'));
}

if( isset($_REQUEST['OPERATION']) )
{
	$op = $_REQUEST['OPERATION'];
}
elseif( isset($_POST['OPERATION']) )
{
	$op = $_POST['OPERATION'];
}
else
{
	$op = '';
}


switch($op)
{
	case 'PROCESSUPLOAD':
		processUploadedAddon();
		main();
		break;
	case 'DELADDON':
		DeleteAddon();
		Main();
		break;
	case 'DISABLEADDON':
		DisableAddon();
		Main();
		break;
	case 'ENABLEADDON':
		EnableAddon();
		Main();
		break;
	default:
		Main();
		break;
}
?>