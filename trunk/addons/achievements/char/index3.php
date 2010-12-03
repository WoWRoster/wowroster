<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Achievement info
 *
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id: index3.php 490 2009-12-22 15:26:36Z Ulminia $
 * @link       http://ulminia.zenutech.com
 * @package    Achievements
 */
define("USE_CURL", TRUE);


/*

      end my localisaiation for xml....

*/




      function arraydisplay($array)
	{
	     echo '<pre>';
	     print_r($array);
	}

$pages = array(
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
		'8' => 'Isle of Conquest',
		),
	'168' => Array(
		'0' => 'Dungeons &amp; Raids',
            '1' => 'Classic',
            '2' => 'The Burning Crusade',
            '3' => 'Lich King Dungeon',
            '4' => 'Lich King Heroic',
            '5' => 'Lich King 10-Player Raid',
            '6' => 'Lich King 25-Player Raid',
            '7' => 'Secrets of Ulduar 10-Player Raid',
            '8' => 'Secrets of Ulduar 25-Player Raid',
            '9' => 'Call of the Crusade 10-Player Raid',
            '10' => 'Call of the Crusade 25-Player Raid',
            '11' => 'Call of the Crusade 10-Player Raid',
            '12' => 'Call of the Crusade 25-Player Raid',
            '13' => 'Fall of the Lich King 10-Player Raid',
            '14' => 'Fall of the Lich King 25-Player Raid',

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
		'3' => 'Noblegarden',
		'4' => 'Children\'s Week',
		'5' => 'Midsummer',
		'6' => 'Brewfest',
		'7' => 'Hallow\'s End',
		'8' => 'Pilgrim\'s Bounty',
		'9' => 'Winter Veil',
		'10' => 'Argent Tournament',
		),
	'81' => Array(
		'0' => 'Feats of Strength',
		),
);

require_once( ROSTER_LIB . 'simple.class.php' );
require_once(ROSTER_LIB . 'armory.class.php');
			$armory = new RosterArmory();




foreach ($pages as $cat => $title)
      {

            $r = $armory->fetchArmorya( $type = '12', $character ='Ulminia', $guild = false, $realm = 'Zangarmarsh', $item_id = $cat,$fetch_type = 'array' );//getArmoryDataXML($url);

            $g = 0;

            foreach ($r as $category )
            {

                 // echo '<hr><br>~~-  '.$title[0].'~~~<br>';
                  foreach ($category->achievement as $achievement)
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
                        $achv_complete = '';
                        $quantity = '';
                        $max = '';
                        $order = '';

                        //echo '~~----  '.$title[$g].'~~~<br>';
                        $achv_cat_sub = $title[$g];
                        $gh=1;
                        $temp = get_object_vars($achievement);
                        $achv_title = $temp['@attributes']['title'];//.' -<br>';
                        //--echo $temp['@attributes']['categoryId']v.' -<br>';// => 97
                        if (isset($temp['@attributes']['dateCompleted']))
                        {
                              $achv_date=$temp['@attributes']['dateCompleted'];//.' -<br>';// => 2009-01-16-07:00
                              $achv_complete = '1';
                        }
                        $achv_disc=$temp['@attributes']['desc'];//.' -<br>';// => Explore the regions of Northrend.
                        $achv_icon=$temp['@attributes']['icon'];//.' -<br>';// => achievement_zone_northrend_01
                        $achv_id=$temp['@attributes']['id'];//.' -<br>';// => 45

                  echo '$lang[\''.$achv_id.'title\'] = \''.addslashes($achv_title).'\';<br>';//echo "$lang['".$achv_id."title''] = '".$achv_disc."';<br>";
                              echo '$lang[\''.$achv_id.'disc\'] = \''.addslashes($achv_disc).'\';<br>';
                         if (isset($temp['@attributes']['points']))
                        {
                              $achv_points=$temp['@attributes']['points'];//.' -<br>';// => 25
                        }
                        if (isset($temp['@attributes']['reward']))
                        {
                              $achv_reward_title = $temp['@attributes']['reward'];//.' -<br><br>';// => Reward: Tabard of the Explorer
                        }
                        ////--echo $achievements->{'@attributes'}['title'].' -<br>';
                        //--echo 'Criteria listing<br>';
                        $datae = '';
                        foreach($achievement->criteria as $achievemen)
                        {

                              $temp2 = get_object_vars($achievemen);
                              if (isset($temp2['@attributes']['name']))
                              {
                                    $datae.= $temp2['@attributes']['name'].'<br>';//.' -<br>';
                              }
                              if (isset($temp2['@attributes']['quantity']))
                              {
                                    $quantity = $temp2['@attributes']['quantity'];//.'-<br>';
                              }
                              if (isset($temp2['@attributes']['maxQuantity']))
                              {
                                     $max = $temp2['@attributes']['maxQuantity'];//.'-<br>';
                              }
                              if ($max != '')
                              {
                                    $achv_progress_width = 'width:'.round( ( ( $quantity / $max )*100 ) ).'%';
                                    $achv_progress = ''.$quantity.' / '.$max.'';
                              }

                        }

                        //--echo 'Achievement Criteria <br />';

                        foreach($achievement->achievement as $achievemen)
                        {
                              $date = '';
                              $b1 = '';
                              $b2 = '';
                              $temp2 = get_object_vars($achievemen);

                              if (isset($temp2['@attributes']['dateCompleted']))
                              {
                        ////--echo '-------'.$temp2['@attributes']['date'].' -<br>';
                                    $date = '( ' . $temp2['@attributes']['dateCompleted'] . ' )';
                                    $b1 = '<b><span style="color:#7eff00;">';
                                    $b2 = '</span></b>';
                              }
                              else
                              {
                                    $date = '';
                                    $b1 = '';
                                    $b2 = '';
                              }
                              if (isset($temp2['@attributes']['icon']))
                              {
                                    $datae.= '<img src="img/Interface/Icons/' .$temp2['@attributes']['icon'] . '.png" width="24" height="24"> ';
                              }
                              if (isset($temp2['@attributes']['title']))
                              {
                                    $datae.= $b1.$temp2['@attributes']['title'].$b2.' ' . $date . ' ';
                              }

                              if (isset($temp2['@attributes']['quantity']))
                              {
                                    $quantity = $temp2['@attributes']['quantity'];//.'-<br>';
                              }
                              if (isset($temp2['@attributes']['maxQuantity']))
                              {
                                     $max = $temp2['@attributes']['maxQuantity'];//.'-<br>';
                              }
                              if ($max != '')
                              {
                                    $achv_progress_width = 'width:'.round( ( ( $quantity / $max )*100 ) ).'%';
                                    $achv_progress = ''.$quantity.' / '.$max.'';
                              }
                              if (isset($temp2['@attributes']['points']))
                              {
                                    $datae.= ' Points'.$temp2['@attributes']['points'].'<br>';
                              }

                        }

                        $achv_criteria = $datae;


                        /*
                        //$this->achnum++;

                        if ($achv_title != '' && $achv_disc != '')
                        {
                              $sql = "INSERT INTO `" . $roster->db->table('data',$this->data['basename']) . "`
                                    (`id`,`member_id`,`guild_id`,`achv_cat`,`achv_cat_title`,`achv_cat_sub`,`achv_cat_sub2`,
                                    `achv_id`,`achv_points`,`achv_icon`,`achv_title`,`achv_reward_title`,`achv_disc`,`achv_date`,
                                    `achv_criteria`,`achv_progress`,`achv_progress_width`,`achv_complete`)
                                    VALUES
                                    (null,'".$memberid."','".$roster->data['guild_id']."','".$cat."','".addslashes($title['0'])."',
                                    '".addslashes($title['0'])."','".$this->order."','".$achv_id."','".$achv_points."',
                                    '".addslashes($achv_icon)."','".$achv_id."title','".addslashes($achv_reward_title)."','".$achv_id."disc',
                                    '".addslashes($achv_date)."','".addslashes($achv_criteria)."','".$achv_progress."','".$achv_progress_width."','".$achv_complete."');";
                              //$result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sql);
                        }

                        $this->order++;
                        */
                  }

                  foreach($category->category as $f => $achievements)
                  {
                        $g++;

                        //aprint($category);
                        $achv_cat_sub = $title[$g];
                        echo "// ".$achv_cat_sub." - ".$f." - ".$category."<br>";

                        foreach ($achievements as $achiev)
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
                              $quantity = '';
                              $max = '';
                              $order = '';
                              $achv_complete = '';


                              $temp = get_object_vars($achiev);
                              $achv_title = $temp['@attributes']['title'];//.' -<br>';
                              //--echo $temp['@attributes']['categoryId']v.' -<br>';// => 97
                              if (isset($temp['@attributes']['dateCompleted']))
                              {
                                    $achv_date=$temp['@attributes']['dateCompleted'];//.' -<br>';// => 2009-01-16-07:00
                                  //  $achv_complete = '1';
                              }
                              $achv_disc=$temp['@attributes']['desc'];//.' -<br>';// => Explore the regions of Northrend.
                              $achv_icon=$temp['@attributes']['icon'];//.' -<br>';// => achievement_zone_northrend_01
                              $achv_id=$temp['@attributes']['id'];//.' -<br>';// => 45
                              $achv_points=$temp['@attributes']['points'];//.' -<br>';// => 25

                  echo '$lang[\''.$achv_id.'title\'] = \''.addslashes($achv_title).'\';<br>';//echo "$lang['".$achv_id."title''] = '".$achv_disc."';<br>";
                              echo '$lang[\''.$achv_id.'disc\'] = \''.addslashes($achv_disc).'\';<br>';
                              if (isset($temp['@attributes']['reward']))
                              {
                                    $achv_reward_title = $temp['@attributes']['reward'];//.' -<br><br>';// => Reward: Tabard of the Explorer
                              }
                              ////--echo $achievements->{'@attributes'}['title'].' -<br>';
                              //--echo 'Criteria listing<br>';
                              $datae = '';
                              foreach($achiev->criteria as $achievemen)
                              {

                                    $temp2 = get_object_vars($achievemen);
                                    if (isset($temp2['@attributes']['name']))
                                    {
                                          $datae.= $temp2['@attributes']['name'].'<br>';//.' -<br>';
                                    }
                                    if (isset($temp2['@attributes']['quantity']))
                                    {
                                          $quantity = $temp2['@attributes']['quantity'];//.'-<br>';
                                    }
                                    if (isset($temp2['@attributes']['maxQuantity']))
                                    {
                                          $max = $temp2['@attributes']['maxQuantity'];//.'-<br>';
                                    }
                                    if ($max != '')
                                    {
                                          $achv_progress_width = 'width:'.round( ( ( $quantity / $max )*100 ) ).'%';
                                          $achv_progress = ''.$quantity.' / '.$max.'';
                                    }

                              }

                        //--echo 'Achievement Criteria <br />';

                              foreach($achiev->achievement as $achievemen)
                              {
                                    $date = '';
                                    $b1 = '';
                                    $b2 = '';
                                    $temp2 = get_object_vars($achievemen);

                                    if (isset($temp2['@attributes']['dateCompleted']))
                                    {
                        ////--echo '-------'.$temp2['@attributes']['date'].' -<br>';
                                          $date = '( ' . $temp2['@attributes']['dateCompleted'] . ' )';
                                          $b1 = '<b><span style="color:#7eff00;">';
                                          $b2 = '</span></b>';
                                    }
                                    else
                                    {
                                          $date = '';
                                          $b1 = '';
                                          $b2 = '';
                                    }
                                    if (isset($temp2['@attributes']['icon']))
                                    {
                                          $datae.= '<img src="img/Interface/Icons/' .$temp2['@attributes']['icon'] . '.png" width="24" height="24"> ';
                                    }
                                    if (isset($temp2['@attributes']['title']))
                                    {
                                          $datae.= $b1.$temp2['@attributes']['title'].$b2.' ' . $date . ' ';
                                    }

                                    if (isset($temp2['@attributes']['quantity']))
                                    {
                                          $quantity = $temp2['@attributes']['quantity'];//.'-<br>';
                                    }
                                    if (isset($temp2['@attributes']['maxQuantity']))
                                    {
                                          $max = $temp2['@attributes']['maxQuantity'];//.'-<br>';
                                    }
                                    if ($max != '')
                                    {
                                          $achv_progress_width = 'width:'.round( ( ( $quantity / $max )*100 ) ).'%';
                                          $achv_progress = ''.$quantity.' / '.$max.'';
                                    }
                                    if (isset($temp2['@attributes']['points']))
                                    {
                                          $datae.= ' Points'.$temp2['@attributes']['points'].'<br>';
                                    }

                              }

                              $achv_criteria = $datae;
                              /*
                              $this->achnum++;

                              if ($achv_title != '' && $achv_disc != '')
                              {
                                    $sql = "INSERT INTO `" . $roster->db->table('data',$this->data['basename']) . "`
                                    (`id`,`member_id`,`guild_id`,`achv_cat`,`achv_cat_title`,`achv_cat_sub`,`achv_cat_sub2`,
                                    `achv_id`,`achv_points`,`achv_icon`,`achv_title`,`achv_reward_title`,`achv_disc`,`achv_date`,
                                    `achv_criteria`,`achv_progress`,`achv_progress_width`,`achv_complete`)
                                    VALUES
                                    (null,'".$memberid."','".$roster->data['guild_id']."','".$cat."','".addslashes($title['0'])."',
                                    '".addslashes($achv_cat_sub)."','".$this->order."','".$achv_id."','".$achv_points."',
                                    '".addslashes($achv_icon)."','".$achv_id."title','".addslashes($achv_reward_title)."','".$achv_id."disc',
                                    '".addslashes($achv_date)."','".addslashes($achv_criteria)."','".$achv_progress."','".$achv_progress_width."','".$achv_complete."');";

                                    //$result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sql);
                              } */
                        }
                  }
            }
            //end pages
      }


/**
     * helper function to get classes for content
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _parseData ( $array = array() ) {
        $datas = array();
        _makeSimpleClass( $array );
        //$this->_debug( 3, true, 'Parsed XML data', 'OK' );
        return $this->datas[0];
        //$this->_debug( 3, '', 'Parsed XML data', 'OK' );
    }

    function _makeSimpleClass ( $array = array() ) {

        $tags = array_keys( $array );
        foreach ( $array as $tag => $content ) {
            foreach ( $content as $leave ) {
                _initClass( $tag, $leave['attribs'] );
                $datas[count($datas)-1]->setProp("_CDATA", $leave['data']);
                if ( array_keys($leave['child']) ) {
                    _makeSimpleClass( $leave['child'] );
                }
                _finalClass( $tag );
            }
        }
        //$this->_debug( 3, '', 'Made simple class', 'OK' );
    }

    /**
     * helper function initialise a simpleClass Object
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _initClass( $tag, $attribs = array() ) {
        $node = new SimpleClass();
        $node->setArray($attribs);
        $node->setProp("_TAGNAME", $tag);
        $datas[] = $node;
        //$this->_debug( 3, '', 'Initialized simple class', 'OK' );
    }


    /**
     * helper function finalize a simpleClass Object
     *
     * @param string $class
     * @param string $tree
     * @return string
     */
    function _finalClass( $tag, $val = array() ) {
        if (count($datas) > 1) {
            $child = array_pop($this->datas);

            if (count($datas) > 0) {
                $parent = &$datas[count($datas)-1];
                $tag = $child->_TAGNAME;

                if ($parent->hasProp($tag)) {
                    if (is_array($parent->$tag)) {
                        //Add to children array
                        $array = &$parent->$tag;
                        $array[] = $child;
                    } else {
                        //Convert node to an array
                        $children = array();
                        $children[] = $parent->$tag;
                        $children[] = $child;
                        $parent->$tag = $children;
                    }
                } else {
                    $parent->setProp($tag, $child);
                }
            }
        }
        //$this->_debug( 3, '', 'Finalized simple class', 'OK' );
    }
    function setProp($propName, $propValue) {
		$this->$propName = $propValue;
		if (!in_array($propName, $this->properties)) {
			$this->properties[] = $propName;
		}
  }

	function setArray($array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$this->setProp($key, $value);
			}
		}
	}

	function hasProp($propName) {
		return in_array($propName, $this->properties);
	}
