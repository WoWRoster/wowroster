<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays Raid Progresion info
 *
 *

 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://ulminia.zenutech.com
 * @package    Achievements
*/

class achievementsUpdate
{
	var $messages = '';			// Update messages
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
			'8' => 'Isle of Conquest',
			),
		'168' => Array(
			'0' => 'Dungeons & Raids',
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

	var $order = '0';

	function achievementsUpdate( $data )
	{
		$this->data = $data;
		$this->baseurl();
	}

	/**
	 * Standalone Update Hook
	 *
	 * @return bool
	 */
	function baseurl()
	{
		global $roster, $addon;

		$r = split("en", $roster->config['locale']);
//		aprint($roster->data);

		if( $r[1] == 'US' )
		{
//			$base_url = 'http://localhost:18080/?url=http://www.wowarmory.com/';
			$this->base_url = 'http://www.wowarmory.com/';
		}
		else
		{
//			$base_url = 'http://localhost:18080/?url=http://eu.wowarmory.com/';
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
		global $roster, $update, $addon;

//		aprint($char);
//		aprint($roster);

		$achi = $char['Achievements'];
		$this->achnum = 0;

//		aprint($achi);

		$sql = "DELETE FROM `" . $roster->db->table('data', $this->data['basename']) . "` WHERE `member_id` = '" . $memberid . "';";
		if( !$roster->db->query($sql) )
		{
			$update->setError('Achievement Data for member id: ' . $memberid . ' could not be cleared',$roster->db->error());
			return false;
		}

		$sql = "DELETE FROM `" . $roster->db->table('summary', $this->data['basename']) . "` WHERE `member_id` = '" . $memberid . "';";
		if( !$roster->db->query($sql) )
		{
			$update->setError('Achievement Summary for member id: ' . $memberid . ' could not be cleared',$roster->db->error());
			return false;
		}

		foreach($achi as $sub_cat => $data)
		{
			//echo $sub_cat.'-'.$data.'--<br>';
			if ($sub_cat == '-1')
			{
				foreach($data as $cat => $achie)
				{
					//echo '-'.$cat.',<br>';
					foreach($achie as $info => $in)
					{
						//echo '--'.$info.'-'.$in['Name'].'<br>';
						
						if ($info != 'id' && $info != 'ParentId')//is_array($achiev))
						{
							$achv_points        = '';  //$infos['Points'];
							$achv_icon          = '';  //$infos['Icon'];
							$achv_title         = '';  //$infos['Name'];
							$achv_disc          = '';  //$infos['Description'];
							$achv_date          = '';  //$infos['Dcomplete'];
							$achv_id            = '';  //$infos['Id'];
							$achv_reward_title  = '';  //$infos['Rewardtext'];

							$achv_points        = $in['Points'];
							$achv_icon          = $in['Icon'];
							$achv_title         = $in['Name'];
							$achv_disc          = $in['Description'];

							if (isset($in['Dcomplete']))
							{
								$achv_date = $in['Dcomplete'];
							}
							else
							{
								$achv_date = '';
							}

							$achv_id = $in['Id'];
							$achv_reward_title = $in['Rewardtext'];

							if ($in['completed'])
							{
								$achiv_complete = '1';
							}
							else
							{
								$achiv_complete = '0';
							}

							$this->achnum++;

							if ($achv_title != '' && $achv_disc != '')
							{
								$build_query = array(
									'member_id'         => $memberid,
									'guild_id'          => $roster->data['guild_id'],
									'achv_cat'          => $achie['id'],//addslashes($cat),
									'achv_cat_title'    => addslashes($cat),
									'achv_cat_sub'      => addslashes($cat),
									'achv_cat_sub2'     => $this->order,
									'achv_id'           => $achv_id,
									'achv_points'       => $achv_points,
									'achv_icon'         => addslashes($achv_icon),
									'achv_title'        => addslashes($achv_title),
									'achv_reward_title' => addslashes($achv_reward_title),
									'achv_disc'         => addslashes($achv_disc),
									'achv_date'         => addslashes($achv_date),
									'achv_complete'     => $achiv_complete
								);

								$sql = 'INSERT INTO `' . $roster->db->table('data', $this->data['basename']) . '` ' . $roster->db->build_query('INSERT', $build_query) . ';';

//								echo $sql . '<br/><hr/><br/>';

								$result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sql);
								if( !$result )
								{
									$update->setError('Achievements not updated for member id: ' . $memberid,$roster->db->error());
									return false;
								}
							}
							$this->order++;
						}
					}
				
				}
			}
			
			
			if ($sub_cat != '-1')
			{
			//echo $sub_cat.'<br>';
				if (isset($this->pages[$sub_cat]['0']))
				{
					$main = $this->pages[$sub_cat]['0'];
				}
				else
				{
					$main = $data['id'];
				}

				//echo $sub_cat . ' (' . $main . ')<br/>';

				foreach($data as $achiev => $infos)
				{
					//echo '++' . $sub_cat . ' - ' . $achiev . '<br/>';

					foreach($infos as $info => $in)
					{
						if ($info != 'id' && $info != 'ParentId')//is_array($achiev))
						{
							$achv_points        = '';  //$infos['Points'];
							$achv_icon          = '';  //$infos['Icon'];
							$achv_title         = '';  //$infos['Name'];
							$achv_disc          = '';  //$infos['Description'];
							$achv_date          = '';  //$infos['Dcomplete'];
							$achv_id            = '';  //$infos['Id'];
							$achv_reward_title  = '';  //$infos['Rewardtext'];

							$achv_points        = $in['Points'];
							$achv_icon          = $in['Icon'];
							$achv_title         = $in['Name'];
							$achv_disc          = $in['Description'];

							if (isset($in['Dcomplete']))
							{
								$achv_date = $in['Dcomplete'];
							}
							else
							{
								$achv_date = '';
							}

							$achv_id = $in['Id'];
							$achv_reward_title = $in['Rewardtext'];

							if ($in['completed'])
							{
								$achiv_complete = '1';
							}
							else
							{
								$achiv_complete = '0';
							}

							$this->achnum++;

							if ($achv_title != '' && $achv_disc != '')
							{
								$build_query = array(
								'member_id'         => $memberid,
								'guild_id'          => $roster->data['guild_id'],
								'achv_cat'          => $sub_cat,
								'achv_cat_title'    => addslashes($main),
								'achv_cat_sub'      => addslashes($achiev),
								'achv_cat_sub2'     => $this->order,
								'achv_id'           => $achv_id,
								'achv_points'       => $achv_points,
								'achv_icon'         => addslashes($achv_icon),
								'achv_title'        => addslashes($achv_title),
								'achv_reward_title' => addslashes($achv_reward_title),
								'achv_disc'         => addslashes($achv_disc),
								'achv_date'         => addslashes($achv_date),
								'achv_complete'     => $achiv_complete
								);

								$sql = 'INSERT INTO `' . $roster->db->table('data', $this->data['basename']) . '` ' . $roster->db->build_query('INSERT', $build_query) . ';';

//								echo $sql . '<br/><hr/><br/>';

								$result = $roster->db->query($sql) or die_quietly($roster->db->error(),'Database Error',basename(__FILE__),__LINE__,$sql);
								if( !$result )
								{
									$update->setError('Achievements not updated for member id: ' . $memberid,$roster->db->error());
									return false;
								}
							}
							$this->order++;
						}
					}
				}
			}
		}

		$this->messages .= '<li>Updating Achievements: ';
		$this->messages .= $this->achnum.'</li>';

		return true;
	}

	function reset_messages()
	{
		$this->messages = '';
	}

	/**
	 * helper function to get classes for content
	 *
	 * @param string $class
	 * @param string $tree
	 * @return string
	 */
	function _parseData ( $array = array() )
	{
		$this->datas = array();
		$this->_makeSimpleClass( $array );
		$this->_debug( 3, true, 'Parsed XML data', 'OK' );

		return $this->datas[0];

		$this->_debug( 3, '', 'Parsed XML data', 'OK' );
	}

	function _makeSimpleClass ( $array = array() )
	{
		$tags = array_keys( $array );
		foreach ( $array as $tag => $content )
		{
			foreach ( $content as $leave )
			{
				$this->_initClass( $tag, $leave['attribs'] );
				$this->datas[count($this->datas)-1]->setProp("_CDATA", $leave['data']);

				if ( array_keys($leave['child']) )
				{
					$this->_makeSimpleClass( $leave['child'] );
				}
				$this->_finalClass( $tag );
			}
		}
		$this->_debug( 3, '', 'Made simple class', 'OK' );
	}

	/**
	 * helper function initialise a simpleClass Object
	 *
	 * @param string $class
	 * @param string $tree
	 * @return string
	 */
	function _initClass( $tag, $attribs = array() )
	{
		$node = new SimpleClass();
		$node->setArray($attribs);
		$node->setProp("_TAGNAME", $tag);
		$this->datas[] = $node;
		$this->_debug( 3, '', 'Initialized simple class', 'OK' );
	}

	/**
	 * helper function finalize a simpleClass Object
	 *
	 * @param string $class
	 * @param string $tree
	 * @return string
	 */
	function _finalClass( $tag, $val = array() )
	{
		if (count($this->datas) > 1)
		{
			$child = array_pop($this->datas);

			if (count($this->datas) > 0)
			{
				$parent = &$this->datas[count($this->datas)-1];
				$tag = $child->_TAGNAME;

				if ($parent->hasProp($tag))
				{
					if (is_array($parent->$tag))
					{
						//Add to children array
						$array = &$parent->$tag;
						$array[] = $child;
					}
					else
					{
						//Convert node to an array
						$children = array();
						$children[] = $parent->$tag;
						$children[] = $child;
						$parent->$tag = $children;
					}
				}
				else
				{
					$parent->setProp($tag, $child);
				}
			}
		}
		$this->_debug( 3, '', 'Finalized simple class', 'OK' );
	}
}
