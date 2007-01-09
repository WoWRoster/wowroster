<?php
/******************************
 * Gear Browser
 * By Rihlsul
 * www.ironcladgathering.com
 * v 1.0  (9/2/2006 2:15 PM)
 * Compatible with Roster 1.70
 ******************************/


if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require_once (ROSTER_BASE.'lib'.DIR_SEP.'item.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'bag.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'char.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'skill.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'reputation.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'quest.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'recipes.php');
require_once (ROSTER_BASE.'lib'.DIR_SEP.'pvp3.php');

$myBonus = array();
$myTooltip = array();

class gbchar extends char
{
	var $data;
	var $posi;

	function gbchar( $data, $posi )
	{
		$this->data = $data;
		//print '<!-- JDV DEBUG GBCHAR 43.  Posi='.$posi.'-->\n';
		$this->posi = $posi;
	}

	function printPet($name, $server)
	{
		global $wowdb, $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		$server = $wowdb->escape( $server );
		/******************************************************************************************************************
		Gets all the pets from the database associated with that character from that server.
		******************************************************************************************************************/

		$query = "SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name' AND `server` = '$server'";
		$result = $wowdb->query( $query );
		$row = $wowdb->fetch_assoc( $result );
		$member_id = $row['member_id'];
		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$member_id' ORDER BY `level` DESC";
		$result = $wowdb->query( $query );

		$petNum = 1;
		while ($row = $wowdb->fetch_assoc($result))
		{

			$showxpBar = true;
			if ( strlen($row['xp']) < 1 )
				$showxpBar = false;

			list($xpearned, $totalxp) = split(":",$row['xp']);
			if ($totalxp == 0)
				$xp_percent = .00;
			else
				$xp_percent = $xpearned / $totalxp;

			$barpixelwidth = floor(381 * $xp_percent);
			$xp_percent_word = floor($xp_percent * 100).'%';
			$unusedtp = $row['totaltp'] - $row['usedtp'];

			if ($row['level'] == 60)
				$showxpBar = false;

			$tmp = split(':',$row['stat_str']);
			$str = $tmp[0];
			$tmp = split(':',$row['stat_agl']);
			$agi = $tmp[0];
			$tmp = split(':',$row['stat_sta']);
			$sta = $tmp[0];
			$tmp = split(':',$row['stat_int']);
			$int = $tmp[0];
			$tmp = split(':',$row['stat_spr']);
			$spr = $tmp[0];
			$tmp = split(':',$row['armor']);
			$basearmor = $tmp[0];

			switch ($petNum)
			{
				case 1:
					$left = 35;
					$top = 285;
					break;
				case 2:
					$left = 85;
					$top = 285;
					break;
				case 3:
					$left = 135;
					$top = 285;
					break;
				default:
					$left = 185;
					$top = 285;
					break;
			}

			// Start Warlock Pet Icon Mod
			$imp = 'Interface\\Icons\\Spell_Shadow_SummonImp';
			$void = 'Interface\\Icons\\Spell_Shadow_SummonVoidWalker';
			$suc = 'Interface\\Icons\\Spell_Shadow_SummonSuccubus';
			$fel = 'Interface\\Icons\\Spell_Shadow_SummonFelHunter';
			$inferno = 'Interface\\Icons\\Spell_Shadow_SummonInfernal';

			$iconStyle='cursor: pointer; position: absolute; left: '.$left.'px; top: '.$top.'px;';

			if ($row['type'] == $wordings[$lang]['Imp'])
				$row['icon'] = $imp;

			if ($row['type'] == $wordings[$lang]['Voidwalker'])
				$row['icon'] = $void;

			if ($row['type'] == $wordings[$lang]['Succubus'])
				$row['icon'] = $suc;

			if ($row['type'] == $wordings[$lang]['Felhunter'])
				$row['icon'] = $fel;

			if ($row['type'] == $wordings[$lang]['Infernal'])
				$row['icon'] = $inferno;
			// End Warlock Pet Icon Mod

			if ($row['icon'] == "" || !isset($row['icon']))
				$row['icon'] = "unknownIcon.gif";
			else
				$row['icon'] .= '.'.$roster_conf['img_suffix'];

			$icons			.= '<img src="'.$roster_conf['interface_url'].preg_replace('|\\\\|','/', $row['icon']).'" onclick="showGBPet(\''.$petNum.'\',\''.$this->posi.'\')" style="'.$iconStyle.'" alt="" onmouseover="overlib(\''.addslashes($row['type']).'\',CAPTION,\''.addslashes($row['name']).'\',WRAP);" onmouseout="return nd();" />';
			$petName		.= '<span class="petName" style="top: 10px; left: 95px; display: none;" id="pet_name'.$this->posi.$petNum.'">' . stripslashes($row['name']).'</span>';
			$petTitle		.= '<span class="petName" style="top: 30px; left: 95px; display: none;" id="pet_title'.$this->posi.$petNum.'">'.$wordings[$lang]['level'].' '.$row['level'].' ' . stripslashes($row['type']).'</span>';
			$loyalty		.= '<span class="petName" style="top: 50px; left: 95px; display: none;" id="pet_loyalty'.$this->posi.$petNum.'">'.$row['loyalty'].'</span>';
			$petIcon		.= '<img id="pet_top_icon'.$this->posi.$petNum.'" style="position: absolute; left: 35px; top: 10px; width: 55px; height: 60px; display: none;" src="'.$roster_conf['interface_url'].preg_replace('|\\\\|','/', $row['icon']).'" alt="" />';
			$resistances	.= '<div  class="pet_resistance" id="pet_resistances'.$this->posi.$petNum.'">
				<ul>
					<li class="pet_fire"><span class="white">'.$row['res_fire'].'</span></li>
					<li class="pet_nature"><span class="white">'.$row['res_nature'].'</span></li>
					<li class="pet_arcane"><span class="white">'.$row['res_arcane'].'</span></li>
					<li class="pet_frost"><span class="white">'.$row['res_frost'].'</span></li>
					<li class="pet_shadow"><span class="white">'.$row['res_shadow'].'</span></li>
				</ul>
			</div>';
			$stats			.= '
			<div class="petStatsBg" id="pet_stats_table'.$this->posi.$petNum.'" >
					<table style="text-align: left; position: absolute; top: 5px; left: 5px;" border="0" cellpadding="2" cellspacing="0" width="130">
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['strength'].':</td>
							<td class="petStatsTableStatValue">'.$str.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['agility'].':</td>
							<td class="petStatsTableStatValue">'.$agi.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['stamina'].':</td>
							<td class="petStatsTableStatValue">'.$sta.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['intellect'].':</td>
							<td class="petStatsTableStatValue">'.$int.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['spirit'].':</td>
							<td class="petStatsTableStatValue">'.$spr.'</td>
						</tr>
					</table>

					<table style="text-align: left;	position: absolute;	top: 5px; left: 146px;" border="0" cellpadding="2" cellspacing="0" width="130">
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['attack'].':</td>
							<td class="petStatsTableStatValue">'.$row['melee_rating'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['power'].':</td>
							<td class="petStatsTableStatValue">'.$row['melee_power'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['damage'].':</td>
							<td class="petStatsTableStatValue">'.str_replace(':',' - ',$row['melee_range']).'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['defense'].':</td>
							<td class="petStatsTableStatValue">'.$row['defense'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$lang]['armor'].':</td>
							<td class="petStatsTableStatValue">'.$basearmor.'</td>
						</tr>
					</table>
			</div>';

			if ($showxpBar)
				$xpBar			.= '
				<div class="pet_xp" id="pet_xp_bar'.$this->posi.$petNum.'">
		            <div class="pet_xpbox">
		                <img class="xp_bg" width="100%" height="15" src="'.$roster_conf['img_url'].'barxpempty.gif" alt="" />
		                <img src="'.$roster_conf['img_url'].'expbar-var2.gif" alt="" class="pet_bit" width="'.$barpixelwidth.'" />
		                <span class="pet_xp_level">'.$xpearned.'/'.$totalxp.' ( '.$xp_percent_word.' )</span>
		            </div>
		        </div>';


			if( $row['totaltp'] != '' && $row['totaltp'] != '0' )
			{
				$trainingPoints .= '
			<span class="petTrainingPts" style="position: absolute; top: 412px; left: 100px;" id="pet_training_nm'.$this->posi.$petNum.'">'.$wordings[$lang]['unusedtrainingpoints'].': </span>
			<span class="petTrainingPts" style="color: #FFFFFF; position: absolute; top: 413px; left: 305px;" id="pet_training_pts'.$this->posi.$petNum.'" >'.$unusedtp.' / '.$row['totaltp'].'</span>';
			}

			$hpMana	.= '
			<div id="pet_hpmana'.$this->posi.$petNum.'" class="health_mana" style="position: absolute;	left: 35px; top: 65px; display: none;">
				<div class="health" style="text-align: left;">
					'.$wordings[$lang]['health'].': <span class="white">'.$row['health'].'</span>
				</div>
		        <div class="mana" style="text-align: left;">
		        	'.$wordings[$lang]['mana'].': <span class="white">'.$row['mana'].'</span>
		        </div>
			</div>';

			$petNum++;
		}


		//return all the objects
		return
		$petName
		.$petTitle
		.$loyalty
		.$petIcon
		.$resistances
		.$stats
		.$xpBar
		.$trainingPoints
		.$hpMana
		.$PetComboBox
		.$icons
		;
	}

	function printTab( $name, $div, $num, $enabled = false )
	{
		/**********************************************************
		 * onlclick event has additional Javascript that will
		 * select the first pet and show the elements for it
		**********************************************************/
		if ($div == 'page2')
			$action = 'onclick="showPet(1); doGBSynchTab($num)"';
		else
			$action = 'onclick="doGBSynchTab( '.$num.' )"';


		if( $enabled )
			$output = '<div class="tab" '.$action.'><span id="tabfont'.$div.'" class="white">'.$name.'</span></div>';
		else
			$output = '<div class="tab" '.$action.'><span id="tabfont'.$div.'" class="yellow">'.$name.'</span></div>';

		return $output;
	}

	function printGBTalents($member_id)
	{
		global $roster_conf, $wowdb, $wordings;

		$lang = $this->data['clientLocale'];

		$query = "SELECT * FROM `".ROSTER_TALENTTREETABLE."` WHERE `member_id` = '$member_id' ORDER BY `order`;";
		$trees = $wowdb->query( $query );
		if( $wowdb->num_rows($trees) > 0 )
		{
			$g = 0;

			$outtalent = '<div><img class="tbar" height="5" src="'.$roster_conf['img_url'].'tbar.png" width="300" alt="" />';
			$outtalent .= '<img id="'.$this->posi.'tlabbg1" height="32" src="'.$roster_conf['img_url'].'atab.gif" width="105" alt="" />';
			$outtalent .= '<img id="'.$this->posi.'tlabbg2" height="32" src="'.$roster_conf['img_url'].'itab.gif" width="105" alt="" />';
			$outtalent .= '<img id="'.$this->posi.'tlabbg3" height="32" src="'.$roster_conf['img_url'].'itab.gif" width="105" alt="" /></div>';

			while( $tree = $wowdb->fetch_assoc( $trees ) )
			{
				$g++;
				$c = 0;
				$outtalent .='<div class="tablabel" id="'.$this->posi.'tlab'.$g.'" onclick="setGBActiveTalentWrapper(\''.$g.'\',\''.$roster_conf['img_url'].'\',\''.$this->posi.'\');">'.$tree["tree"].'</div>';

				$output .= '<div id="'.$this->posi.'talentwrapper'.$tree['order'].'">';
				$output .= '<div id="'.$this->posi.'talentpage'.$tree['order'].'" style="background: url('.$roster_conf['interface_url'].preg_replace("|\\\\|","/", $tree['background']).'.'.$roster_conf['img_suffix'].') no-repeat">';
				$output .= $wordings[$lang]['pointsspent'].' ' . $tree['pointsspent'].'
	<table align="center" width="100%">
	  <tr>';
				while( $c < 4)
				{
					$c++;
					$r = 0;

					$output .= '
	    <td width="20"></td>
	    <td>
	      <table>';

					while( $r < 7 )
					{
						$r++;

						$output .= '        <tr>
	          <td height="45">';

						$query4 = "SELECT * FROM `".ROSTER_TALENTSTABLE."` where `member_id` = '$member_id' and `tree` = '".$tree['tree']."' and `column` = '" . $c . "' and `row` ='" . $r . "'";
						$talents4 = $wowdb->query( $query4 );

						if ($wowdb->num_rows($talents4) == 0)
						{
							$output .= '<div class="item"><img src="'.$roster_conf['img_url'].'pixel.gif" class="icon" alt="" /></div>';
						}
						else
						{
							$talent4 = $wowdb->fetch_assoc( $talents4 );
							$path4 = $roster_conf['interface_url'] . preg_replace("|\\\\|","/", $talent4['texture']) . "." . $roster_conf['img_suffix'];

							$first_line = True;
							$talent_tooltip = '';

							// Compatibility with < 1.7
							$talent4['tooltip'] = str_replace('<br>',"\n",$talent4['tooltip']);
							foreach (explode("\n", $talent4['tooltip']) as $line)
							{
								if( $first_line )
								{
									$color = 'ffffff; font-size: 12px; font-weight: bold;';
									$first_line = False;
								}
								else
								{
									if( substr( $line, 0, 2 ) == '|c' )
									{
										$color = substr( $line, 4, 6 ).';';
										$line = substr( $line, 10, -2 );
									}
									else if ( strpos( $line, $wordings[$lang]['tooltip_rank'] ) === 0 )
										$color = '00ff00; font-size: 11px;';
									else if ( strpos( $line, $wordings[$lang]['tooltip_next_rank'] ) === 0 )
										$color = 'ffffff; font-size: 11px;';
									else if ( strpos( $line, $wordings[$lang]['tooltip_requires'] ) === 0 )
										$color = 'ff0000;';
									else
										$color = 'dfb801;';
								}
								if( $line != '' )
								{
									$talent_tooltip .= '<span style="color:#'.$color.'">'.$line.'</span><br />';
								}
							}

							$talent_tooltip = str_replace("'", "\'", $talent_tooltip);
							$talent_tooltip = str_replace('"', '&quot;', $talent_tooltip);
							$talent_tooltip = str_replace('<','&lt;', $talent_tooltip);
							$talent_tooltip = str_replace('>','&gt;', $talent_tooltip);

							if ($talent4['rank'] == 0)
								$class = 'talenticonGreyed';
							else
								$class = 'talenticon';

							$output .= '<div class="item" onmouseover="return overlib(\''.$talent_tooltip.'\');" onmouseout="return nd();"><img src="'.$path4.'" class="'.$class.'" width="40" height="40" alt="" />';

							if( $talent4['rank'] == $talent4['maxrank'] )
							{
								$output .= '<span class="talvalue green">'.$talent4['rank'].'</span>';
							}
							elseif( $talent4['rank'] < $talent4['maxrank'] && $talent4['rank'] > 0 )
							{
								$output .= '<span class="talvalue yellow">'.$talent4['rank'].'</span>';
							}

							$output .= '</div>';
						}
						$output .= "</td>\n        </tr>\n";
					}
					$output .= "      </table></td>\n";
				}
				$output .= "  </tr>\n</table></div></div>\n";
			}
			return $output.$outtalent;
		}
		else
		{
			return 'No Talents for '.$this->data['name'];
		}
	}

	function out()
	{
		global $wordings, $roster_conf;

		$lang = $this->data['clientLocale'];

		if ($this->data['name'] != '')
		{
?>
<script type="text/javascript">
<!--
  addGBTab('<?php print $this->posi; ?>page1',1,'<?php print $this->posi; ?>')
<?php
if ( $this->getNumPets($this->data['name'], $this->data['server']))
	print '  addGBTab(\''.$this->posi.'page2\',2,\''.$this->posi.'\')'."\n";
?>
  addGBTab('<?php print $this->posi; ?>page3',3,'<?php print $this->posi; ?>')
  addGBTab('<?php print $this->posi; ?>page4',4,'<?php print $this->posi; ?>')
<?php
if( $roster_conf['show_talents'] )
	print '  addGBTab(\''.$this->posi.'page5\',5,\''.$this->posi.'\')'."\n";
?>
  addGBTab('<?php print $this->posi; ?>page6',6,'<?php print $this->posi; ?>')
//-->
</script>


<div class="char" id="char"><!-- Begin char -->
  <div class="main"><!-- Begin char-main -->
    <div class="top" id="top"><!-- Begin char-main-top -->
		<?php
		
		if( $this->data['RankName'] == $wordings[$lang]['PvPRankNone'] )
			$RankName = '';
		else
			$RankName = $this->data['RankName'].' ';
		
		?>
			  <h1><?php print ($RankName.$this->data['name']); ?></h1>
			  <h2>Level <?php print ($this->data['level'].' | '.$this->data['sex'].' '.$this->data['race'].' '.$this->data['class']); ?></h2>
		<?php
		
		if( isset( $this->data['guild_name'] ) )
			echo '      <h2>'.$this->data['guild_title'].' of '.$this->data['guild_name']."</h2>\n";
		
		?>
    </div><!-- End char-main-top -->
    <div class="page1" id="<?php print $this->posi; ?>page1"><!-- begin char-main-page1 -->
      <div class="left"><!-- begin char-main-page1-left -->
        <div class="equip"><?php print $this->printEquip('Head'); ?></div>
        <div class="equip"><?php print $this->printEquip('Neck'); ?></div>
        <div class="equip"><?php print $this->printEquip('Shoulder'); ?></div>
        <div class="equip"><?php print $this->printEquip('Back'); ?></div>
        <div class="equip"><?php print $this->printEquip('Chest'); ?></div>
        <div class="equip"><?php print $this->printEquip('Shirt'); ?></div>
        <div class="equip"><?php print $this->printEquip('Tabard'); ?></div>
        <div class="equip"><?php print $this->printEquip('Wrist'); ?></div>
      </div><!-- end char-main-page1-left -->
      <div class="middle"><!-- begin char-main-page1-middle -->
        <div class="portrait"><!-- begin char-main-page1-middle-portrait -->
          <div class="resistance"><!-- begin char-main-page1-middle-portrait-resistance -->
            <ul>
                <?php print $this->printRes('res_fire'); ?>
            	<?php print $this->printRes('res_nature'); ?>
            	<?php print $this->printRes('res_arcane'); ?>
            	<?php print $this->printRes('res_frost'); ?>
            	<?php print $this->printRes('res_shadow'); ?>
            </ul>
          </div><!-- end char-main-page1-middle-portrait-resistance -->
          <div class="health_mana"><!-- begin char-main-page1-middle-portrait-health_mana -->
            <div class="health"><?php print $wordings[$lang]['health'].': <span class="white">'.$this->data['health']; ?></span></div>
            <?php
			
			if( $this->data['class'] == $wordings[$lang]['Warrior'] )
				print '<div class="mana">'.$wordings[$lang]['rage'].': <span class="white">'.$this->data['mana'].'</span></div>';
			elseif( $this->data['class'] == $wordings[$lang]['Rogue'] )
				print '<div class="mana">'.$wordings[$lang]['energy'].': <span class="white">'.$this->data['mana'].'</span></div>';
			else
				print '<div class="mana">'.$wordings[$lang]['mana'].': <span class="white">'.$this->data['mana'].'</span></div>';
			
			?>

          </div><!-- end char-main-page1-middle-portrait-health_mana -->
          <div class="talentPoints"><!-- begin char-main-page1-middle-portrait-talentPoints -->
			<?php
			
			if( $this->data['crit'] != '0' ) print	$wordings[$lang]['crit'].': <span class="white">'.$this->data['crit'].'%</span><br />'."\n";
			if( $this->data['dodge'] != '0' ) print	$wordings[$lang]['dodge'].': <span class="white">'.$this->data['dodge'].'%</span><br />'."\n";
			if( $this->data['parry'] != '0' ) print	$wordings[$lang]['parry'].': <span class="white">'.$this->data['parry'].'%</span><br />'."\n";
			if( $this->data['block'] != '0' ) print	$wordings[$lang]['block'].': <span class="white">'.$this->data['block'].'%</span><br />'."\n";
			
			if($this->data['talent_points'])
				print '            '.$wordings[$lang]['unusedtalentpoints'].': <span class="white">'.$this->data['talent_points'].'</span><br />';
			
			$TimeLevelPlayedConverted = seconds_to_time($this->data['timelevelplayed']);
			$TimePlayedConverted = seconds_to_time($this->data['timeplayed']);
			
			print "<br />\n";
			print '            '.$wordings[$lang]['timeplayed'].': <span class="white">'.$TimePlayedConverted[days].$TimePlayedConverted[hours].$TimePlayedConverted[minutes].$TimePlayedConverted[seconds].'</span><br />'."\n";
			print '            '.$wordings[$lang]['timelevelplayed'].': <span class="white">'.$TimeLevelPlayedConverted[days].$TimeLevelPlayedConverted[hours].$TimeLevelPlayedConverted[minutes].$TimeLevelPlayedConverted[seconds].'</span>'."\n";
			?>
			</div><!-- end char-main-page1-middle-portrait-talentPoints -->
          <div class="xp" style="padding-left:12px;"><!-- begin char-main-page1-middle-portrait-xp -->
            <div class="xpbox">
				<?php
				
				// Code to write a "Max Exp bar" just like in SigGen
				if( $this->data['level'] == '60' )
				{
					$expbar_width = '248';
					$expbar_text = $wordings[$lang]['max_exp'];
				}
				else
				{
					$expbar_width = $this->printXP();
					list($xp, $xplevel, $xprest) = explode(':',$this->data['exp']);
					if ($xplevel != '0' || $xplevel != '')
					{
						if( $xprest > 0 )
						{
							$expbar_text = $xp.'/'.$xplevel.' : '.$xprest.' ('.round($xp/$xplevel*100).'%)';
						}
						else
						{
							$expbar_text = $xp.'/'.$xplevel.' ('.round($xp/$xplevel*100).'%)';
						}
					}
				}
				
				?>
              <img class="bg" alt="" src="<?php echo $roster_conf['img_url']?>barxpempty.gif"/>
              <img src="<?php echo $roster_conf['img_url']?>expbar-var2.gif" alt="" class="bit" width="<?php print $expbar_width; ?>"/>
              <span class="level"><?php print $expbar_text;?></span>
            </div>
          </div><!-- end char-main-page1-middle-portrait-xp -->
        </div><!-- end char-main-page1-middle-portrait -->
        <div class="bottom"><!-- begin char-main-page1-middle-bottom -->
          <div class="padding">
            <ul class="stats">
              <li><?php print $wordings[$lang]['strength'] .': '.$this->printStat('stat_str'); ?></li>
              <li><?php print $wordings[$lang]['agility']  .': '.$this->printStat('stat_agl'); ?></li>
              <li><?php print $wordings[$lang]['stamina']  .': '.$this->printStat('stat_sta'); ?></li>
              <li><?php print $wordings[$lang]['intellect'].': '.$this->printStat('stat_int'); ?></li>
              <li><?php print $wordings[$lang]['spirit']   .': '.$this->printStat('stat_spr'); ?></li>
              <li><?php print $wordings[$lang]['armor']    .': '.$this->printStat('stat_armor'); ?></li>
            </ul>
            <ul class="stats">
              <li><?php print $wordings[$lang]['melee_att'] .' '.$this->printAtk('melee','rating')."\n"; ?>
                <ul>
                  <li><?php print $wordings[$lang]['power'] .': '. $this->printAtk('melee','power'); ?></li>
                  <li><?php print $wordings[$lang]['damage'].': '. $this->printAtk('melee','range'); ?></li>
                </ul></li>
              <li><?php print $wordings[$lang]['range_att'] .' '. $this->printAtk('ranged','rating')."\n"; ?>
                <ul>
                  <li><?php print $wordings[$lang]['power'] .': '. $this->printAtk('ranged','power'); ?></li>
                  <li><?php print $wordings[$lang]['damage'].': '. $this->printAtk('ranged','range'); ?></li>
                </ul></li>
            </ul>
          </div><!-- end char-main-page1-middle-bottom-padding -->
          <div class="hands">
            <div class="weapon0"><?php print $this->printEquip('MainHand'); ?></div>
            <div class="weapon1"><?php print $this->printEquip('SecondaryHand'); ?></div>
            <div class="weapon2"><?php print $this->printEquip('Ranged'); ?></div>
            <div class="ammo"><?php print $this->printEquip('Ammo'); ?></div>
          </div><!-- end char-main-page1-middle-bottom-hands -->
        </div><!-- end char-main-page1-middle-bottom -->
      </div><!-- end char-main-page1-middle -->
      <div class="right"> <!-- begin char-main-page1-right -->
        <div class="equip"><?php print $this->printEquip('Hands'); ?></div>
        <div class="equip"><?php print $this->printEquip('Waist'); ?></div>
        <div class="equip"><?php print $this->printEquip('Legs'); ?></div>
        <div class="equip"><?php print $this->printEquip('Feet'); ?></div>
        <div class="equip"><?php print $this->printEquip('Finger0'); ?></div>
        <div class="equip"><?php print $this->printEquip('Finger1'); ?></div>
        <div class="equip"><?php print $this->printEquip('Trinket0'); ?></div>
        <div class="equip"><?php print $this->printEquip('Trinket1'); ?></div>
      </div><!-- end char-main-page1-right -->
    </div><!-- end char-main-page1 -->
	<?php
	
	if ($this->getNumPets($this->data['name'], $this->data['server']) > 0)
	{
		$petTab = $this->printPet($this->data['name'], $this->data['server']);
		$tab = '
		<div class="page2" id="'.$this->posi.'page2"><!-- begin char-main-page2 -->
		  <div class="left"></div>
		  <div class="pet"><!-- begin char-main-page2-pet -->
				'.$petTab.'
		  </div>
		  <div class="right"></div>
			</div><!-- end char-main-page2 -->';
		print $tab;
	}
	
	?>

    <div class="page3" id="<?php print $this->posi; ?>page3"><!-- begin char-main-page3 -->
      <div class="left"></div>
      <div class="reputation"><!-- begin char-main-page3-reputation -->
        <?php print $this->printReputation(); ?>
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page3 -->
    <div class="page4" id="<?php print $this->posi; ?>page4"><!-- begin char-main-page4 -->
      <div class="left"></div>
      <div class="skills"><!-- begin char-main-page4-skills -->
        <?php print $this->printSkills(); ?>
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page4 -->
		<?php
		
		if( $roster_conf['show_talents'] )
		{
			$talent_tab = '
			<div class="page5" id="'.$this->posi.'page5"><!-- begin char-main-page5 -->
			  <div class="gbleft"></div>
			  <div class="talents"><!-- begin char-main-page5-talents -->
					'.$this->printGBTalents( $this->data['member_id']).'
			  </div>
			  <div class="gbright"></div>
			</div><!-- end char-main-page5 -->';
			print $talent_tab;
		}
		
		?>

    <div class="page6" id="<?php print $this->posi; ?>page6"><!-- begin char-main-page6 -->
      <div class="left"></div>
      <div class="honor"><!-- begin char-main-page6-honor -->
        <?php print $this->printHonor(); ?>
      </div>
      <div class="right"></div>
    </div><!-- end char-main-page6 -->
  </div><!-- end char-main -->
  <div class="bottomBorder"></div>
  <div class="tabs"><!-- begin char-tabs -->
<?php

print $this->printTab( $wordings[$lang]['tab1'], $this->posi.'page1', 1, True);
echo "\n";
if ($this->data['class'] == $wordings[$lang]['Hunter'] || $this->data['class'] == $wordings[$lang]['Warlock'])
{
	if ($this->getNumPets($this->data['name'],$this->data['server']) > 0)
	{
		print $this->printTab( $wordings[$lang]['tab2'], $this->posi.'page2' , 2 );
		echo "\n";
	}
}
print $this->printTab( $wordings[$lang]['tab3'], $this->posi.'page3' , 3 )."\n";
print $this->printTab( $wordings[$lang]['tab4'], $this->posi.'page4' , 4)."\n";
if( $roster_conf['show_talents'] )
{
	print $this->printTab( $wordings[$lang]['tab5'], $this->posi.'page5' , 5);
	echo "\n";
}
print $this->printTab( $wordings[$lang]['tab6'], $this->posi.'page6' , 6 )."\n";

echo '  </div><!-- end char-tabs -->
</div><!-- end char -->
';
		}
		else
		{
			die_quietly('Sorry no data in database for '.$name.' of '.$_GET['server'],'Character Not Found');
		}
	}
}

function gbchar_get_one( $name, $server, $posi )
{
	global $wowdb;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT a.*, b.*, c.guild_name FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
	"WHERE a.member_id = b.member_id AND a.name = '$name' AND a.server = '$server' AND a.guild_id = c.guild_id";
	$result = $wowdb->query( $query );
	if( $wowdb->num_rows($result) > 0 )
	{
		$data = $wowdb->fetch_assoc( $result );
		return new gbchar( $data, $posi );
	}
	else
	{
		return false;
	}
}

function cleandumpBonuses($char, $server)
{
	global $myBonus, $myTooltip, $wordings, $roster_conf, $wowdb;

	$server = $wowdb->escape( $server );
	$qry  = "SELECT i.item_tooltip, i.item_name, i.item_color, p.parry, p.dodge, p.block, p.crit, p.mitigation, p.clientLocale
			FROM `".ROSTER_ITEMSTABLE."` i, `".ROSTER_PLAYERSTABLE."` p
			WHERE i.item_parent = 'equip'
				and i.member_id = p.member_id
				and p.name = '".$char."'
				and p.server = '".$server."'";

	$result = $wowdb->query($qry) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry);
	while($row = $wowdb->fetch_array($result))
	{
		sortOutTooltip($row['item_tooltip'], $row['item_name'], substr($row['item_color'], 2, 6),$row['clientLocale'] );
	}

	$wowdb->data_seek($result, 0);
	$row = $wowdb->fetch_array($result);

	$bt .= border('sgray','start',$wordings[$roster_conf['roster_lang']]['itembonuses']).
		'<table style="width:400px;" class="bodyline" cellspacing="0" cellpadding="0" border="0">'."\n";

	$row = 0;
	foreach ($myBonus as $key => $value)
	{
		$bt .= '	<tr>
		<td class="membersRowRight'.(($row%2)+1).'" style="white-space:normal;" onmouseover="return overlib(\''.
		$myTooltip[$key].
		'\',CAPTION,\''.
		addslashes(str_replace('XX', $value, $key)).
		'\');" onmouseout="return nd();">'.
		str_replace('XX', $value, $key).'</td>
	</tr>';

		$row++;
	}
	$bt .= '</table>'.border('sgray','end');
	
	$wowdb->free_result($result);
	//unset($GLOBALS['myBonus']);
	$myBonus = array();
	$myTooltip = '';
	if( $row > 0  ) {
		return $bt;
	}
}

?>