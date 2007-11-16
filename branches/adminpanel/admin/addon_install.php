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


if( isset($_POST['op']) )
{
	$op = $_POST['op'];
}
else
{
	$op = '';
}

if( isset($_POST['id']) )
{
	$id = $_POST['id'];
}
else
{
	$id = '';
}

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



// Generate addons table
$output =
'<table class="bodyline" cellspacing="0">
	<tr>
		<th class="membersHeader">Icon</th>
		<th class="membersHeader">Addon Info</th>
		<th class="membersHeader">Status</th>
		<th class="membersHeaderRight">Installation</th>
	</tr>
';
foreach( getAddonList() as $addon )
{
	$output .= '	<tr>
		<td class="membersRowCell membersRowColor1">[icon]</td>
		<td class="membersRowCell membersRowColor1"><table cellpadding="0" cellspacing="0">
			<tr>
				<td><span style="font-size:18px;" class="green">'.ucfirst($addon['fullname']).'</span> v'.$addon['version'].'</td>
			</tr>
			<tr>
				<td>'.$addon['description'].'</td>
			<tr>
				<td><span class="blue">Author: '.$addon['author'].'</span></td>
			</tr>
		</table></td>
		<td class="membersRowCell membersRowColor1">'.( $addon['install'] == 3 ? '&nbsp;' : activeInactive($addon['active'],$addon['id']) ).'</td>
		<td class="membersRowRightCell membersRowColor1">'.installUpgrade($addon['install'],$addon['basename']).'</td>
	</tr>
';
}
$output .= '</table>';


$errorstringout = $installer->geterrors();
$messagestringout = $installer->getmessages();
$sqlstringout = $installer->getsql();

$message = '<br />';

// print the error messages
if( !empty($errorstringout) )
{
	$message .= messagebox($errorstringout,'Install Errors','sred','550px').'<br />';
}

// Print the update messages
if( !empty($messagestringout) )
{
	$message .= messagebox($messagestringout,'Addon Manager Log','syellow','550px');
}

// Print the SQL messages
if( $roster_conf['sqldebug'] && !empty($sqlstringout) )
{
	$message .= '<br />'.scrollboxtoggle(nl2br($sqlstringout),'SQL Queries','sgreen');
}

$body = messagebox($output,'Addons','sblue').$message;





/**
 * Gets the current action for active/inactive
 *
 * @param string $mode
 * @param int $id
 * @return string
 */
function activeInactive( $mode,$id )
{
	global $script_filename, $roster_conf;

	if( $mode )
	{
		$type = '<form name="deactivate_'.$id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="deactivate" />
		<input type="hidden" name="id" value="'.$id.'" />
		<input '.makeOverlib('Click to Deactivate','Activated').'type="image" src="'.$roster_conf['img_url'].'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	else
	{
		$type = '<form name="activate_'.$id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="activate" />
		<input type="hidden" name="id" value="'.$id.'" />
		<input '.makeOverlib('Click to Activate','Deactivated').' type="image" src="'.$roster_conf['img_url'].'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
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
	global $roster_conf, $script_filename;

	if( $mode == -1 )
	{
		$type = '<img '.makeOverlib('Replace files with latest version','Old Version Overwrite').' src="'.$roster_conf['img_url'].'admin/purple.png" style="height:16px;width:16px;border:0;" alt="" />';
	}
	elseif( $mode == 0 )
	{
		$type = '<form name="uninstall_'.$name.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="'.$name.'" />
		<input type="hidden" name="dbname" value="'.$name.'" />
		<input type="hidden" name="type" value="uninstall" />
		<input '.makeOverlib('Click to Uninstall','Installed').'type="image" src="'.$roster_conf['img_url'].'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	elseif( $mode == 1 )
	{
		$type = '<form name="upgrade_'.$name.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="'.$name.'" />
		<input type="hidden" name="dbname" value="'.$name.'" />
		<input type="hidden" name="type" value="upgrade" />
		<input '.makeOverlib('Click to Upgrade','Upgrade Available').'type="image" src="'.$roster_conf['img_url'].'admin/blue.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	elseif( $mode == 3 )
	{
		$type = '<form name="install_'.$name.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="process" />
		<input type="hidden" name="addon" value="'.$name.'" />
		<input type="hidden" name="dbname" value="'.$name.'" />
		<input type="hidden" name="type" value="install" />
		<input '.makeOverlib('Click to Install','Not Installed').'type="image" src="'.$roster_conf['img_url'].'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
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
	global $roster_conf, $wordings, $wowdb, $installer;

	$addonsPath = ROSTER_BASE.'addons'.DIR_SEP;

	// Initialize output
	$addons = '';
	$output = '';

	if ($handle = opendir($addonsPath))
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
			$installfile = $addonsPath.$addon.DIR_SEP.'install.def.php';

			if (file_exists($installfile))
			{
				include_once($installfile);

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
	global $wowdb, $installer;

	$query = "UPDATE `".$wowdb->table('addon')."` SET `active` = '$mode' WHERE `addon_id` = '$id' LIMIT 1;";
	$result = $wowdb->query($query);
	if (!$result)
		$installer->seterrors($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
	else
	{
		$mode = ( $mode ? 'Activated' : 'Deactivated' );
		$installer->setmessages('Addon '.$mode);
	}
}


/**
 * Addon installer/upgrader/uninstaller
 *
 */
function processAddon()
{
	global $wowdb, $installer, $roster_conf, $wordings;

	// Include addon install definitions
	$addonDir = ROSTER_ADDONS.$_POST['addon'].DIR_SEP;
	if (!file_exists($addonDir.'install.def.php'))
	{
		$installer->seterrors('Files for '.$_POST['addon'].' are not correctly installed','Roster Addon Installer');
		return;
	}

	require($addonDir.'install.def.php');

	$addon = new $_POST['addon']($_POST['dbname']);
	$addata = escape_array((array)$addon);
	$addata['dbname'] = $_POST['dbname'];
	$addata['basename'] = $_POST['addon'];

	if ($addata['dbname'] == '')
	{
		$installer->seterrors('Cannot install in empty dbname','Roster Addon Installer');
		return;
	}


	// Get existing addon record if available
	$query = 'SELECT * FROM `'.ROSTER_ADDONTABLE.'` WHERE `dbname` = "'.$addata['dbname'].'"';
	$result = $wowdb->query($query);
	if( !$result )
	{
		$installer->seterrors('Failed to fetch addon data for '.$addata['dbname'].'.<br />MySQL said: '.$wowdb->error(),'Roster Addon Installer');
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
				$installer->seterrors('Dbname '.$installer->addata['dbname'].' already contains '.$previous['fullname'].'. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different dbname.');
				break;
			}
			$wowdb->query('INSERT INTO `'.ROSTER_ADDONTABLE.'` VALUES (0,"'.$installer->addata['basename'].'","'.$installer->addata['dbname'].'","'.$installer->addata['version'].'","'.$installer->addata['hasconfig'].'",0,"'.$installer->addata['fullname'].'","'.$installer->addata['description'].'","'.$wowdb->escape(serialize($installer->addata['credits'])).'")');
			$installer->addata['addon_id'] = $wowdb->insert_id();
			$success = $addon->install();
			$installer->sql[] = 'UPDATE `'.ROSTER_ADDONTABLE.'` SET `active`='.(int)$installer->addata['active'];
			break;

		case 'upgrade':
			if (!$previous)
			{
				$installer->seterrors('Dbname '.$installer->addata['dbname'].' doesn\`t contain an addon to upgrade from');
				break;
			}
			if (!in_array($previous['basename'],$addon->upgrades))
			{
				$installer->seterrors($addon->name.' cannot upgrade '.$previous['name'].' since its basename '.$previous['basename'].' isn\'t in the list of upgradable addons.');
				break;
			}

			$wowdb->query('UPDATE `'.ROSTER_ADDONTABLE.'` SET `basename`="'.$installer->addata['basename'].'", `dbname`="'.$installer->addata['dbname'].'", `version`="'.$installer->addata['version'].'", `hasconfig`='.$installer->addata['hasconfig'].', `active`='.$installer->addata['active'].', `fullname`="'.$installer->addata['fullname'].'", `description`="'.$installer->addata['description'].'", `credits`="'.serialize($installer->addata['credits']).'" WHERE `addon_id`='.$previous['addon_id']);
			$installer->addata['addon_id'] = $previous['addon_id'];
			$success = $addon->upgrade($previous['basename'],$previous['version']);
			break;

		case 'uninstall':
			if (!$previous)
			{
				$installer->seterrors('Dbname '.$installer->addata['dbname'].' doesn\'t contain an addon to uninstall');
				break;
			}
			if ($previous['basename'] != $installer->addata['basename'])
			{
				$installer->seterrors($installer->addata['dbname'].' contains an addon with basename '.$previous['basename'].' which must be uninstalled with that addon\'s uninstaller.');
				break;
			}
			$wowdb->query('DELETE FROM `'.ROSTER_ADDONTABLE.'` WHERE `addon_id`='.$previous['addon_id']);
			$installer->addata['addon_id'] = $previous['addon_id'];
			$success = $addon->uninstall();
			break;

		case 'purge':
			$success = purge($installer->addata['dbname']);
			break;

		default:
			$installer->seterrors('Invalid install type '.$_POST['type']);
			$success = false;
			break;
	}

	if (!$success)
	{
		$installer->seterrors('Queries were not successfully added to the installer');
	}
	else
	{
		$success = $installer->install();
		$installer->setmessages($wordings[$roster_conf['roster_lang']]['installer_'.$_POST['type']].' of '.$installer->addata['dbname'].' '.
			$wordings[$roster_conf['roster_lang']]['installer_success'.$success]);
	}
	return true;
}


function purge($dbname)
{
	global $installer, $db_prefix, $wowdb;

	// Delete addon tables under dbname.
	$query = 'SHOW TABLES LIKE "'.$db_prefix.'addons_'.$dbname.'_%"';
	$tables = $wowdb->query($query);
	if( !$tables )
	{
		$installer->seterrors('Error while getting table names for '.$dbname.'. MySQL said: '.$wowdb->error(),'Roster Addon Installer',__FILE__,__LINE__,$query);
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
				$installer->seterrors('Error while dropping '.$row[0].'.<br />MySQL said: '.$wowdb->error(),'Roster Addon Installer',__FILE__,__LINE__,$query);
				return false;
			}
			$wowdb->free_result($dropped);
		}
	}
	$wowdb->free_result($tables);

	// Delete menu entries
	$query = 'DELETE FROM `'.ROSTER_ADDONMENUTABLE.'` WHERE `addon_name` = "'.$dbname.'"';
	$wowdb->query($query) or $installer->seterrors('Error while deleting menu entries for '.$dbname.'.<br />MySQL said: '.$wowdb->error(),'Roster Addon Installer',__FILE__,__LINE__,$query);

	// Delete addon table entry
	$query = 'DELETE FROM `'.ROSTER_ADDONTABLE.'` WHERE `dbname` = "'.$dbname.'"';
	$wowdb->query($query) or $installer->seterrors('Error while deleting addon table entry for '.$dbname.'.<br />MySQL said: '.$wowdb->error(),'Roster Addon Installer',__FILE__,__LINE__,$query);

	return true;
}

?>