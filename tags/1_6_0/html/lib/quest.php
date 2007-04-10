<?php
$versions['versionDate']['quest'] = '$Date: 2005/12/30 20:40:53 $'; 
$versions['versionRev']['quest'] = '$Revision: 1.5 $'; 
$versions['versionAuthor']['quest'] = '$Author: mordon $'; 

require_once 'wowdb.php';

class quest {
  var $data;

  function quest( $data ) {
    $this->data = $data;
  }

  function get( $field ) {
    return $this->data[$field];
  }

  function outHeader() {
    echo '<div class="questtype">'.$this->data['quest_zone'].' </div>';
  }

  function out2() { 
	echo '<b><font face="Georgia" size="+1" color="#0000FF"></font></b>';
   echo '['.$this->data['quest_level'].'] '.$this->data['quest_name'];
  }

  function out() {
    $max = 60;
    $level = $this->data['quest_level'];
    if( $max == 1 ) {
      $bgImage = 'img/barGrey.gif';
    } else {
      $bgImage = 'img/barEmpty.gif';
    }

    echo '
<div class="quest">
  <div class="questbox">
    <img class="bg" alt="" src="'.$bgImage.'" />';
    if( $max > 1 ) {
      $width = intval(($level/$max) * 354);
      echo '<img src="img/barBit.gif" alt="" class="bit" width="'.$width.'" />';
    }
    echo '
    <span class="name">'.$this->data['quest_name'].'</span>';
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
#    echo '<div class="quest">'.$this->data['quest_name'].": ".$this->data['quest_level']."</div>\n";

  }
}
function quest_get_many( $member_id, $search ) {
  global $wowdb;

  if (isset($char)) { $char = $wowdb->escape( $char ); }
  if (isset($server)) { $server = $wowdb->escape( $server ); }
  $query= "SELECT * FROM `".ROSTER_QUESTSTABLE."` where `member_id` = '$member_id' ORDER BY 'zone', 'quest_level'";

  $result = $wowdb->query( $query );

  $quests = array();
  while( $data = $wowdb->getrow( $result ) ) {
    $quest = new quest( $data );
    $quests[] = $quest;
  }
  return $quests;
}
?>