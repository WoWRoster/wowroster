<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character display configuration
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( isset($_POST['process']) && $_POST['process'] != '' )
{
	processData();
}
if( isset($_POST['default']) && $_POST['default'] != '' )
{
	defaultData();
}

$start = (isset($_GET['start']) ? $_GET['start'] : 0);

// Change scope to guild, and rerun detection to load default
$roster->scope = 'guild';
$roster->get_scope_data();


/**
 * Actual list
 */
$query = "SELECT COUNT(`member_id`)"
	. " FROM `" . $roster->db->table('players') . "`"
	. " WHERE `guild_id` = " . ( isset($roster->data['guild_id']) ? $roster->data['guild_id'] : 0 ) . ";";

$num_members = $roster->db->query_first($query);


$query = 'SELECT * FROM `' . $roster->db->table('default',$addon['basename']) . '`;';

$result = $roster->db->query($query);
$disp_defaults = $roster->db->fetch_all($result, SQL_ASSOC);
$disp_defaults = $disp_defaults[0];


// Check to see if we can actually configure anything
$config = false;
foreach( $disp_defaults as $name => $value )
{
	if( $addon['config'][$name] == -1 )
	{
		$config = true;
	}

	$values = array(
		'name' => $name,
		'value' => $value,
	);

	$l_name = explode('|',$roster->locale->act['admin'][$name]);
	$l_name = $l_name[0];

	$roster->tpl->assign_block_vars('headers', array(
		'L_NAME' => $l_name,
		'NAME' => $name,
		'VALUE' => $value,
		'DISPLAY' => ( $addon['config'][$name] == -1 ? true : false ),
		'SELECT' => $roster->auth->rosterAccess($values)
		)
	);
}

$roster->tpl->assign_vars(array(
	'S_CONFIG' => $config,

	'L_PER_CHAR_DISPLAY' => $roster->locale->act['admin']['per_character_display'],
	'L_DEFAULT' => $roster->locale->act['default'],
	'L_SUBMIT'  => $roster->locale->act['config_submit_button'],
	'L_RESET'   => $roster->locale->act['config_reset_button'],
	'L_CONFIRM_RESET' => $roster->locale->act['confirm_config_reset'],

	'L_NOTHING_TO_CONFIG' => $roster->locale->act['admin']['nothing_to_config'],

	'L_NAME'       => $roster->locale->act['name'],

	'PREV'    => '',
	'NEXT'    => '',
	'LISTING' => ''
	)
);



if( $num_members > 0 )
{
	// Draw the header line
	if( $start > 0 )
	{
		$prev = '<a href="' . makelink('&amp;start=0') . '">|&lt;&lt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;start=' . ($start - 15)) . '">&lt;</a> ';
	}
	else
	{
		$prev = '';
	}

	if( ($start+15) < $num_members )
	{
		$listing = ' <small>[' . $start . ' - ' . ($start+15) . '] of ' . $num_members . '</small>';
		$next = ' <a href="' . makelink('&amp;start=' . ($start+15)) . '">&gt;</a>&nbsp;&nbsp;<a href="' . makelink('&amp;start=' . ( floor( $num_members / 15) * 15 )) . '">&gt;&gt;|</a>';
	}
	else
	{
		$listing = ' <small>[' . $start . ' - ' . ($num_members) . '] of ' . $num_members . '</small>';
		$next = '';
	}

	$roster->tpl->assign_vars(array(
		'PREV'    => $prev,
		'NEXT'    => $next,
		'LISTING' => $listing
		)
	);

	$query = 'SELECT '
		. ' `p`.`member_id`, `p`.`name`,'
		. ' `p`.`level`, `p`.`class`,'
		. ' `i`.*'
		. ' FROM `' . $roster->db->table('display',$addon['basename']) . '` AS i'
		. ' LEFT JOIN `' . $roster->db->table('players') . '` AS p'
		. ' ON `p`.`member_id` = `i`.`member_id`'
		. ' WHERE `p`.`guild_id` = ' . $roster->data['guild_id']
		. ' ORDER BY `p`.`name` ASC'
		. ' LIMIT ' . ($start > 0 ? $start : 0) . ', 15;';

	$result = $roster->db->query($query);

	while( $data = $roster->db->fetch($result,SQL_ASSOC) )
	{
		$roster->tpl->assign_block_vars('members',array(
			'ROW_CLASS' => $roster->switch_row_class(),
			'ID' => $data['member_id'],
			'LINK' => makelink('char-info&amp;a=c:' . $data['member_id']),
			'NAME' => $data['name'],
			'LEVEL' => $data['level'],
			'CLASS' => $data['class'],
			)
		);

		$k=0;
		foreach( $data as $val_name => $value )
		{
			if( substr( $val_name, 0, 5 ) != 'show_' )
			{
				continue;
			}
			if( $addon['config'][$val_name] != -1 )
			{
				continue;
			}

			$values = array(
				'name' => $data['member_id'] . ':' .$val_name,
				'value' => $value,
			);

			$roster->tpl->assign_block_vars('members.data',array(
				'ID' => $k,
				'NAME' => $val_name,
				'VALUE' => $value,
				'SELECT' => $roster->auth->rosterAccess($values),
				)
			);

			$k++;
		}
	}
}
else
{
	return $roster->locale->act['admin']['no_data'];
}


/**
 * Make our menu from the config api
 */
// ----[ Set the tablename and create the config class ]----
include(ROSTER_LIB . 'config.lib.php');
$config = new roster_config( $roster->db->table('addon_config'), '`addon_id` = "' . $addon['addon_id'] . '"' );

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build the page items using lib functions ]---------
$menu .= $config->buildConfigMenu('rostercp-addon-' . $addon['basename']);



$roster->tpl->set_filenames(array('body' => $addon['basename'] . '/admin/display.html'));
$body = $roster->tpl->fetch('body');




/**
 * Process Data for entry to the database
 *
 * @return string Settings changed or not changed
 */
function processData()
{
	global $roster, $addon;

	$update_sql = array();

	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		if( $settingName != 'process' && substr($settingName,0,7) == 'config_' )
		{
			$settingName = str_replace('config_','',$settingName);

			list($member_id,$settingName) = explode(':',$settingName);

			$update_sql[$member_id][$settingName] = $settingValue;

		}
	}

	// Update DataBase
	if( count($update_sql) > 0 )
	{
		foreach( $update_sql as $member_id => $data )
		{
			$sql = "UPDATE `" . $roster->db->table('display',$addon['basename']) . "`"
				 . " SET " . $roster->db->build_query('UPDATE',$data)
				 . " WHERE `member_id` = '$member_id';";

			$result = $roster->db->query($sql);
			if( !$result )
			{
				$roster->set_message('There was an error saving settings.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
				return false;
			}
		}
		$roster->set_message('Settings were saved');
		return true;
	}
	else
	{
		$roster->set_message('No changes have been made');
		return false;
	}
}
/**
 * Process Defaults
 *
 * @return string Settings changed or not changed
 */
function defaultData()
{
	global $roster, $addon;

	$build_sql = array();
	// Update only the changed fields
	foreach( $_POST as $settingName => $settingValue )
	{
		if( $settingName != 'default' && substr($settingName,0,7) == 'config_' )
		{
			$settingName = str_replace('config_','',$settingName);

			$build_sql[$settingName] = $settingValue;
		}
	}

	if( count($build_sql) > 0 )
	{
		if( $_POST['default'] == 'setall' )
		{
			$sql = "UPDATE `" . $roster->db->table('display',$addon['basename']) . "` SET " . $roster->db->build_query('UPDATE',$build_sql) . ";";

			// Update DataBase
			$result = $roster->db->query($sql);
			if( !$result )
			{
				$roster->set_message('There was an error saving defaults.', '', 'error');
				$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
				return false;
			}
		}

		$sql = "UPDATE `" . $roster->db->table('default', $addon['basename']) . "` SET " . $roster->db->build_query('UPDATE',$build_sql) . ";";

		// Update DataBase
		$result = $roster->db->query($sql);
		if( !$result )
		{
			$roster->set_message('There was an error saving defaults.', '', 'error');
			$roster->set_message('<pre>' . $roster->db->error() . '</pre>', 'MySQL Said', 'error');
			return false;
		}
		$roster->set_message('Settings were saved.');
		return true;
	}
}
