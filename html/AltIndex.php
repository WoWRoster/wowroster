<?php include 'conf.php'; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>[<?php print $guild_name; ?> Roster] <?php print $wordings[$roster_lang]['byclass']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php print $stylesheet2 ?>">
<link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">
<style type="text/css">
/* This is the border line & background colour round the entire page */
.bodyline       { background-color: #000000; border: 1px #212121 solid; }
</style>
<SCRIPT LANGUAGE="JavaScript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=1,width=650,height=600,left = 400,top = 300');");
}
// End -->
</script>
</head>
<body>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center">
<table border=0 cellpadding=8 cellspacing=0 width="100%">
<tr><td width="100%" class="bodyline">
<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center" width="100%" class="bodyline">
<a href="<? print $website_address ?>"><img src="<? print $logo ?>" alt="" border="0"></a><br>
</td>

<tr><td align="center">
<br><br>

<?php
function has_uploaded_data() {
  global $db_table_prefix;
	$query = "SELECT `name`, `server` FROM `".ROSTER_PLAYERSTABLE."` WHERE name = '".func_get_arg(0)."' and server = '".func_get_arg(1)."'";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_row($result);

	if($row) {
?>

		<a href="char.php?name=<?php print $row[0];?>&server=<?php print $row[1];?>"><?php print $row[0];?></a>

<?php
	}	else {
		print func_get_arg(0);
	}
	return;
}

$link = mysql_connect($db_host, $db_user, $db_passwd) or die("Could not connect");
mysql_select_db($db_name) or die("Could not select DB");
$default_sortby = 'name';

include 'lib/menu.php';

$ClassArray = array (	$wordings[$roster_lang]['Druid'],
											$wordings[$roster_lang]['Hunter'],
											$wordings[$roster_lang]['Mage'],
											$wordings[$roster_lang]['Paladin'],
											$wordings[$roster_lang]['Priest'],
											$wordings[$roster_lang]['Rogue'],
											$wordings[$roster_lang]['Shaman'],
											$wordings[$roster_lang]['Warlock'],
											$wordings[$roster_lang]['Warrior']);
for ( $ClassNr=0; $ClassNr<=8; $ClassNr++ ) {
	$query = "SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE `class` = '$ClassArray[$ClassNr]'";
	$result = mysql_query($query) or die(mysql_error());
	$countit = mysql_num_rows($result);
	if( $countit != 0 ) {
?>

<table border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div align="left"><img src="<?php 
	//workaround for displaying german icons, because Apache can't handle umlauts in filenames
	if ($ClassArray[$ClassNr] == $wordings['deDE']['Hunter']) {
		print $img_url.'Jaeger';
	} else {
		print $img_url.$ClassArray[$ClassNr];
	}	
?>.gif" alt="<?php print $ClassArray[$ClassNr]; ?>"></div>
<table border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td background="<?php print $img_url; ?>rankingborder-top.gif">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
					<td width="16"><img src="<?php print $img_url; ?>rankingborder-top-left.gif" height="14" width="16"></td>
					<td width="100%"><img src="<?php print $img_url; ?>pixel.gif" height="14" width="598"></td>
					<td align="right" width="16"><img src="<?php print $img_url; ?>rankingborder-top-right.gif" height="14" width="16"></td>
				</tr></tbody>
				</table>		
			</td>
		</tr>
		<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr>
			<td background="<?php print $img_url; ?>rankingborder-left.gif" valign="bottom" width="11"><img src="<?php print $img_url;?>rankingborder-left-bot.gif" height="10" width="11"></td>
			<td width="100%">
	<table id="LeaderBoard" border="0" cellpadding="0" cellspacing="0" rules="all" width="100%">
	<tbody>	
	<tr>
		<td class="rankingHeader" width="60"><?php print $wordings[$roster_lang]['level']; ?></td>
		<td class="rankingHeader"><?php print $wordings[$roster_lang]['name']; ?></td>
		<td class="rankingHeader" width="100"><?php print $wordings[$roster_lang]['race']; ?></td>
		<td class="rankingHeader" width="100"><?php print $wordings[$roster_lang]['title']; ?></td>
		<td class="rankingHeader" width="130"><?php print $wordings[$roster_lang]['lastonline']; ?></td>
	</tr>

<?php
		$query = "SELECT members.name AS name, members.level AS level, members.guild_title AS guild_title, players.race AS race, DATE_FORMAT(members.last_online,'".$timeformat[$roster_lang]."') AS date_out FROM `".ROSTER_MEMBERSTABLE."` members LEFT JOIN `".ROSTER_PLAYERSTABLE."` players ON players.member_id = members.member_id WHERE members.class = '$ClassArray[$ClassNr]' ORDER BY members.level DESC";
		//echo "<br/>" . $query . "<br/>"; // debugging code to view query
		$result = mysql_query($query) or die(mysql_error());
		$steps = '1';
		while ( $row = mysql_fetch_assoc( $result ) ) {
			if ( $steps == '1' ) { $color = $color1; $steps = '2'; }
			else { $color = $color2; $steps = '1'; }

			$levelpct = $row['level'] / 60 * 100;
			settype( $levelpct, 'integer' );
?>

  <tr class="rankingRow">
	<td class="rankingRow" width="60" bgcolor="<?php print $color; ?>"><div id="levelbarParent"><div id="levelbarChild"><?php print $row['level']; ?></div></div><table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td background="<?php print $img_url; ?>expbar-var2.gif" width="<?php print $levelpct; ?>%"><img src="<?php print $img_url; ?>pixel.gif" height="14" width="1"></td><td width="50%"></td></tr></tbody></table></td>
	<td class="rankingRow" bgcolor="<?php print $color; ?>"><?php has_uploaded_data($row['name'], addslashes($server_name)); ?></td>
	<td class="rankingRow" bgcolor="<?php print $color; ?>" width="100"><?php print $row['race']; ?></td>
	<td class="rankingRow" width="100" bgcolor="<?php print $color; ?>"><?php print $row['guild_title']; ?>
	<td class="rankingRowRight" bgcolor="<?php print $color; ?>" width="130"><?php print $row['date_out']; ?></td>
	</span></td></tr>

<?php
		}
?>

	</tbody>
	</table>
					</td>
					<td background="<?php print $img_url; ?>rankingborder-right.gif" valign="bottom" width="11"><img src="<?php print $img_url; ?>rankingborder-right-bot.gif" height="10" width="11"></td>
				</tr>
				</tbody></table>
			</td>
		</tr>
		<tr>
			<td background="<?php print $img_url; ?>rankingborder-bot.gif">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
					<td width="50%"><img src="<?php print $img_url; ?>rankingborder-bot-left.gif" height="12" width="19"></td>
					<td align="right" width="50%"><img src="<?php print $img_url; ?>rankingborder-bot-right.gif" height="12" width="19"></td>
				</tr>
				</tbody></table>		
			
			</td>
		</tr>			
		</tbody></table>
<br>

<?php
	}
}
mysql_close($link);
?>

</tr></td>
</table>
</div>
</td></tr>
</table>
</td></tr></table>
</td></tr></table>
</body>
</html>

<?php $versionAltIndex = 1.00; ?>