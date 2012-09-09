<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster upload rule config
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
 */

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
	exit('Detected invalid access to this file!');
}

if (isset($_POST['process']) && $_POST['process'] == 'process')
{
	$count=1;
	if (isset($_POST['class_id']))
	{
		//	aprint($_POST);
		$classid = (isset($_POST['class_id']) ? $_POST['class_id'] : $_GET['class']);
		echo '<br>--[ '.$classid.' ]--<br>';
		$talents = $roster->api->Talents->getTalentInfo($classid);
		
		$querystr = "DELETE FROM `" . $roster->db->table('talents_data') . "` WHERE `class_id` = '" . $classid . "';";
		if (!$roster->db->query($querystr))
		{
			$roster->set_message('Talent Data Table could not be emptied.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			return;
		}

		//talent_mastery
		/*
		$querystr = "DELETE FROM `" . $roster->db->table('talent_mastery') . "` WHERE `class_id` = '" . $classid . "';";
		if (!$roster->db->query($querystr))
		{
			$roster->set_message('Talent Tree Data Table could not be emptied.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			return;
		}
		*/
		$treenum = 1;
		$t=1;
		foreach ($talents['talentData']['talentTrees'] as $a => $treedata)
		{

			$lvl = 15;
			foreach ($treedata as $t => $talent)
			{

				$tooltip = '';
				$tooltip .= (isset($talent['spell']['cost']) ? $talent['spell']['cost'] : '');
				$tooltip .=	(isset($talent['spell']['range']) ? '<span style="float:right;">'.$talent['spell']['range'].'</span>' : '');
				$tooltip .=	(isset($talent['spell']['castTime']) ? '<br>'.$talent['spell']['castTime'] : '');
				$tooltip .=	(isset($talent['spell']['cooldown']) ? '<span style="float:right;">'.$talent['spell']['cooldown'].'</span>' : '');
				$tooltip .= '<br>'.$talent['spell']['htmlDescription'];
				
				$values = array(
					'talent_id'  => $talent['spell']['spellId'],
					'talent_num' => $t,
					'tree_order' => '0',
					'class_id'   => $talent['classKey'],
					'name'       => $talent['spell']['name'],
					'tree'       => '',//$treedata['name'],
					'tooltip'    => $tooltip,
					'texture'    => $talent['spell']['icon'],
					'isspell'	 => ( !$talent['spell']['keyAbility'] ? false : true ),
					'row'        => ($talent['tier'] + 1),
					'column'     => ($talent['column'] + 1),
					'rank'       => $lvl
				);

				
				$querystr = "INSERT INTO `" . $roster->db->table('talents_data') . "` "
					. $roster->db->build_query('INSERT', $values) . ";";
				$result = $roster->db->query($querystr);
				$count++;
			$t++;	
			}
			$lvl = ($lvl+15);
			/*
			$role = '';
			foreach ($treedata['roles'] as $name => $h)
			{
				if ($h == 1)
				{
					$role = $name;
				}
			}
			$values = array(
				'tree'       => $treedata['name'],
				'order'      => $treenum,
				'class_id'   => $classid,
				'background' => strtolower($treedata['backgroundFile']),
				'icon'       => $treedata['icon'],
				'roles'		 => $role,
				'desc'		 => $treedata['description'],
				'tree_num'   => $treenum
			);
			$masterys = array(
				'class_id'	=>	$classid,
				'tree'		=>	$treedata['name'],
				'tree_num'	=>	$treenum,
				'icon'		=>	$treedata['masteries'][0]['icon'],
				'name'		=>	$treedata['masteries'][0]['name'],
				'desc'		=>	$treedata['masteries'][0]['description'],
				'spell_id'	=>	$treedata['masteries'][0]['spellId']
			);

			$mquerystr = "INSERT INTO `" . $roster->db->table('talent_mastery') . "` "
				. $roster->db->build_query('INSERT', $masterys) . "
				;";
			$mresult = $roster->db->query($mquerystr);
				
			$querystr = "INSERT INTO `" . $roster->db->table('talenttree_data') . "` "
				. $roster->db->build_query('INSERT', $values) . "
				;";
				$result = $roster->db->query($querystr);
			*/
			$count++;
			$treenum++;
		}


		$roster->set_message(sprintf($roster->locale->act['adata_update_class'], $roster->locale->act['id_to_class'][$classid]));
		$roster->set_message(sprintf($roster->locale->act['adata_update_row'], $count));
	}
	if (isset($_POST['truncate']))
	{
		//TRUNCATE TABLE  `roster_api_gems`
		$qgem = "TRUNCATE TABLE `" . $roster->db->table('api_gems') . "`;";
		$resultgem = $roster->db->query($qgem);

		$qitem = "TRUNCATE TABLE `" . $roster->db->table('api_items') . "`;";
		$resultitem = $roster->db->query($qitem);
		
		$roster->set_message(sprintf($roster->locale->act['installer_purge_0'],'Item/gem cache'));
	}
}

	if (isset($_GET['parse']) && $_GET['parse'] == 'all')
	{

		$classes = array('1','2','3','4','5','6','7','8','9','11','0');
		
		foreach ($classes as $tid)
		{
			//$tid = $_GET['classid'];
			$i = $tid;
			$talents = $roster->api->Talents->getTalentInfo(''.$tid.'');
			
			$querystr = "DELETE FROM `" . $roster->db->table('talents_data') . "` WHERE `class_id` = '" . $tid . "';";
			if (!$roster->db->query($querystr))
			{
				$roster->set_message('Talent Data Table could not be emptied.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
				return;
			}

			$querystr = "DELETE FROM `" . $roster->db->table('talenttree_data') . "` WHERE `class_id` = '" . $tid . "';";
			if (!$roster->db->query($querystr))
			{
				$roster->set_message('Talent Tree Data Table could not be emptied.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
				return;
			}

			$count = 1;
			$treenum = 1;
		//$i=$tid;
			foreach ($talents['talentData']['talentTrees'] as $a => $treedata)
			{

				$lvl = 15;
				foreach ($treedata as $t => $talent)
				{
					$tooltip = '';
					$tooltip .= (isset($talent['spell']['cost']) ? '<br>'.$talent['spell']['cost'] : '');
					$tooltip .=	(isset($talent['spell']['range']) ? '<span style="float:right;">'.$talent['spell']['range'].'</span>' : '');
					$tooltip .=	(isset($talent['spell']['castTime']) ? '<br>'.$talent['spell']['castTime'] : '');
					$tooltip .=	(isset($talent['spell']['cooldown']) ? '<span style="float:right;">'.$talent['spell']['cooldown'].'</span>' : '');
					$tooltip .= '<br>'.$talent['spell']['htmlDescription'];
					$values = array(
						'talent_id'  => $talent['spell']['spellId'],
						'talent_num' => $t,
						'tree_order' => '0',
						'class_id'   => $talent['classKey'],
						'name'       => $talent['spell']['name'],
						'tree'       => '',//$treedata['name'],
						'tooltip'    => tooltip($tooltip),
						'texture'    => $talent['spell']['icon'],
						'isspell'	 => ( !$talent['spell']['keyAbility'] ? false : true ),
						'row'        => ($talent['tier'] + 1),
						'column'     => ($talent['column'] + 1),
						'rank'       => $lvl
					);

					
					$querystr = "INSERT INTO `" . $roster->db->table('talents_data') . "` "
						. $roster->db->build_query('INSERT', $values) . ";";
					$result = $roster->db->query($querystr);
					$count++;
				$t++;	
				}
				$lvl = ($lvl+15);

				$count++;
				$treenum++;
			}


			$roster->set_message(sprintf($roster->locale->act['adata_update_class'], $roster->locale->act['id_to_class'][$_POST['class_id']]));
			$roster->set_message(sprintf($roster->locale->act['adata_update_row'], $count));
		}
	}
//echo 'will have update information for talents';
$array1 = $roster->locale->act['class_to_id'];
$array2 = array('Pets' => 0);
$classes = array_merge($array1, $array2);


foreach ($classes as $class => $num)
{
	$querystra = $classr = $resulta = 0;
	$querystra = "SELECT * FROM `" . $roster->db->table('talents_data') . "` WHERE `class_id` = '" . $num . "';";
	$resulta = $roster->db->query($querystra);
	$classr = $roster->db->num_rows($resulta);
	$i = 0;

	$roster->tpl->assign_block_vars('classes', array(
		'NAME'       => $class,
		'ID'         => $num,
		'UPDATELINK' => makelink('&amp;class=' . $num),
		'ROWS'       => $classr,
		'ROW'        => (($i % 2) + 1)
		)
	);
}
	
	
	$qgem = "SELECT * FROM `" . $roster->db->table('api_gems') . "`;";
	$resultgem = $roster->db->query($qgem);
	$gem = $roster->db->num_rows($resultgem);
	$roster->tpl->assign_block_vars('cache', array(
		'NAME'       => 'Gems',
		'ROWS'       => $gem,
		'ROW'        => (($i % 2) + 1)
		)
	);
	$qitem = "SELECT * FROM `" . $roster->db->table('api_items') . "`;";
	$resultitem = $roster->db->query($qitem);
	$item = $roster->db->num_rows($resultitem);
	$roster->tpl->assign_block_vars('cache', array(
		'NAME'       => 'Items',
		'ROWS'       => $item,
		'ROW'        => (($i % 2) + 1)
		)
	);

	
	
	$queryx = "SELECT * FROM `" . $roster->db->table('api_usage') . "` ORDER BY `date` DESC LIMIT 0,150;";
	$resultx = $roster->db->query($queryx);
	$usage = array();
	while ($row = $roster->db->fetch($resultx))
	{
		$usage[$row['date']][$row['type']]['total']=$row['total'];
	}

	
	foreach($usage as $date => $x)
	{
		$roster->tpl->assign_block_vars('apiusage', array(
				'DATE'	=> $date
			)
		);
		foreach($x as $type => $d)
		{
			$roster->tpl->assign_block_vars('apiusage.type', array(
					'TYPE'       => $type,
					'REQ'         => $d['total'],
					'PERCENT'       => ($d['total']/3000*100).'% (Based on daily limit of 3000 with no API key)',
					'ROW_CLASS'        => (($i % 2) + 1)
				)
			);
		}
	}
	
$roster->tpl->set_filenames(array('body' => 'admin/armory_data.html'));
$body = $roster->tpl->fetch('body');



/**
 * Format tooltips for insertion to the db
 *
 * @param mixed $tipdata
 * @return string
 */
function tooltip( $tipdata )
{
	$tooltip = '';

	if( is_array($tipdata) )
	{
		$tooltip = implode("\n",$tipdata);
	}
	else
	{
		$tooltip = str_replace('<br>',"\n",$tipdata);
	}
	return $tooltip;
}
