<?php
/**
 * WoWRoster.net WoWRoster
 *
 * RosterCP (Control Panel)
 * After Install Guide
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

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Check log-in ]-------------------------------------
if( ! $roster->auth->getAuthorized( ROSTERLOGIN_ADMIN ) )
{
	echo '<span class="title_text">' . $roster->locale->act['setup_guide'] . '</span><br />'
		. $roster->auth->getLoginForm();

	return;
}
// ----[ End Check log-in ]---------------------------------

$roster->output['body_onload'] .= 'initARC(\'guide\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

$use_temp_tables = explode('|',$roster->locale->act['admin']['use_temp_tables']);
$default_name = explode('|',$roster->locale->act['admin']['default_name']);
$default_desc = explode('|',$roster->locale->act['admin']['default_desc']);

// ----[ Render the page ]----------------------------------
$roster->tpl->assign_vars(array(
	'U_ROSTERCP' => makelink('rostercp'),

	'L_SETUP_GUIDE'       => $roster->locale->act['setup_guide'],
	'L_INITIAL_SETTINGS'  => $roster->locale->act['initial_settings'],
	'L_DEFAULT_DATA'      => $roster->locale->act['default_data'],
	'L_DEFAULT_DATA_HELP' => $roster->locale->act['default_data_help'],

	'L_NAME'              => $roster->locale->act['name'],
	'L_NAME_TIP'          => makeOverlib( $roster->locale->act['guildname'] ),
	'L_SERVER'            => $roster->locale->act['server'],
	'L_SERVER_TIP'        => makeOverlib($roster->locale->act['realmname']),
	'L_REGION'            => $roster->locale->act['region'],
	'L_REGION_TIP'        => makeOverlib($roster->locale->act['regionname']),

	'L_DEFAULT_NAME'            => $default_name[0],
	'L_DEFAULT_NAME_TIP'        => makeOverlib($default_name[1],$default_name[0]),

	'L_DEFAULT_DESC'            => $default_desc[0],
	'L_DEFAULT_DESC_TIP'        => makeOverlib($default_desc[1],$default_desc[0]),

	'L_USE_TEMP_TABLES'     => $use_temp_tables[0],
	'L_USE_TEMP_TABLES_TIP' => makeOverlib($use_temp_tables[1],$use_temp_tables[0]),
	)
);


include(ROSTER_LIB . 'install.lib.php');
$installer = new Install;


$addons = getAddonList();

if( !empty($addons) )
{
	$roster->tpl->assign_vars(array(
		'S_ADDON_LIST' => true,

		'L_ADDONINFO'     => $roster->locale->act['installer_addoninfo'],
		'L_STATUS'        => $roster->locale->act['installer_status'],
		'L_INSTALL'  => $roster->locale->act['install'],
		'L_AUTHOR'        => $roster->locale->act['installer_author'],
		'L_MANAGE_ADDONS' => $roster->locale->act['pagebar_addoninst'],

		'MESSAGE' => '',
		)
	);

	foreach( $addons as $addon )
	{
		$roster->tpl->assign_block_vars('addon_list', array(
			'ROW_CLASS'   => $roster->switch_row_class(),
			'ID'          => ( isset($addon['id']) ? $addon['id'] : '' ),
			'FULLNAME'    => $addon['fullname'],
			'BASENAME'    => $addon['basename'],
			'VERSION'     => $addon['version'],
			'DESCRIPTION' => $addon['description'],
			'AUTHOR'      => $addon['author'],
			)
		);
	}
}
else
{
	$roster->tpl->assign_var('S_ADDON_LIST',false);
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
	$message .= messagebox($messagestringout,$roster->locale->act['installer_log'],'syellow') . '<br />';
}

$roster->tpl->assign_var('MESSAGE',$message);


$roster->tpl->set_filenames(array('guide' => 'install_guide.html'));
$roster->tpl->display('guide');


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

	usort($addons, 'strnatcasecmp');

	if( is_array($addons) )
	{
		foreach( $addons as $addon )
		{
			$installfile = ROSTER_ADDONS . $addon . DIR_SEP . 'inc' . DIR_SEP . 'install.def.php';
			$install_class = $addon . 'Install';

			if( file_exists($installfile) )
			{
				include_once($installfile);

				if( !class_exists($install_class) )
				{
					$installer->seterrors(sprintf($roster->locale->act['installer_no_class'],$addon));
					continue;
				}

				$addonstuff = new $install_class;

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
					$output[$addon]['oldversion'] = $row['version'];

					// -1 = overwrote newer version
					//  0 = same version
					//  1 = upgrade available
					$output[$addon]['install'] = version_compare($addonstuff->version,$row['version']);

				}
				else
				{
					$output[$addon]['install'] = 3;
				}

				// Save current locale array
				// Since we add all locales for localization, we save the current locale array
				// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
				$localetemp = $roster->locale->wordings;

				foreach( $roster->multilanguages as $lang )
				{
					$roster->locale->add_locale_file(ROSTER_ADDONS . $addon . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
				}

				$output[$addon]['basename'] = $addon;
				$output[$addon]['fullname'] = ( isset($roster->locale->act[$addonstuff->fullname]) ? $roster->locale->act[$addonstuff->fullname] : $addonstuff->fullname );
				$output[$addon]['author'] = $addonstuff->credits[0]['name'];
				$output[$addon]['version'] = $addonstuff->version;
				$output[$addon]['icon'] = $addonstuff->icon;
				$output[$addon]['description'] = ( isset($roster->locale->act[$addonstuff->description]) ? $roster->locale->act[$addonstuff->description] : $addonstuff->description );

				unset($addonstuff);

				// Restore our locale array
				$roster->locale->wordings = $localetemp;
				unset($localetemp);
			}
		}
	}
	return $output;
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

	// Check for temp tables
	$old_error_die = $roster->db->error_die(false);
	if( false === $roster->db->query("CREATE TEMPORARY TABLE `test` (id int);") )
	{
		$installer->temp_tables = false;
	}
	else
	{
		$installer->temp_tables = true;
	}
	$roster->db->error_die($old_error_die);

	// Include addon install definitions
	$addonDir = ROSTER_ADDONS . $addon_name . DIR_SEP;
	$addon_install_file = $addonDir . 'inc' . DIR_SEP . 'install.def.php';
	$install_class = $addon_name . 'Install';

	if( !file_exists($addon_install_file) )
	{
		$installer->seterrors(sprintf($roster->locale->act['installer_no_installdef'],$addon_name),$roster->locale->act['installer_error']);
		return;
	}

	require($addon_install_file);

	$addon = new $install_class();
	$addata = escape_array((array)$addon);
	$addata['basename'] = $addon_name;

	if( $addata['basename'] == '' )
	{
		$installer->seterrors($roster->locale->act['installer_no_empty'],$roster->locale->act['installer_error']);
		return;
	}

	// Give the installer the addon data
	$installer->addata = $addata;

	$success = false;


	// Save current locale array
	// Since we add all locales for localization, we save the current locale array
	// This is in case one addon has the same locale strings as another, and keeps them from overwritting one another
	$localetemp = $roster->locale->wordings;

	foreach( $roster->multilanguages as $lang )
	{
		$roster->locale->add_locale_file(ROSTER_ADDONS . $addata['basename'] . DIR_SEP . 'locale' . DIR_SEP . $lang . '.php',$lang);
	}

	// Collect data for this install type
	$query = 'INSERT INTO `' . $roster->db->table('addon') . '` VALUES (NULL,"' . $installer->addata['basename'] . '","' . $installer->addata['version'] . '",0,"' . $installer->addata['fullname'] . '","' . $installer->addata['description'] . '","' . $roster->db->escape(serialize($installer->addata['credits'])) . '","' . $installer->addata['icon'] . '","' . $installer->addata['wrnet_id'] . '",NULL);';
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
		$installer->sql[] = 'UPDATE `' . $roster->db->table('addon') . '` SET `active` = ' . (int)$installer->addata['active'] . " WHERE `addon_id` = '" . $installer->addata['addon_id'] . "';";
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

	// Restore our locale array
	$roster->locale->wordings = $localetemp;
	unset($localetemp);

	return true;
}
