<?php require_once 'conf.php'; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>WoW Guild Roster</title>
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet2 ?>">
  <link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">
<style type="text/css"> 
/* This is the border line & background colour round the entire page */
.bodyline       { background-color: #000000; border: 1px #212121 solid; }
</style>
</head>
<body>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center">
<table border=0 cellpadding=8 cellspacing=0 width="1000">
<tr><td width="1000" class="bodyline">
<table border=0 cellpadding=0 cellspacing=0 width="1000">
<tr><td width="1000" class="bodyline">
<p align="center"><a href="<?php print $website_address ?>">
<img src="<?php print $logo ?>" alt="" border="0"></a><p align="center"><br>
</td>

<tr><td align="center">
<br><br>

<?php require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '%b %d %l%p') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
  $guildId = $row[0];
  $updateTime = $row[1];
} else {
  die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}


include 'lib/menu.php';

include 'admin/update.php';
?>
</td></tr>
<tr><td>
<br>
<br><br><br><br>
</td></tr>
</table>
</td></tr></table>
</body>
<hr>
Click browse and upload your SavedVariables.lua file<br>
(This file is located in C:\Program Files\World of Warcraft\WTF\Account\*Accountname*\*username*\)<br>
</html>