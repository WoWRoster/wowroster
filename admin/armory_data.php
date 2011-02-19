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

require_once( ROSTER_LIB . 'simple.class.php' );

include_once( ROSTER_LIB . 'armory.class.php');
$armory = new RosterArmory;

if (isset($_POST['process']) && $_POST['process'] == 'process')
{
	//	aprint($_POST);

	$i = $_POST['class_id'];

	$querystr = "DELETE FROM `" . $roster->db->table('talents_data') . "` WHERE `class_id` = '" . $_POST['class_id'] . "';";
	if (!$roster->db->query($querystr))
	{
		$roster->set_message('Talent Data Table could not be emptied.', '', 'error');
		$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
		return;
	}

	$querystr = "DELETE FROM `" . $roster->db->table('talenttree_data') . "` WHERE `class_id` = '" . $_POST['class_id'] . "';";
	if (!$roster->db->query($querystr))
	{
		$roster->set_message('Talent Tree Data Table could not be emptied.', '', 'error');
		$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
		return;
	}

	$d = $armory->_parseData(
		$armory->fetchArmory('10', $character = false, $guild = false, $realm = false, $i, $fetch_type = 'array')
	);

	// aprint($d);
	$count = '0';
	foreach ($d->talentTrees->tree as $a => $treedata)
	{
		$treenum = 0;

		foreach ($treedata->talent as $t => $talents)
		{
			$treenum = $t;

			if (isset($talents->rank->description))
			{
				$values = array(
					'talent_id'  => $talents->key,
					'talent_num' => $t,
					'tree_order' => $treedata->order,
					'class_id'   => $i,
					'name'       => $talents->name,
					'tree'       => $treedata->name,
					'tooltip'    => tooltip(addslashes($talents->rank->description)),
					'texture'    => $talents->icon,
					'row'        => ($talents->tier + 1),
					'column'     => ($talents->column + 1),
					'rank'       => $talents->rank->lvl
				);

				$querystr = "INSERT INTO `" . $roster->db->table('talents_data') . "` "
					. $roster->db->build_query('INSERT', $values) . ";";
				$result = $roster->db->query($querystr);
				$count++;
			}
			else
			{
				foreach ($talents->rank as $r => $ranks)
				{
					$values = array(
						'talent_id'  => $talents->key,
						'talent_num' => $t,
						'tree_order' => $treedata->order,
						'class_id'   => $i,
						'name'       => $talents->name,
						'tree'       => $treedata->name,
						'tooltip'    => tooltip(addslashes($ranks->description)),
						'texture'    => $talents->icon,
						'row'        => ($talents->tier + 1),
						'column'     => ($talents->column + 1),
						'rank'       => $ranks->lvl
					);

					$querystr = "INSERT INTO `" . $roster->db->table('talents_data') . "` "
						. $roster->db->build_query('INSERT', $values) . ";";

					$result = $roster->db->query($querystr);
					$count++;
				}
			}
		}

		$values = array(
			'tree'       => $treedata->name,
			'order'      => $treedata->order,
			'class_id'   => $i,
			'background' => strtolower($treedata->bgImage),
			'icon'       => $treedata->icon,
			'tree_num'   => $treenum
		);

		$querystr = "INSERT INTO `" . $roster->db->table('talenttree_data') . "` "
			. $roster->db->build_query('INSERT', $values) . ";";

		$result = $roster->db->query($querystr);
		$count++;
	}

	$roster->set_message(sprintf($roster->locale->act['adata_update_class'], $roster->locale->act['id_to_class'][$_POST['class_id']]));
	$roster->set_message(sprintf($roster->locale->act['adata_update_row'], $count));
}

//echo 'will have update information for talents';

foreach ($roster->locale->act['class_to_id'] as $class => $num)
{
	$querystra = $classr = $resulta = 0;
	$querystra = "SELECT * FROM `" . $roster->db->table('talents_data') . "` WHERE `class_id` = '" . $num . "';";
	$resulta = $roster->db->query($querystra);
	$classr = $roster->db->num_rows($resulta);
	$i = 0;

	$roster->tpl->assign_block_vars('classes', array(
		'NAME'       => $class,
		'ID'         => $roster->locale->act['class_to_id'][$class],
		'UPDATELINK' => makelink('&amp;class=' . $roster->locale->act['class_to_id'][$class]),
		'ROWS'       => $classr,
		'ROW'        => (($i % 2) + 1)
		)
	);
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
