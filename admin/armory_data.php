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

class bob
{
	var $assignstr = '';

	function reset_values()
	{
		$this->assignstr = '';
	}

	/**
	 * Add a value to an INSERT or UPDATE SQL string
	 *
	 * @param string $row_name
	 * @param string $row_data
	 */
	function add_value( $row_name , $row_data )
	{
		global $roster;

		if( $this->assignstr != '' )
		{
			$this->assignstr .= ',';
		}

		// str_replace added to get rid of non breaking spaces in cp.lua tooltips
		$row_data = str_replace(chr(194) . chr(160), ' ', $row_data);
		$row_data = $roster->db->escape($row_data);

		$this->assignstr .= " `$row_name` = '$row_data'";
	}
}

$bob = new bob;

require_once( ROSTER_LIB . 'simple.class.php' );

include_once( ROSTER_LIB . 'armory.class.php');
$armory = new RosterArmory;

if (isset($_POST['process']) && $_POST['process'] == 'process')
{
	// We have a response
	$roster->tpl->assign_var('S_RESPONSE', true);

	//	aprint($_POST);

	$i = $_POST['class_id'];

	$querystr = "DELETE FROM `" . $roster->db->table('talents_data') . "` WHERE `class_id` = '" . $_POST['class_id'] . "';";
	if (!$roster->db->query($querystr))
	{
		$this->setError('Talent Data Table could not be emptied', $roster->db->error());
		return;
	}

	$querystr = "DELETE FROM `" . $roster->db->table('talenttree_data') . "` WHERE `class_id` = '" . $_POST['class_id'] . "';";
	if (!$roster->db->query($querystr))
	{
		$this->setError('Talent Tree Data Table could not be emptied', $roster->db->error());
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
				$bob->reset_values();
				$bob->add_value('talent_id', $talents->key);
				$bob->add_value('talent_num', $t);
				$bob->add_value('tree_order', $treedata->order);
				$bob->add_value('class_id', $i);
				$bob->add_value('name', $talents->name);
				$bob->add_value('tree', $treedata->name);
				$bob->add_value('tooltip', addslashes($talents->rank->description));
				$bob->add_value('texture', $talents->icon);
				$bob->add_value('row', ($talents->tier + 1));
				$bob->add_value('column', ($talents->column + 1));
				$bob->add_value('rank', $talents->rank->lvl);
				$querystr = "INSERT INTO `" . $roster->db->table('talents_data') . "` SET " . $bob->assignstr;
				$result = $roster->db->query($querystr);
				$count++;
			}
			else
			{
				foreach ($talents->rank as $r => $ranks)
				{
					$bob->reset_values();
					$bob->add_value('talent_id', $talents->key);
					$bob->add_value('talent_num', $t);
					$bob->add_value('tree_order', $treedata->order);
					$bob->add_value('class_id', $i);
					$bob->add_value('name', $talents->name);
					$bob->add_value('tree', $treedata->name);
					$bob->add_value('tooltip', addslashes($ranks->description));
					$bob->add_value('texture', $talents->icon);
					$bob->add_value('row', ($talents->tier + 1));
					$bob->add_value('column', ($talents->column + 1));
					$bob->add_value('rank', $ranks->lvl);

					$querystr = "INSERT INTO `" . $roster->db->table('talents_data') . "` SET " . $bob->assignstr;
					$result = $roster->db->query($querystr);
					$count++;
				}
			}
		}

		$bob->reset_values();
		$bob->add_value('tree', $treedata->name);
		$bob->add_value('order', $treedata->order);
		$bob->add_value('class_id', $i);
		$bob->add_value('background', strtolower($treedata->bgImage));
		$bob->add_value('icon', $treedata->icon);
		$bob->add_value('tree_num', $treenum);

		$querystr = "INSERT INTO `" . $roster->db->table('talenttree_data') . "` SET " . $bob->assignstr;
		$result = $roster->db->query($querystr);
		$count++;
	}

	$roster->tpl->assign_vars(array(
		'RESPONSE' => 'Class ' . $roster->locale->act['id_to_class'][$_POST['class_id']] . ' updated<br />' . $count . ' rows added to database.<br />',
		'RESPONSE_POST' => htmlspecialchars(stripAllHtml($messages)
		)
	));
}
else
{
	$roster->tpl->assign_var('S_RESPONSE',false);

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
