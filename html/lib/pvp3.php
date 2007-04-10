<?php
$versions['versionDate']['pvp3'] = '$Date: 2006/02/03 14:09:12 $'; 
$versions['versionRev']['pvp3'] = '$Revision: 1.2 $'; 
$versions['versionAuthor']['pvp3'] = '$Author: anthonyb $';

require_once 'wowdb.php';
require_once 'conf.php';

class pvp3 {
  var $data;

  function pvp3( $data ) {
    $this->data = $data;
  }

  function get( $field ) {
    return $this->data[$field];
  }

  function outHeader() {
    return '<div class="pvptype">'.$this->data['guild'].' </div>';
  }

  function out2() { 
    $returnstring = '<b><font face="Georgia" size="+1" color="#0000FF"></font></b>';
    $returnstring .= '['.$this->data['pvp_level'].'] '.$this->data['pvp_name'];
    return $returnstring;
  }

  function out() {
    $max = 60;
    $level = $this->data['pvp_level'];
    if( $max == 1 ) {
      $bgImage = 'img/barGrey.gif';
    } else {
      $bgImage = 'img/barEmpty.gif';
    }

    $returnstring = '
<div class="pvp">
  <div class="pvpbox">
    <img class="bg" alt="" src="'.$bgImage.'" />';
    if( $max > 1 ) {
      $width = intval(($level/$max) * 354);
      $returnstring .= '<img src="img/barBit.gif" alt="" class="bit" width="'.$width.'" />';
    }
    $returnstring .= '
    <span class="name">'.$this->data['pvp_name'].'</span>';
    if( $max > 1 ) {
      $returnstring .= '<span class="level"> ['.$level.']</span>';
    }
    $returnstring .= '
  </div>
</div>
';

    return $returnstring;
  }
}
function pvp_get_many3($member_id, $type, $sort, $start) {
  global $wowdb;  
  $workaround = $start; // otherwise $start is overwritten witch extract below
  extract($GLOBALS);

  $query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_lang]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND ";
  if ($type == 'PvP') {
    $query=$query."`enemy` = '1' AND `bg` = '0'";
  } else if ($type == 'BG') {
    $query=$query."`enemy` = '1' AND `bg` >= '1'";
  } else {
    $query=$query."`enemy` = '0'";
  }

  if ($sort == 'name') {
    $query=$query." ORDER BY 'name', 'level' DESC, 'guild'";
  } else if ($sort == 'race') {
    $query=$query." ORDER BY 'race', 'guild', 'name', 'level' DESC";
  } else if ($sort == 'class') {
    $query=$query." ORDER BY 'class', 'guild', 'name', 'level' DESC";
  } else if ($sort == 'leveldiff') {
    $query=$query." ORDER BY 'leveldiff' DESC, 'guild', 'name' ";
  } else if ($sort == 'result') {
    $query=$query." ORDER BY 'win' DESC, 'guild', 'name' ";
  } else if ($sort == 'zone') {
    $query=$query." ORDER BY 'zone', 'guild', 'name' ";
  } else if ($sort == 'subzone') {
    $query=$query." ORDER BY 'subzone', 'guild', 'name' ";
  } else if ($sort == 'date') {
    $query=$query." ORDER BY 'date', 'guild', 'name' ";
  } else if ($sort == 'bg') {
    $query=$query." ORDER BY 'bg', 'guild', 'name' ";
  } else if ($sort == 'honor') {
    $query=$query." ORDER BY 'honor', 'guild', 'name' ";
  } else if ($sort == 'rank') {
    $query=$query." ORDER BY 'rank', 'guild', 'name' ";	  	  	  
  } else if ($sort == 'guild') {
    $query=$query." ORDER BY 'guild', 'name', 'level' DESC ";
  } else {
    $query=$query." ORDER BY 'date' DESC, 'guild', 'name' ";
  }
  if ($workaround != -1) $query = $query.' LIMIT '.$start.', 50';
  $result = mysql_query($query) or die(mysql_error());
  $pvps = array();
  while( $data = $wowdb->getrow( $result ) ) {
    $pvp = new pvp3( $data );
    $pvps[] = $pvp;
  }
  return $pvps;
}

function output_pvp_summary($pvps) {
  extract($GLOBALS);
  $tot_wins = 0;
  $tot_losses = 0;
  $ave_win_level_diff = 0;
  $ave_loss_level_diff = 0;
  foreach ($pvps as $row) {
    if ($row->data['win'] == '1') {
      $tot_wins = $tot_wins + 1;
      $ave_win_level_diff = $ave_win_level_diff + $row->data['leveldiff'];
    } else {
      $tot_losses = $tot_losses + 1;
      $ave_loss_level_diff = $ave_loss_level_diff + $row->data['leveldiff'];
    }
  }
  if ($tot_wins > 0) {
    $ave_win_level_diff = $ave_win_level_diff / $tot_wins;
    $ave_win_level_diff = round($ave_win_level_diff, 2);
    if ($ave_win_level_diff > 0) {
      $ave_win_level_diff = '+'.$ave_win_level_diff;
    }
  } else {
    $ave_win_level_diff = 0;
  }
  if ($tot_losses > 0) {
    $ave_loss_level_diff = $ave_loss_level_diff / $tot_losses;
    $ave_loss_level_diff = round($ave_loss_level_diff, 2);
    if ($ave_loss_level_diff > 0) {
      $ave_loss_level_diff = '+'.$ave_loss_level_diff;
    }
  } else {
    $ave_loss_level_diff = 0;
  }
  
  $total = $tot_wins - $tot_losses;
  if ($total > 0) {
    $total = '+'.$total;
  }

  $returnstring = '
  <div align="center">
  <table class="bodyline" width="280" cellspacing="1">
  <tr class="membersRow2">
    <td width="200">'.$wordings[$roster_lang]['totalwins'].'</td>
    <td width="80">'.$tot_wins.'</td>
	</tr><tr class="membersRow1">
    <td width="200">'.$wordings[$roster_lang]['totallosses'].'</td>
    <td width="80">'.$tot_losses.'</td>
	</tr><tr class="membersRow2">
    <td width="200">'.$wordings[$roster_lang]['totaloverall'].'</td>
    <td width="80">'.$total.'</td>
	</tr><tr class="membersRow1">
    <td width="200">'.$wordings[$roster_lang]['win_average'].'</td>
    <td width="80">'.$ave_win_level_diff.'</td>
	</tr><tr class="membersRow2">
    <td width="200">'.$wordings[$roster_lang]['loss_average'].'</td>
    <td width="80">'.$ave_loss_level_diff.'</td>
  </tr>
  </table>
  </div>
  <br />
  <br />';

  return $returnstring;
}

function output_bglog($member_id){
  global $wowdb;
  extract($GLOBALS);

  $query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_lang]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` >= '1'";

  $result = mysql_query($query) or die(mysql_error());
  $pvps = array();
  while( $data = $wowdb->getrow( $result ) ) {
    $pvp = new pvp3( $data );
    $pvps[] = $pvp;
  }

  $abWins = 0;
  $abLoss = 0;
  $avWins = 0;
  $avLoss = 0;
  $wsWins = 0;
  $wsLoss = 0;

  $returnstring = "
<table border='0' cellspacing='0' cellpadding='0' align='center'>
<tr>
	<td>
	<table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
		<tr>
			<td height='7'></td>
			<td height='7'</td>
			<td height='7'></td>
		</tr>
		<tr>
			<td width='7'</td>
			<td>
				<table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
					<tr>
						<td width='200' class='mainstatsbody' align='center'>Arathi Basin:</td>
					</tr>
				</table>
			</td>
			<td width='7'</td>
		</tr>
		<tr>
			<td height='7'></td>
			<td height='7'</td>
			<td height='7'></td>
		</tr>
	</table>
  
	<table width='225' cellpadding='0' cellspacing='0' class='membersList' align='center'>
		<tr><th colspan='2' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
		<tr>
			<td class='rankbordercenterleft'><div class='membersRow2'>Total HK's</td><td class='rankbordercenterright'><div class='membersRow2'>".$abWins."</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow1'>Total Death's</td><td class='rankbordercenterright'><div class='membersRow1'>".$abLoss."</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow2'>Number of Wins</td><td class='rankbordercenterright'><div class='membersRow2'>0</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow1'>Best Zone</td><td class='rankbordercenterright'><div class='membersRow1'>NA</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow2'>Worst Zone</td><td class='rankbordercenterright'><div class='membersRow2'>NA</td></tr>
		<tr><th colspan='2' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
	</table>
	</td>
	<td width='250' align='center'>
		<table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
    		<tr>
				<td height='7'></td>
				<td height='7'</td>
				<td height='7'></td>
			</tr>
			<tr>
				<td width='7'</td>
				<td>
					<table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
  						<tr>
							<td width='200' class='mainstatsbody' align='center'>Alterac Valley:</td></tr>
					</table>
				</td>
				<td width='7'</td>
			</tr>
			<tr>
				<td height='7'></td>
				<td height='7'</td>
				<td height='7'></td>
			</tr>
		</table>

	<table width='225' cellpadding='0' cellspacing='0' class='membersList' align='center'>
	<tr><th colspan='2' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
	<tr>
		<td class='rankbordercenterleft'><div class='membersRow2'>Total HK's</td><td class='rankbordercenterright'><div class='membersRow2'>".$avWins."</td></tr>
		<td class='rankbordercenterleft'><div class='membersRow1'>Total Death's</td><td class='rankbordercenterright'><div class='membersRow1'>".$avLoss."</td></tr>
		<td class='rankbordercenterleft'><div class='membersRow2'>Number of Wins</td><td class='rankbordercenterright'><div class='membersRow2'>0</td></tr>
		<td class='rankbordercenterleft'><div class='membersRow1'>Best Zone</td><td class='rankbordercenterright'><div class='membersRow1'>NA</td></tr>
		<td class='rankbordercenterleft'><div class='membersRow2'>Worst Zone</td><td class='rankbordercenterright'><div class='membersRow2'>NA</td></tr>
		<tr><th colspan='2' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
	</table>
	</td>
	</tr>
	<tr align='center'><td width='250' align='center'>
	<table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
		<tr>
			<td height='7'></td>
			<td height='7'</td>
			<td height='7'></td>
		</tr>
		<tr>
			<td width='7'</td>
			<td>
				<table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
					<tr>
						<td width='200' class='mainstatsbody' align='center'>Warsong Gulch:</td>
					</tr>
				</table>
			</td>
			<td width='7'</td>
		</tr>
		<tr>
			<td height='7'></td>
			<td height='7'</td>
			<td height='7'></td>
		</tr>
	</table>
	<table width='225' cellpadding='0' cellspacing='0' class='membersList' align='center'>
		<tr><th colspan='2' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
		<tr>
			<td class='rankbordercenterleft'><div class='membersRow2'>Total HK's</td><td class='rankbordercenterright'><div class='membersRow2'>".$wsWins."</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow1'>Total Death's</td><td class='rankbordercenterright'><div class='membersRow1'>".$wsLoss."</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow2'>Number of Wins</td><td class='rankbordercenterright'><div class='membersRow2'>0</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow1'>Best Zone</td><td class='rankbordercenterright'><div class='membersRow1'>NA</td></tr>
			<td class='rankbordercenterleft'><div class='membersRow2'>Worst Zone</td><td class='rankbordercenterright'><div class='membersRow2'>NA</td></tr>
			<tr><th colspan='2' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
	</table>
	</td>
	</td>
	</tr>
	</table>";

  return $returnstring;
}

function output_duellog($member_id){
  global $wowdb;
  extract($GLOBALS);

  $query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_lang]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '0'";

  $result = mysql_query($query) or die(mysql_error());
  $pvps = array();
  while( $data = $wowdb->getrow( $result ) ) {
    $pvp = new pvp3( $data );
    $pvps[] = $pvp;
  }

  $returnstring = "";

  return $returnstring;
}

function output_pvplog($member_id){
  global $wowdb;
  extract($GLOBALS);

  $query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_lang]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0'";

  $result = mysql_query($query) or die(mysql_error());
  $pvps = array();
  while( $data = $wowdb->getrow( $result ) ) {
    $pvp = new pvp3( $data );
    $pvps[] = $pvp;
  }

  $worldPvPWin = 0;
  $worldPvPLoss = 0;
  $worldPvPPerc = 0;

  foreach ($pvps as $row) {
    if ($row->data['win'] == '1') {
      $worldPvPWin = $worldPvPWin + 1;
    }
    else {
      $worldPvPLoss = $worldPvPLoss + 1;
    }
  }
  if ($worldPvPWin > 0 and $worldPvPLoss > 0) {
    $worldPvPPerc = (100*$worldPvPWin)/($worldPvPWin + $worldPvPLoss);
  }
    


  $returnstring = "
<table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr>
      <td height='7'></td>
      <td height='7'</td>
      <td height='7'></td>
    </tr><tr>
      <td width='7'</td>
      <td><table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
  <tr><td width='200' class='mainstatsbody' align='center'>World PvP:</td></tr>
  </table></td>
      <td width='7'</td>
    </tr><tr>
      <td height='7'></td>
      <td height='7'</td>
      <td height='7'></td>
    </tr>
  </table>
<table width='225' cellpadding='0' cellspacing='0' class='membersList'>
<tr><th colspan='2' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>";

  $returnstring .= "
<tr><td class='rankbordercenterleft'><div class='membersRow2'>Total HK's</td><td class='rankbordercenterright'><div class='membersRow2'>".$worldPvPWin."</td></tr><td class='rankbordercenterleft'><div class='membersRow1'>Total Death's</td><td class='rankbordercenterright'><div class='membersRow1'>".$worldPvPLoss."</td></tr><td class='rankbordercenterleft'><div class='membersRow2'>Win %</td><td class='rankbordercenterright'><div class='membersRow2'>".$worldPvPPerc."</td></tr><td class='rankbordercenterleft'><div class='membersRow1'>Best Zone</td><td class='rankbordercenterright'><div class='membersRow1'>";
  $query = "SELECT zone, COUNT(zone) as countz FROM ".ROSTER_PVP2TABLE." WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '1' GROUP BY zone ORDER BY countz DESC LIMIT 0,1";
  $result = mysql_query($query) or die(mysql_error());
  $rzone = mysql_fetch_array($result);
  if ($rzone) {
    $returnstring .= $rzone['zone'];
  }
  else {
    $returnstring .= "Not Avail";
  }
  $returnstring .= "</td></tr><td class='rankbordercenterleft'><div class='membersRow2'>Worst Zone</td><td class='rankbordercenterright'><div class='membersRow2'>";

  $query = "SELECT zone, COUNT(zone) AS countz FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '1' AND `bg` = '0' AND `win` = '0' GROUP BY zone ORDER BY countz DESC LIMIT 0,1";
  $result = mysql_query($query) or die(mysql_error());
  $rzone = mysql_fetch_array($result);
  if ($rzone) {
    $returnstring .= $rzone['zone'];
  }
  else {
    $returnstring .= "Not Avail";
  }

  $returnstring .= "</td></tr><tr><th colspan='2' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>

</table>
</td></tr>

</table>

<table width='500' align='center'><tr><td colspan='2' width='500' align='center'><br><br><center><img src='img/VersusGuilds.gif' alt='Versus Guilds'></center><br><br></td></tr>
<tr><td width='250' align='center'>
<table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr>
      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>

    </tr>
    <tr>

      <td width='7'></td>
      <td><table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
  <tr>
    <td width='200' class='mainstatsbody' align='center'>Most Killed:</td>
	</tr>
  </table></td>

      <td width='7'></td>
    </tr>
    <tr>

      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>
    </tr>
  </table><table width='225' cellpadding='0' cellspacing='0' class='membersList'>

<tr><th colspan='2' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
<tr><th colspan='2' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
</table>
</td><td width='250' align='center'><table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>

    <tr>
      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>
    </tr>
    <tr>

      <td width='7'></td>
      <td><table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>

  <tr>
    <td width='200' class='mainstatsbody' align='center'>Most Killed By:</td>
	</tr>
  </table></td>
      <td width='7'></td>
    </tr>
    <tr>

      <td height='7'></td>

      <td height='7'></td>
      <td height='7'></td>
    </tr>
  </table><table width='225' cellpadding='0' cellspacing='0' class='membersList'>
<tr><th colspan='2' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
<tr><th colspan='2' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
</table>
</td></tr></table><table width='500' align='center'><tr><td width='500' align='center'><br><br><center><img src='img/VersusPlayers.gif' alt='Versus Players'><br><br></td></tr><tr><td width='500' align='center'><table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr>
      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>
    </tr>
    <tr>

      <td width='7'></td>
      <td><table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
  <tr>
    <td width='200' class='mainstatsbody' align='center'>Most Killed:</td>
	</tr>
  </table></td>
      <td width='7'></td>
    </tr>

    <tr>

      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>
    </tr>
  </table><table width='475' cellpadding='0' cellspacing='0' class='membersList'>
<tr><th colspan='6' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
<th background='./img/Roster-Background.jpg' class='rankbordercenterleft'><div class='membersHeader'><span style='font-weight: bold'>Name</span></div></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Kills</span></th>

<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Guild</span></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Race</span></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Class</span></th>
<th background='./img/Roster-Background.jpg' class='rankbordercenterright'><div class='membersHeaderRight'><span style='font-weight: bold;'>Level</span></div></th>
<tr><th colspan='6' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
</table>
</td></table><table width='500' align='center'><tr><td width='500' align='center'><table width='175' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr>

      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>
    </tr>
    <tr>

      <td width='7'></td>
      <td><table width='161' align='center' cellpadding='1' cellspacing='1' class='bodyline'>
  <tr>

    <td width='200' class='mainstatsbody' align='center'>Most Killed By:</td>
	</tr>
  </table></td>
      <td width='7'></td>
    </tr>
    <tr>

      <td height='7'></td>
      <td height='7'></td>
      <td height='7'></td>
    </tr>
  </table><table width='475' cellpadding='0' cellspacing='0' class='membersList'>
<tr><th colspan='6' class='rankbordertop'><span class='rankbordertopleft'></span><span class='rankbordertopright'></span></th></tr>
<th background='./img/Roster-Background.jpg' class='rankbordercenterleft'><div class='membersHeader'><span style='font-weight: bold'>Name</span></div></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Kills</span></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Guild</span></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Race</span></th>
<th background='./img/Roster-Background.jpg' class='membersHeader'><span style='font-weight: bold;'>Class</span></th>

<th background='./img/Roster-Background.jpg' class='rankbordercenterright'><div class='membersHeaderRight'><span style='font-weight: bold;'>Level</span></div></th>
<tr><th colspan='6' class='rankborderbot'><span class='rankborderbotleft'></span><span class='rankborderbotright'></span></th></tr>
</table>
</table>

</td></table>";
  return $returnstring;
}

function output_pvp2($pvps,$url) {
  extract($GLOBALS);
  $returnstring = '
  <div align="center">
  <table class="bodyline" cellspacing="0">
  <tr>
	<th class="membersHeader">'.$url.'&s=date">'.$wordings[$roster_lang]['when'].'</a></th>
    <th class="membersHeader">'.$url.'&s=name">'.$wordings[$roster_lang]['name'].'</a></th>
    <th class="membersHeader">'.$url.'&s=rank">'.$wordings[$roster_lang]['rank'].'</a></th>
    <th class="membersHeader">'.$url.'&s=guild">'.$wordings[$roster_lang]['guild'].'</a></th>
    <th class="membersHeader">'.$url.'&s=race">'.$wordings[$roster_lang]['race'].'</a></th>
    <th class="membersHeader">'.$url.'&s=class">'.$wordings[$roster_lang]['class'].'</a></th>
    <th class="membersHeader">'.$url.'&s=leveldiff">'.$wordings[$roster_lang]['leveldiff'].'</a></th>
    <th class="membersHeader">'.$url.'&s=result">'.$wordings[$roster_lang]['result'].'</a></th>
    <th class="membersHeader">'.$url.'&s=honor">'.$wordings[$roster_lang]['honor'].'</a></th>
    <th class="membersHeader">'.$url.'&s=zone">'.$wordings[$roster_lang]['zone2'].'</a></th>
    <th class="membersHeader">'.$url.'&s=subzone">'.$wordings[$roster_lang]['subzone'].'</a></th>
    <th class="membersHeaderRight">'.$url.'&s=bg">'.$wordings[$roster_lang]['bg'].'</a></th>
  </tr> ';

  $rc = 1;
  foreach ($pvps as $row) {
    $diff = $row->data['leveldiff'];
    if ($diff < -10) {
      $color = grey; }
    else if ($diff < -4) {
      $color = green; }
    else if ($diff < 4) {
      $color = yellow; }
    else {
      $color = red; }

    if ($row->data['win'] == '1') {
      $result = $wordings[$roster_lang]['win'];
    } else {
      $result = $wordings[$roster_lang]['loss'];
    }
    if ($row->data['bg'] > 0) {
      $bg = $wordings[$roster_lang]['yes'];
    } else {
      $bg = $wordings[$roster_lang]['no'];
    }

    $row_st = (($rc%2)+1);
    $returnstring .= '
	  <tr>
  	<td class="membersRow'.$row_st.'">'.$row->data['date2'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['name'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['rank'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['guild'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['race'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['class'].'</td>
		<td class="membersRow'.$row_st.'">';
    if ($diff > 0) {
      $returnstring .= '+';
    }
    $returnstring .= $row->data['leveldiff'].'</td>
		<td class="membersRow'.$row_st.'">'.$result.'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['honor'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['zone'].'</td>
		<td class="membersRow'.$row_st.'">'.$row->data['subzone'].'</td>
		<td class="membersRowRight'.$row_st.'">'.$bg.'</td>
	  </tr>
	';
    $rc++;
  }
  $returnstring .= '
	</tr></td>
	</table>
	</div>
	';
  return $returnstring;
}
?>

