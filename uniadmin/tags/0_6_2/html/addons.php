<?php

include("config.php");

function Main(){
	global $dblink, $config, $url, $_POST;

	$addonInputForm ="
<form method='post' ENCTYPE='multipart/form-data' action='addons.php'>
	<table class='uuTABLE' border='1'>
		<tr>
			<th colspan='2'><center><b>Add / Update Addon</b></center></th>
		</tr>
		<tr>
			<td>Required Addon?</td><td><input type='checkbox' checked='checked' name='required'></td>
		</tr>
		<tr>
			<td>Select file:</td><td><input type='file' name='file'></td>
		</tr>
		<tr>
			<td>Version:</td><td><input type='textbox' name='version'></td>
		</tr>
		<tr>
			<td>Homepage:</td><td><input type='textbox' name='homepage'></td>
		</tr>
		<tr>
			<td colspan='2'><center><input type='submit' value='Add / Update Addon'></center></td>
		</tr>
	</table>
	<input type='hidden' value='PROCESSUPLOAD' name='OPERATION'>
</form>
    	";

	$AddonPanel = "
		<table class='uuTABLE' border='1'>
			<tr>
				<th colspan='10'><center>Addon Management</center></th>
			</tr>
			<tr>
				<td><center><b>Name</b></center></td>
				<td><center><b>TOC</b></center></td>
				<td><center><b>Required</b></center></td>
				<td><center><b>Version</b></center></td>
				<td><center><b>Uploaded</b></center></td>
				<td><center><b>Enabled</b></center></td>
				<td><center><b>Files</b></center></td>
				<td><center><b>URL</b></center></td>
				<td><center><b>Delete</b></center></td>
				<td><center><b>Disable / Enable</b></center></td>
			</tr>";

	$sql = "SELECT * FROM `".$config['db_tables_addons']."` ORDER BY `name`";
	$result = mysql_query($sql,$dblink);

	while ($row = mysql_fetch_assoc($result)){

		$sql = "SELECT * FROM `".$config['db_tables_files']."` WHERE `addon_name` = '".addslashes($row['name'])."'";
		$result2 = mysql_query($sql,$dblink);
		$numFiles = mysql_num_rows($result2);
		$AddonName = $row['name'];
		$homepage = $row['homepage'];
		$version = '<center>'.$row['version'].'</center>';
		$time = date("M jS y H:i",$row['time_uploaded']);
		$url = $row['dl_url'];
		$addonID = $row['id'];
		if ($row['enabled'] == "1"){
			$enabled = "<font color='green'>yes</font>";
			$disableHREF = "<a href='addons.php?OPERATION=DISABLEADDON&amp;ADDONID=$addonID'>Disable</a>";
		}else{
			$enabled="<font color='red'>no</font>"; $disableHREF = "<a href='addons.php?OPERATION=ENABLEADDON&amp;ADDONID=$addonID'>Enable</a>";
		}
		if ($row['homepage'] == ""){
			$homepage = "./";
		}

		if ($row['required'] == 1)$required = '<center><font color="red"><b>yes</b></font></center>'; else $required = '<center><font color="green"><b>no</b></font></center>';
		$toc = '<center>'.$row['toc'].'</center>';

		$AddonPanel .="
		<tr>
			<td><a target=_blank href=\"$homepage\">$AddonName</a></td>
			<td>$toc</td>
			<td>$required</td>
			<td>$version</td>
			<td>$time</td>
			<td><center><b>$enabled</b></center></td>
			<td><center>$numFiles</center></td>
			<td><a href='$url' target='_BLANK'>Check</a></td>
			<td><a href='addons.php?OPERATION=DELADDON&amp;ADDONID=$addonID'>Delete!</a></td>
			<td><center>$disableHREF</center></td>
		</tr>
		";
	}
	$AddonPanel .= "</table>";




	EchoPage("
		<br>
		<br>
		<center>
			$AddonPanel
			<br>
			<BR>
			<BR>
			$addonInputForm
		</center>","Addons");
}

function DisableAddon(){
	global $dblink, $config, $url, $_REQUEST, $_SERVER;
	$id = $_REQUEST['ADDONID'];
	$sql = "UPDATE `".$config['db_tables_addons']."` SET `enabled` = '0' WHERE `id` = '$id' LIMIT 1 ;";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
}

function EnableAddon(){
	global $dblink, $config, $url, $_REQUEST, $_SERVER;
	$id = $_REQUEST['ADDONID'];
	$sql = "UPDATE `".$config['db_tables_addons']."` SET `enabled` = '1' WHERE `id` = '$id' LIMIT 1 ;";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
}

function DeleteAddon(){
	global $dblink, $config, $url, $_REQUEST, $_SERVER;
	$sep = DIRECTORY_SEPARATOR;
	$id = $_REQUEST['ADDONID'];
	$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `id` = '$id'";
	$result = mysql_query($sql, $dblink);
	$row = mysql_fetch_assoc($result);
	$name = $row['name'];
	$AddonUrl = $row['dl_url'];
	$k = explode("/",$AddonUrl);
	$fileName = $k[count($k) - 1];
	$scriptpath = explode($sep,$_SERVER['PATH_TRANSLATED']);
	array_pop($scriptpath);
	//$LocalPath = implode($sep,$scriptpath).$sep.$config['addon_folder'].$sep.$fileName;
	$LocalPath = dirname($_SERVER["SCRIPT_FILENAME"]).$sep.$config['addon_folder'].$sep.$fileName;
	unlink($LocalPath);
	$sql = "DELETE FROM `".$config['db_tables_addons']."` WHERE `id` = '$id'";
	mysql_query($sql,$dblink);
	$sql = "DELETE FROM `".$config['db_tables_files']."` WHERE `addon_name` LIKE '".addslashes($name)."';";
	mysql_query($sql,$dblink);
}

function unzipUsingPCLZIP($file, $path) {
	require_once('pclzip.lib.php');
	$archive = new PclZip($file);
	//$list = $archive->extract(PCLZIP_OPT_PATH, $path, PCLZIP_OPT_REMOVE_ALL_PATH);
	//$archive->extract(PCLZIP_OPT_PATH, $path, PCLZIP_OPT_REMOVE_ALL_PATH);
	$archive->extract(PCLZIP_OPT_PATH, $path); //removed PCLZIP_OPT_REMOVE_ALL_PATH to preserve file structure
}

function unzip($file, $path) {
	$sep = DIRECTORY_SEPARATOR;
	$zip = zip_open($file);
	if ($zip) {
		while ($zip_entry = zip_read($zip)) {
			if (zip_entry_filesize($zip_entry) > 0) {
				// str_replace must be used under windows to convert "/" into "\"
				$complete_path = $path.str_replace('/',$sep,dirname(zip_entry_name($zip_entry)));
				$complete_name = $path.str_replace ('/',$sep,zip_entry_name($zip_entry));
				if(!file_exists($complete_path)) {
					$tmp = '';
					foreach(explode($sep,$complete_path) AS $k) {
						$tmp .= $k.$sep;
						if(!file_exists($tmp)) {
							mkdir($tmp, 0777);
							chmod($tmp,0777);
						}
					}
				}
				if (zip_entry_open($zip, $zip_entry, "r")) {
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

function ls($dir, $array){
	$sep = DIRECTORY_SEPARATOR;
	$handle = opendir($dir);
	for(;(false !== ($readdir = readdir($handle)));){
		if($readdir != '.' && $readdir != '..' && $readdir != 'index.htm' && $readdir != 'index.html'){
			$path = $dir.$sep.$readdir;
			if(is_dir($path))  $array =  ls($path, $array);
			if(is_file($path)){$array[count($array)] = $path;}
		}
	}
	closedir($handle);
	return $array;
}

function processUploadedAddon(){
	$sep = DIRECTORY_SEPARATOR;
	global $dblink, $config, $url, $_SERVER;
	$tempFilename = $_FILES['file']['tmp_name'];
	$url = $config['URL'];
	$fileName = str_replace(" ","_",$_FILES['file']['name']);
	$addonFolder = dirname($_SERVER["SCRIPT_FILENAME"]).$sep.$config['addon_folder'];
	$tempFolder = dirname($_SERVER["SCRIPT_FILENAME"]).$sep.$config['temp_analyze_folder'];
	$version = $_POST['version'];
	$addonName = substr($fileName,0,count($fileName) -5);
	$homepage = $_POST['homepage'];
	if ($_POST['required'] == 'on')
		$required = 1;
	else
		$required = 0;

	if ($homepage == "") {
		$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `name` LIKE '".addslashes($addonName)."';";
		$result = mysql_query($sql,$dblink);
		$row = mysql_fetch_assoc($result);
		$homepage = $row['homepage'];
	}
	if ($version == "") {
		$sql = "SELECT * FROM `".$config['db_tables_addons']."` WHERE `name` LIKE '".addslashes($addonName)."';";
		$result = mysql_query($sql,$dblink);
		$row = mysql_fetch_assoc($result);
		$version = $row['version'];
	}



	$downloadLocation = $url.$config['addon_folder']."/".$fileName;

	$sql = "DELETE FROM `".$config['db_tables_addons']."` WHERE `name` LIKE '".addslashes($addonName)."';";
	mysql_query($sql,$dblink);
	$sql = "DELETE FROM `".$config['db_tables_files']."` WHERE `addon_name` LIKE '".addslashes($addonName)."';";
	mysql_query($sql,$dblink);





	@unlink($addonFolder.$sep.$fileName);//delete if exists
	move_uploaded_file($tempFilename,$addonFolder.$sep.$fileName);
	chmod($addonFolder.$sep.$fileName,0777);
	if ($config['ziplibsupport']){
		unzip($addonFolder.$sep.$fileName,$tempFolder.$sep);
	}else {
		unzipUsingPCLZIP($addonFolder.$sep.$fileName,$tempFolder.$sep);
	}
	$files = ls($tempFolder,array());



	$toc = "00000";
	foreach ($files as $file){
		if (getFileExtention($file) == 'toc')
		{
			$toc = getToc($file);

			$k = explode($sep,$file);
			$tocFileName = $k[count($k) - 1];
			$trueAddonName = substr($tocFileName,0,count($tocFileName) -5);
		}
	}

	foreach ($files as $file){
		$md5 = md5_file($file);
		$k = explode($sep,$file);
		$pos_t = strpos($file,"addon_temp");
		$fileName = substr($file,$pos_t + 10);




		if ($fileName != "index.htm" && $fileName != "index.html"){
			$sql = "INSERT INTO `".$config['db_tables_files']."` ( `id` , `addon_name` , `filename` , `md5sum` )VALUES (
		'', '".addslashes($addonName)."', '".addslashes($fileName)."', '".addslashes($md5)."');";
			mysql_query($sql,$dblink);
			unlink($file);//we have obtained the md5 and inserted the row into the database, now delete the temp file
		}
	}
	//now delete the temp folders
	foreach ($files as $file){
		$dir = explode($sep,$file);
		for($i=0;$i < count($dir);$i++){
			array_pop($dir);
			if ($dir[count($dir) - 1] == $config['temp_analyze_folder'])break;
			if (is_dir(implode($sep,$dir)))@rmdir(implode($sep,$dir));
		}
	}


	$sql = "INSERT INTO `".$config['db_tables_addons']."` ( `id` , `time_uploaded` , `version` , `enabled` , `name`, `dl_url`, `homepage`, `toc`, `required` )VALUES (
        '', '".time()."', '".addslashes($version)."', '1', '".addslashes($addonName)."', '".addslashes($downloadLocation)."', '".addslashes($homepage)."', $toc, $required);";
	mysql_query($sql,$dblink);

}

function getToc($file){
	$lines = file($file);
	foreach ($lines as $line) {
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

if (!isset ($_POST['OPERATION'])) {
	$op = $_REQUEST['OPERATION'];
} else {
	$op = $_POST['OPERATION'];
}


switch($op){
	case "PROCESSUPLOAD":
		processUploadedAddon();
		main();
		break;
	case "DELADDON":
		DeleteAddon();
		Main();
		break;
	case "DISABLEADDON":
		DisableAddon();
		Main();
		break;
	case "ENABLEADDON":
		EnableAddon();
		Main();
		break;
	default:
		Main();
		break;
}
?>