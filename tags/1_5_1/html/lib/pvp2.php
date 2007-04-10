<?php
require_once 'wowdb.php';

class pvp2 {
  var $data;

  function pvp2( $data ) {
    $this->data = $data;
  }

  function get( $field ) {
    return $this->data[$field];
  }

  function outHeader() {
    echo '<div class="pvptype">'.$this->data['guild'].' </div>';
  }

  function out2() { 
	echo '<b><font face="Georgia" size="+1" color="#0000FF"></font></b>';
   echo '['.$this->data['pvp_level'].'] '.$this->data['pvp_name'];
  }

  function out() {
    $max = 60;
    $level = $this->data['pvp_level'];
    if( $max == 1 ) {
      $bgImage = 'img/barGrey.gif';
    } else {
      $bgImage = 'img/barEmpty.gif';
    }

    echo '
<div class="pvp">
  <div class="pvpbox">
    <img class="bg" alt="" src="'.$bgImage.'" />';
    if( $max > 1 ) {
      $width = intval(($level/$max) * 354);
      echo '<img src="img/barBit.gif" alt="" class="bit" width="'.$width.'" />';
    }
    echo '
    <span class="name">'.$this->data['pvp_name'].'</span>';
    if( $max > 1 ) {
      echo '<span class="level"> ['.$level.']</span>';
    }
    echo '
  </div>
</div>
';


#      echo '<img class="bgGrey" src="img/barGrey.gif" />';
#    } else {
#      echo '<img class="bgGrey" src="img/barGrey.gif" />';
#    }
#    echo '<div class="pvp">'.$this->data['pvp_name'].": ".$this->data['pvp_level']."</div>\n";

  }
}
function pvp_get_many2( $member_id, $type, $sort, $start) {
  global $wowdb;  
  $workaround = $start; //otherwhise $start is overwritten with extract globals
  extract($GLOBALS);
  //$char = $wowdb->escape( $char );
  //$server = $wowdb->escape( $server );
  $query= "SELECT *, DATE_FORMAT(date, '".$timeformat[$roster_lang]."') AS date2 FROM `".ROSTER_PVP2TABLE."` WHERE `member_id` = '".$member_id."' AND `enemy` = '";
  if ($type == 'PvP') {
    $query=$query."Yes'";
  } else {
    $query=$query."No'";
  }
  
  if ($sort == 'name') {
	  $query=$query." ORDER BY 'name', 'level' DESC, 'guild'";
  } else if ($sort == 'race') {
	  $query=$query." ORDER BY 'race', 'guild', 'name', 'level' DESC";
  } else if ($sort == 'class') {
	  $query=$query." ORDER BY 'class', 'guild', 'name', 'level' DESC";
  } else if ($sort == 'level') {
	  $query=$query." ORDER BY 'level' DESC, 'guild', 'name' ";
  } else if ($sort == 'mylevel') {
	  $query=$query." ORDER BY 'mylevel' DESC, 'guild', 'name' ";
  } else if ($sort == 'diff') {
	  $query=$query." ORDER BY 'diff' DESC, 'guild', 'name' ";
  } else if ($sort == 'result') {
	  $query=$query." ORDER BY 'win' DESC, 'guild', 'name' ";
  } else if ($sort == 'zone') {
	  $query=$query." ORDER BY 'zone', 'guild', 'name' ";
  } else if ($sort == 'subzone') {
	  $query=$query." ORDER BY 'subzone', 'guild', 'name' ";
  } else if ($sort == 'group') {
	  $query=$query." ORDER BY 'group', 'guild', 'name' ";
  } else if ($sort == 'date') {
	  $query=$query." ORDER BY 'date', 'guild', 'name' ";
  } else if ($sort == 'guild') {
	  $query=$query." ORDER BY 'guild', 'name', 'level' DESC ";
  } else {
	  $query=$query." ORDER BY 'date' DESC, 'guild', 'name' ";
  }
  if ($workaround != -1) $query = $query.' LIMIT '.$start.', 50';
    $result = mysql_query($query) or die(mysql_error());
  $pvps = array();
  while( $data = $wowdb->getrow( $result ) ) {
    $pvp = new pvp2( $data );
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
	if ($row->data['win'] == 'Yes') {
		$tot_wins = $tot_wins + 1;
		$ave_win_level_diff = $ave_win_level_diff + $row->data['diff'];
	} else {
    	$tot_losses = $tot_losses + 1;
    	$ave_loss_level_diff = $ave_loss_level_diff + $row->data['diff'];
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

  echo '
  <div align="center">
  <table class="bodyline" width="280">
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
}

function output_pvp2($pvps,$url) {
 extract($GLOBALS);
 print '
  <div align="center">
  <table class="bodyline">
  <tr class="membersHeader">
	<th>'.$url.'&s=date">'.$wordings[$roster_lang]['when'].'</a></th>
    <th>'.$url.'&s=name">'.$wordings[$roster_lang]['name'].'</a></th>
    <th>'.$url.'&s=guild">'.$wordings[$roster_lang]['guild'].'</a></th>
    <th>'.$url.'&s=race">'.$wordings[$roster_lang]['race'].'</a></th>
    <th>'.$url.'&s=class">'.$wordings[$roster_lang]['class'].'</a></th>
    <th>'.$url.'&s=level">'.$wordings[$roster_lang]['theirlevel'].'</a></th>
    <th>'.$url.'&s=mylevel">'.$wordings[$roster_lang]['yourlevel'].'</a></th>
    <th>'.$url.'&s=diff">'.$wordings[$roster_lang]['diff'].'</a></th>
    <th>'.$url.'&s=result">'.$wordings[$roster_lang]['result'].'</a></th>
    <th>'.$url.'&s=zone">'.$wordings[$roster_lang]['zone2'].'</a></th>
    <th>'.$url.'&s=subzone">'.$wordings[$roster_lang]['subzone'].'</a></th>
    <th>'.$url.'&s=group">'.$wordings[$roster_lang]['group'].'</a></th>
  </tr> ';

  $rc = 1;
  foreach ($pvps as $row) {
		$diff = $row->data['diff'];
		if ($diff < -10) {
		    $color = grey; }
	    else if ($diff < -4) {
		    $color = green; }
	    else if ($diff < 4) {
		    $color = yellow; }
	    else {
		    $color = red; }

	    if ($row->data['win'] == 'Yes') {
		  $result = 'Win';
		} else {
	      $result = 'Lose';
		}

		echo '
	  <tr class="membersRow'.(($rc%2)+1).'">
  	<td width="150">'.$row->data['date2'].'</td>
		<td width="120">'.$row->data['name'].'</td>
		<td width="150">'.$row->data['guild'].'</td>
		<td width="90">'.$row->data['race'].'</td>
		<td width="90">'.$row->data['class'].'</td>
		<td width="70">'.$row->data['level'].'</td>
		<td width="70">'.$row->data['mylevel'].'</td>
		<td width="50">';
		if ($diff > 0) {
			echo '+';
		}
		echo $row->data['diff'].'</td>
		<td width="50">'.$result.'</td>
		<td width="100">'.$row->data['zone'].'</td>
		<td width="100">'.$row->data['subzone'].'</td>
		<td width="50">'.$row->data['group'].'</td>
	  </tr>
	';
	$rc++;
	}
	echo '
	</tr></td>
	</table>
	</div>
	';
	}
	?>