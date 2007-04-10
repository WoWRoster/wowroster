<?php
/* 
* $Date: 2006/01/11 19:45:26 $ 
* $Revision: 1.9 $ 
*/ 

if (eregi("index.php",$_SERVER['PHP_SELF'])) {
    die("You can't access this file directly!");
}

// example connect
$server_name_escape = $wowdb->escape($server_name);
$server = $_REQUEST['server'];
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, guild_name, DATE_FORMAT(update_time, '".$timeformat[$lang]."') from `".ROSTER_GUILDTABLE."` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$guildname = $row[1];
	$updateTime = $row[2];
	$content .= "<!--$guildId $updateTime-->\n";
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

if ($sqldebug) {
	$content .= "<!--$query $sqldebug-->\n";
}



$display_text = $_REQUEST['display_text'];
$display_image = $_REQUEST['display_image'];

//begin generation the addon's output
$content .= '<br>';

if ($generate_output){
	if ($display_text) {
		$content .=	"Your guild Id is [ <b>$guildId</b> ] for Guild: [ <b>$guildname</b> ] .";
		$content .= '<br>';
	}
	if ($display_image){
		$content .="<div class='dummyStyle' >".$wordings[$roster_lang]['dummy']."<br><img src='./img/mini_diablo.gif'></div>\n";
		$content .= '<br>';
	}
}

$content .= '<br>';
echo $content;
?>