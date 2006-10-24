<?php

include("config.php");
if (!isset ($_POST['op'])) {
	$op = $op;
} else {
	$op = $_POST['op'];
}

if (!isset ($_POST['id'])) {
	$id = $id;
} else {
	$id = $_POST['id'];
}

if (!isset ($_POST)) {
	$post = $post;
} else {
	$post = $_POST;
}




$url = sprintf("%s%s%s","http://",$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']);
function Main(){
	global $dblink, $config, $_POST;

	//$sql = "SELECT * FROM `".$config['db_tables_settings']."` ORDER BY `enabled` DESC";
	$sql = "SELECT * FROM `".$config['db_tables_settings']."` ORDER BY `id` DESC";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

	$form = "<form method='post' ENCTYPE='multipart/form-data' action='settings.php'>
		<table class='uuTABLE'>
	<tr>
		<td class='tableHeader'><b>Setting Name</b></td>
		<td class='tableHeader'><b>Description</b></td>
		<td class='tableHeader'><b>Value</b></td>
		<td class='tableHeader'><b>Enabled</b></td>
	</tr>";

	while ($row = mysql_fetch_assoc($result)) {
		$description = stringChop($row['description'],25,"...");
		$setname = stringChop($row['set_name'],20,"...");
		
		if($i % 2){
			$tdClass = 'data2';
		}else{
			$tdClass = 'data1';
		}

		$form .= "
		<tr>
			<td class='$tdClass' title='".$row['set_name']."' caption='".$row['set_name']."'>$setname</td>
			<td class='$tdClass' 
			onmouseover=\"return overlib('<b>".$row['description'].":</b><br><br><img src=images/".$row['set_name'].".jpg>',VAUTO);\" onmouseout=\"return nd();\"
			>$description</td>      
			<td class='$tdClass'><input type='textbox' size='50' name='".$row['set_name']."' value='".$row['set_value']."'></td>";
		
		if ($row['enabled'] == "1"){
			$form .= "<td class='$tdClass'><center><input type='checkbox' name='".$row['set_name']."enabled' checked></center></td>";
		}else{
			$form .= "<td class='$tdClass'><center><input type='checkbox' name='".$row['set_name']."enabled'></center></td>";
		}
		$form .= "</tr>";
		$i++;
	}

	$form .= "<tr>
	<td colspan=4><center><input type='hidden' name='op' value='PROCESSUPDATE'><input type='submit' value='Update Settings'></center></td>
	</tr>
	</table>
	</form>";
	$sql = "SELECT * FROM `".$config['db_tables_svlist']."` ORDER BY `id` DESC";
	$result = mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);

	$svTable .= "
	<table class='uuTABLE'>
		<tr>
			<td class='tableHeader'><b>Saved Variable Name (filename)</b></td>
			<td class='tableHeader'><b>Remove</b></td>
		</tr>
	";
	while ($row = mysql_fetch_assoc($result)) {
		
		if($i % 2){
			$tdClass = 'data2';
		}else{
			$tdClass = 'data1';
		}
		
		$svTable .= "
		
		<tr>
			<td class='$tdClass'>".$row['sv_name']." <b> .lua</b></td>
			<td class='$tdClass'>
				<form method='post' ENCTYPE='multipart/form-data' action='settings.php'>
					<input type=submit value=Remove>
					<input type=hidden value=".$row['id']." name=svid>
					<input type=hidden value=removesv name=op>
				</form>
			</td>
		</tr>
		
		";
		
		$i++;
	}
	$svTable .= "
		<tr>
			<form method='post' ENCTYPE='multipart/form-data' action='settings.php'>
				<td class='tableHeader'><input type=text name=svname> <b> .lua</b></td>
				<td class='tableHeader'><input type=submit value=Add></td>
				<input type=hidden value=addsv name=op>
			</form>
		</tr>
	</table>
	
	
	
	";
	
	EchoPage("<br><br><center>".$svTable."<br>".$form."</center>","UU Critical Settings");
}

function stringChop($string, $desiredLength, $suffix){
	if (strlen($string) > $desiredLength){
		$string = substr($string,0,$desiredLength).$suffix;
		return $string;
	}
	return $string;
}

function ProcessUpdate(){
	global $dblink, $config, $post;
	foreach ($post as $settingName => $settingValue){
		if(substr_count(strtoupper($settingName),"enabled") > 1)break;
		if ($post["$settingName"."enabled"] == "on"){
			$enabled = "1";
		}else {
			$enabled = "0";
		}
		$sql = "UPDATE `".$config['db_tables_settings']."` SET `enabled` = '$enabled', `set_value` = '$settingValue' WHERE `set_name` = '$settingName' LIMIT 1 ;";
		mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
	}
}

function addSv(){
	global $dblink, $config, $post;
	$sql = "INSERT INTO `".$config['db_tables_svlist']."` ( `id` , `sv_name` ) VALUES ( '', '".$post['svname']."' );";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	
}

function removeSv(){
	global $dblink, $config, $post;
	$sql = "DELETE FROM `".$config['db_tables_svlist']."` WHERE `id` = ".$post['svid']." LIMIT 1;";
	mysql_query($sql,$dblink);
	MySqlCheck($dblink,$sql);
	
}

switch ($op){

	case "PROCESSUPDATE":
	ProcessUpdate();
	Main();
	break;
	
	case "addsv":
	addSv();
	Main();
	break;
	
	case "removesv":
	removeSv();
	Main();
	break;

	default:
	Main();
	break;
}






?>