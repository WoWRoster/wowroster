<?php
/* 
* $Date: 2005/12/28 21:59:55 $ 
* $Revision: 1.5 $ 
*/ 
$subdir2 = '../../';
include $subdir2.'conf.php';
$link = mysql_connect( $db_host, $db_user, $db_passwd ) or die( "Could not connect to desired database." );
mysql_select_db( $db_name ) or die( "Could not select desired database." );
$guild_name_escaped = addslashes($guild_name);
$server_name_escaped = addslashes($server_name);
//$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') from `guild` where guild_name= '$guild_name_escaped' and server='$server_name_escaped'";
$query = "SELECT guild_id, guild_dateupdatedutc FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escaped' and server='$server_name_escaped'";
$result4 = mysql_query($query) or die(__LINE__.":".mysql_error());

if ($row = mysql_fetch_row($result4)) {
	$guildId = $row[0];
	$updateTimeUTC = $row[1];
	$updateTime = DateDataUpdated($updateTimeUTC);
} else
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");

$result1 = mysql_query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND $alt_location NOT LIKE '%$alt_type%'") or die(__LINE__.":".mysql_error());
$rows = mysql_fetch_array($result1);
$result2 = mysql_query("SELECT * FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND $alt_location LIKE '%$alt_type%'") or die(__LINE__.":".mysql_error());
$result3 = mysql_query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND `level` = 60") or die(__LINE__.":".mysql_error());
$result5 = mysql_query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND `level` > 49 and `level` < 60") or die(__LINE__.":".mysql_error());
$result6 = mysql_query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND `level` > 39 and `level` < 50") or die(__LINE__.":".mysql_error());
$result7 = mysql_query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND `level` > 29 and `level` < 40") or die(__LINE__.":".mysql_error());
$result8 = mysql_query("SELECT * from `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId AND `level` > 0 and `level` < 30") or die(__LINE__.":".mysql_error());
$result9 = mysql_fetch_array(mysql_query("SELECT AVG(level) FROM `".ROSTER_MEMBERSTABLE."` WHERE guild_id=$guildId")) or die(__LINE__.":".mysql_error());

function DateDataUpdated($updateTimeUTC) {
	global $wowdb;
	extract($GLOBALS);
	$day = substr($updateTimeUTC,3,2);
	$month = substr($updateTimeUTC,0,2);
	$year = substr($updateTimeUTC,6,2);
	$hour = substr($updateTimeUTC,9,2);
	$minute = substr($updateTimeUTC,12,2);
	$second = substr($updateTimeUTC,15,2);

	$localtime = mktime($hour+$localtimeoffset ,$minute, $second, $month, $day, $year, -1);
	return date($phptimeformat[$roster_lang], $localtime);
}
?>

<!-- Begin WoWRoster Menu -->
<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td colspan="100%" class="rankbordertop"><span class="rankbordertopleft"></span>
      <span class="rankbordertopright"></span></td>
  </tr>
  <tr>
    <td class="rankbordercenterleft"></td>
    <td colspan="2" valign="top">
      <strong><a href="<?php print $website_address.'">'.$guild_name.'</a></strong>
      <small> of '.$server_name."$server_type"; ?></small></td>
    <td class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td class="rankbordercenterleft"></td>
    <td valign="center">
      <small>Total <?php print $wordings[$roster_lang]['members'].': '.mysql_num_rows($result1).' (+'.mysql_num_rows($result2); ?> Alts)</small><br>
      <small><font color="#0099FF">&nbsp;Average Level: <?php print round($result9[0]);?></font></small><br>
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 60: '.mysql_num_rows($result3);?></small><br>
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 50-59: '.mysql_num_rows($result5);?></small><br>
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 40-49: '.mysql_num_rows($result6);?></small><br>
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 30-39: '.mysql_num_rows($result7);?></small><br>
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 1-29: '.mysql_num_rows($result8);?></small><br>
      <small><?php print $wordings[$roster_lang]['update'] ?>: <font color="#0099FF"><?php print $updateTime; if ($timezone) print " ($timezone)"; ?></font></small><br>
    </td>
    <td valign="center">
    </td>
    <td class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td class="rankbordercenterleft"></td>
    <td align="left" colspan="2"><div class="membersRow"> 
      <p>
      	<br />
        <a href="<?php print $roster_dir ?>/index.php"><?php print $wordings[$roster_lang]['roster'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/index.php?s=class"><?php print $wordings[$roster_lang]['byclass'] ?></a>
		<br />
        <font color="#999999"></font><a href="<?php print $roster_dir ?>/indexAlt.php"><?php print $wordings[$roster_lang]['alternate'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexStat.php"><?php print $wordings[$roster_lang]['menustats'] ?></a>
		<br />
        <font color="#999999"></font><a href="<?php print $roster_dir ?>/indexHonor.php"><?php print $wordings[$roster_lang]['menuhonor'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/tradeskills.php"><?php print $wordings[$roster_lang]['professions'] ?></a><br>
        <a href="<?php print $roster_dir ?>/indexInst.php"><?php print $wordings[$roster_lang]['keys'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexquests.php"><?php print $wordings[$roster_lang]['team'] ?></a>
		<br />
<?php
if ($show_guildbank)
	print '        <font color="#999999"></font><a href="'.$roster_dir.'/guildbank'.$guildbank_ver.'.php">'.$wordings[$roster_lang]['guildbank'].'</a>';
?>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexSearch.php"><?php print $wordings[$roster_lang]['search'] ?></a>
		<br />
        <font color="#999999"></font><a href="<?php print $roster_dir ?>/admin/update.php"><?php print $wordings[$roster_lang]['upprofile'] ?></a>
      </div>
      </p>
    </td>
    <td class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td colspan="100%" class="rankborderbot"><span class="rankborderbotleft"></span>
      <span class="rankborderbotright"></span></td>
  </tr>
</table>
<!-- End WoWRoster Menu -->