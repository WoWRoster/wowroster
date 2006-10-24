<?php

include("config.php");

$op = $_POST['op'];
if (isset ($_REQUEST['op']))$op = $_REQUEST['op'];
$id = $_REQUEST['id'];
$post = $_POST;
$files = $_FILES;




function Main(){
	global $dblink, $config, $_POST;
	$sql = "SELECT * FROM `".$config['db_tables_logos']."`";
	$result = mysql_query($sql,$dblink);

	while ($row = mysql_fetch_assoc($result)) {
		switch ($row['logo_num'])
		{
			case "1":
			$logo1['filename'] = $row['filename'];
			$logo1['updated'] = date("M jS y H:i",$row['updated']);
			if ( $row['active']=="1"){$logo1['active'] ="Yes";}else{$logo1['active'] ="No";}
			$logo1['id'] = $row['id'];
			break;

			case "2":
			$logo2['filename'] = $row['filename'];
			$logo2['updated'] = date("M jS y H:i",$row['updated']);
			$logo2['id'] = $row['id'];
			if ( $row['active']=="1"){$logo2['active'] ="Yes";}else{$logo2['active'] ="No";}
			break;

			default:
			break;

		}

	}
	$logoDir = $config['logo_folder'];

	$table1 = "<table class=logos BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD COLSPAN=3>
			<IMG SRC=\"images/logo1_01.gif\" WIDTH=500 HEIGHT=56 ALT=\"\"></TD>
	</TR>
	<TR>
		<TD ROWSPAN=2>
			<IMG SRC=\"images/logo1_02.gif\" WIDTH=281 HEIGHT=231 ALT=\"\"></TD>
		<TD bgcolor=#ECE9D8 ALIGN=\"left\">
			<IMG SRC=\"$logoDir/".$logo1['filename']."\" WIDTH=201 HEIGHT=156 ALT=\"\"></TD>
		<TD ROWSPAN=2>
			<IMG SRC=\"images/logo1_04.gif\" WIDTH=18 HEIGHT=231 ALT=\"\"></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC=\"images/logo1_05.gif\" WIDTH=201 HEIGHT=75 ALT=\"\"></TD>
	</TR>
</TABLE>";
	$table2 = "<table class=logos BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD COLSPAN=3>
			<IMG SRC=\"images/logo2_01.gif\" WIDTH=500 HEIGHT=73 ALT=\"\"></TD>
	</TR>
	<TR>
		<TD ROWSPAN=2>
			<IMG SRC=\"images/logo2_02.gif\" WIDTH=153 HEIGHT=214 ALT=\"\"></TD>
		<TD bgcolor=#ECE9D8>
			<IMG SRC=\"$logoDir/".$logo2['filename']."\" WIDTH=316 HEIGHT=144 ALT=\"\"></TD>
		<TD ROWSPAN=2>
			<IMG SRC=\"images/logo2_04.gif\" WIDTH=31 HEIGHT=214 ALT=\"\"></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC=\"images/logo2_05.gif\" WIDTH=316 HEIGHT=70 ALT=\"\"></TD>
	</TR>
</TABLE>";


	$Logo1InputForm ="
	<form method='post' ENCTYPE='multipart/form-data' action='logo.php'>
		<table class='uuTABLE'>
    		<tr>
    			<td>Select file:<input type='file' name='logo1'><input type='submit' value='Update Logo 1'><input type='hidden' value='PROCESSUPLOAD' name='op'></td>
    		</tr>
    	</table>
    </form>
    	";

	$Logo2InputForm ="
	<form method='post' ENCTYPE='multipart/form-data' action='logo.php'>
		<table class='uuTABLE'>
    		<tr>
    			<td>Select file:<input type='file' name='logo2'><input type='submit' value='Update Logo 2'><input type='hidden' value='PROCESSUPLOAD' name='op'></td>
    		</tr>
    	</table>
    </form>
    	";

	if ($logo1['active']=="Yes"){
		$logo1EnableLink = "<a href='logo.php?op=DISABLE&amp;id=".$logo1['id']."'>Disable</a>";
	}
	else{
		$logo1EnableLink = "<a href='logo.php?op=ENABLE&amp;id=".$logo1['id']."'>Enable</a>";
	}

	if ($logo2['active']=="Yes"){
		$logo2EnableLink = "<a href='logo.php?op=DISABLE&amp;id=".$logo2['id']."'>Disable</a>";
	}
	else{
		$logo2EnableLink = "<a href='logo.php?op=ENABLE&amp;id=".$logo2['id']."'>Enable</a>";
	}


	EchoPage("
		<br>
		<br>
		<center>
			<table class='uuTABLE'>

						<tr>
							<td><center>$table1</center></td>
						</tr>
							<tr>
								<td><center>Updated: ".$logo1['updated']."<br>Enabled? ".$logo1['active']."<br>$logo1EnableLink</center></td>
							</tr>
							<tr>
								<td><center>$Logo1InputForm</center></td>
						</tr>

						<tr>
							<td><center>$table2</center></td>
						</tr>
							<tr>
								<td><center>Updated: ".$logo2['updated']."<br>Enabled? ".$logo2['active']."<br>$logo2EnableLink</center></td>
							</tr>
							<tr>
								<td><center>$Logo2InputForm</center></td>
						</tr>

			</table>
		</center>","Logos");
}

function ToggleLogo(){
	global $dblink, $config, $op, $id;
	switch ($op){
		case "DISABLE":
		$sql = "UPDATE `".$config['db_tables_logos']."` SET `active` = '0' WHERE `id` = '$id';";
		break;
		case "ENABLE":
		$sql = "UPDATE `".$config['db_tables_logos']."` SET `active` = '1' WHERE `id` = '$id';";
		break;
		default:
		break;
	}
	mysql_query($sql, $dblink);
	MySqlCheck($dblink,$sql);
}

function ProcessUploadedLogo(){
	global $dblink, $config, $op, $files;
	$sep = DIRECTORY_SEPARATOR;
	//$logoFolder = dirname($_SERVER["PATH_TRANSLATED"]).$sep.$config['logo_folder'];
	$logoFolder = dirname($_SERVER["SCRIPT_FILENAME"]).$sep.$config['logo_folder'];
	if (isset($files['logo1'])){
		$sql = "select * from `".$config['db_tables_logos']."` where `logo_num` = '1'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);
		$RowNum = $row['id'];
		$logoNum = "1";
		$filefield = "logo1";
	}else {
		$sql = "select * from `".$config['db_tables_logos']."` where `logo_num` = '2'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$row = mysql_fetch_assoc($result);
		$RowNum = $row['id'];
		$logoNum = "2";
		$filefield = "logo2";
	}

	if (substr_count(strtoupper($files[$filefield]['name']),"GIF") > 0){
		$LocalLocation = $logoFolder.$sep.stripslashes("logo".$logoNum.".gif");
		@unlink($logoFolder.$sep."logo".$logoNum.".gif");
		move_uploaded_file($files[$filefield]['tmp_name'],$LocalLocation);
		$md5 = md5_file($LocalLocation);
		chmod($LocalLocation,0777);
		$sql = "DELETE FROM `".$config['db_tables_logos']."` WHERE `id` = '$RowNum'";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
		$sql = "INSERT INTO `".$config['db_tables_logos']."` ( `id` , `filename` , `updated` , `logo_num` , `active` , `download_url` , `md5` ) VALUES ( '', '"."logo".$logoNum.".gif"."', '".time()."', '".$logoNum."', '1', '".$config['URL'].$config['logo_folder']."/"."logo".$logoNum.".gif"."', '$md5' );";
		$result = mysql_query($sql,$dblink);
		MySqlCheck($dblink,$sql);
	}
	else {
		echo "The Uploaded file MUST be a GIF IMAGE!";
	}
}
switch ($op){

	case "PROCESSUPLOAD":
	ProcessUploadedLogo();
	Main();
	break;

	case "DISABLE":
	ToggleLogo();
	Main();
	break;

	case "ENABLE":
	ToggleLogo();
	Main();
	break;

	default:
	Main();
	break;





}


?>