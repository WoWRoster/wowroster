<?php

/*
* $Date: 2006/02/28 $
* $Revision: 2.0 $
*/

class maxres
{
    var $data;

    function maxres( $data )
    {
        $this->data = $data;
    }

    function printEquip( $item_name, $slot )
    {
    	global $roster_conf;

        $item = item_get( $this->data['member_id'], $item_name );
        if( isset($item) and $item != "" && $item_name != '' )
            $output = $item->out();
        else
        {
            $output = '<div class="item">';
            $output .= '<span style="z-index: 1001;" onMouseover="return overlib(\''.$slot.':<br />No item equipped\');" onMouseout="return nd();">';
            if ($slot == 'Ammo')
                $output .= '<img src="'.$roster_conf['interface_url'].'Interface/EmptyEquip/'.$slot.'.gif" class="iconsmall" /></a>';
            else
                $output .= '<img src="'.$roster_conf['interface_url'].'Interface/EmptyEquip/'.$slot.'.gif" class="icon" /></a>';

            $output .= '</span></div>';
        }
        return $output;
    }

    function Summaryout()
    {
          extract($GLOBALS);
        global $img_url; // Added this global variable to use in the image link

        echo border('syellow','start','Gear used');
        $output = '<table width="405" cellspacing="0" cellpadding="0" border="0"><tr><td colspan="5"></td></tr>';
          $output .= '<tr class="membersHeader"><td colspan="1" >'.$wordings[$roster_conf['roster_lang']]['MaxDot'].$wordings[$roster_conf['roster_lang']][$resist].'</td><td>Main</td><td>Enchant</td><td>Total</td></tr>';

        $maintotal = 0;
        $enchtotal = 0;
        $tottotal = 0;

        $row = 0;
        $MyEquiparray = array();
        $MyEquiparray = getslotres($this->data['member_id'],$this->data['clientLocale'],$wordings[$roster_conf['roster_lang']][$resist]);

            foreach ( $MyEquiparray as $item_name )
            {
                $item = item_get( $this->data['member_id'], $item_name['name'] );
                if( isset($item) and $item != "" ) {
                    $color = substr( $item->data['item_color'], 2, 6 );
                }

                if( $item_name['FR1'] == 0 and $item_name['FR2'] > 0 ) {
                    $item_name['FR1'] = $item_name['FR2'];
                    $item_name['FR2'] = 0;
                }
                if( $item_name['FR3'] > 0 ) {
                    $output .= '<tr class="membersRow'.(($row%2)+1).'">'.
                        ''.
                        '<td>';
                        if( isset($color) and $color != "") {
                        $output .= '<span style="color:#'.$color.'">'.$item_name['name'].'</span>';
                        } else {
                        $output .= $item_name['name'];
                        }
                        $output .= '</td><td>'.$item_name['FR1'].'</td><td>'.$item_name['FR2'].'</td><td>'.$item_name['FR3'].'</td>'.
                        ''.
                        '</tr>';
                        $maintotal = $maintotal + $item_name['FR1'];
                        $enchtotal = $enchtotal + $item_name['FR2'];
                        $tottotal = $tottotal + $item_name['FR3'];
                        $row++;
                    }
            }
            $output .= '<tr class="membersRow'.(($row%2)+1).'">'.
                              ''.
                              '<td align="left">Total:</td><td>'.$maintotal.'</td><td>'.$enchtotal.'</td><td>'.$tottotal.'</td>'.
                              ''.
                              '</tr>';
             $output .= '<tr><td colspan="5"></td></tr></table>';
        echo $output;
        echo border('syellow','end');
    }

    function printRes ( $resname )
    {
        extract($GLOBALS);

        switch($resname)
        {
        case 'res_fire':
            $name = $wordings[$roster_conf['roster_lang']]['res_fire'];
            $sql_name = "max_res_fire";
            $tooltip = $wordings[$roster_conf['roster_lang']]['max_res_fire_tooltip'];
            $color = 'red';
            break;
        case 'res_nature':
            $name = $wordings[$roster_conf['roster_lang']]['res_nature'];
            $sql_name = "max_res_nat";
            $tooltip = $wordings[$roster_conf['roster_lang']]['max_res_nature_tooltip'];
            $color = 'green';
            break;
        case 'res_arcane':
            $name = $wordings[$roster_conf['roster_lang']]['res_arcane'];
            $sql_name = "max_res_arc";
            $tooltip = $wordings[$roster_conf['roster_lang']]['max_res_arcane_tooltip'];
            $color = 'yellow';
            break;
        case 'res_frost':
            $name = $wordings[$roster_conf['roster_lang']]['res_frost'];
            $sql_name = "max_res_fro";
            $tooltip = $wordings[$roster_conf['roster_lang']]['max_res_frost_tooltip'];
            $color = 'blue';
            break;
        case 'res_shadow':
            $name = $wordings[$roster_conf['roster_lang']]['res_shadow'];
            $sql_name = "max_res_shad";
            $tooltip = $wordings[$roster_conf['roster_lang']]['max_res_shadow_tooltip'];
            $color = 'purple';
            break;
        }

        $player_name = $_REQUEST['maxresname'];

        $result = $wowdb->query("select ".$sql_name." as resist from `".ROSTER_PLAYERSTABLE."` where name='".$this->data['name']."'");
        //echo "select ".$sql_name." from `".ROSTER_PLAYERSTABLE."` where name='".$this->data['name']."'";
        $row = $wowdb->getrow($result);

        $tooltipheader = $name;
        $line = '<span style="color: '.$color.'; font-size: 12px; font-weight: bold;">'.$tooltipheader.'</span><br />';
        $line .= '<span style="color: #DFB801; text-align: left;">'.$tooltip.'</span>';
        $line = str_replace("'", "\'", $line);
        $line = str_replace('"','&quot;', $line);

        $output = '<span style="z-index: 1002;" onMouseover="return overlib(\''.$line.'\');" onMouseout="return nd();">';
        $output .= '<span class="'.substr($resname,4).'">';
        $output .= '<span class="white">'.Resistlink($player_name, $server, $name, $row['resist']).'</span>';
        $output .= "</span></span>\n";


        return $output;
    }

    function out()
        {
            extract($GLOBALS);

            if ($this->data['name'] != '')
            {

    ?>

    <div class="char" id="char"> <!-- Begin char -->
      <div class="main"> <!-- Begin char-main -->
        <div class="top" id="top"> <!-- Begin char-main-top -->

    <?php

    if( $this->data['RankName'] == $wordings[$roster_conf[$this->data['clientLocale']]]['PvPRankNone'] )
        $RankName = '';
    else
        $RankName = $this->data['RankName'].' ';

    ?>
          <h1><?php print ($RankName.$this->data['name']); ?></h1>
          <h2>Level <?php print ($this->data['level'].' | '.$this->data['sex'].' '.$this->data['race'].' '.$this->data['class']); ?></h2>
    <?php

    if( isset( $this->data['guild_name'] ) )
        echo '      <h2>'.$this->data['guild_title'].' of '.$this->data['guild_name']."</h2>\n";

    $MyEquiparray = array();
    $MyEquiparray = getslotres($this->data['member_id'],$this->data['clientLocale'],$wordings[$roster_conf['roster_lang']][$resist]);

    ?>
        </div> <!-- End char-main-top -->
        <div class="page1" id="page1"> <!-- begin char-main-page1 -->
          <div class="left"> <!-- begin char-main-page1-left -->
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Head']['name'],'Head'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Neck']['name'], 'Neck'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Shoulder']['name'], 'Shoulder'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Back']['name'], 'Back'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Chest']['name'], 'Chest'); ?></div>
            <div class="equip"><?php print $this->printEquip('', 'Shirt'); ?></div>
            <div class="equip"><?php print $this->printEquip('', 'Tabard'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Wrist']['name'], 'Wrist'); ?></div>
          </div> <!-- end char-main-page1-left -->
          <div class="middle"> <!-- begin char-main-page1-middle -->
            <div class="portrait"> <!-- begin char-main-page1-middle-portrait -->
              <div class="resistance"> <!-- begin char-main-page1-middle-portrait-resistance -->
                <?php print $this->printRes('res_fire'); ?>
                <?php print $this->printRes('res_nature'); ?>
                <?php print $this->printRes('res_arcane'); ?>
                <?php print $this->printRes('res_frost'); ?>
                <?php print $this->printRes('res_shadow'); ?>
              </div> <!-- end char-main-page1-middle-portrait-resistance -->
              <div class="health_mana"> <!-- begin char-main-page1-middle-portrait-health_mana -->
                <div class="health"></span></div>
                <div class="mana"></span></div>
              </div> <!-- end char-main-page1-middle-portrait-health_mana -->
              <div class="talentPoints"> <!-- begin char-main-page1-middle-portrait-talentPoints -->

              </div> <!-- end char-main-page1-middle-portrait-talentPoints -->
              <div class="xp"> <!-- begin char-main-page1-middle-portrait-xp -->
                <div class="xpbox">

                </div>
              </div> <!-- end char-main-page1-middle-portrait-xp -->
            </div> <!-- end char-main-page1-middle-portrait -->
            <div class="bottom"> <!-- begin char-main-page1-middle-bottom -->
              <div class="padding">
                <ul class="stats">
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>
                <ul class="stats">
                  <li>
                    <ul>
                      <li></li>
                      <li></li>
                    </ul></li>
                  <li>
                    <ul>
                      <li></li>
                      <li></li>
                    </ul></li>
                </ul>
              </div> <!-- end char-main-page1-middle-bottom-padding -->
              <div class="hands">
                <?php if(array_key_exists('2HWeapon', $MyEquiparray)) {
                    print "<div class=\"weapon0\">".$this->printEquip($MyEquiparray['2HWeapon']['name'],'MainHand')."</div>";
                    print "<div class=\"weapon1\">".$this->printEquip('','SecondaryHand')."</div>";
                    } else {
                    print "<div class=\"weapon0\">".$this->printEquip($MyEquiparray['Weapon']['name'],'MainHand')."</div>";
                    print "<div class=\"weapon1\">".$this->printEquip($MyEquiparray['OffWeapon']['name'],'SecondaryHand')."</div>";
                    } ?>
                <div class="weapon2"><?php print $this->printEquip($MyEquiparray['Ranged']['name'],'Ranged'); ?></div>
                <div class="ammo"><?php print $this->printEquip('','Ammo'); ?></div>
              </div> <!-- end char-main-page1-middle-bottom-hands -->
            </div> <!-- end char-main-page1-middle-bottom -->
          </div> <!-- end char-main-page1-middle -->
          <div class="right"> <!-- begin char-main-page1-right -->
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Hands']['name'], 'Hands'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Waist']['name'], 'Waist'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Legs']['name'], 'Legs'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Feet']['name'], 'Feet'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Finger1']['name'],'Finger0'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Finger2']['name'],'Finger1'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Trinket1']['name'],'Trinket0'); ?></div>
            <div class="equip"><?php print $this->printEquip($MyEquiparray['Trinket2']['name'],'Trinket1'); ?></div>
          </div> <!-- end char-main-page1-right -->
        </div> <!-- end char-main-page1 -->
      </div> <!-- end char-main -->
      <div class="bottomBorder">
      </div>
      <div class="tabs"> <!-- begin char-tabs -->
    <?php
    echo "\n";
    echo '  </div> <!-- end char-tabs -->
    </div> <!-- end char -->
    ';
            }
            else
                echo 'Sorry no data in database for '.$_REQUEST['name'].' of '.$_REQUEST['server'];
        }
    }

    function char_get_userinfo( $name, $server )
{
    global $wowdb;

    $name = $wowdb->escape( $name );
    $server = $wowdb->escape( $server );
    $query = "SELECT a.*, b.guild_rank, b.guild_title, c.guild_name, b.note FROM `".ROSTER_PLAYERSTABLE."` a, `".ROSTER_MEMBERSTABLE."` b, `".ROSTER_GUILDTABLE."` c " .
    "where a.member_id = b.member_id and a.name = '$name' and a.server = '$server' and a.guild_id = c.guild_id";
    $result = $wowdb->query( $query );
    $data = $wowdb->getrow( $result );
    return new maxres( $data );

}

   function item_get( $member_id, $item )
{
    global $wowdb;

    $item = $wowdb->escape( $item );
    $query = "SELECT * FROM `".ROSTER_ITEMSTABLE."` where member_id = $member_id and item_name = '$item'";
    if ($roster_conf['sqldebug'])
        print "<!-- $query --> \n";

    $result = $wowdb->query( $query );
    $data = $wowdb->getrow( $result );
    if( $data )
        return new item( $data );
    else
        return Null;
}

function getslotres($id, $client_lang, $Fire_Resistance) {

    global $wowdb;
      extract($GLOBALS);

    $Neck         = $wordings[$client_lang]['Neck'];
    $Head         = $wordings[$client_lang]['Head'];
    $Feet         = $wordings[$client_lang]['Feet'];
    $Chest         = $wordings[$client_lang]['Chest'];
    $Finger     = $wordings[$client_lang]['Finger'];
    $Shoulder     = $wordings[$client_lang]['Shoulder'];
    $Legs         = $wordings[$client_lang]['Legs'];
    $Wrist         = $wordings[$client_lang]['Wrist'];
    $Hands         = $wordings[$client_lang]['Hands'];
    $Back         = $wordings[$client_lang]['Back'];
    $Waist         = $wordings[$client_lang]['Waist'];
    $Trinket     = $wordings[$client_lang]['Trinket'];
    $Main_Hand     = $wordings[$client_lang]['Main_Hand'];
    $One_Hand     = $wordings[$client_lang]['One-Hand'];
    $TwoWeapon     = $wordings[$client_lang]['Two-Hand'];
    $Offhand     = $wordings[$client_lang]['Off-hand'];
    $Off_hand     = $wordings[$client_lang]['Off Hand'];
    $Wand         = $wordings[$client_lang]['Wand'];
    $Crossbow     = $wordings[$client_lang]['Crossbow'];
    $Ranged     = $wordings[$client_lang]['Ranged'];
    $Gun         = $wordings[$client_lang]['Gun'];
    $tooltip_soulbound = $wordings[$client_lang]['tooltip_soulbound'];
    $Unique = $wordings[$client_lang]['Unique'];
    $Plans         = $wordings[$client_lang]['Plans'];
    $Schematic    = $wordings[$client_lang]['Schematic'];
    $Formula    = $wordings[$client_lang]['Formula'];
    $Pattern    = $wordings[$client_lang]['Pattern'];

     $output .= ""; //this will hold all the stuff this function used to print/echo

     $querystr = "select item_name, ".
     "CASE ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Neck."%' THEN 'Neck' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Head."%' THEN 'Head' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Feet."%' THEN 'Feet' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Chest."%' THEN 'Chest' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Finger."%' THEN 'Finger' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Shoulder."%' THEN 'Shoulder' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Legs."%' THEN 'Legs' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Wrist."%' THEN 'Wrist' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Hands."%' THEN 'Hands' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Back."%' THEN 'Back' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Waist."%' THEN 'Waist' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Trinket."%' THEN 'Trinket' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Main_Hand."%' THEN 'Weapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$One_Hand."%' THEN 'Weapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$TwoWeapon."%' THEN '2HWeapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Offhand."%' THEN 'OffWeapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Off_hand."%' THEN 'OffWeapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Wand."%' THEN 'Ranged' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Crossbow."%' THEN 'Ranged' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Ranged."%' THEN 'Ranged' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Gun."%' THEN 'Ranged' ".
     "END as slot, ".
     "CONVERT(replace(SUBSTRING(item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$tooltip_soulbound."', item_tooltip))-3,2),'+','') ,SIGNED INTEGER) as FR1, ".
     "CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))+1),0)-3,2),'+',''),0),SIGNED INTEGER) + CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance." +', item_tooltip,LOCATE('".$Fire_Resistance." +', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))-1),0)+17,2),'+',''),0),SIGNED INTEGER) as FR2, ".
     "CONVERT(replace(SUBSTRING(item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$tooltip_soulbound."', item_tooltip))-3,2),'+','') ,SIGNED INTEGER) + ".
     "CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))+1) ,0)-3,2),'+',''),0) ,SIGNED INTEGER) + CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance." +', item_tooltip,LOCATE('".$Fire_Resistance." +', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))-1),0)+17,2),'+',''),0),SIGNED INTEGER) as FR3 ".
     "from ".ROSTER_ITEMSTABLE." ".
     "where member_id = $id and ( ( item_tooltip like '%".$tooltip_soulbound."%' or item_tooltip like '%".$Unique."%' ) and item_tooltip like '%".$Fire_Resistance."%') AND ".
     "(item_tooltip not like '%".$Plans."%' AND item_tooltip not like '%".$Schematic."%' AND item_tooltip not like '%".$Formula."%' AND item_tooltip not like '%".$Pattern."%'  AND item_tooltip not like '%Juju%'  ) ".
     "order by FR3 DESC, slot";

     $totalFR = 0;
     $Myarray = array();
     $MyEquiparray = array();

     if ($roster_conf['sqldebug']) {
        echo "<!-- $querystr -->\n";
     }

     $result = mysql_query($querystr) or die(mysql_error());

     while ( $row = mysql_fetch_array($result) ) {
        $slot = $row['slot'];

        If( $row['slot'] == 'Finger' or $row['slot'] == 'Trinket' ) {
           If( array_key_exists($slot, $Myarray) ) {
              $thecount = $Myarray[$slot];
              If($thecount < 2) {
                 $Myarray[$slot] = $Myarray[$slot] + 1;
                 $MyEquiparray[$slot.$Myarray[$slot]]['name'] = $row['item_name'];
                 $MyEquiparray[$slot.$Myarray[$slot]]['FR1'] = $row['FR1'];
                 $MyEquiparray[$slot.$Myarray[$slot]]['FR2'] = $row['FR2'];
                 $MyEquiparray[$slot.$Myarray[$slot]]['FR3'] = $row['FR3'];
                 }
           } else {
           $Myarray[$slot] = 1;
           $MyEquiparray[$slot.'1']['name'] = $row['item_name'];
           $MyEquiparray[$slot.'1']['FR1'] = $row['FR1'];
           $MyEquiparray[$slot.'1']['FR2'] = $row['FR2'];
           $MyEquiparray[$slot.'1']['FR3'] = $row['FR3'];
           }
        } elseif( $row['slot'] == 'Weapon' or $row['slot'] == '2HWeapon' or $row['slot'] == 'OffWeapon' ) {
           $skipit = 0;
           If( array_key_exists('Weapon', $Myarray) ) {
              If( $row['slot'] == '2HWeapon' )
                 $skipit = 1;
           } elseif(array_key_exists('2HWeapon', $Myarray) ) {
              If( $row['slot'] == 'Weapon' or $row['slot'] == 'OffWeapon' )
                 $skipit = 1;
           } elseif(array_key_exists('OffWeapon', $Myarray) ) {
              If( $row['slot'] == '2HWeapon' )
                 $skipit = 1;
           }
           IF($skipit == 0 and !array_key_exists($slot, $Myarray)) {
              $Myarray[$slot] = 1;
              $MyEquiparray[$slot]['name'] = $row['item_name'];
              $MyEquiparray[$slot]['FR1'] = $row['FR1'];
              $MyEquiparray[$slot]['FR2'] = $row['FR2'];
              $MyEquiparray[$slot]['FR3'] = $row['FR3'];
           }
        } else {
           If( !array_key_exists($slot, $Myarray) ) {
           $Myarray[$slot] = 1;
           $MyEquiparray[$slot]['name'] = $row['item_name'];
           $MyEquiparray[$slot]['FR1'] = $row['FR1'];
           $MyEquiparray[$slot]['FR2'] = $row['FR2'];
           $MyEquiparray[$slot]['FR3'] = $row['FR3'];
           }
        } // end if slot trinket or finger
     } // end while
     return $MyEquiparray;
  } // end function


  function getres($id, $client_lang, $Fire_Resistance) {

  global $wowdb;
  extract($GLOBALS);

    $Neck         = $wordings[$client_lang]['Neck'];
    $Head         = $wordings[$client_lang]['Head'];
    $Feet         = $wordings[$client_lang]['Feet'];
    $Chest         = $wordings[$client_lang]['Chest'];
    $Finger     = $wordings[$client_lang]['Finger'];
    $Shoulder     = $wordings[$client_lang]['Shoulder'];
    $Legs         = $wordings[$client_lang]['Legs'];
    $Wrist         = $wordings[$client_lang]['Wrist'];
    $Hands         = $wordings[$client_lang]['Hands'];
    $Back         = $wordings[$client_lang]['Back'];
    $Waist         = $wordings[$client_lang]['Waist'];
    $Trinket     = $wordings[$client_lang]['Trinket'];
    $Main_Hand     = $wordings[$client_lang]['Main_Hand'];
    $One_Hand     = $wordings[$client_lang]['One-Hand'];
    $TwoWeapon     = $wordings[$client_lang]['Two-Hand'];
    $Offhand     = $wordings[$client_lang]['Off-hand'];
    $Off_hand     = $wordings[$client_lang]['Off Hand'];
    $Wand         = $wordings[$client_lang]['Wand'];
    $Crossbow     = $wordings[$client_lang]['Crossbow'];
    $Ranged     = $wordings[$client_lang]['Ranged'];
    $Gun         = $wordings[$client_lang]['Gun'];
    $tooltip_soulbound = $wordings[$client_lang]['tooltip_soulbound'];
    $Unique = $wordings[$client_lang]['Unique'];
    $Plans         = $wordings[$client_lang]['Plans'];
    $Schematic    = $wordings[$client_lang]['Schematic'];
    $Formula    = $wordings[$client_lang]['Formula'];
    $Pattern    = $wordings[$client_lang]['Pattern'];

     $output .= ""; //this will hold all the stuff this function used to print/echo

     $querystr = "select item_name, ".
     "CASE ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Neck."%' THEN 'Neck' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Head."%' THEN 'Head' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Feet."%' THEN 'Feet' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Chest."%' THEN 'Chest' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Finger."%' THEN 'Finger' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Shoulder."%' THEN 'Shoulder' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Legs."%' THEN 'Legs' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Wrist."%' THEN 'Wrist' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Hands."%' THEN 'Hands' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Back."%' THEN 'Back' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Waist."%' THEN 'Waist' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Trinket."%' THEN 'Trinket' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Main_Hand."%' THEN 'Weapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$One_Hand."%' THEN 'Weapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$TwoWeapon."%' THEN '2HWeapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Offhand."%' THEN 'OffWeapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Off_hand."%' THEN 'OffWeapon' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Wand."%' THEN 'Ranged' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Crossbow."%' THEN 'Ranged' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Ranged."%' THEN 'Ranged' ".
     "WHEN SUBSTRING(item_tooltip,1,LOCATE('+',item_tooltip)) like '%".$Gun."%' THEN 'Ranged' ".
     "END as slot, ".
     "CONVERT(replace(SUBSTRING(item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$tooltip_soulbound."', item_tooltip))-3,2),'+','') ,SIGNED INTEGER) as FR1, ".
     "CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))+1),0)-3,2),'+',''),0),SIGNED INTEGER) + CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance." +', item_tooltip,LOCATE('".$Fire_Resistance." +', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))-1),0)+17,2),'+',''),0),SIGNED INTEGER) as FR2, ".
     "CONVERT(replace(SUBSTRING(item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$tooltip_soulbound."', item_tooltip))-3,2),'+','') ,SIGNED INTEGER) + ".
     "CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance."\n', item_tooltip,LOCATE('".$Fire_Resistance."\n', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))+1) ,0)-3,2),'+',''),0) ,SIGNED INTEGER) + CONVERT(Ifnull(replace(SUBSTRING(item_tooltip,Nullif(LOCATE('".$Fire_Resistance." +', item_tooltip,LOCATE('".$Fire_Resistance." +', item_tooltip, LOCATE('".$tooltip_soulbound."', item_tooltip))-1),0)+17,2),'+',''),0),SIGNED INTEGER) as FR3 ".
     "from ".ROSTER_ITEMSTABLE." ".
     "where member_id = $id and ( ( item_tooltip like '%".$tooltip_soulbound."%' or item_tooltip like '%".$Unique."%' ) and item_tooltip like '%".$Fire_Resistance."%') AND ".
     "(item_tooltip not like '%".$Plans."%' AND item_tooltip not like '%".$Schematic."%' AND item_tooltip not like '%".$Formula."%' AND item_tooltip not like '%".$Pattern."%'  AND item_tooltip not like '%Juju%'  ) ".
     "order by FR3 DESC, slot";

     $totalFR = 0;
     $Myarray = array();

     if ($roster_conf['sqldebug']) {
        echo "<!-- $querystr -->\n";
     }

     $result = mysql_query($querystr) or die(mysql_error());

     while ( $row = mysql_fetch_array($result) ) {
        $slot = $row['slot'];

        If( $row['slot'] == 'Finger' or $row['slot'] == 'Trinket' ) {
           If( array_key_exists($slot, $Myarray) ) {
              $thecount = $Myarray[$slot];
              If($thecount < 2) {
                 $Myarray[$slot] = $Myarray[$slot] + 1;
                 $totalFR = $row['FR1'] + $row['FR2'] + $totalFR;
                 }
           } else {
           $Myarray[$slot] = 1;
           $totalFR = $row['FR1'] + $row['FR2'] + $totalFR;
           }
        } elseif( $row['slot'] == 'Weapon' or $row['slot'] == '2HWeapon' or $row['slot'] == 'OffWeapon' ) {
           $skipit = 0;
           If( array_key_exists('Weapon', $Myarray) ) {
              If( $row['slot'] == '2HWeapon' )
                 $skipit = 1;
           } elseif(array_key_exists('2HWeapon', $Myarray) ) {
              If( $row['slot'] == 'Weapon' or $row['slot'] == 'OffWeapon' )
                 $skipit = 1;
           } elseif(array_key_exists('OffWeapon', $Myarray) ) {
              If( $row['slot'] == '2HWeapon' )
                 $skipit = 1;
           }
           IF($skipit == 0 and !array_key_exists($slot, $Myarray)) {
              $Myarray[$slot] = 1;
              $totalFR = $row['FR1'] + $row['FR2'] + $totalFR;
           }
        } else {
           If( !array_key_exists($slot, $Myarray) ) {
           $Myarray[$slot] = 1;
           $totalFR = $row['FR1'] + $row['FR2'] + $totalFR;
           }
        } // end if slot trinket or finger
     } // end while
     return $totalFR;
  } // end function

     function ResistLink($name, $server, $resist, $resist_value) {
         $output = "";
        $script_url = basename($_SERVER['PHP_SELF']);
        if(isset($_SERVER['QUERY_STRING']))
                {$script_url .= '?'.$_SERVER['QUERY_STRING'];}
        $findstr = "&amp;maxresname";
        $trimstr = strpos($script_url, $findstr);
        if ($trimstr !== false) {
            $script_url = substr($script_url, 0, $trimstr);
         }
        if($resist_value == 0) {
        $output = "$resist_value\n";
        } else {
        $output = '<a href="'.$script_url.'&amp;maxresname='.$name.'&amp;resist='.urlencode(utf8_decode($resist)).'">'.$resist_value."</a>\n";
        }
    return $output;
     }

?>
