<?php
$versions['versionDate']['menu'] = '$Date: 2006/01/25 08:42:58 $'; 
$versions['versionRev']['menu'] = '$Revision: 1.23 $'; 
$versions['versionAuthor']['menu'] = '$Author: zanix $';

$guild_name_escaped = addslashes($guild_name);
$server_name_escaped = addslashes($server_name);
//$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') from `guild` where guild_name= '$guild_name_escaped' and server='$server_name_escaped'";
$query = "SELECT guild_id, guild_dateupdatedutc FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escaped' and server='$server_name_escaped'";
$result4 = mysql_query($query) or die(__LINE__.":".mysql_error());

if ($row = mysql_fetch_row($result4))
{
	$guildId = $row[0];
	$updateTimeUTC = $row[1];
	$updateTime = DateDataUpdated($updateTimeUTC);
}
else
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

function DateDataUpdated($updateTimeUTC)
{
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

$addons = makeAddonList();

function makeAddonList()
{
	global $paths,$roster_lang,$wordings;

	$sep = DIRECTORY_SEPARATOR;
	$addonsPath = $paths[0].'/addons';
	$addonsURL = $paths[1].'/addons';

	if ($handle = opendir(realpath($addonsPath)))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..')
			{
				$addons[$i] =$file;
				$i++;
			}
		}
	}

	$aCount = 0; //addon count
	$lCount = 0; //link count
	$newl = true; //new line , omit leading -

	
	foreach ($addons as $addon)
	{
		$menufile = $addonsPath.$sep.$addon.$sep.'menu.php';
		if (file_exists($menufile))
		{
			$localizationFile = $paths[0].$sep.'addons'.$sep.$addon.$sep.'localization.php';
			if (file_exists($localizationFile))include($localizationFile);

			include($menufile);
			
			if (0 >= $config['menu_min_user_level']) //modify this line for user level / authentication stuff (show the link for user level whatever for this addon)  you understand :P
			{
				if (isset($config['menu_index_file'][0]))
				{
					//$config['menu_index_file'] is the new array type
					foreach ($config['menu_index_file'] as $addonLink)
					{
						if (!$newl)$output .= '<font color="#999999"> - </font>'; else $newl = false;
						$fullQuery = "?roster_addon_name=$addon" . $addonLink[0];
						$query = str_replace(' ','%20',$fullQuery);
						$output .="<a href='addon.php$query'>" . $addonLink[1]."</a>\n";
						$lCount++;

						if ($lCount%5 == 0)
						{
							$output .= "<br />";
							$newl = true;
						}
					}
				}
			}
		}
	}

	$output .= "<br />\n";


	if ($lCount < 1)return '';

	return $output;


}




?>
<!-- Begin WoWRoster Menu -->
<table bgcolor="#292929" cellspacing="0" cellpadding="2" border="0">
  <tr>
    <td bgcolor="#000000" colspan="4" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></td>
  </tr>
  <tr>
    <td bgcolor="#000000" class="rankbordercenterleft"></td>
    <td colspan="2" valign="top">
      <strong><a href="<?php print $website_address.'">'.$guild_name.'</a></strong>
      <small> of '.$server_name." ($server_type)"; ?></small></td>
    <td bgcolor="#000000" class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td bgcolor="#000000" class="rankbordercenterleft"></td>
    <td valign="middle">
      <small>Total <?php print $wordings[$roster_lang]['members'].': '.mysql_num_rows($result1).' (+'.mysql_num_rows($result2); ?> Alts)</small><br />
      <small><font color="#999999">&nbsp;Average Level: <?php print round($result9[0]);?></font></small><br />
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 60: '.mysql_num_rows($result3);?></small><br />
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 50-59: '.mysql_num_rows($result5);?></small><br />
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 40-49: '.mysql_num_rows($result6);?></small><br />
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 30-39: '.mysql_num_rows($result7);?></small><br />
      <small>&nbsp;&nbsp;&nbsp;- <?php print $wordings[$roster_lang]['level'].' 1-29: '.mysql_num_rows($result8);?></small><br />
      <small><?php print $wordings[$roster_lang]['update'] ?>: <font color="#0099FF"><?php print $updateTime; if ($timezone) print " ($timezone)"; ?></font></small><br />
    </td>
    <td valign="middle"><?php

if ($blizstatuspage)
{
	if($rs_image)
		print '<img alt="WoW Server Status" src="realmstatus.php" />';
	else
		include ('realmstatus.php');
}
else
	print '&nbsp;';

?></td>
    <td bgcolor="#000000" class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td bgcolor="#000000" class="rankbordercenterleft"></td>
    <td align="center" colspan="2"><div class="membersRow">
      <p>
        <a href="<?php print $roster_dir ?>/index.php"><?php print $wordings[$roster_lang]['roster'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/index.php?s=class"><?php print $wordings[$roster_lang]['byclass'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexAlt.php"><?php print $wordings[$roster_lang]['alternate'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexStat.php"><?php print $wordings[$roster_lang]['menustats'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexHonor.php"><?php print $wordings[$roster_lang]['menuhonor'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/tradeskills.php"><?php print $wordings[$roster_lang]['professions'] ?></a><br />
        <a href="<?php print $roster_dir ?>/indexInst.php"><?php print $wordings[$roster_lang]['keys'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexquests.php"><?php print $wordings[$roster_lang]['team'] ?></a>
<?php

if ($show_guildbank)
	print '        <font color="#999999"> - </font><a href="'.$roster_dir.'/guildbank'.$guildbank_ver.'.php">'.$wordings[$roster_lang]['guildbank'].'</a>
';

if ($show_raid)
	print '        <font color="#999999"> - </font><a href="'.$roster_dir.'/indexRaid.php">'.$wordings[$roster_lang]['raid'].'</a>
';

?>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/indexSearch.php"><?php print $wordings[$roster_lang]['search'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/credits.php"><?php print $wordings[$roster_lang]['credit'] ?></a>
        <font color="#999999"> - </font><a href="<?php print $roster_dir ?>/admin/update.php"><?php print $wordings[$roster_lang]['upprofile'] ?></a>
      </p>
   </div></td>
    <td bgcolor="#000000" class="rankbordercenterright"></td>
  </tr>
  <tr>
    <td bgcolor="#000000" class="rankbordercenterleft"></td>
    <td align="center" colspan="2"><div class="membersRow">
      <p>
      <small><font color="#0099FF"><?php print $wordings[$roster_lang]['Addon'] ?></font></small>
      <?php print $addons ?>
      </p>
      </div>
    </td>
    <td bgcolor="#000000" class="rankbordercenterright"></td>
  </tr>
	<tr>
    <td bgcolor="#000000" colspan="4" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></td>
  </tr>
</table>
<!-- End WoWRoster Menu -->