<?php
$interface=1;
include("config.php");
include("EchoPage.php");

if (!isset ($_POST['op'])) {
	$op = "";
} else {
	$op = $_POST['op'];
}

function Main(){
	global $dblink, $config, $url, $_POST;

	$addonInputForm ="
	<form method='post' enctype='multipart/form-data' action='".UA_FORMACTION."users'>
	<table border='1'>
		<th colspan='2'>Add / Update Addon</th>
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
    		<td colspan='2'><input class='submit' type='submit' value='Add / Update Addon'>
    			<input type='hidden' value='PROCESSUPLOAD' name='OPERATION'></td>
    	</tr>
    </table>
    </form>
    	";

	$AddonPanel = "
		<table border='1'>
			<tr>
				<th colspan='8'>Addon Management</th>
			</tr>
			<tr>
				<td>Name</td>
				<td>Version</td>
				<td>Uploaded</td>
				<td>Enabled</td>
				<td>Files</td>
				<td>URL</td>
			</tr>";

	$sql = "SELECT * FROM `uniadmin_addons` ORDER BY `name`";
	$result = mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)){

		$sql = "SELECT * FROM `uniadmin_files` WHERE `addon_name` = '".addslashes($row['name'])."'";
		$result2 = mysql_query($sql);
		$numFiles = mysql_num_rows($result2);
		$AddonName = $row['name'];
		$version = $row['version'];
		$homepage = $row['homepage'];
		$time = date("M jS y H:i",$row['time_uploaded']);
		$url = $row['dl_url'];
		$addonID = $row['id'];
		if ($row['enabled'] == "1"){
			$enabled = "<font color='green'>yes</font>";
			$disableHREF = "<a href='".UA_FORMACTION."addons&amp;OPERATION=DISABLEADDON&ADDONID=$addonID'>Disable</a>";
		}else{
			$enabled="<font color='red'>no</font>"; $disableHREF = "<a href='".UA_FORMACTION."addons&amp;OPERATION=ENABLEADDON&ADDONID=$addonID'>Enable</a>";
		}
		$AddonPanel .="
		<tr>
			<td><a target=_blank href=\"$homepage\">$AddonName</a></td>
			<td>$version</td>
			<td>$time</td>
			<td>$enabled</td>
			<td>$numFiles</td>
			<td><a href='$url' target='_BLANK'>Get</a></td>
		</tr>
		";
	}
	$AddonPanel .= "</table>";




	EchoPage("
		<br>
		<br>

			$AddonPanel
			<br>
			<BR>
			<BR>
		","Addons");
}


function ls($dir, $array){
	$sep = DIRECTORY_SEPARATOR;
	$handle = opendir($dir);
	for(;(false !== ($readdir = readdir($handle)));){
		if($readdir != '.' && $readdir != '..'){
			$path = $dir.$sep.$readdir;
			if(is_dir($path))  $array =  ls($path, $array);
			if(is_file($path)){$array[count($array)] = $path;}
		}
	}
	closedir($handle);
	return $array;
}



//the switch function is bugged in my version of PHP, so had to use this:

if (!isset ($_REQUEST['OPERATION'])) {
	$op = "";
} else {
	$op = $_REQUEST['OPERATION'];
}


Main();



?>
