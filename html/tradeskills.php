<?php include 'conf.php'; ?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>[<?php print $guild_name; ?> Roster] <?php print $wordings[$roster_lang]['professions'] ?></title>
<link rel="stylesheet" type="text/css" href="<?php print $stylesheet2 ?>">
<link rel="stylesheet" type="text/css" href="<?php print $stylesheet1 ?>">

<style type="text/css">
/* This is the border line & background colour round the entire page */
.bodyline       { background-color: #000000; border: 1px #212121 solid; }
</style>
</head>
<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center">
<table border=0 cellpadding=8 cellspacing=0 width="100%">
<tr><td width="100%" class="bodyline">
<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr><td align="center" width="100%" class="bodyline">
<a href="<?php print $website_address ?>"><img src="<?php print $logo ?>" alt="" border="0"></a><br>
</td>
<tr><td align="center">
<br><br>

<?php
$link = mysql_connect($db_host, $db_user, $db_passwd) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not connect");
mysql_select_db($db_name) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not select DB");

include 'lib/menu.php';

for ( $tsNr=0; $tsNr<=11; $tsNr++ ) {
    $countit = 0;
    for ($i=0;$i<count($multilanguages);$i++) {
	   $query = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `skill_name` = '".$tsArray[$multilanguages[$i]][$tsNr]."'";
	   $result = mysql_query($query) or die($_SERVER['PHP_SELF'].":".__LINE__." ".mysql_error());
	   $countit += mysql_num_rows($result);
	   if( $countit != 0 ) { break; }
    }
	if( $countit != 0 ) {
?>

<table border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div align="left"><img src="<?php 
	//workaround for displaying german icons, because Apache can't handle umlauts in filenames
	if ($tsArray[$roster_lang][$tsNr] == $wordings['deDE']['Herbalism']) {
	print "$img_url/Kraeuterkunde.gif";
	} else if ($tsArray[$roster_lang][$tsNr] == $wordings['deDE']['Skinning']) {
	print "$img_url/Kuerschnerei.gif";
	} else {
		print "$img_url/{$tsArray[$roster_lang][$tsNr]}.gif";
	}
?>" alt="<?php print $tsArray[$roster_lang][$tsNr];?>"></div>
<table border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td background="<?php print $img_url;?>rankingborder-top.gif">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
					<td width="16"><img src="<?php print $img_url;?>rankingborder-top-left.gif" height="14" width="16"></td>
					<td width="100%"><img src="<?php print $img_url;?>pixel.gif" height="14" width="598"></td>
					<td align="right" width="16"><img src="<?php print $img_url;?>rankingborder-top-right.gif" height="14" width="16"></td>
				</tr></tbody>
				</table>		
			</td>
		</tr>
		<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr>
			<td background="<?php print $img_url;?>rankingborder-left.gif" valign="bottom" width="11"><img src="<?php print $img_url;?>rankingborder-left-bot.gif" height="10" width="11"></td>
			<td width="100%">
	<table id="LeaderBoard" border="0" cellpadding="0" cellspacing="0" rules="all" width="100%">
	<tbody>
	<tr>
		<td class="rankingHeader"><?php print $wordings[$roster_lang]['level'];?></td>
		<td class="rankingHeader"><?php print $wordings[$roster_lang]['name'];?></td>
	</tr>

<?php
		//$query = "SELECT * FROM `skills` WHERE `skill_name` = '$tsArray[$tsNr]' order by `skill_level` DESC";
		$query = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `skill_name` ='".$tsArray[$multilanguages[0]][$tsNr]."'";
		if (count($multilanguages) > 1)
		  for ($i=1;$i<count($multilanguages);$i++)
		      $query .= " OR `skill_name` = '".$tsArray[$multilanguages[$i]][$tsNr]."'";
        $query .= " ORDER BY (mid(skill_level FROM 1 FOR (locate(':', skill_level)-1)) + 0) DESC";
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'].":".__LINE__." ".mysql_error());
		$steps = 1;
		while ( $row = mysql_fetch_assoc( $result ) ) {
			if ( $steps = 1 ) { $color = $color1; $steps = 2; }
			else { $color = $color2; $steps = 1; }
			$level_array = explode (':',$row['skill_level']);
			$levelpct = $level_array[0] / 300 * 100 ;
			settype( $levelpct, 'integer' );
			if ( !$levelpct ) { $levelpct = 1; }
			$result2 = mysql_query("SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` LIKE '" . $row['member_id'] . "'");
			$getdata = mysql_fetch_array($result2);
			$nameid = $getdata['name'];
			$namequery = mysql_query("SELECT name,server FROM `".ROSTER_PLAYERSTABLE."` WHERE name = '$nameid'");
			if ($row = mysql_fetch_row($namequery)) {
				$nameid = '<a href="char.php?name='.$row[0].'&server='.$row[1].'">'.$row[0].'</a>';
			}
?>

  <tr class="rankingRow">
	<td class="rankingRow" width="300" bgcolor="<?php print $color;?>"><div id="levelbarParent2"><div id="levelbarChild2"><?php print $level_array[0];?></div></div><table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td background="<?php print $img_url;?>expbar-var2.gif" width="<?php print $levelpct;?>%"><img src="<?php print $img_url;?>pixel.gif" height="14" width="1"></td><td width="50%"></td></tr></tbody></table></td>
	<td class="rankingRow" bgcolor="<?php print $color;?>"><span class="rankingName"><?php print $nameid;?></td>
	</span></td></tr>

<?php
		}
?>

	</tbody>
	</table>
					</td>
					<td background="<?php print $img_url;?>rankingborder-right.gif" valign="bottom" width="11"><img src="<?php print $img_url;?>rankingborder-right-bot.gif" height="10" width="11"></td>
				</tr>
				</tbody></table>
			</td>
		</tr>
		<tr>
			<td background="<?php print $img_url;?>rankingborder-bot.gif">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
					<td width="50%"><img src="<?php print $img_url;?>rankingborder-bot-left.gif" height="12" width="19"></td>
					<td align="right" width="50%"><img src="<?php print $img_url;?>rankingborder-bot-right.gif" height="12" width="19"></td>
				</tr>
				</tbody></table>		
			</td>
		</tr>			
		</tbody></table>
<br>

<?php
	}
}
?>

</tr></td>
</table>
</div>
<br><br><br><br>
</td></tr>
</table>
</td></tr></table>
</td></tr></table>
</body>
</html>