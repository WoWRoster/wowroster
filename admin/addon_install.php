<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}


include(ROSTER_LIB.'install.lib.php');


$op = ( isset($_POST['op']) ? $_POST['op'] : '');

$id = ( isset($_POST['id']) ? $_POST['id'] : '');


switch($op)
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
			<th class="membersHeader">'.$act_words['installer_icon'].'</th>
			<th class="membersHeader">'.$act_words['installer_addoninfo'].'</th>
			<th class="membersHeader">'.$act_words['installer_status'].'</th>
			<th class="membersHeaderRight">'.$act_words['installer_installation'].'</th>
		</tr>
	';
	foreach( $addons as $addon )
	{
		$output .= '	<tr>
			<td class="membersRow1"><img src="'.$roster_conf['interface_url'].'Interface/Icons/'.$addon['icon'].'.'.$roster_conf['img_suffix'].'" alt="[icon]" /></td>
			<td class="membersRow1"><table cellpadding="0" cellspacing="0">
				<tr>
					<td><span style="font-size:18px;" class="green">'.ucfirst($addon['fullname']).'</span> v'.$addon['version'].'</td>
				</tr>
				<tr>
					<td>'.$addon['description'].'</td>
				<tr>
					<td><span class="blue">'.$act_words['installer_author'].': '.$addon['author'].'</span></td>
				</tr>
			</table></td>
			<td class="membersRow1">'.( $addon['install'] == 3 ? '&nbsp;' : activeInactive($addon['active'],$addon['id']) ).'</td>
			<td class="membersRowRight1">'.installUpgrade($addon['install'],$addon['basename']).'</td>
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
	$message .= messagebox($errorstringout,$act_words['installer_error'],'sred').'<br />';
}

// Print the update messages
if( !empty($messagestringout) )
{
	$message .= messagebox($messagestringout,$act_words['installer_log'],'syellow');
}

$body = $roster_login->getMessage().'<br />'.($message != '' ? $message.'<br />' : '').((isset($output) && !empty($output)) ? messagebox($output,$act_words['pagebar_addoninst'],'sblue') : '');





/**
 * Gets the current action for active/inactive
 *
 * @param string $mode
 * @param int $id
 * @return string
 */
function activeInactive( $mode,$id )
{
	global $script_filename, $roster_conf, $act_words;

	if( $mode )
	{
		$type = '<form name="deactivate_'.$id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.makelink($script_filename).'">
		<input type="hidden" name="op" value="deactivate" />
		<input type="hidden" name="id" value="'.$id.'" />
		<input '.makeOverlib($act_words['installer_turn_off'],$act_words['installer_activated']).'type="image" src="'.$roster_conf['img_url'].'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	else
	{
		$type = '<form name="activate_'.$id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.makelink($script_filename).'">
		<input type="hidden" name="op" value="activate" />
		<input type="hidden" name="id" value="'.$id.'" />
		<input '.makeOverlib($act_words['installer_turn_on'],$act_words['installer_deactivated']).' type="image" src="'.$roster_conf['img_url'].'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
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
function installUpgrade( $mode,$name )
{
	global $roster_conf, $script_filename, $act_words;

	if( $mode == -1 )
	{
		$type = '<img '.makeOverlib($act_words['installer_replace_files'],$act_words['installer_overwrite']).' src="'.$roster_conf['img_url'].'admin/purple.png" style="height:16px;width:16px;border:0;" alt="" />';
	}
	elseif( $mode == 0 )
	{
		$type = '<form name="uninstall_'.$name.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.makelink($script_filename).'">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="'.$name.'" />
		<input type="hidden" name="type" value="uninstall" />
		<input '.makeOverlib($act_words['installer_click_uninstall'],$act_words['installer_installed']).'type="image" src="'.$roster_conf['img_url'].'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	elseif( $mode == 1 )
	{
		$type = '<form name="upgrade_'.$name.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.makelink($script_filename).'">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="'.$name.'" />
		<input type="hidden" name="type" value="upgrade" />
		<input '.makeOverlib($act_words['installer_click_upgrade'],$act_words['installer_upgrade_avail']).'type="image" src="'.$roster_conf['img_url'].'admin/blue.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	elseif( $mode == 3 )
	{
		$type = '<form name="install_'.$name.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.makelink($script_filename).'">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="'.$name.'" />
		<input type="hidden" name="type" value="install" />
		<input '.makeOverlib($act_words['installer_click_install'],$act_words['installer_not_installed']).'type="image" src="'.$roster_conf['img_url'].'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
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
	global $roster_conf, $wowdb, $installer, $act_words;


	// Initialize output
	$addons = '';
	$output = '';

	if ($handle = opendir(ROSTER_ADDONS))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..' && $file != '.svn' )
			{
				$addons[] = $file;
			}
		}
	}

	if( is_array($addons) )
	{
		foreach ($addons as $addon)
		{
			$installfile = ROSTER_ADDONS.$addon.DIR_SEP.'install.def.php';

			if (file_exists($installfile))
			{
				include_once($installfile);

				if( !class_exists($addon) )
				{
					$installer->seterrors(sprintf($act_words['installer_no_class'],$addon));
					continue;
				}

				$addonstuff = new $addon;

				$query = "SELECT * FROM `".$wowdb->table('addon')."` WHERE `basename` = '$addon'";
				$result = $wowdb->query($query);
				if (!$result)
				{
					$installer->seterrors($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
					return;
				}


				if( $wowdb->num_rows($result) > 0 )
				{
					$row = $wowdb->fetch_assoc($result);

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
function processActive($id,$mode)
{
	global $wowdb, $installer, $act_words;

	$query = "UPDATE `".$wowdb->table('addon')."` SET `active` = '$mode' WHERE `addon_id` = '$id' LIMIT 1;";
	$result = $wowdb->query($query);
	if (!$result)
		$installer->seterrors($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	else
	{
		$mode = ( $mode ? $act_words['installer_activated'] : $act_words['installer_deactivated'] );
		$installer->setmessages('Addon '.$mode);
	}
}


/**
 * Addon installer/upgrader/uninstaller
 *
 */
function processAddon()
{
	global $wowdb, $installer, $roster_conf, $act_words;

	$addon_name = $_POST['addon'];

	if( preg_match('/[^a-zA-Z0-9_]/', $addon_name) )
	{
		$installer->seterrors($act_words['invalid_char_addon'],$act_words['installer_error']);
		return;
	}

	// Include addon install definitions
	$addonDir = ROSTER_ADDONS.$addon_name.DIR_SEP;
	if (!file_exists($addonDir.'install.def.php'))
	{
		$installer->seterrors(sprintf($act_words['installer_no_installdef'],$addon_name),$act_words['installer_error']);
		return;
	}

	require($addonDir.'install.def.php');

	$addon = new $addon_name();
	$addata = escape_array((array)$addon);
	$addata['basename'] = $addon_name;

	if ($addata['basename'] == '')
	{
		$installer->seterrors($act_words['installer_no_empty'],$act_words['installer_error']);
		return;
	}

	// Get existing addon record if available
	$query = 'SELECT * FROM `'.ROSTER_ADDONTABLE.'` WHERE `basename` = "'.$addata['basename'].'"';
	$result = $wowdb->query($query);
	if( !$result )
	{
		$installer->seterrors(sprintf($act_words['installer_fetch_failed'],$addata['basename']).'.<br />MySQL said: '.$wowdb->error(),$act_words['installer_error']);
		return;
	}
	$previous = $wowdb->fetch_assoc($result);
	$wowdb->free_result($result);

	// Give the installer the addon data
	$installer->addata = $addata;

	// Collect data for this install type
	switch ($_POST['type'])
	{
		case 'install':
			if ($previous)
			{
				$installer->seterrors(sprintf($act_words['installer_addon_exist'],$installer->addata['basename'],$previous['fullname']));
				break;
			}
			$wowdb->query('INSERT INTO `'.ROSTER_ADDONTABLE.'` VALUES (NULL,"'.$installer->addata['basename'].'","'.$installer->addata['version'].'","'.(int)$installer->addata['hasconfig'].'",0,"'.$installer->addata['fullname'].'","'.$installer->addata['description'].'","'.$wowdb->escape(serialize($installer->addata['credits'])).'")');
			$installer->addata['addon_id'] = $wowdb->insert_id();
			$success = $addon->install();
			$installer->sql[] = 'UPDATE `'.ROSTER_ADDONTABLE.'` SET `active`='.(int)$installer->addata['active'];
			break;

		case 'upgrade':
			if (!$previous)
			{
				$installer->seterrors(sprintf($act_words['installer_no_upgrade'],$installer->addata['basename']));
				break;
			}
			if (!in_array($previous['basename'],$addon->upgrades))
			{
				$installer->seterrors(sprintf($act_words['installer_not_upgradable'],$addon->name,$previous['name'],$previous['basename']));
				break;
			}

			$wowdb->query('UPDATE `'.ROSTER_ADDONTABLE.'` SET `basename`="'.$installer->addata['basename'].'", `version`="'.$installer->addata['version'].'", `hasconfig`='.$installer->addata['hasconfig'].', `active`='.$installer->addata['active'].', `fullname`="'.$installer->addata['fullname'].'", `description`="'.$installer->addata['description'].'", `credits`="'.serialize($installer->addata['credits']).'" WHERE `addon_id`='.$previous['addon_id']);
			$installer->addata['addon_id'] = $previous['addon_id'];
			$success = $addon->upgrade($previous['basename'],$previous['version']);
			break;

		case 'uninstall':
			if (!$previous)
			{
				$installer->seterrors(sprintf($act_words['installer_no_uninstall'],$installer->addata['basename']));
				break;
			}
			if ($previous['basename'] != $installer->addata['basename'])
			{
				$installer->seterrors(sprintf($act_words['installer_not_uninstallable'],$installer->addata['basename'],$previous['fullname']));
				break;
			}
			$wowdb->query('DELETE FROM `'.ROSTER_ADDONTABLE.'` WHERE `addon_id`='.$previous['addon_id']);
			$installer->addata['addon_id'] = $previous['addon_id'];
			$success = $addon->uninstall();
			break;

		case 'purge':
			$success = purge($installer->addata['basename']);
			break;

		default:
			$installer->seterrors($act_words['installer_invalid_type']);
			$success = false;
			break;
	}

	if (!$success)
	{
		$installer->seterrors($act_words['installer_no_success_sql']);
	}
	else
	{
		$success = $installer->install();
		$installer->setmessages(sprintf($act_words['installer_'.$_POST['type'].'_'.$success],$installer->addata['basename']));
	}
	return true;
}


function purge($dbname)
{
	global $installer, $wowdb, $act_words;

	// Delete addon tables under dbname.
	$query = 'SHOW TABLES LIKE "'.$wowdb->db_prefix.'addons_'.$dbname.'%"';
	$tables = $wowdb->query($query);
	if( !$tables )
	{
		$installer->seterrors('Error while getting table names for '.$dbname.'. MySQL said: '.$wowdb->error(),$act_words['installer_error'],__FILE__,__LINE__,$query);
		return false;
	}
	if ($wowdb->num_rows($tables))
	{
		while ($row = $wowdb->fetch_row($tables))
		{
			$query = 'DROP TABLE `'.$row[0].'`';
			$dropped = $wowdb->query($query);
			if( !$dropped )
			{
				$installer->seterrors('Error while dropping '.$row[0].'.<br />MySQL said: '.$wowdb->error(),$act_words['installer_error'],__FILE__,__LINE__,$query);
				return false;
			}
			$wowdb->free_result($dropped);
		}
	}
	$wowdb->free_result($tables);

	// Delete menu entries
	$query = 'DELETE FROM `'.ROSTER_ADDONMENUTABLE.'` WHERE `addon_name` = "'.$dbname.'"';
	$wowdb->query($query) or $installer->seterrors('Error while deleting menu entries for '.$dbname.'.<br />MySQL said: '.$wowdb->error(),$act_words['installer_error'],__FILE__,__LINE__,$query);

	// Delete addon table entry
	$query = 'DELETE FROM `'.ROSTER_ADDONTABLE.'` WHERE `basename` = "'.$dbname.'"';
	$wowdb->query($query) or $installer->seterrors('Error while deleting addon table entry for '.$dbname.'.<br />MySQL said: '.$wowdb->error(),$act_words['installer_error'],__FILE__,__LINE__,$query);

	return true;
}
