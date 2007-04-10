<?php
$versions['versionDate']['skill'] = '$Date: 2006/01/01 13:44:34 $'; 
$versions['versionRev']['skill'] = '$Revision: 1.5 $'; 
$versions['versionAuthor']['skill'] = '$Author: zanix $';

require_once 'wowdb.php';

class skill
{
  var $data;

  function skill( $data )
  {
    $this->data = $data;
  }

  function get( $field )
  {
    return $this->data[$field];
  }

  function outHeader()
  {
    return '<div class="skilltype">'.$this->data['skill_type'].' </div>';
  }
  function out()
  {
    list($level, $max) = explode( ':', $this->data['skill_level'] );
    if( $max == 1 )
    {
      $bgImage = 'img/barGrey.gif';
    }
    else
    {
      $bgImage = 'img/barEmpty.gif';
    }

    $returnstring = '
<div class="skill">
  <div class="skillbox">
    <img class="bg" alt="" src="'.$bgImage.'" />';
    if( $max > 1 )
    {
      $width = intval(($level/$max) * 354);
      $returnstring .= '<img src="img/barBit.gif" alt="" class="bit" width="'.$width.'" />';
    }

    $returnstring .= '
    <span class="name">'.$this->data['skill_name'].'</span>';

    if( $max > 1 )
    {
      $returnstring .= '<span class="level">'.$level.'/'.$max.'</span>';
    }
    $returnstring .= '
  </div>
</div>
';


#      echo '<img class="bgGrey" src="img/barGrey.gif" />';
#    } else {
#      echo '<img class="bgGrey" src="img/barGrey.gif" />';
#    }
#    echo '<div class="skill">'.$this->data['skill_name'].": ".$this->data['skill_level']."</div>\n";

		return $returnstring;
  }
}

function skill_get_many_by_type( $member_id, $type )
{
  global $wowdb;
  $type = $wowdb->escape( $type );

  return skill_get_many( $member_id, "`skill_type` = '$type'" );
}

function skill_get_many_by_order( $member_id, $order )
{
  global $wowdb;
  $order = $wowdb->escape( $order );

  return skill_get_many( $member_id, "`skill_order` = '$order'" );
}

function skill_get_many( $member_id, $search )
{
  global $wowdb;

  if (isset($char))
  {
  	$char = $wowdb->escape( $char );
  }
  if (isset($server))
  {
  	$server = $wowdb->escape( $server );
  }
  $query= "SELECT * FROM `".ROSTER_SKILLSTABLE."` WHERE `member_id` = '$member_id' AND $search";

  $result = $wowdb->query( $query );

  $skills = array();
  while( $data = $wowdb->getrow( $result ) )
  {
    $skill = new skill( $data );
    $skills[] = $skill;
  }
  return $skills;
}
?>