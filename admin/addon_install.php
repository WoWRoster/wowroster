<?php
/**
 * WoWRoster.net WoWRoster
 *
 * AddOn installer
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_addoninst'];


include(ROSTER_LIB . 'install.lib.php');


$op = ( isset($_POST['op']) ? $_POST['op'] : '' );

$id = ( isset($_POST['id']) ? $_POST['id'] : '' );


switch( $op )
{
	case 'deactivate':
		processActive($id,0);
		break;

	case 'activate':
		processActive($id,1);
		break;

	case 'process':
		$processed = processAddon();
		break;

	default:
		break;
}

$addons = getAddonList();

if( !empty($addons) )
{
	// Generate addons table
	$output =
	'<table class="bodyline" cellspacing="0">
		<tr>
			<th class="membersHeader">' . $roster->locale->act['installer_icon'] . '</th>
			<th class="membersHeader">' . $roster->locale->act['installer_addoninfo'] . '</th>
			<th class="membersHeader">' . $roster->locale->act['installer_status'] . '</th>
			<th class="membersHeaderRight">' . $roster->locale->act['installer_installation'] . '</th>
		</tr>
	';
	foreach( $addons as $addon )
	{
		$output .= '	<tr>
			<td class="membersRow1"><img src="' . $roster->config['interface_url'] . 'Interface/Icons/' . $addon['icon'] . '.' . $roster->config['img_suffix'] . '" alt="[icon]" /></td>
			<td class="membersRow1"><table cellpadding="0" cellspacing="0">
				<tr>
					<td><span style="font-size:18px;" class="green">' . ucfirst($addon['fullname']) . '</span> v' . $addon['version'] . '</td>
				</tr>
				<tr>
					<td>' . $addon['description'] . '</td>
				</tr>
				<tr>
					<td><span class="blue">' . $roster->locale->act['installer_author'] . ': ' . $addon['author'] . '</span></td>
				</tr>
			</table></td>
			<td class="membersRow1">' . ( $addon['install'] == 3 ? '&nbsp;' : activeInactive($addon['active'],$addon['id']) ) . '</td>
			<td class="membersRowRight1">' . installUpgrade($addon['install'],$addon['basename']) . '</td>
		</tr>
	';
	}
	$output .= '</table>';
}
else
{
	$installer->setmessages('No addons available!');
}

$errorstringout = $installer->geterrors();
$messagestringout = $installer->getmessages();
$sqlstringout = $installer->getsql();

$message = '';

// print the error messages
if( !empty($errorstringout) )
{
	$message .= messagebox($errorstringout,$roster->locale->act['installer_error'],'sred') . '<br />';
}

// Print the update messages
if( !empty($messagestringout) )
{
	$message .= messagebox($messagestringout,$roster->locale->act['installer_log'],'syellow');
}

$body = $roster_login->getMessage() . '<br />' . ($message != '' ? $message . '<br />' : '') . ((isset($output) && !empty($output)) ? messagebox($output,$roster->locale->act['pagebar_addoninst'],'sblue') : '');

return;

/**
 * Gets the current action for active/inactive
 *
 * @param string $mode
 * @param int $id
 * @return string
 */
function activeInactive( $mode , $id )
{
	global $roster;

	if( $mode )
	{
		$type = '<form name="deactivate_' . $id . '" style="display:inline;" method="post" enctype="multipart/form-data" action="' . makelink() . '">
		<input type="hidden" name="op" value="deactivate" />
		<input type="hidden" name="id" value="' . $id . '" />
		<input ' . makeOverlib($roster->locale->act['installer_turn_off'],$roster->locale->act['installer_activated']) . 'type="image" src="' . $roster->config['img_url'] . 'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	else
	{
		$type = '<form name="activate_' . $id . '" style="display:inline;" method="post" enctype="multipart/form-data" action="' . makelink() . '">
		<input type="hidden" name="op" value="activate" />
		<input type="hidden" name="id" value="' . $id . '" />
		<input ' . makeOverlib($roster->locale->act['installer_turn_on'],$roster->locale->act['installer_deactivated']) . ' type="image" src="' . $roster->config['img_url'] . 'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}

	return $type;
}


/**
 * Gets the current action for install/uninstall/upgrade
 *
 * @param string $mode
 * @param string $name
 * @return string
 */
function installUpgrade( $mode , $name )
{
	global $roster;

	if( $mode == -1 )
	{
		$type = '<img ' . makeOverlib($roster->locale->act['installer_replace_files'],$roster->locale->act['installer_overwrite']) . ' src="' . $roster->config['img_url'] . 'admin/purple.png" style="height:16px;width:16px;border:0;" alt="" />';
	}
	elseif( $mode == 0 )
	{
		$type = '<form name="uninstall_' . $name . '" style="display:inline;" method="post" enctype="multipart/form-data" action="' . makelink() . '">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="' . $name . '" />
		<input type="hidden" name="type" value="uninstall" />
		<input ' . makeOverlib($roster->locale->act['installer_click_uninstall'],$roster->locale->act['installer_installed']) . 'type="image" src="' . $roster->config['img_url'] . 'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	elseif( $mode == 1 )
	{
		$type = '<form name="upgrade_' . $name . '" style="display:inline;" method="post" enctype="multipart/form-data" action="' . makelink() . '">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="' . $name . '" />
		<input type="hidden" name="type" value="upgrade" />
		<input ' . makeOverlib($roster->locale->act['installer_click_upgrade'],$roster->locale->act['installer_upgrade_avail']) . 'type="image" src="' . $roster->config['img_url'] . 'admin/blue.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	elseif( $mode == 3 )
	{
		$type = '<form name="install_' . $name . '" style="display:inline;" method="post" enctype="multipart/form-data" action="' . makelink() . '">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="' . $name . '" />
		<input type="hidden" name="type" value="install" />
		<input ' . makeOverlib($roster->locale->act['installer_click_install'],$roster->locale->act['installer_not_installed']) . 'type="image" src="' . $roster->config['img_url'] . 'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}

	return $type;
}


/**
 * Gets the list of currently installed roster addons
 *
 * @return array
 */
function getAddonList()
{
	global $roster, $installer;

	// Initialize output
	$addons = '';
	$output = '';

	if( $handle = opendir(ROSTER_ADDONS) )
	{
		while( false !== ($file = readdir($handle)) )
		{
			if( $file != '.' && $file != '..' && $file != '.svn' )
			{
				$addons[] = $file;
			}
		}
	}

	if( is_array($addons) )
	{
		foreach( $addons as $addon )
		{
			$installfile = ROSTER_ADDONS . $addon . DIR_SEP . 'inc' . DIR_SEP . 'install.def.php';

			if( file_exists($installfile) )
			{
				include_once($installfile);

				if( !class_exists($addon) )
				{
					$installer->seterrors(sprintf($roster->locale->act['installer_no_class'],$addon));
					continue;
				}

				$addonstuff = new $addon;

				$query = "SELECT * FROM `" . $roster->db->table('addon') . "` WHERE `basename` = '$addon';";
				$result = $roster->db->query($query);
				if (!$result)
				{
					$installer->seterrors('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
					return;
				}


				if( $roster->db->num_rows($result) > 0 )
				{
					$row = $roster->db->fetch($result);

					$output[$addon]['id'] = $row['addon_id'];
					$output[$addon]['active'] = $row['active'];

					// -1 = overwrote newer version
					//  0 = same version
					//  1 = upgrade available
					$output[$addon]['install'] = version_compare($addonstuff->version,$row['version']);

				}
				else
				{
					$output[$addon]['install'] = 3;
				}

				$output[$addon]['basename'] = $addon;
				$output[$addon]['fullname'] = $addonstuff->fullname;
				$output[$addon]['author'] = $addonstuff->credits[0]['name'];
				$output[$addon]['version'] = $addonstuff->version;
				$output[$addon]['icon'] = $addonstuff->icon;
				$output[$addon]['description'] = $addonstuff->description;

				unset($addonstuff);
			}
		}
	}
	return $output;
}


/**
 * Sets addon active/inactive
 *
 * @param int $id
 * @param int $mode
 */
function processActive( $id , $mode )
{
	global $roster, $installer;

	$query = "UPDATE `" . $roster->db->table('addon') . "` SET `active` = '$mode' WHERE `addon_id` = '$id' LIMIT 1;";
	$result = $roster->db->query($query);
	if( !$result )
	{
		$installer->seterrors('Database Error: ' . $roster->db->error() . '<br />SQL: ' . $query);
	}
	else
	{
		$mode = ( $mode ? $roster->locale->act['installer_activated'] : $roster->locale->act['installer_deactivated'] );
		$installer->setmessages('Addon ' . $mode);
	}
}


/**
 * Addon installer/upgrader/uninstaller
 *
 */
function processAddon()
{
	global $roster, $installer;

	$addon_name = $_POST['addon'];

	if( preg_match('/[^a-zA-Z0-9_]/', $addon_name) )
	{
		$installer->seterrors($roster->locale->act['invalid_char_module'],$roster->locale->act['installer_error']);
		return;
	}

	// Include addon install definitions
	$addonDir = ROSTER_ADDONS . $addon_name . DIR_SEP;
	$addon_install_file = $addonDir . 'inc' . DIR_SEP . 'install.def.php';

	if( !file_exists($addon_install_file) )
	{
		$installer->seterrors(sprintf($roster->locale->act['installer_no_installdef'],$addon_name),$roster->locale->act['installer_error']);
		return;
	}

	require($addon_install_file);

	$addon = new $addon_name();
	$addata = escape_array((array)$addon);
	$addata['basename'] = $addon_name;

	if( $addata['basename'] == '' )
	{
		$installer->seterrors($roster->locale->act['installer_no_empty'],$roster->locale->act['installer_error']);
		return;
	}

	// Get existing addon record if available
	$query = 'SELECT * FROM `' . $roster->db->table('addon') . '` WHERE `basename` = "' . $addata['basename'] . '";';
	$result = $roster->db->query($query);
	if( !$result )
	{
		$installer->seterrors(sprintf($roster->locale->act['installer_fetch_failed'],$addata['basename']) . '.<br />MySQL said: ' . $roster->db->error(),$roster->locale->act['installer_error']);
		return;
	}
	$previous = $roster->db->fetch($result);
	$roster->db->free_result($result);

	// Give the installer the addon data
	$installer->addata = $addata;

	$success = false;

	// Collect data for this install type
	switch( $_POST['type'] )
	{
		case 'install':
			if( $previous )
			{
				$installer->seterrors(sprintf($roster->locale->act['installer_addon_exist'],$installer->addata['basename'],$previous['fullname']));
				break;
			}
			$query = 'INSERT INTO `' . $roster->db->table('addon') . '` VALUES (NULL,"' . $installer->addata['basename'] . '","' . $installer->addata['version'] . '",0,"' . $installer->addata['fullname'] . '","' . $installer->addata['description'] . '","' . $roster->db->escape(serialize($installer->addata['credits'])) . '","' . $installer->addata['icon'] . '");';
			$result = $roster->db->query($query);
			if( !$result )
			{
				$installer->seterrors('DB error while creating new addon record. <br /> MySQL said:' . $roster->db->error(),$roster->locale->act['installer_error']);
				break;
			}
			$installer->addata['addon_id'] = $roster->db->insert_id();

			// We backup the addon config table to prevent damage
			$installer->add_backup($roster->db->table('addon_config'));

			$success = $addon->install();

			// Delete the addon record if there is an error
			if( !$success )
			{
				$query = 'DELETE FROM `' . $roster->db->table('addon') . "` WHERE `addon_id` = '" . $installer->addata['addon_id'] . "';";
				$result = $roster->db->query($query);
			}
			else
			{
				$installer->sql[] = 'UPDATE `' . $roster->db->table('addon') . '` SET `active` = ' . (int)$installer->addata['active'] . ';';
			}
			break;

		case 'upgrade':
			if( !$previous )
			{
				$installer->seterrors(sprintf($roster->locale->act['installer_no_upgrade'],$installer->addata['basename']));
				break;
			}
			if( !in_array($previous['basename'],$addon->upgrades) )
			{
				$installer->seterrors(sprintf($roster->locale->act['installer_not_upgradable'],$addon->fullname,$previous['fullname'],$previous['basename']));
				break;
			}

			$query = 'UPDATE `' . $roster->db->table('addon') . '` SET `basename`="' . $installer->addata['basename'] . '", `version`="' . $installer->addata['version'] . '", `active`=' . $installer->addata['active'] . ', `fullname`="' . $installer->addata['fullname'] . '", `description`="' . $installer->addata['description'] . '", `credits`="' . serialize($installer->addata['credits']) . '", `icon`="' . $installer->addata['icon'] . '" WHERE `addon_id`=' . $previous['addon_id'] . ';';
			$result = $roster->db->query($query);
			if( !$result )
			{
				$installer->seterrors('DB error while updating the addon record. <br /> MySQL said:' . $roster->db->error(),$roster->locale->act['installer_error']);
				break;
			}
			$installer->addata['addon_id'] = $previous['addon_id'];

			// We backup the addon config table to prevent damage
			$installer->add_backup($roster->db->table('addon_config'));

			$success = $addon->upgrade($previous['version']);
			break;

		case 'uninstall':
			if( !$previous )
			{
				$installer->seterrors(sprintf($roster->locale->act['installer_no_uninstall'],$installer->addata['basename']));
				break;
			}
			if( $previous['basename'] != $installer->addata['basename'] )
			{
				$installer->seterrors(sprintf($roster->locale->act['installer_not_uninstallable'],$installer->addata['basename'],$previous['fullname']));
				break;
			}
			$query = 'DELETE FROM `' . $roster->db->table('addon') . '` WHERE `addon_id`=' . $previous['addon_id'] . ';';
			$result = $roster->db->query($query);
			if( !$result )
			{
				$installer->seterrors('DB error while deleting the addon record. <br /> MySQL said:' . $roster->db->error(),$roster->locale->act['installer_error']);
				break;
			}
			$installer->addata['addon_id'] = $previous['addon_id'];

			// We backup the addon config table to prevent damage
			$installer->add_backup($roster->db->table('addon_config'));

			$success = $addon->uninstall();
			break;

		case 'purge':
			$success = purge($installer->addata['basename']);
			break;

		default:
			$installer->seterrors($roster->locale->act['installer_invalid_type']);
			$success = false;
			break;
	}

	if( !$success )
	{
		$installer->seterrors($roster->locale->act['installer_no_success_sql']);
		return false;
	}
	else
	{
		$success = $installer->install();
		$installer->setmessages(sprintf($roster->locale->act['installer_' . $_POST['type'] . '_' . $success],$installer->addata['basename']));
	}
	return true;
}


function purge( $dbname )
{
	global $roster, $installer;

	// Delete addon tables under dbname.
	$query = 'SHOW TABLES LIKE "' . $roster->db->prefix . 'addons_' . $dbname . '%"';
	$tables = $roster->db->query($query);
	if( !$tables )
	{
		$installer->seterrors('Error while getting table names for ' . $dbname . '. MySQL said: ' . $roster->db->error(),$roster->locale->act['installer_error'],__FILE__,__LINE__,$query);
		return false;
	}
	if( $roster->db->num_rows($tables) )
	{
		while ($row = $roster->db->fetch($tables))
		{
			$query = 'DROP TABLE `' . $row[0] . '`;';
			$dropped = $roster->db->query($query);
			if( !$dropped )
			{
				$installer->seterrors('Error while dropping ' . $row[0] . '.<br />MySQL said: ' . $roster->db->error(),$roster->locale->act['installer_error'],__FILE__,__LINE__,$query);
				return false;
			}
			$roster->db->free_result($dropped);
		}
	}
	$roster->db->free_result($tables);

	// Get the addon id for this basename
	$query = "SELECT `addon_id` FROM `" . $roster->db->table('addon') . "` WHERE `basename` = '" . $dbname . "';";
	$addon_id = $roster->db->query_first($query);

	if( $addon_id !== false )
	{
		// Delete menu entries
		$query = 'DELETE FROM `' . $roster->db->table('menu_button') . '` WHERE `addon_id` = "' . $addon_id . '";';
		$roster->db->query($query) or $installer->seterrors('Error while deleting menu entries for ' . $dbname . '.<br />MySQL said: ' . $roster->db->error(),$roster->locale->act['installer_error'],__FILE__,__LINE__,$query);
	}

	// Delete addon table entry
	$query = 'DELETE FROM `' . $roster->db->table('addon') . '` WHERE `basename` = "' . $dbname . '"';
	$roster->db->query($query) or $installer->seterrors('Error while deleting addon table entry for ' . $dbname . '.<br />MySQL said: ' . $roster->db->error(),$roster->locale->act['installer_error'],__FILE__,__LINE__,$query);

	return true;
}
