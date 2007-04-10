<?php
require_once 'conf.php';
require_once 'lib/item.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ('Could not connect to desired database.');
mysql_select_db($db_name) or die ('Could not select desired database');

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
mysql_free_result($result);

include 'lib/menu.php';
print "<br>\n";

// Tooltip colors
$colorcmp = '00ff00; font-size: 10px;'; // Complete color
$colorcur = 'ffd700; font-size: 10px;'; // Current color
$colorno = 'ff0000; font-size: 10px;';  // Uncomplete color

$striping_counter = 0;
$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">'."\n";
$tableFooter = '</table>'."\n";

function borderTop($col) {
	if ($col < 2) $col = 2;
		print '<tr><th colspan="'.$col.'" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>'."\n";
}

function tableHeaderRow($th) {
	global $items;
	extract($GLOBALS);
	$acount = 0;
	foreach ($th as $header) {
		++$acount;
		if($items[$header]) {
			list($iname, $thottnum) = explode('|', $items[$header][$header]);
			$header = '<a href="'.$itemlink.urlencode($iname).'" target="_thottbot">'.$header.'</a>';
		}
		if ($acount == 1) {
			print '<th class="rankbordercenterleft"><div class="membersHeader">'.$header.'</div></th>'."\n";
		} elseif ($acount == count($th)) {
			print '<th class="rankbordercenterright"><div class="membersHeaderRight"><center>'.$header.'</center></div></th>'."\n";
		} else {
			print '<th class="membersHeader"><center>'.$header."</center></th>\n";
		}
	}
}

function borderBottom($col) {
	if ($col < 2) $col = 2;
		print '<tr><th colspan="'.$col.'" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>'."\n";
}

function rankRight($sc) {
	print '<td class="rankbordercenterleft"><div class="membersKeyRowLeft'.$sc.'">';
}

function rankMid($sc) {
	print '<td class="membersKeyRow'.$sc.'">';
}

function rankLeft($sc) {
	print '<td class="rankbordercenterright"><div class="membersKeyRowRight'.$sc.'">';
}

function buildSQL($item,$key,$type) {
	global $selectp; global $wherep; global $pcount;
	global $selectq; global $whereq; global $qcount;
	list($iname, $thottnum) = explode('|', $item);

	if ($type == 'quest') {
		++$pcount;
		$selectq .= ", sum(if(quests.quest_name = '".$iname."', 1, 0)) AS $key";
		if ($pcount == 1) {
			$whereq .= " quests.quest_name = '".$iname."'";
		} else {
			$whereq .= " OR quests.quest_name = '".$iname."'";
		}
	} else {
		++$qcount;
		$selectp .= ", sum(if(items.item_name = '".$iname."', 1, 0)) AS $key";
		if ($qcount == 1) {
			$wherep .= " items.item_name = '".$iname."'";
		} else {
			$wherep .= " OR items.item_name = '".$iname."'";
		}
	}
}

//Minimum lockpicking skill to get it, 301 indicates that the lock can't be picked
$min_skill_for_lock = array(
'SG' => 225,
'Gnome' => 150,
'SM' => 175,
'ZF' => 301,
'Mauro' => 301,
'BRDp' => 250,
'BRDs' => 301,
'DM' => 295,
'Scholo' => 280,
'Strath' => 295,
'UBRS' => 301,
'Onyxia' => 301
);

$items = $inst_keys[$roster_lang][$faction];
$keys = array('Name');
foreach ($min_skill_for_lock as $key => $data)
	array_push($keys,$key);

print($tableHeader);
borderTop(count($keys));
tableHeaderRow($keys);

$query = "SELECT name, level, member_id, class, clientLocale FROM `".ROSTER_PLAYERSTABLE."` GROUP BY name ORDER BY name ASC";
$result = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_array($result)) {
	if ($row['clientLocale'] == '')
		$row['clientLocale'] = $roster_lang;
	$items = $inst_keys[$row['clientLocale']][$faction];
	// build SQL search string for the instance keys only
	$selectk = ''; $wherek = ''; $countk = 0;
	foreach ($items as $key => $item) {
		foreach ($items[$key] as $subkey => $subitem) {
			$onechar = substr($subkey, 0, 1);
			if (!is_numeric($onechar)) {
				++$countk;
				list($iname, $thottnum) = explode('|', $subitem);
				$selectk .= ", sum(if(items.item_name = '".$iname."', -1, 0)) as $key";
				if ($countk == 1) {
					$wherek .= " items.item_name = '".$iname."'";
				} else {
					$wherek .= " or items.item_name = '".$iname."'";
				}
			}
		}
	}
	// instance key search
	$kquery = "SELECT members.name".$selectk." FROM `".ROSTER_ITEMSTABLE."` items LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = items.member_id WHERE items.member_id = '".$row['member_id']."' AND (".$wherek.") GROUP BY members.name";
	$kresult = mysql_query($kquery) or die(mysql_error());
	$krow = mysql_fetch_array($kresult);
	$kcount = 0; // counts how many keys this player has. if 0 at the end don't display
	$selectp = ''; $wherep = ''; $pcount = 0;
	$selectq = ''; $whereq = ''; $qcount = 0;
	// ==============================
	// VALUE:MEANING for $krow[$key]:
	// ==============================
	// -1: player has the key
	// -2: player (rogue) can pick the lock but doesn't have the key
	//  0: no access
	// 1+: current quest step
	// 0|1|2|...: completed steps
	// ==============================
	foreach ($items as $key => $item) {
		if ($krow[$key] == '-1') {
			++$kcount;
		} else {
			if ($row['class'] == $wordings[$row['clientLocale']]['Rogue']) {
				$squery = "SELECT skill_level FROM `".ROSTER_SKILLSTABLE."` WHERE member_id = ".$row['member_id']." and skill_name = '".$wordings[$row['clientLocale']]['lockpicking']."'";
				$sresult = mysql_query($squery) or die(mysql_error());
				$srow = mysql_fetch_array($sresult);
				list($current_skill,$max_skill) = explode(':',$srow['skill_level']);
				mysql_free_result($sresult);
				if ($current_skill >= $min_skill_for_lock[$key]) {
					$krow[$key] = '-2';
					++$kcount;
					continue;
				}
			}
			if ($items[$key][0] == 'Quests') {
				$type = 'quest';
			} else if ($items[$key][0] == 'Parts') {
				$type = 'item';
			} else {
				continue;
			}
			for ($acount=1;$acount<count($items[$key])-1;$acount++) {
				buildSQL($items[$key][$acount], "${key}$acount", $type);
			}
		}
	}

	if ($selectp != '') {
		// parts search (only the remaining ones!)
		$queryp = "SELECT members.name".$selectp." FROM `".ROSTER_ITEMSTABLE."` items LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = items.member_id WHERE items.member_id = ".$row['member_id']." AND (".$wherep.") GROUP BY members.name ORDER BY members.name ASC";
		$presult = mysql_query($queryp) or die(mysql_error());
		$prow = mysql_fetch_array($presult);
		if (is_array($prow)) {
			foreach ($prow as $pkey => $pvalue) {
				if ($pvalue == 1 && !is_numeric($pkey)) {
					++$kcount;
					$key = preg_replace('/[0-9]/', '', $pkey);
					$step = preg_replace('/[A-Za-z]/', '', $pkey);
					list($junk,$milestone) = explode('||',$items[$key][$step]);
					if ($milestone == 'MS') {
						$krow[$key] = '0';
						for ($i=1;$i<=$step;$i++) {
							$krow[$key] .= "|".$i;
						}
					} else {
						$krow[$key] .= "|".$step;
					}
				}
			}
		}
		mysql_free_result($presult);
	}
	if ($selectq != '') {
		// quests search (only the remaining ones!)
		$queryq = "SELECT members.name".$selectq." FROM `".ROSTER_QUESTSTABLE."` quests LEFT JOIN `".ROSTER_MEMBERSTABLE."` members ON members.member_id = quests.member_id WHERE quests.member_id = ".$row['member_id']." AND (".$whereq.") GROUP BY members.name ORDER BY members.name ASC";
		$qresult = mysql_query($queryq) or die(mysql_error());
		$qrow = mysql_fetch_array($qresult);
		if (is_array($qrow)) {
			foreach ($qrow as $qkey => $qvalue) {
				if ($qvalue == 1 && !is_numeric($qkey)) {
					++$kcount;
					$key = preg_replace('/[0-9]/', '', $qkey);
					$step = preg_replace('/[A-Za-z]/', '', $qkey);
					$krow[$key] = $step;
				}
			}
		}
		mysql_free_result($qresult);
	}
	if ($kcount == 0)
		continue; // nothing to display -> next player

	// ========================================================================
	// ----------------------------> DISPLAY CODE <----------------------------
	// ========================================================================
	++$striping_counter;
	print '<tr>'."\n";
	$acount = 0;
	rankRight((($striping_counter % 2) +1));
	print '<a href="char.php?name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a><br>'.$row['class'].' ('.$row['level'].')</div></td>'."\n";
	foreach ($items as $key => $data) {
		++$acount;
		if($acount == count($items)) {
			rankLeft((($striping_counter % 2) +1));
		} else {
			rankMid((($striping_counter % 2) +1));
		}
		if ($krow[$key] == '-2') {
			$iname = $wordings[$row['clientLocale']]['thievestools'];
			$iquery = "SELECT * FROM `".ROSTER_ITEMSTABLE."` WHERE item_name = '".$iname."' AND member_id = '".$row['member_id']."'";
			$iresult = $wowdb->query($iquery);
			$idata = $wowdb->getrow($iresult);
			print '<div class="bagSlot">';
			$item = new item($idata);
			$item->out($key);
			print '</div>';
		} else if ($krow[$key] == '-1') {
			list($iname, $thottnum) = explode('|', $data[$key]);
			$iquery = "SELECT * FROM `".ROSTER_ITEMSTABLE."` items WHERE item_name = '".$iname."' AND member_id = '".$row['member_id']."'";
			$iresult = $wowdb->query($iquery);
			$idata = $wowdb->getrow($iresult);
			print '<div class="bagSlot">';
			$item = new item($idata);
			$item->out();
			print '</div>';
		} else if ($krow[$key] == '0') {
			print '&nbsp;';
		} else if ($krow[$key] == '') {
			print '&nbsp;';
		} else {
			list($iname, $thottnum) = explode('|', $items[$key][$key]);
			$qcount = count($items[$key])-2;    //number of parts/quests
			if ($items[$key][0] == 'Quests')    //-> $krow[$key] = "5" (e.g.)
				$bcount = $krow[$key];
			else {                             //-> $krow[$key] = "0|1|2|3" (e.g.)
				$parray = explode('|',$krow[$key]); //array for completed parts
				$bcount = count($parray)-1;
			}
			$pcent = round(($bcount / $qcount)* 35, 1);
			print '<div class="bagSlot">';
			print '<div class="keys">';

			$tooltip = '<span class="tooltipheader" style="color:#ffffff; font-weight:bold;">'.$key.' '.$wordings[$roster_lang]['key'].' Status<br></span>';
			$tooltip = $tooltip . '<span class="tooltipline" style="color:#'.$colorcmp.'">'.$wordings[$roster_lang]['completedsteps'].'<br></span>';
			if ($items[$key][0] == 'Quests')
				$tooltip = $tooltip . '<span class="tooltipline" style="color:#'.$colorcur.'">'.$wordings[$roster_lang]['currentstep'].'<br></span>';
			$tooltip = $tooltip . '<span class="tooltipline" style="color:#'.$colorno.'">'.$wordings[$roster_lang]['uncompletedsteps'].'<br><br></span>';
			if ($items[$key][0] == 'Quests') {
				for ($i=1;$i<count($items[$key])-1;$i++) {
					if ($krow[$key]>$i)
						$color = $colorcmp;
					else if ($krow[$key]==$i)
						$color = $colorcur;
					else
						$color = $colorno;
					list($qname,$junk) = explode('|',$items[$key][$i]);
					$qname = preg_replace('/\\\/', '', $qname);
					$tooltip = $tooltip . '<span class="tooltipline" style="color:#'.$color.'">'.$i.': '.$qname.'<br></span>';
				}
			} else {
				$j=1;
				for ($i=1;$i<count($items[$key])-1;$i++) {
					if ($j < count($parray) && $parray[$j] == $i) {
						$color = $colorcmp;
						$j++;
					} else {
						$color = $colorno;
					}
					list($pname,$junk) = explode('|',$items[$key][$i]);
					$pname = preg_replace('/\\\/', '', $pname);
					$tooltip = $tooltip . '<span class="tooltipline" style="color:#'.$color.'">'.$i.': '.$pname.'<br></span>';
				}
			}
			$tooltip = str_replace("'", "\'", $tooltip);
			$tooltip = str_replace('"','&quot;', $tooltip);
			echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$tooltip.'\');" onMouseout="return nd();">';
			print '<a href="'.$itemlink.urlencode($iname).'" target="_thottbot">';
			print '<span class="name"><center>'.$items[$key][0].'</center></span>';
			print '</a>';
			print '<div class="xpbox">';
			print '<img class="bg" alt="" src="img/barXpEmpty.gif"><img src="img/expbar-var2.gif" alt="" class="bit" width="'.$pcent.'">';
			print '<span class="level">'.$bcount.'/'.$qcount.'</span>';  
			print '</div></span>';
			print '</div></div>';
			print '</div>';
		}
		print "</td>\n";
	}
	print('</tr>');
	mysql_free_result($kresult);
}
mysql_free_result($result);

borderBottom(count($keys));
print($tableFooter);
?>