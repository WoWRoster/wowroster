<?php
require_once 'conf.php';
require_once 'lib/item.php';
require_once 'lib/wowdb.php';

// Establish our connection and select our database
$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') from `".$db_table_prefix."guild` where guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

include 'lib/menu.php';
print "<br>\n";

$items = $inst_keys[$roster_lang];

$select = ''; $where = ''; $acount = 0;
foreach ($items as $key => $item) {
	++$acount;
	list($iname, $thottnum) = explode('|', $item);
	$select .= ", sum(if(items.item_name = '".$iname."', 1, 0)) as $key";
	if ($acount == 1) {
		$where .= " items.item_name = '".$iname."'";
	} else {
		$where .= " or items.item_name = '".$iname."'";
	}
}

$query = "SELECT members.name, members.class, items.member_id".$select." FROM `".$db_table_prefix."items` items LEFT JOIN `".$db_table_prefix."members` members ON members.member_id = items.member_id WHERE".$where." GROUP BY members.name ORDER BY members.name";
//print "<br>$query<br>\n";

$striping_counter = 0;
$tableHeader = '<table cellpadding="0" cellspacing="0" class="membersList">'."\n";

function borderTop($col) {
	if ($col < 2) { $col = 2; }
	print '<tr><th colspan="'.$col.'" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></th></tr>'."\n";
}

function tableHeaderRow($th) {
	global $items;
	extract($GLOBALS);
	$acount = 0;
	foreach ($th as $header) {
		++$acount;
		if($items[$header]) {
			list($iname, $thottnum) = explode('|', $items[$header]);
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
	if ($col < 2) { $col = 2; }
	print '<tr><th colspan="'.$col.'" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></th></tr>'."\n";
}

$tableFooter = '</table>'."\n";

function rankRight($sc) {
	print '<td class="rankbordercenterleft"><div class="membersKeyRowLeft'.$sc.'">';
}
function rankMid($sc) {
	print '<td class="membersKeyRow'.$sc.'">';
}
function rankLeft($sc) {
	print '<td class="rankbordercenterright"><div class="membersKeyRowRight'.$sc.'">';
}

$keys = array('Name');
foreach ($items as $key => $item) {
  if (!($faction=='H' && $key == 'SG')) {
	array_push($keys, "$key");
  }
}

print($tableHeader);
borderTop(count($keys));
tableHeaderRow($keys);

$result = mysql_query($query) or die(mysql_error());
if ($sqldebug) { print ("<!--$query-->"); }

while ($row = mysql_fetch_array($result)) {
	++$striping_counter;
	print '<tr>'."\n";
	$acount = 0;
	rankRight((($striping_counter % 2) +1));
	print '<a href="char.php?name='.$row['name'].'&server='.$server_name.'">'.$row['name'].'</a></div></td>'."\n";

	foreach ($items as $key => $item) {
		list($iname, $thottnum) = explode('|', $item);
		++$acount;
		if ( $faction=='H' && $key=='SG' ) {
		  continue;
		}
		if($acount == count($items)) {
			rankLeft((($striping_counter % 2) +1));
		} else {
			rankMid((($striping_counter % 2) +1));
		}
		if ($row[$key] == 1) {
			$subquery = "SELECT * FROM `".ROSTER_ITEMSTABLE."` WHERE item_name = '".$iname."' AND member_id = '".$row['member_id']."'";
			$subresult = $wowdb->query($subquery);
			$data = $wowdb->getrow($subresult);
			print '<div class="bagSlot">';
			$itemb = new item($data);
			$itemb->out();
			print '</div>';
		} else if ($row['class'] == $wordings[$roster_lang]['Rogue'] ) {
			// Show the thieves' tools if rogue can pick the lock
			$iname = $wordings[$roster_lang]['thievestools'];
			$subquery2 = "SELECT * FROM `".ROSTER_ITEMSTABLE."` WHERE item_name = '".$iname."' AND member_id = '".$row['member_id']."'";
			$subresult2 = $wowdb->query($subquery2);
			$data = $wowdb->getrow($subresult2);

			$skill_query = "SELECT skill_level FROM `".ROSTER_SKILLSTABLE."` WHERE member_id = ".$row['member_id']." and skill_name = '".$wordings[$roster_lang]['lockpicking']."'";
			$skill_result = mysql_query($skill_query) or die(mysql_error());
			$skill_result_array = mysql_fetch_array($skill_result);
			list($current,$max) = explode(":",$skill_result_array['skill_level']);

			switch($key):
				case 'SG':
					if ($current>= 225) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('SG');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				case 'Gnome':
					if ($current>= 150) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('Gnome');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				case 'SM':
					if ($current>= 175) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('SM');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				case 'BRD1':
					if ($current>= 250) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('BRD1');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				case 'Sholo':
					if ($current>= 280) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('Sholo');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				case 'Strat':
					if ($current>= 295) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('Strat');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				case 'DM':
					if ($current>= 295) {
						print '<div class="bagSlot">';
						$itemb = new item($data);
						$itemb->out_lockpicking('DM');
						print '</div>';
					} else { print '&nbsp;'; }
				break;
				default:
					print '&nbsp;';
			endswitch;
			mysql_free_result($skill_result);
		} else {
			print '&nbsp;';
		}
		print '</td>'."\n";
	}
	print('</div></td></tr>'."\n");
}

borderBottom(count($keys));
print($tableFooter);
mysql_free_result($result);

print "<p id=\"last_update\" class=\"last_update\">".$wordings[$roster_lang]['update']." ".$updateTime."</p>";
?>