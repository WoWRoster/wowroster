<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

require_once( 'settings.php' );

//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}

$header_title = $wordings[$roster_conf['roster_lang']]['professions'];
include_once(ROSTER_BASE.'roster_header.tpl');


include_once(ROSTER_BASE.'lib'.DIR_SEP.'menu.php');

for ( $tsNr=0; $tsNr<=11; $tsNr++ )
{
	$countit = 0;
	for ($i=0;$i<count($roster_conf['multilanguages']);$i++)
	{
		$query = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `skill_name` = '".$tsArray[$roster_conf['multilanguages'][$i]][$tsNr]."'";
		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		$countit += $wowdb->num_rows($result);
		if( $countit != 0 )
			break;
	}
	if( $countit != 0 )
	{
		$skill_name = $tsArray[$roster_conf['roster_lang']][$tsNr];
		// Set an link to the top behind the profession image
		$skill_image = 'Interface/Icons/'.$wordings[$roster_conf['roster_lang']]['ts_iconArray'][$skill_name];
		$skill_image = "<div style=\"display:inline;float:left;\"><img width=\"17\" height=\"17\" src=\"".$roster_conf['interface_url'].$skill_image.'.'.$roster_conf['img_suffix']."\" alt=\"\" /></div>\n";

		$header = $skill_image.$skill_name;

		print
		'<div id="ts'.$tsNr.'_col" style="display:none;">
		'.border('sgray','start',"<div style=\"cursor:pointer;width:370px;\" onclick=\"swapShow('ts".$tsNr."_col','ts".$tsNr."_full')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" alt=\"\" />".$header."</div>").'
		'.border('sgray','end').'
		</div>
		<div id="ts'.$tsNr.'_full">
		'.border('sgray','start',"<div style=\"cursor:pointer;width:370px;\" onclick=\"swapShow('ts".$tsNr."_col','ts".$tsNr."_full')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" alt=\"\" />".$header."</div>")."\n";
?>

      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodyline">
        <tr>
          <th class="membersHeader"><?php print $wordings[$roster_conf['roster_lang']]['level'];?></th>
          <th class="membersHeaderRight" width="150"><?php print $wordings[$roster_conf['roster_lang']]['name'];?></th>
        </tr>
<?php
		//$query = "SELECT * FROM `skills` WHERE `skill_name` = '$tsArray[$tsNr]' order by `skill_level` DESC";
		$query = "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `skill_name` ='".$tsArray[$roster_conf['multilanguages'][0]][$tsNr]."'";
		if (count($roster_conf['multilanguages']) > 1)
		{
			for ($i=1;$i<count($roster_conf['multilanguages']);$i++)
			{
				$query .= " OR `skill_name` = '".$tsArray[$roster_conf['multilanguages'][$i]][$tsNr]."'";
			}
		}
		$query .= " ORDER BY (mid(skill_level FROM 1 FOR (locate(':', skill_level)-1)) + 0) DESC";
		$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
		$steps = 0;
		while ( $row = $wowdb->fetch_array( $result ) )
		{
			if ( $steps == 1 )
			{
				$steps = 2;
			}
			else
			{
				$steps = 1;
			}

			$level_array = explode (':',$row['skill_level']);
			$levelpct = $level_array[0] / 300 * 100 ;
			settype( $levelpct, 'integer' );
			if ( !$levelpct )
				$levelpct = 1;

			$result2 = $wowdb->query("SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `member_id` = '" . $row['member_id'] . "'");
			$getdata = $wowdb->fetch_array($result2);
			$nameid = $getdata['name'];
			$namequery = $wowdb->query("SELECT name,server FROM `".ROSTER_PLAYERSTABLE."` WHERE name = '$nameid'");
			if ($row = $wowdb->fetch_array($namequery))
				$nameid = '<a href="char.php?name='.$row[0].'&amp;server='.$row[1].'&amp;action=recipes">'.$row[0].'</a>';

?>
        <tr>
          <td class="membersRow<?php print $steps; ?>"><div class="levelbarParent" style="width:200px;"><div class="levelbarChild"><?php print $level_array[0];?></div></div>
            <table class="expOutline" border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td style="background-image: url('<?php print $roster_conf['img_url'];?>expbar-var2.gif');" width="<?php print $levelpct;?>%"><img src="<?php print $roster_conf['img_url'];?>pixel.gif" height="14" width="1" alt=""></td>
                <td width="<?php print (100-$levelpct); ?>%"></td>
              </tr>
            </table></td>
          <td class="membersRowRight<?php print $steps; ?>"><?php print $nameid;?></td>
        </tr>
<?php
		}
?>
      </table>
<?php print border('sgray','end').'</div>'; ?>

<br />

<?php
	}
}
include_once(ROSTER_BASE.'roster_footer.tpl');
?>