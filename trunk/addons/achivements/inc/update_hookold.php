<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Raid Progresion info
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *

 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: update_hookold.php 477 2009-11-13 07:03:44Z Ulminia $
 * @link       http://ulminia.zenutech.com
 * @package    Achivements
*/

class achivementsUpdate
{

var $messages = '';		// Update messages
	var $data = array();		// Addon config data automatically pulled from the addon_config table
	var $files = array();
	var $evid = '';
	var $evt = '0';
	var $debuging_flag = '0';
	var $a = Array();
	var $cfg = array();
	var $achnum = '';
	var $armory;
	var $base_url;
	var $snoopy;
      
      
      
var $pages = array(
	'92' => Array(
		'0' => 'General',
		),
	'96' => Array(
		'0' => 'Quests',
		'1' => 'Classic',
		'2' => 'The Burning Crusade',
		'3' => 'Wrath of the Lich King',
		),
	'97' => Array(
		'0' => 'Exploration',
		'1' => 'Eastern Kingdoms',
		'2' => 'Kalimdor',
		'3' => 'Outland',
		'4' => 'Northrend',
		),
	'95' => Array(
		'0' => 'Player vs. Player',
		'1' => 'Arena',
		'2' => 'Alterac Valley',
		'3' => 'Arathi Basin',
		'4' => 'Eye of the Storm',
		'5' => 'Warsong Gulch',
		'6' => 'Strand of the Ancients',
		'7' => 'Wintergrasp',
		),
	'168' => Array(
		'0' => 'Dungeons &amp; Raids',
		'1' => 'Classic',
		'2' => 'The Burning Crusade',
		'3' => 'Lich King Dungeon',
		'4' => 'Lich King Heroic',
		'5' => 'Lich King Raid',
		'6' => 'Lich King Heroic Raid',
		),
	'169' => Array(
		'0' => 'Professions',
		'1' => 'Cooking',
		'2' => 'Fishing',
		'3' => 'First Aid',
		),
	'201' => Array(
		'0' => 'Reputation',
		'1' => 'Classic',
		'2' => 'The Burning Crusade',
		'3' => 'Wrath of the Lich King',
		),
	'155' => Array(
		'0' => 'World Events',
		'1' => 'Lunar Festival',
		'2' => 'Love is in the Air',
		'3' => 'Children\'s Week',
		'4' => 'Noble Garden',
		'5' => 'Midsummer',
		'6' => 'Brewfest',
		'7' => 'Hallow\'s End',
		'8' => 'Winter Veil',
		),
	'81' => Array(
		'0' => 'Feats of Strength',
		),
);

      var $order = '0';
      
	function achivementsUpdate( $data )
	{
		$this->data = $data;
                       
            $this->baseurl();
	}

	/**
	 * Standalone Update Hook
	 *
	 * @return bool
	 */
      function baseurl(){
      global $roster, $addon;
      
      $r =split("en", $roster->config['locale']);
      //print_r($r);
	 
	 
	 if( $r[1] == 'US' )
		{
			//$base_url = 'http://localhost:18080/?url=http://www.wowarmory.com/';
			$this->base_url = 'http://www.wowarmory.com/';
		}
		else
		{
			//$base_url = 'http://localhost:18080/?url=http://eu.wowarmory.com/';
			$this->base_url = 'http://eu.wowarmory.com/';
		}
	}

	/**
	 * Standalone Update Hook
	 *
	 * @return bool
	 */
	function update( )
	{
		global $roster;
		return true;
	}

	function char($char, $memberid) 
	{
	     //print_r($this->data);
		global $roster, $addon;
            $this->messages .='<li>Updating Achievements: ';
            $sql = '';
            $sqll = "DELETE FROM `" . $roster->db->table('data',$this->data['basename']) . "` Where `member_id` = '".$memberid."';";
            $resultl = $roster->db->query($sqll) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sqll);
            $sqlll = "DELETE FROM `" . $roster->db->table('summary',$this->data['basename']) . "` Where `member_id` = '".$memberid."';";
            $resultll = $roster->db->query($sqlll) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sqlll);
                
      
                        
            foreach ($this->pages as $cat => $title)
            {
                  $url = $x = $xy = '';
                  $url = $this->base_url.'character-achievements.xml?r='.$char['Server'].'&n='.$char['Name'].'&c='.$cat.'';
                  require_once( ROSTER_LIB . 'armory.class.php');
                  $this->armory = new RosterArmory;
                  
                  //echo $url.' - '.$cat.' - '.$title['0'].'<br>';
                  $xy = urlgrabber($url, '25', 'Opera/9.22 (X11; Linux i686; U; en)');
                  
                  $x = $this->armory->fetchArmoryachive( $url, $char['Name'], $char['Guild']['Name'], $char['Server'], $item_id = false,'array' );

      	    
                  foreach($x['div']['0']['child']['div'] as $g => $h)
                  {
                  $this->order++;
                        $achv_cat_sub = $title[$g];
                        foreach($h['child']['div'] as $a => $b)
                        {

                              $achv_points='';
                              $achv_icon='';
                              $achv_title='';
                              $achv_disc='';
                              $achv_date='';
                              $achv_title='';
                              $achv_id='';
                              $achv_reward_title = '';
                              $achv_criteria = '';
                              $achv_progress = '';
                              $achv_progress_width = '';
                              $order = '';
                              $a = '';

                              if (isset($b['child']['div']['0']['child']['div']['0']['data']))
                              {
                                    $achv_points = $b['child']['div']['0']['child']['div']['0']['data'];
                              }
                              else
                              {
                                    $achv_points = '';
                              }
                  
                              if (isset($b['child']['div']['1']['attribs']['style']))
                              {
                                    $f = str_replace('background-image:url("/wow-icons/_images/51x51/' , '', $b['child']['div']['1']['attribs']['style']);
                                    $f = str_replace('.jpg")','',$f);
                                    $achv_icon = $f;
                              }
                              else
                              {                                         
                                    $achv_icon = '';
                              }
                              
                              if (isset($b['child']['ul']['0']['child']['div']['0']['child']['div']['0']['child']['div']['2']['child']['div']['1']['data']))
                              {
                                    $achv_progress = $b['child']['ul']['0']['child']['div']['0']['child']['div']['0']['child']['div']['2']['child']['div']['1']['data'];
                              }
                              else
                              {                                         
                                    $achv_progress = '';
                              }
                              
                              if (isset($b['child']['ul']['0']['child']['div']['0']['child']['div']['0']['child']['div']['2']['child']['div']['0']['attribs']['style']))
                              {
                                    $achv_progress_width = $b['child']['ul']['0']['child']['div']['0']['child']['div']['0']['child']['div']['2']['child']['div']['0']['attribs']['style'];
                              }
                              else
                              {                                         
                                    $achv_progress_width = '';
                              }
                              //echo '<pre>';
                                    //print_r($b['child']['div']['0']);
                                    if ($cat == '81' && $achv_icon == '' && isset($b['child']['div']['0']['attribs']['style']))
                                    {
                                          $f = str_replace('background-image:url("/wow-icons/_images/51x51/' , '', $b['child']['div']['0']['attribs']['style']);
                                          $f = str_replace('.jpg")','',$f);
                                          $achv_icon = $f;
                                    }
                                    else
                                    {
                                          //$achv_icon = '';
                                    }
                              
                  
                  
	           
                              foreach ($b['child']['div'] as $ac => $ar)
                              {
                              
                                    if (isset($ar['data']))
                                    {
                                          if ($ar['attribs']['class'] == 'achv_title')
                                          {
                                                $achv_title = $ar['data'];
                                          }
                                    }
                                    else
                                    {
                                          $achv_title = '';
                                    }
                  
                  
                                    if (isset($ar['data']))
                                    {
                                          if ($ar['attribs']['class'] == 'achv_desc')
                                          {
                                                $achv_disc = $ar['data'];
                                          }
                                    }
                                    else
                                    {
                                          $achv_disc = '';
                                    }
                  
                  
                                    if (isset($ar['data']))
                                    {
                                    $achv_date = '';
                                          if ($ar['attribs']['class'] == 'achv_reward_bg')
                                          {
                                                $achv_reward_title = $ar['data'];
                                          }
                                          if ($ar['attribs']['class'] == 'achv_date')
                                          {
                                                if (isset($ar['data'])){
                                                list($month,$day,$year ) = explode("-", $ar['data'], 3);
                                                $achv_date = $year.'-'.$month.'-'.$day;
                                                }
                                                else
                                                {
                                                $achv_date = $ar['data'];
                                                }
                                          }
                                    }
                                    else
                                    {
                                          $achv_date = '';
                                    }
                  
                              }

                  
                              if (isset($b['attribs']['class']))
                              {	
                                    $achv_complete = $b['attribs']['class'];
                              }
                              else
                              {
                                    $achv_complete = '';
                              }
                  
                              if (isset($b['attribs']['id']))
                              {	
                                    $achv_id = $b['attribs']['id'];
                              }
                              else
                              {
                                    $achv_id = '';
                              }

                              if (isset($b['child']['ul']['0']['child']['li']))
                              {
                                    if (is_array($b['child']['ul']['0']['child']['li']))
                                    {
                                          foreach ($b['child']['ul']['0']['child']['li'] as $e => $f)
                                          {
                                                $cd = '';
                                                if ($f['attribs']['class'] == 'c_list_col criteriamet')
                                                { 
                                                      $cd = '( Completed )'; 
                                                }
                                                $achv_criteria .= $f['data'].' '.$cd.'<br>';
                                                 $cd = '';
                                          }
                                    }
                                    else
                                    {
                                          $achv_criteria .= $b['child']['ul']['0']['child']['div']['0']['child']['div']['0']['child']['div']['2']['child']['div']['1']['data'];
                                    }
                              }
                              $this->achnum++;

                    if ($achv_title != '' && $achv_disc != ''){
                    $sql = "INSERT INTO `" . $roster->db->table('data',$this->data['basename']) . "` 
                    (`id`,`member_id`,`guild_id`,`achv_cat`,`achv_cat_title`,`achv_cat_sub`,`achv_cat_sub2`,
                    `achv_id`,`achv_points`,`achv_icon`,`achv_title`,`achv_reward_title`,`achv_disc`,`achv_date`,
                    `achv_criteria`,`achv_progress`,`achv_progress_width`,`achv_complete`) 
                    VALUES 
                    (null,'".$memberid."','".$this->data['guild_id']."','".$cat."','".$title['0']."',
                        '".addslashes($achv_cat_sub)."','".$this->order."','".$achv_id."','".$achv_points."',
                        '".addslashes($achv_icon)."','".$achv_id."title','".addslashes($achv_reward_title)."','".$achv_id."disc',
                        '".addslashes($achv_date)."','".addslashes($achv_criteria)."','".$achv_progress."','".$achv_progress_width."','".$achv_complete."');";
                    $result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sql);

                  }
                        }
                  }
            }
            // now get summery data
            
            $url = $this->base_url.'character-achievements.xml?r='.$char['Server'].'&n='.$char['Name'].'';
            include('addons/'.$this->data['basename'].'/inc/Snoopy.class.php');
            $this->snoopy = new Snoopy;
            
            if($this->snoopy->fetch($url))
                  $text = ($this->snoopy->results);
            preg_match_all('|<div class="null_progress">(.+?)</div>|', $text, $match2);
            $feats = $match2[1][0];
            preg_match_all('|<div class="prog_int_text">(.+?)</div>|', $text, $match);
            ///echo '<pre>';
           // print_r($match2);

            $total  = $match[1][0];
            $general = $match[1][1];      $quests = $match[1][2];
            $exploration = $match[1][3];  $pvp = $match[1][4];
            $dn_raids = $match[1][5];     $prof = $match[1][6];
            $rep = $match[1][7];          $world_events = $match[1][8];
            
            preg_match_all('/\(?[0-9]{2}[-. ]?[0-9]{2}[-. ]?[0-9]{4}\)/', $text, $date);
            preg_match_all('|class="s_ach_stat">(?<points>\d+)|', $text, $point);
            preg_match_all('|<span>(.+?)</span><span class="achv_desc">(.+?)</span>|', $text, $disc);

            
            $sql7 = "INSERT INTO `" . $roster->db->table('summary',$this->data['basename']) . "` 
                  (`id`,`member_id`,`guild_id`,`total`,
                    `general`,`quests`,`exploration`,`pvp`,`dn_raids`,`prof`,`rep`,`world_events`,`feats`,
                    `title_1`,`disc_1`,`date_1`,`points_1`,`title_2`,`disc_2`,`date_2`,`points_2`,
                    `title_3`,`disc_3`,`date_3`,`points_3`,`title_4`,`disc_4`,`date_4`,`points_4`,
                    `title_5`,`disc_5`,`date_5`,`points_5`
                    ) 
                    VALUES 
                    (null,'".$memberid."','".$this->data['guild_id']."','".$total."','".$general."','".$quests."',".
                    "'".$exploration."','".$pvp."','".$dn_raids."','".$prof."','".$rep."','".$world_events."','".$feats."',".
                    "'".addslashes($disc[1][0])."','".addslashes($disc[2][0])."','".$date[0][0]."','".$point[1][0]."',".
                    "'".addslashes($disc[1][1])."','".addslashes($disc[2][1])."','".$date[0][1]."','".$point[1][1]."',".
                    "'".addslashes($disc[1][2])."','".addslashes($disc[2][2])."','".$date[0][2]."','".$point[1][2]."',".
                    "'".addslashes($disc[1][3])."','".addslashes($disc[2][3])."','".$date[0][3]."','".$point[1][3]."',".
                    "'".addslashes($disc[1][4])."','".addslashes($disc[2][4])."','".$date[0][4]."','".$point[1][4]."');";
                    $result7 = $roster->db->query($sql7) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sql7);
            $this->messages .=$this->achnum.'</li>';
            return true;
      }
	
	function reset_messages()
	{
		$this->messages = '';

	}
	
	function reset_values()
	{
		$this->assignstr = '';
	}
	     
	function setError($message,$error)
	{
		$this->errors[] = array($message=>$error);
	}     

}

