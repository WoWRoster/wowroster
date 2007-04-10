<?php
$versions['versionDate']['tradeskills'] = '$Date: 2005/12/30 20:40:53 $'; 
$versions['versionRev']['tradeskills'] = '$Revision: 1.7 $'; 
$versions['versionAuthor']['tradeskills'] = '$Author: mordon $';

include 'conf.php';

$header_title = $wordings[$roster_lang]['professions'];
include 'roster_header.tpl';


$link = mysql_connect($db_host, $db_user, $db_passwd) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not connect");
mysql_select_db($db_name) or die($_SERVER['PHP_SELF'].":".__LINE__." "."Could not select DB");

include 'lib/menu.php';

for ( $tsNr=0; $tsNr<=11; $tsNr++ )
{
	$countit = 0;
	for ($i=0;$i<count($multilanguages);$i++)
	{
		$query = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `skill_name` = '".$tsArray[$multilanguages[$i]][$tsNr]."'";
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'].":".__LINE__." ".mysql_error());
		$countit += mysql_num_rows($result);
		if( $countit != 0 )
			break;
	}
	if( $countit != 0 )
	{
?>

<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="left">
    	<img src="<?php
	//workaround for displaying german icons, because Apache can't handle umlauts in filenames
	if ($tsArray[$roster_lang][$tsNr] == $wordings['deDE']['Herbalism'])
		print "$img_url/Kraeuterkunde.gif";
	else if ($tsArray[$roster_lang][$tsNr] == $wordings['deDE']['Skinning'])
		print "$img_url/Kuerschnerei.gif";
	else
		print "$img_url{$tsArray[$roster_lang][$tsNr]}.gif";

?>" alt="<?php print $tsArray[$roster_lang][$tsNr];?>"></div></td>
  </tr>
  <tr>
    <td>

      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td style="background-image: url('<?php print $img_url; ?>rankingborder-top.gif');">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td width="16"><img src="<?php print $img_url;?>rankingborder-top-left.gif" height="14" width="16" alt=""></td>
                <td width="100%"><img src="<?php print $img_url;?>pixel.gif" height="14" width="598" alt=""></td>
                <td align="right" width="16"><img src="<?php print $img_url;?>rankingborder-top-right.gif" height="14" width="16" alt=""></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td style="background-image: url('<?php print $img_url;?>rankingborder-left.gif');" valign="bottom" width="11"><img src="<?php print $img_url;?>rankingborder-left-bot.gif" height="10" width="11" alt=""></td>
                <td width="100%">
                  <table class="LeaderBoard" border="0" cellpadding="0" cellspacing="0" rules="all" width="100%">
                    <tr>
                      <th class="membersHeader"><?php print $wordings[$roster_lang]['level'];?></th>
                      <th class="membersHeaderRight"><?php print $wordings[$roster_lang]['name'];?></th>
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
		while ( $row = mysql_fetch_assoc( $result ) )
		{
			if ( $steps = 1 )
			{
				$color = $color1;
				$steps = 2;
			}
			else
			{
				$color = $color2;
				$steps = 1;
			}
			$level_array = explode (':',$row['skill_level']);
			$levelpct = $level_array[0] / 300 * 100 ;
			settype( $levelpct, 'integer' );
			if ( !$levelpct )
				$levelpct = 1;

			$result2 = mysql_query("SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` LIKE '" . $row['member_id'] . "'");
			$getdata = mysql_fetch_array($result2);
			$nameid = $getdata['name'];
			$namequery = mysql_query("SELECT name,server FROM `".ROSTER_PLAYERSTABLE."` WHERE name = '$nameid'");
			if ($row = mysql_fetch_row($namequery))
				$nameid = '<a href="char.php?name='.$row[0].'&amp;server='.$row[1].'">'.$row[0].'</a>';

?>
                    <tr class="rankingRow">
                      <td class="membersRow2" width="300" bgcolor="<?php print $color;?>"><div id="levelbarParent2"><div id="levelbarChild2"><?php print $level_array[0];?></div></div>
                        <table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td style="background-image: url('<?php print $img_url;?>expbar-var2.gif');" width="<?php print $levelpct;?>%"><img src="<?php print $img_url;?>pixel.gif" height="14" width="1" alt=""></td>
                            <td width="50%"></td>
                          </tr>
                        </table></td>
                      <td class="membersRowRight2" bgcolor="<?php print $color;?>"><span class="rankingName"><?php print $nameid;?></span></td>
                    </tr>
<?php
		}
?>
                  </table></td>
                <td style="background-image: url('<?php print $img_url;?>rankingborder-right.gif');" valign="bottom" width="11"><img src="<?php print $img_url;?>rankingborder-right-bot.gif" height="10" width="11" alt=""></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td style="background-image: url('<?php print $img_url;?>rankingborder-bot.gif');">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td width="50%"><img src="<?php print $img_url;?>rankingborder-bot-left.gif" height="12" width="19" alt=""></td>
                <td align="right" width="50%"><img src="<?php print $img_url;?>rankingborder-bot-right.gif" height="12" width="19" alt=""></td>
              </tr>
            </table></td>
        </tr>			
      </table></td>
  </tr>
</table>
<br>

<?php
	}
}
include 'roster_footer.tpl';
?>