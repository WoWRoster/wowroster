<?php
require_once 'wowdb.php';
require_once 'item.php';
require_once 'skill.php';
require_once 'reputation.php';
require_once 'quest.php';
require_once 'recipes.php';
require_once 'pvp2.php';

class char {
	var $data;

	function char( $data ) {
		$this->data = $data;
	}

	function printXP() {
		list($current, $max) =
		explode( ':', $this->data['exp'] );
		if ($current > 0) {
			$perc = round(($current / $max)* 255, 1);
			//echo '<span class="white">';
			$forCurr = number_format($current);
			$forMax = number_format($max);
			echo /*"$forCurr of $forMax (*/"$perc" /*%)</span>"*/;
		}
	}

	function show_pvp2($Type, $url, $sort, $start) {
		$pvps = pvp_get_many2( $this->data['member_id'],$Type, $sort, -1);
		output_pvp_summary($pvps);
		$max = sizeof($pvps);
		print '<br><center>';
		$sort_part=$sort? "&s=$sort" : "";
		if ($start > 0) {
			print $url.'&start='.($start-50).$sort_part.'">Previous Page</a>  |  ';
		}
		if (($start+50) < $max) {
			print $start." - ".($start+50);
			print "  |  ".$url.'&start='.($start+50).$sort_part.'">Next Page</a>';
		} else {
			print $start." - ".($max);
		}
		print '</center><br>';
		$pvps = pvp_get_many2( $this->data['member_id'],$Type, $sort, $start);
		if( isset( $pvps[0] ) ) {
			echo '<b><u><h3>'.$Type.' Log</h3></u></b>';
			output_pvp2($pvps, $url."&start=".$start);
		}
		print '<br><center>';
		if ($start > 0) {
			print $url.'&start='.($start-50).$sort_part.'">Previous Page</a>  |  ';
		}
		if (($start+50) < $max) {
			print $start." - ".($start+50);
			print "  |  ".$url.'&start='.($start+50).$sort_part.'">Next Page</a>';
		} else {
			print $start." - ".($max);
		}
		print '</center><br>';
	}

	function show_quests() {
		extract($GLOBALS);
		$quests = quest_get_many( $this->data['member_id'],'');
		if( isset( $quests[0] ) ) {
			$zone = '';
			echo '<h3>'.$wordings[$roster_lang]['questlog'].'&nbsp; ('.count($quests).'/20)</h3>';
			echo '<table class="bodyline">';

			foreach ($quests as $quest) {
				if ($zone != $quest->data['zone']) {
					$zone = $quest->data['zone'];
					echo '<tr class="membersRow2"><td colspan="5">'."\n";
					echo '<h2>'.$zone.'</h2></td></tr>'."\n";
				}
				$quest_level = $quest->data['quest_level'];
				$char_level = $this->data['level'];
				$font = 'grey';

				if ($quest_level + 7 < $char_level) {
					$font = 'grey'; }
					else if ($quest_level + 2 < $char_level) {
						$font = 'green'; }
						else if ($quest_level < $char_level+2) {
							$font = 'yellow'; }
							else {
								$font = 'red';
							}

							echo '<tr class="membersRow1"><td>';
							echo '&nbsp;&nbsp;&nbsp;&nbsp;<font class="'.$font.'">['.$quest_level.'] </font>';

							$name = $quest->data['quest_name'];
							if ($name{0} == '[') {
								$name = trim(strstr($name, ' '));
							}
							echo $name;

							if ($quest->data['quest_tag']) {
								echo ' ('.$quest->data['quest_tag'].')</td>';
							}

							if ($this->data['clientLocale'] == 'enUS') {
								if ($questlink_1)  {
									echo '<td>&nbsp;<a href="'.$questlink_1_url1[$this->data['clientLocale']].urlencode($name).$questlink_1_url2[$this->data['clientLocale']].$quest_level.$questlink_1_url3[$this->data['clientLocale']].$quest_level.'" target="_blank">'.$questlink_1_name[$this->data['clientLocale']].'</a>&nbsp;</td>';
								}
								if ($questlink_2)  {
									echo '<td>&nbsp;<a href="'.$questlink_2_url1[$this->data['clientLocale']].urlencode($name).$questlink_2_url2[$this->data['clientLocale']].$quest_level.$questlink_2_url3[$this->data['clientLocale']].$quest_level.'" target="_blank">'.$questlink_2_name[$this->data['clientLocale']].'</a>&nbsp;</td>';
								}
								if ($questlink_3)  {
									echo '<td>&nbsp;<a href="'.$questlink_3_url1[$this->data['clientLocale']].urlencode($name).'" target="_blank">'.$questlink_3_name[$this->data['clientLocale']].'</a>&nbsp;</td>';
								}
							}
							if ($this->data['clientLocale'] == 'deDE') {
								if ($questlink_1)  {
									echo '<td>&nbsp;<a href="'.$questlink_1_url1[$this->data['clientLocale']].urlencode(utf8_decode($name)).'" target="_blank">'.$questlink_1_name[$this->data['clientLocale']].'</a>&nbsp;</td>';
								}
								if ($questlink_2)  {
									echo '<td>&nbsp;<a href="'.$questlink_2_url1[$this->data['clientLocale']].urlencode(utf8_decode($name)).'" target="_blank">'.$questlink_2_name[$this->data['clientLocale']].'</a>&nbsp;</td>';
								}
								if ($questlink_3)  {
									echo '<td>&nbsp;<a href="'.$questlink_3_url1[$this->data['clientLocale']].urlencode($name).'" target="_blank">'.$questlink_3_name[$this->data['clientLocale']].'</a>&nbsp;</td>';
								}
							}
			}
			echo '</table>';
		}
	}

	function show_recipes() {
		extract($GLOBALS);
		global $img_url;
		global $url;
		global $sort_recipe;
		$recipes = recipe_get_many( $this->data['member_id'],'', $sort_recipe );
		if( isset( $recipes[0] ) ) {
			$skill_name = '';
			$active_table = 0;
			echo '<h1>'.$wordings[$roster_lang]['recipelist'].'</h1>'."\n";
			$rc = 0;
			foreach ($recipes as $recipe) {
				if ($skill_name != $recipe->data['skill_name']) {
					$skill_name = $recipe->data['skill_name'];
					if ($active_table == 1) {
						echo '</table>';
						$active_table = 0;
					}
					echo '<table class="bodyline"><br><br><div align="left"><img src="'.$img_url.$skill_name.'.gif" alt="'.$skill_name.'"></div>
	<tr class="membersHeader">
		<td>'.$url.'&action=recipes&sort=item">'.$wordings[$roster_lang]['item'].'</a></td>
		<td>'.$url.'&action=recipes&sort=name">'.$wordings[$roster_lang]['name'].'</td>
		<td>'.$url.'&action=recipes&sort=type">'.$wordings[$roster_lang]['type'].'</td>
		<td>'.$url.'&action=recipes&sort=reagents">'.$wordings[$roster_lang]['reagents'].'</td></tr>'."\n";
				}
				if( $recipe->data['difficulty'] == '4' ) {
					$difficultycolor = 'FF9900';
				} elseif( $recipe->data['difficulty'] == '3' ) {
					$difficultycolor = 'FFFF66';
				} elseif( $recipe->data['difficulty'] == '2' ) {
					$difficultycolor = '339900';
				} elseif( $recipe->data['difficulty'] == '1' ) {
					$difficultycolor = 'CCCCCC';
				} else { $difficultycolor = 'FFFF80'; }

				echo '	<tr class="membersRow'.(($rc%2)+1).'"><td><div class="equip">';
				$recipe->out($difficultycolor);
				echo '</div></td>
		<td><font color="#'.$difficultycolor.'">&nbsp;'.$recipe->data['recipe_name'].'</font></td>
		<td>&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>
		<td>&nbsp;'.str_replace('<br>','&nbsp;<br>&nbsp;',$recipe->data['reagents']).'</td></tr>'."\n";
				$rc++;
			}
			echo '</table>';
		}
	}

	function get( $field ) {
		return $this->data[$field];
	}
	
	function getNumPets($name, $server){
		global $wowdb; 				//the object derived from class wowdb used to do queries
		extract($GLOBALS);
		/******************************************************************************************************************
		returns the number of pets the character has in the database
		******************************************************************************************************************/
		$query = "SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name' AND `server` = '".addslashes($server)."'";
		$result = $wowdb->query( $query );
		$row = $wowdb->getrow( $result );
		$member_id = $row['member_id'];
		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$member_id' order by `level` DESC";
		$result = $wowdb->query( $query );
		return mysql_num_rows($result);
	}

	function printPet($name, $server) {
		global $img_url;
		global $img_suffix;
		global $wowdb; 				//the object derived from class wowdb used to do queries
		extract($GLOBALS);

		/******************************************************************************************************************
		Gets all the pets from the database associated with that character from that server.
		******************************************************************************************************************/

		$query = "SELECT * FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name' AND `server` = '".addslashes($server)."'";
		$result = $wowdb->query( $query );
		$row = $wowdb->getrow( $result );
		$member_id = $row['member_id'];
		$query = "SELECT * FROM `".ROSTER_PETSTABLE."` WHERE `member_id` = '$member_id' order by `level` DESC";
		$result = $wowdb->query( $query );

		$petNum = 1;
		while ($row = mysql_fetch_assoc($result)) {

			$showxpBar = true;
			if ( strlen($row['xp']) < 1 )$showxpBar = false;

			list($xpearned, $totalxp) = split(":",$row['xp']);
			if ($totalxp == 0) {
				$xp_percent = .00;
			} else {
				$xp_percent = $xpearned / $totalxp;
			}
			$barpixelwidth = floor(381 * $xp_percent);
			$xp_percent_word = floor($xp_percent * 100).'%';
			$unusedtp = $row['totaltp'] - $row['usedtp'];
			
			if ($row['level'] == 60) {
				$showxpBar = false;
			}

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

			switch ($petNum){
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
			$iconStyle='cursor: hand; cursor: pointer; position: absolute; left: '.$left.'px; top: '.$top.'px;';

			if ($row['icon'] == "" || !isset($row['icon']))
			{
				$row['icon'] = "unknownIcon.gif";
			}
			else {
				$row['icon'] .= ".$img_suffix";
			}
			
			$icons			.= '<img src="'.$img_url.str_replace("\\","/",$row['icon']).'" onclick="showPet(\''.$petNum.'\')" style="'.$iconStyle.'">';
			$petName		.= '<span class="petName" style="top: 20px; left: 95px; display: none;" id="pet_name'.$petNum.'">' . stripslashes($row['name']).'</span>';
			$petTitle		.= '<span class="petName" style="top: 40px; left: 95px; display: none;" id="pet_title'.$petNum.'">'.$wordings[$roster_lang]['level'].' '.$row['level'].' ' . stripslashes($row['type']).'</span>';
			$loyalty		.= '<span class="petName" style="top: 60px; left: 95px; display: none;" id="pet_loyalty'.$petNum.'">'.$row['loyalty'].'</span>';
			$petIcon		.= '<img id="pet_top_icon'.$petNum.'" style="position: absolute;	left: 35px;	top: 20px; width: 55px;	height: 60px; display: none;" src="'.$img_url.str_replace("\\","/",$row['icon']).'">';
			$resistances	.= '<div  class="pet_resistance" id="pet_resistances'.$petNum.'">
				<ul>
				<li class="pet_fire"><span class="white" id="pet_fire">'.$row['res_fire'].'</span></li>
				<li class="pet_nature"><span class="white">'.$row['res_nature'].'</span></li>
				<li class="pet_arcane"><span class="white">'.$row['res_arcane'].'</span></li>
				<li class="pet_frost"><span class="white">'.$row['res_frost'].'</span></li>
				<li class="pet_shadow"><span class="white">'.$row['res_shadow'].'</span></li>
				</ul>
			</div>';
			$stats			.= '
			<div class="petStatsBg" id="pet_stats_table'.$petNum.'" >
					<table style="text-align: left; position: absolute; top: 5px; left: 5px;" border="0" cellpadding="2" cellspacing="0" width="130">
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['strength'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_strength">'.$str.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['agility'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_agility">'.$agi.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['stamina'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_stamina">'.$sta.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['intellect'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_intellect">'.$int.'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['spirit'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_spirit">'.$spr.'</td>
						</tr>
					</table>
					
					<table style="text-align: left;	position: absolute;	top: 5px; left: 146px;" border="0" cellpadding="2" cellspacing="0" width="130">
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['attack'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_attack">'.$row['melee_rating'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['power'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_power">'.$row['melee_power'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['damage'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_damage">'.str_replace(':',' - ',$row['melee_range']).'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['defense'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_defense">'.$row['defense'].'</td>
						</tr>
						<tr>
							<td class="petStatsTableStatname">'.$wordings[$roster_lang]['armor'].':</td>
							<td class="petStatsTableStatValue" id="pet_stat_armor">'.$basearmor.'</td>
						</tr>
					</table>
			</div>';

			if ($showxpBar)
				$xpBar			.= '
				<div class="pet_xp" id="pet_xp_bar'.$petNum.'">
		            <div class="pet_xpbox">
		                <img class="xp_bg" alt="" width="100%" height=15 src="img/barXpEmpty.gif">
		                <img src="img/expbar-var2.gif" alt="" id="pet_xp_bar" class="pet_bit" width="'.$barpixelwidth.'">
		                <span id="pet_xp_percent" class="pet_xp_level">'.$xpearned.'/'.$totalxp.' ( '.$xp_percent_word.' )</span>
		            </div>
		        </div>';

			$trainingPoints	.= '
			<span class="petTrainingPts" style="position: absolute; top: 412px; left: 100px;" id="pet_training_nm'.$petNum.'">'.$wordings[$roster_lang]['unusedtrainingpoints'].': </span>
			<span class="petTrainingPts" style="color: #FFFFFF; position: absolute; top: 413px; left: 305px;" id="pet_training_pts'.$petNum.'" >'.$unusedtp.' / '.$row['totaltp'].'</span>';
			if ($row['totaltp'] == "" || $row['totaltp'] == 0)$trainingPoints = "";
			$hpMana			.= '
			<div id="pet_hpmana'.$petNum.'" class="health_mana" style="position: absolute;	left: 35px; top: 65px; display: none;">
				<div class="health" style="text-align: left;">
					<span>'.$wordings[$roster_lang]['health'].': 
						<span class="white">'.$row['health'].'</span>
					</span>
				</div>
		        <div class="mana" style="text-align: left;">
		        	<span>'.$wordings[$roster_lang]['mana'].': 
		        		<span class="white">'.$row['mana'].'</span>
		        	</span>
		        </div>
			</div>';

			$petNum++;
		}
		$javascript ="<script type=\"text/javascript\">
<!--


function showPet(cNum){

	var ids = new Array();
	ids[0] = 'pet_name';
	ids[1] = 'pet_title';
	ids[2] = 'pet_loyalty';
	ids[3] = 'pet_top_icon';
	ids[4] = 'pet_resistances';
	ids[5] = 'pet_stats_table';
	ids[6] = 'pet_xp_bar';
	ids[7] = 'pet_training_pts';
	ids[8] = 'pet_hpmana';
	ids[9] = 'pet_training_nm';


	for(a = 0; a < 15; a++) {
		for(i = 0; i < 15; i++) {
			if (cNum != i) {			
				var oName= document.getElementById(ids[a]+String(i));
				if (oName != null)
				{
					hideElem(oName);
				}
			} else {
				var oName= document.getElementById(ids[a]+String(i));
				if (oName != null) {
					if(oName.style.display == 'none' || oName.style.display == '') { showElem(oName); }
				}
			}
		}
	}
}
	
function showElem(oName){oName.style.display='block';}
function hideElem(oName){oName.style.display='none';} 
//-->
</script>

";


		//return all the objects
		return
		$javascript
		.$petName
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

	function printStat( $statname ) {
		extract($GLOBALS);
		$base = 0;
		$mod = 0;
		$current = 0;
		list($base, $current, $mod) = explode( ':', $this->data[$statname] );
		if (ereg(':', $mod)) { $mod = substr($mod, 0, strpos($mod, ':')); }
		$id = $statname.':'.$base.':'.$current.':'.$mod;
		if( $mod == 0 ) {
			$color = 'white';
			$mod_symbol = '';
		} else if( $mod < 0 ) {
			$color = 'purple';
			$mod_symbol = '-';
		} else {
			$color = 'green';
			$mod_symbol = '+';
		}
		switch($statname) {
			case 'stat_str':
			$name = $wordings[$roster_lang]['strength'];
			$tooltip = $wordings[$roster_lang]['strength_tooltip'];
			break;
			case 'stat_int':
			$name = $wordings[$roster_lang]['intellect'];
			$tooltip = $wordings[$roster_lang]['intellect_tooltip'];
			break;
			case 'stat_sta':
			$name = $wordings[$roster_lang]['stamina'];
			$tooltip = $wordings[$roster_lang]['stamina_tooltip'];
			break;
			case 'stat_spr':
			$name = $wordings[$roster_lang]['spirit'];
			$tooltip = $wordings[$roster_lang]['spirit_tooltip'];
			break;
			case 'stat_agl':
			$name = $wordings[$roster_lang]['agility'];
			$tooltip = $wordings[$roster_lang]['agility_tooltip'];
			break;
			case 'armor':
			$name = $wordings[$roster_lang]['armor'];
			$tooltip = $wordings[$roster_lang]['armor_tooltip'];
			break;
		}

		if($mod_symbol == '') {
			$tooltipheader = $name.' '.$current;
		} else {
			$tooltipheader = "$name $current ($base <font class=\"$color\">$mod_symbol $mod</font>)";
		}
                $line = '<span style="color: #FFFFFF; font-weight: bold;">'.$tooltipheader.'</span><br>'; 
                $line .= '<span style="color: #DFB801; font-size: 9px;">'.$tooltip.'</span>'; 
                $clean_line = str_replace("'", "\'", $line); 
                $clean_line = str_replace('"','&quot;', $clean_line); 
                $clean_line = str_replace('(',"\(", $clean_line); 
                $clean_line = str_replace(')',"\)", $clean_line); 
                echo '<span class="tooltip" style="z-index: 1000;" onMouseover="return overlib(\''.$clean_line.'\');" onMouseout="return nd();">'; 
                echo '<strong class="tip">'; 
                echo $line; 
                echo '</strong><strong class="'.$color.'">'.$current.'</strong>'; 
                echo '</span>'; 

	}

	function printRes ( $resname ) {
		extract($GLOBALS);

		switch($resname) {
		case 'res_fire':
			$name = $wordings[$roster_lang]['res_fire'];
			$tooltip = $wordings[$roster_lang]['res_fire_tooltip'];
			$color = 'red';
			break;
		case 'res_nature':
			$name = $wordings[$roster_lang]['res_nature'];
			$tooltip = $wordings[$roster_lang]['res_nature_tooltip'];
			$color = 'green';
			break;
		case 'res_arcane':
			$name = $wordings[$roster_lang]['res_arcane'];
			$tooltip = $wordings[$roster_lang]['res_arcane_tooltip'];
			$color = 'yellow';
			break;
		case 'res_frost':
			$name = $wordings[$roster_lang]['res_frost'];
			$tooltip = $wordings[$roster_lang]['res_frost_tooltip'];
			$color = 'blue';
			break;
		case 'res_shadow':
			$name = $wordings[$roster_lang]['res_shadow'];
			$tooltip = $wordings[$roster_lang]['res_shadow_tooltip'];
			$color = 'purple';
			break;
		}

		$tooltipheader = $name;
		$line = '<span style="color: '.$color.'; font-weight: bold;">'.$tooltipheader.'</span><br>';
		$line .= '<span style="color: #DFB801; font-size: 9px; text-align: left;">'.$tooltip.'</span>';
		$line = str_replace("'", "\'", $line);
		$line = str_replace('"','&quot;', $line);
		echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$line.'\');"onMouseout="return nd();">';
		echo '<strong class="white">'.$this->data[$resname].'</strong>';
		echo '</span>';
	}

	function printEquip( $slot ) {
		global $img_url; // Added this global variable to use in the image link
		$item = item_get_one( $this->data['member_id'], $slot );
		if( isset($item) ){
			$item->out();
		} else {
			echo '<div class="item">';
			echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$slot.': No&nbsp;item&nbsp;equipped\',WIDTH,10); "onMouseout="return nd();">';
			if ($slot == 'Ammo') {
				echo '<img src="'.$img_url.'Interface/EmptyEquip/'.$slot.'.gif" class="iconsmall" /></a>';
			} else {
				echo '<img src="'.$img_url.'Interface/EmptyEquip/'.$slot.'.gif" class="icon" /></a>';
			}
			echo '</span></div>';
		}
	}

	function printAtk( $type, $stat ) {
		extract($GLOBALS);
		$atk = $this->data[$type.'_'.$stat];
		$atktooltip = $this->data[$type.'_' .$stat.'_tooltip'];

		if (ereg(':', $atk)) { $atk = ereg_replace(':', ' - ', $atk); }
		switch($stat) {
			case 'rating':
			if($type =='melee') {
				$tooltipheader = $wordings[$roster_lang]['melee_rating'];
				$tooltip = $wordings[$roster_lang]['melee_rating_tooltip'];
			} else {
				$tooltipheader = $wordings[$roster_lang]['range_rating'];
				$tooltip = $wordings[$roster_lang]['range_rating_tooltip'];
			}
			break;
			case 'power':
			if($type =='melee') {
				$tooltipheader = $wordings[$roster_lang]['melee_att_power'].' '.$atk;
				$tooltip = nl2br($atktooltip);
			}else{
				$tooltipheader = $wordings[$roster_lang]['range_att_power'].' '.$atk;
				$tooltip =  nl2br($atktooltip);
			}
			break;
			case 'range':
			if($type =='melee') {
				$tooltipheader = $wordings[$roster_lang]['damage'].' '.$atk;
				$tooltip = nl2br($atktooltip);
			}else{
				$tooltipheader = $wordings[$roster_lang]['damage'].' '.$atk;
				$tooltip =  nl2br($atktooltip);
			}
			break;
		}
		$line = "<span style=\"color: #FFFFFF; font-weight: bold;\">$tooltipheader</span><br>";
		$line .= "<span style=\"color: #DFB801; font-size: 9px;\">$tooltip</span>";
		$line = str_replace("'", "\'", $line);
		$line = str_replace('"','&quot;', $line);
		$line = str_replace("\n", '', $line);
		echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$line.'\');" onMouseout="return nd();">';
		if($atk == '') { $atk = 'N/A'; }
		echo '<strong style="color:#ffffff">'.$atk.'</strong>';
		echo '</span>';
	}


	function printTab( $name, $div, $enabled = False ) {
		if( $enabled ) {
			echo '<div class="tab"><font id="tabfont'.$div.'" class="white">';
		} else {
			echo '<div class="tab"><font id="tabfont'.$div.'" class="yellow">';
		}
		if ($div == 'page2') {
		/**********************************************************
		onlclick event has additional Javascript that will
		select the first pet and show the elements for it
		**********************************************************/
			echo "<span onClick=\"showPet(1); doTab( '$div' )\">$name</span></font></div>";
		} else {
			echo "<span onClick=\"doTab( '$div' )\">$name</span></font></div>";
		}
	}

/*	function printTalents($member_id) {
		global $img_url;
		global $img_suffix;
		global $wowdb;
		extract($GLOBALS);
		$query = "SELECT * FROM `".ROSTER_TALENTTREETABLE."` where `member_id` = '$member_id' ORDER BY `order`;";
		$trees = $wowdb->query( $query );
		$outtalent =  '<table cellpadding="10" cellshading="0" border="0" class="talent">';
		if(mysql_num_rows($trees) == 0)
		$outtalent = $outtalent . '<tr><td colspan="2">No Talents in DB Yet!</td></tr>';
		while( $tree = $wowdb->getrow( $trees ) ) {
			$outtalent = $outtalent . '<tr><td colspan="2">';
			$outtalent = $outtalent .  '<div class="treename"><div class="treenamebox"><img class="bg" alt="" src="img/barGrey.gif" />';
			$outtalent = $outtalent .  '<span class="name">'.$tree['tree'].'</span>';
			$outtalent = $outtalent .  '<span class="level">'.$wordings[$roster_lang]['pointsspent'].' ' . $tree['pointsspent'].'</span>';
			$outtalent = $outtalent .  '</div></div></td></tr>';
			$name = $tree['tree'];
			$query= "SELECT * FROM `".ROSTER_TALENTSTABLE."` where `member_id` = '$member_id' and `tree` = '$name' and `rank` > 0 ORDER BY `row`, `column`;";

			$talents = $wowdb->query( $query );

			if(mysql_num_rows($talents) == 0) {
				$outtalent = $outtalent .  '<tr><td colspan="2"><i>'.$wordings[$roster_lang]['none'].'</i></td></tr>';
			}
			while( $talent = $wowdb->getrow( $talents ) ) {
				$path = $img_url . preg_replace("|\\\\|",'/', $talent['texture']).".$img_suffix";
				$outtalent = $outtalent .  '<tr><td><p><img src="' . $path . '" class="talenticon"></p>' . $talent['rank'] . ' / ' . $talent['maxrank'] . '</td><td>' .
				$talent['tooltip'] . '</td></tr>';
			}
		}
		$outtalent = $outtalent .  '</table>';

		//$outtalent->out();
		echo $outtalent;
	} */

	function printTalents($member_id) {
		global $img_url;
		global $img_suffix;
		global $wowdb;
		extract($GLOBALS);
		$query = "SELECT * FROM `".ROSTER_TALENTTREETABLE."` where `member_id` = '$member_id' ORDER BY `order`;";
		$trees = $wowdb->query( $query );
		$g = 0;

		$outtalent = '<img class="tbar" height="5" src="'.$img_url.'tbar.png" width="300">';
		$outtalent = $outtalent . '<img id="tlabbg1" height="32" src="'.$img_url.'atab.gif" width="105">';
		$outtalent = $outtalent . '<img id="tlabbg2" height="32" src="'.$img_url.'itab.gif" width="105">';
		$outtalent = $outtalent . '<img id="tlabbg3" height="32" src="'.$img_url.'itab.gif" width="105">';

		while( $tree = $wowdb->getrow( $trees ) ) {
			$g++;
			$c = 0;
			$outtalent = $outtalent . '<div class="tablabel" id="tlab'.$g.'" style="font-weight: bold; color: #cdad0f" onclick="setActiveTalentWrapper(\''.$g.'\');">'.$tree["tree"].'</div>';

			echo '<div id="talentwrapper'.$tree['order'].'">';
			echo '<div id="talentpage'.$tree['order'].'" style="background: url('.$img_url.preg_replace("|\\\\|","/", $tree['background']).'.'.$img_suffix.') no-repeat">';
			echo '<table align="center" width="100%" valign="center"><tr>';

			while( $c < 4) {
				$c++;
				$r = 0;

				echo '<td width="20"></td>';
				echo '<td><table>';

				while( $r < 7 ) {
					$r++;

					echo '<tr><td height="45">';

					$query4 = "SELECT * FROM `".ROSTER_TALENTSTABLE."` where `member_id` = '$member_id' and `tree` = '".$tree['tree']."' and `column` = '" . $c . "' and `row` ='" . $r . "'";
					$talents4 = $wowdb->query( $query4 );

					$first_line = True;
					$talent_tooltip = "";

					if (mysql_num_rows($talents4) == 0) {
						echo '<div class="item"><img src="'.$img_url.'pixel.gif" class="icon" /></div>';
					} else {
						$talent4 = $wowdb->getrow( $talents4 );
						$path4 = $img_url . preg_replace("|\\\\|","/", $talent4['texture']) . "." . $img_suffix;

						foreach (explode("<br>", $talent4['tooltip']) as $line) {

							if( $first_line ) {
								$color = 'ffffff; font-weight: bold;';
								$first_line = False;
							} else {
								if( substr( $line, 0, 2 ) == '|c' ) {
									$color = substr( $line, 4, 6 ).'; font-size: 10px;';
									$line = substr( $line, 10, -2 );
								} else if ( substr( $line, 0, 4 ) == 'Rank' ) {
									$color = '00ff00; font-size: 10px;';
								} else if ( substr( $line, 0, 9 ) == 'Next rank' ) {
									$color = 'ffffff; font-size: 10px;';
								} else if ( substr( $line, 0, 8 ) == 'Requires' ) {
									$color = 'ff0000; font-size: 10px;';
								} else {
									$color = 'dfb801; font-size: 10px;';
								}
							}

							$talent_tooltip = $talent_tooltip . '<font style="color:#'.$color.'">'.$line.'</font><br>';
						}

						$talent_tooltip = str_replace("'", "\'", $talent_tooltip);
						$talent_tooltip = str_replace('"', '&quot;', $talent_tooltip);
						echo '<div class="equip">';
						echo '<span onMouseover="return overlib(\''.$talent_tooltip.'\');" onMouseout="return nd();">';
						if ($talent4['rank'] == 0)
						{
							$class = 'talenticonGreyed';
						}
						else
						{
							$class = 'talenticon';
						}
						echo '<div class="item"><img src="' . $path4 . '" class="'.$class.'" width="40" height="40"/><span class="talvalue">'.$talent4['rank'].'/'.$talent4['maxrank'].'</span>';
						echo '</div></span></div>';
					}
					echo '</td></tr>';
				}
				echo '</table></td>';
			}
			echo '</tr></table></div></div>';
		}
		echo $outtalent;
	}

	function printSkills() {
		list( $major, $minor ) = explode( '.', $this->data['version'] );
		extract($GLOBALS);
		for( $i=1 ; $i<7 ;  $i++ ) {
			if( ($major == 0) && ($minor < 96) ) {
				$skills = skill_get_many_by_type( $this->data['member_id'], $skilltypes[$this->data['clientLocale']][$i] );
			} else {
				$skills = skill_get_many_by_order( $this->data['member_id'], $i );
			}
			if( isset( $skills[0] ) ) {
				$skills[0]->outHeader();
				foreach ($skills as $skill) {
					$skill->out();
				}
			}
		}
	}

	function printReputation() {
		extract($GLOBALS);
		$reputation = get_reputation( $this->data['member_id']);
		$temp = '';
		for( $i=0 ; $i<sizeof($reputation) ;  $i++ ) {
			$temp = $reputation[$i]->outHeader($temp);
			$reputation[$i]->out();
		}
	}

	function printHonor() {
		extract($GLOBALS);
		if($this->data['RankInfo']) {
			$RankInfo = $this->data['RankInfo'];
			$RankIcon = $this->data['RankIcon'];
			if (!$RankIcon) {
				$RankIcon = 'pixel';
				$img_suffix = 'gif';
			}
			$Badge = '<img src="'.$img_url.preg_replace("|\\\\|",'/',$RankIcon).'.'.$img_suffix.'">';
		} else {
			$RankInfo = '&nbsp;';
			$RankIcon = 'pixel.gif';
			$Badge = '<img src="'.$img_url.$RankIcon.'" width="16" hieght="16">';
		}

		print '<div class="honortitle">'.$Badge.' '.$this->data['RankName'].' '.$RankInfo.'</div>'."\n";
		print '<div class="today">'.$wordings[$roster_lang]['today'].'</div>'."\n";
		print '<div class="honortext0_">'.$wordings[$roster_lang]['honorkills'].'</div>'."\n";
		print '<div class="honortext0">'.$this->data['sessionHK'].'</div>'."\n";
		print '<div class="honortext1_">'.$wordings[$roster_lang]['dishonorkills'].'</div>'."\n";
		print '<div class="honortext1">'.$this->data['sessionDK'].'</div>'."\n";
		print '<div class="yesterday">'.$wordings[$roster_lang]['yesterday'].'</div>'."\n";
		print '<div class="honortext2_">'.$wordings[$roster_lang]['honorkills'].'</div>'."\n";
		print '<div class="honortext2">'.$this->data['yesterdayHK'].'</div>'."\n";
		print '<div class="honortext3_">'.$wordings[$roster_lang]['honor'].'</div>'."\n";
		print '<div class="honortext3">'.$this->data['yesterdayContribution'].'</div>'."\n";
		print '<div class="thisweek">'.$wordings[$roster_lang]['thisweek'].'</div>'."\n";

		//TODO
		//Showing Scores from actual week
		print '<div class="honortext4_">'.$wordings[$roster_lang]['honorkills'].'</div>'."\n";
		print '<div class="honortext4">'.$this->data['TWHK'].'</div>'."\n";
		print '<div class="honortext4"></div>'."\n";
		print '<div class="honortext5_">'.$wordings[$roster_lang]['honor'].'</div>'."\n";
		print '<div class="honortext5">'.$this->data['TWContribution'].'</div>'."\n";
		print '<div class="honortext5"></div>'."\n";

		print '<div class="lastweek">'.$wordings[$roster_lang]['lastweek'].'</div>'."\n";
		print '<div class="honortext6_">'.$wordings[$roster_lang]['honorkills'].'</div>'."\n";
		print '<div class="honortext6">'.$this->data['lastweekHK'].'</div>'."\n";
		print '<div class="honortext7_">'.$wordings[$roster_lang]['honor'].'</div>'."\n";
		print '<div class="honortext7">'.$this->data['lastweekContribution'].'</div>'."\n";
		print '<div class="honortext8_">'.$wordings[$roster_lang]['standing'].'</div>'."\n";
		print '<div class="honortext8">'.$this->data['lastweekRank'].'</div>'."\n";

		print '<div class="alltime">'.$wordings[$roster_lang]['alltime'].'</div>'."\n";
		print '<div class="honortext9_">'.$wordings[$roster_lang]['honorkills'].'</div>'."\n";
		print '<div class="honortext9">'.$this->data['lifetimeHK'].'</div>'."\n";
		print '<div class="honortext10_">'.$wordings[$roster_lang]['dishonorkills'].'</div>'."\n";
		print '<div class="honortext10">'.$this->data['lifetimeDK'].'</div>'."\n";
		print '<div class="honortext11_">'.$wordings[$roster_lang]['highestrank'].'</div>'."\n";
		print '<div class="honortext11">'.$this->data['lifetimeRankName'].'</div>'."\n";
	}
	function out() {
		if ($this->data['name'] != '') {
?>

<script>
<!--
var tabs = new Array()
var tab_count = 0

function addTab( name ) {
	tabs[tab_count] = name;
	tab_count++;
}
function doTab( div ) {
	for( i=0 ; i<tab_count ; i++ ) {
		obj = document.getElementById( tabs[i] );
		fontobj = document.getElementById( "tabfont"+tabs[i] );
		if( tabs[i] == div ) {
			obj.style.display="block";
			fontobj.style.color="#ffffff";
		} else {
			obj.style.display="none";
			fontobj.style.color="#aa9900";
		}
	}
}
addTab('page1')
<?php
extract($GLOBALS);
//if ($this->data['class'] == $wordings[$this->data['clientLocale']]['Hunter'] || $this->data['class'] == $wordings[$this->data['clientLocale']]['Warlock']) {
if ( $this->getNumPets($name, $server)) {
	print 'addTab(\'page2\')';
	echo "\n";
}
?>
addTab('page3')
addTab('page4')
addTab('page5')
addTab('page6')

--></script>
<div class="char" id="char">
  <div class="main">
    <div class="top" id="top">

<?php
if($this->data['RankName'] == 'None') { $RankName='';
} else { $RankName=$this->data['RankName']; }
?>

      <h1><?php print ($RankName.' '.$this->data['name']); ?></h1>
      <h2>Level <?php print ($this->data['level'].' | '.$this->data['race'].' '.$this->data['class']); ?></h2>

<?php
if( isset( $this->data['guild_name'] ) ) {
	echo '<h2>'.$this->data['guild_title'].' of '.$this->data['guild_name'].'</h2>';
}
?>
    </div>
    <div class="page1" id="page1">
      <div class="left">
        <div class="equip"><?php print $this->printEquip('Head'); ?></div>
        <div class="equip"><?php print $this->printEquip('Neck'); ?></div>
        <div class="equip"><?php print $this->printEquip('Shoulder'); ?></div>
        <div class="equip"><?php print $this->printEquip('Back'); ?></div>
        <div class="equip"><?php print $this->printEquip('Chest'); ?></div>
        <div class="equip"><?php print $this->printEquip('Shirt'); ?></div>
        <div class="equip"><?php print $this->printEquip('Tabard'); ?></div>
        <div class="equip"><?php print $this->printEquip('Wrist'); ?></div>
      </div> <!-- left -->
      <div class="middle">
        <div class="portrait">

<?php extract($GLOBALS); ?>
    <div class="resistance">
        <li class="fire"><span class="white"><?php print $this->printRes('res_fire'); ?></span></li>
        <li class="nature"><span class="white"><?php print $this->printRes('res_nature'); ?></span></li>
        <li class="arcane"><span class="white"><?php print $this->printRes('res_arcane'); ?></span></li>
        <li class="frost"><span class="white"><?php print $this->printRes('res_frost'); ?></span></li>
        <li class="shadow"><span class="white"><?php print $this->printRes('res_shadow'); ?></span></li>
    </div>
    <div class="health_mana">
        <div class="health"><span><?php print $wordings[$roster_lang]['health'].': <span class="white">'.$this->data['health']; ?></span></span></div>
        <div class="mana"><span><?php print $wordings[$roster_lang]['mana'].': <span class="white">'.$this->data['mana']; ?></span></span></div>
    </div>
    <div class="talentPoints">  
<?php print "\n".$wordings[$roster_lang]['gender'].': <span class="white">'.$this->data['sex']; ?></span><br>        
<?php print "\n".$wordings[$roster_lang]['unusedtalentpoints'].': <span class="white">'.$this->data['talent_points']; ?></span>
        <br><br><br><br><br><br>
    </div>
    <center>
        <div class="xp">
            <div class="xpbox">
                <img class="bg" alt="" src="img/barXpEmpty.gif">
                <img src="img/expbar-var2.gif" alt="" class="bit" width="<?php print $this->printXP(); ?>">
                <span class="name"></span><span class="level"><?php echo str_replace(':','/',$this->data['exp']); list($xp, $xplevel) = explode(':',$this->data['exp']);if ($xplevel != '0') echo ' ('.round($xp/$xplevel*100).'%)'; ?></span>
            </div>
        </div>
    </center>          
        </div>
        <div class="bottom">
          <div class="padding">
            <ul class="stats">
              <li><?php print $wordings[$roster_lang]['strength'].': ';  $this->printStat('stat_str'); ?></li>
              <li><?php print $wordings[$roster_lang]['agility'].': ';   $this->printStat('stat_agl'); ?></li>
              <li><?php print $wordings[$roster_lang]['stamina'].': ';   $this->printStat('stat_sta'); ?></li>
              <li><?php print $wordings[$roster_lang]['intellect'].': '; $this->printStat('stat_int'); ?></li>
              <li><?php print $wordings[$roster_lang]['spirit'].': ';    $this->printStat('stat_spr'); ?></li>
              <li><?php print $wordings[$roster_lang]['armor'].': ';     $this->printStat('armor'); ?></li>
            </ul>
            <ul class="stats">
              <li><?php print $wordings[$roster_lang]['melee_att'].' '; $this->printAtk('melee','rating'); ?>
                <ul>
                  <li><?php print $wordings[$roster_lang]['power'].': '; $this->printAtk('melee','power'); ?></li>
                  <li><?php print $wordings[$roster_lang]['damage'].': '; $this->printAtk('melee','range'); ?></li>
                </ul>
              </li>
              <li><?php print $wordings[$roster_lang]['range_att'].' '; $this->printAtk('ranged','rating'); ?>
                <ul>
                  <li><?php print $wordings[$roster_lang]['power'].': '; $this->printAtk('ranged','power'); ?></li>
                  <li><?php print $wordings[$roster_lang]['damage'].': '; $this->printAtk('ranged','range'); ?></li>
                </ul>
              </li>
            </ul>
          </div> <!-- padding -->
          <div class="hands">
            <div class="weapon0"><?php print $this->printEquip('MainHand'); ?></div>
            <div class="weapon1"><?php print $this->printEquip('SecondaryHand'); ?></div>
            <div class="weapon2"><?php print $this->printEquip('Ranged'); ?></div>
            <div class="ammo"><?php print $this->printEquip('Ammo'); ?></div>
          </div><!-- hands -->
        </div> <!-- bottom -->
      </div> <!-- middle -->
      <div class="right">
        <div class="equip"><?php print $this->printEquip('Hands'); ?></div>
        <div class="equip"><?php print $this->printEquip('Waist'); ?></div>
        <div class="equip"><?php print $this->printEquip('Legs'); ?></div>
        <div class="equip"><?php print $this->printEquip('Feet'); ?></div>
        <div class="equip"><?php print $this->printEquip('Finger0'); ?></div>
        <div class="equip"><?php print $this->printEquip('Finger1'); ?></div>
        <div class="equip"><?php print $this->printEquip('Trinket0'); ?></div>
        <div class="equip"><?php print $this->printEquip('Trinket1'); ?></div>
      </div> <!-- right -->
    </div><!-- page1 -->

<?php
extract($GLOBALS);

//if ($this->data['class'] == $wordings[$this->data['clientLocale']]['Hunter'] || $this->data['class'] == $wordings[$this->data['clientLocale']]['Warlock']) {
if ( $this->getNumPets($name, $server)) {
	if ($this->getNumPets($name,$server) > 0){
		
		$petTab = $this->printPet($name, $server);
		$tab ='
		<div class="page2" id="page2">
		<div class="left"></div>
		<div class="pet">
		'.$petTab.'
		</div>
		<div class="right"></div>
		</div><!-- page2 -->';
		print $tab;
		
	}
}
?>
    <div class="page3" id="page3">
      <div class="left"></div>
      <div class="reputation"><?php print $this->printReputation(); ?></div>
      <div class="right"></div>
    </div><!-- page3 -->

    <div class="page4" id="page4">
      <div class="left"></div>
      <div class="skills"><?php print $this->printSkills(); ?></div>
      <div class="right"></div>
    </div><!-- page4 -->

    <div class="page5" id="page5">
      <div class="left"></div>
    <div class="talents"><?php print $this->printTalents( $this->data['member_id']); ?></div>
      <div class="right"></div>
    </div><!-- page5 -->

      <div class="page6" id="page6">
      <div class="left"></div>
      <div class="honor"><?php print $this->printHonor(); ?></div>
      <div class="right"></div>
    </div><!-- page6 -->

  </div><!-- main -->
  <div class="bottomBorder">
  </div>
  <div class="tabs">
  	
<?php
print $this->printTab( $wordings[$roster_lang]['tab1'], 'page1', True );
echo "\n";
if ($this->data['class'] == $wordings[$this->data['clientLocale']]['Hunter'] || $this->data['class'] == $wordings[$this->data['clientLocale']]['Warlock']) {
	if ($this->getNumPets($this->data['name'],$this->data['server']) > 0){
		print $this->printTab( $wordings[$roster_lang]['tab2'], 'page2' );
		echo "\n";
	}
}
print $this->printTab( $wordings[$roster_lang]['tab3'], 'page3' );
echo "\n";
print $this->printTab( $wordings[$roster_lang]['tab4'], 'page4' );
echo "\n";
print $this->printTab( $wordings[$roster_lang]['tab5'], 'page5' );
echo "\n";
print $this->printTab( $wordings[$roster_lang]['tab6'], 'page6' );
echo "\n";

echo "</div>
</div> <!-- char -->

";
		} else {
			echo 'Sorry no data in database for '.$_REQUEST['name'].' of '.$_REQUEST['server'];
		}
	}
}

function char_get_one_by_id( $member_id ) {
	global $wowdb;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT a.*, b.guild_rank, b.guild_title, c.guild_name FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
	"where a.member_id = b.member_id and a.member_id = '$member_id' and a.guild_id = c.guild_id";
	$result = $wowdb->query( $query );
	$data = $wowdb->getrow( $result );
	return new char( $data );
}

function char_get_one( $name, $server ) {
	global $wowdb;

	$name = $wowdb->escape( $name );
	$server = $wowdb->escape( $server );
	$query = "SELECT a.*, b.guild_rank, b.guild_title, c.guild_name FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
	"where a.member_id = b.member_id and a.name = '$name' and a.server = '$server' and a.guild_id = c.guild_id";
	$result = $wowdb->query( $query );
	$data = $wowdb->getrow( $result );
	return new char( $data );
}

function DateDataUpdated($name) {
	global $wowdb;
	extract($GLOBALS);
	$query1 = "SELECT `dateupdatedutc` FROM `".ROSTER_PLAYERSTABLE."` WHERE `name` = '$name'";
	$result1 = $wowdb->query($query1);
	$data1 = $wowdb->getrow($result1);
	$dateupdatedutc = $data1["dateupdatedutc"];
	$day = substr($dateupdatedutc,3,2);
	$month = substr($dateupdatedutc,0,2);
	$year = substr($dateupdatedutc,6,2);
	$hour = substr($dateupdatedutc,9,2);
	$minute = substr($dateupdatedutc,12,2);
	$second = substr($dateupdatedutc,15,2);

	$localtime = mktime($hour+$localtimeoffset ,$minute, $second, $month, $day, $year, -1);
	return date($phptimeformat[$roster_lang], $localtime);
}
?>