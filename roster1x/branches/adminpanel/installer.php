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

/******************************
 * Call parameters:
 *
 * addon        Directory the addon is installed in
 * dbname       The database name for this addon installation. Used as key in
 *              the installed addons table and is part of the DB prefix for this
 *              addon installation
 * type         install, upgrade, uninstall, purge
 ******************************/

// Include roster environment and install library
require_once('settings.php');
include(ROSTER_BASE.'lib'.DIR_SEP.'install.lib.php');

// Include addon install definitions
$addonDir = ROSTER_BASE.'addons'.DIR_SEP.$_GET['addon'].DIR_SEP;
if (!file_exists($addonDir.'install.def.php'))
	die_quietly('Files for '.$_GET['addon'].' are not correctly installed','Roster Addon Installer');
require($addonDir.'install.def.php');
$addon = new $_GET['addon']($_GET['dbname']);
$addata = (array)$addon;
$addata['dbname'] = $_GET['dbname'];
$addata['basename'] = $_GET['addon'];

// Get existing addon record if available
$query = 'SELECT * FROM `'.$db_prefix.'addon` WHERE `dbname` = "'.$addata['dbname'].'"';
$result = $wowdb->query($query) or die_quietly('Failed to fetch addon data for '.$addata['dbname'].'. MySQL said: '.$wowdb->error(),'Roster Addon Installer');
$previous = $wowdb->fetch_assoc($result);
$wowdb->free_result($result);


// Collect data for this install type
switch ($_GET['type']) {
case 'install':
	if ($previous) {
		$installer->errors[] = 'Dbname '.$addata['dbname'].' already contains '.$previous['name'].'. You can go back and uninstall that addon first, or upgrade it, or install this addon with a different dbname.';
		break;
	}
	$success = $addon->install();
	$installer->sql[] = 'INSERT INTO `'.$db_prefix.'addon` VALUES (0,"'.$addata['basename'].'","'.$addata['dbname'].'","'.$addata['version'].'",'.(int)$addata['hasconfig'].','.(int)$addata['hastrigger'].','.(int)$addata['active'].')';
	break;
case 'upgrade':
	if (!$previous) {
		$installer->errors[] = 'Dbname '.$addata['dbname'].' doesn\`t contain an addon to upgrade from';
		break;
	}
	if (in_array($previous['basename'],$addon->upgrades))
		$success = $addon->upgrade($previous['basename'],$previous['version']);
	else {
		$installer->errors[] = $addon->name.' cannot upgrade '.$previous['name'].' since its basename '.$previous['basename'].' isn\'t in the list of upgradable addons.';
		break;
	}
	$installer->sql[] = 'UPDATE `'.$db_prefix.'addon` SET `basename`="'.$addata['basename'].'", `dbname`="'.$addata['dbname'].'", `version`="'.$addata['version'].'", `hasconfig`='.(int)$addata['hasconfig'].', `hastrigger`='.(int)$addata['hastrigger'].', `active`='.(int)$addata['active'].' WHERE `addon_id`='.$previous['addon_id'];
	break;
case 'uninstall':
	if (!$previous) {
		$installer->errors[] = 'Dbname '.$addata['dbname'].' doesn\'t contain an addon to uninstall';
		break;
	}
	if ($previous['basename'] == $addata['basename'])
		$success = $addon->uninstall();
	else {
		$installer->errors[] = $addata['dbname'].' contains an addon with basename '.$previous['basename'].' which must be uninstalled with that addon\'s uninstaller.';
		break;
	}
	$installer->sql[] = 'DELETE FROM `'.$db_prefix.'addon` WHERE `addon_id`='.$previous['addon_id'];
	break;
case 'purge':
	$success = purge($addata['dbname']); // Purge is always valid. It also runs its own queries since rolling back would be rather besides the point.
default:
	$installer->errors[] = 'Invalid install type '.$_GET['type'];
	break;
}

if (!success)
	$installer->errors[] = 'Queries were not successfully added to the installer';
else {
	$success = $installer->install();
	switch ($success) {
	case 0:
		$installer->messages[] = '<p>Installation successful</p>';
		break;
	case 1:
		$installer->messages[] = '<p>Installation failed, but rollback was successful</p>';
		break;
	case 2:
		$installer->messages[] = '<p>Installation failed, rollback also failed. Purge and reinstall is probably the easiest way</p>';
		break;
	default:
		$installer->messages[] = '<p>Installation result unknown</p>';
		break;
	}
}

$errorstringout = $installer->geterrors();
$messagestringout = $installer->getmessages();
$sqlstringout = $installer->getsql;


// Time to build the page

include('roster_header.tpl');
// print the error messages
if( !empty($errorstringout) )
{
	print
	'<div id="errorCol" style="display:inline;">
		'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" /><span class=\"red\">Install Errors</span></div>").'
		'.border('sred','end').'
	</div>
	<div id="error" style="display:none">
	'.border('sred','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('errorCol','error')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" /><span class=\"red\">Install Errors</span></div>").
	$errorstringout.
	border('sred','end').
	'</div>';

	// Print the downloadable errors separately so we can generate a download
	print "<br />\n";
	print '<form method="post" action="update.php" name="post">'."\n";
	print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($errorstringout)).'" />'."\n";
	print '<input type="hidden" name="send_file" value="error" />'."\n";
	print '<input type="submit" name="download" value="Save Error Log" />'."\n";
	print '</form>';
	print "<br />\n";
}

// Print the update messages
print
	border('syellow','start','Update Log').
	'<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:300px;width:550px;overflow:auto;">'.
	$messagestringout.
	'</div>'.
	border('syellow','end');

// Print the downloadable messages separately so we can generate a download
print "<br />\n";
print '<form method="post" action="'.$roster_conf['roster_dir'].'/admin/update.php" name="post">'."\n";
print '<input type="hidden" name="data" value="'.htmlspecialchars(stripAllHtml($messagestringout)).'" />'."\n";
print '<input type="hidden" name="send_file" value="update" />'."\n";
print '<input type="submit" name="download" value="Save Update Log" />'."\n";
print '</form>';
print "<br />\n";

if( $roster_conf['sqldebug'] )
{
	print
	'<div id="sqlDebugCol" style="display:inline;">
		'.border('sgray','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('sqlDebugCol','sqlDebug')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />SQL Queries</div>").'
		'.border('sgray','end').'
	</div>
	<div id="sqlDebug" style="display:none">
	'.border('sgreen','start',"<div style=\"cursor:pointer;width:550px;\" onclick=\"swapShow('sqlDebugCol','sqlDebug')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />SQL Queries</div>").'
	<div style="font-size:10px;background-color:#1F1E1D;text-align:left;height:300px;width:560px;overflow:auto;">'.
		nl2br(sql_highlight($sqlstringout)).
	'</div>
	'.border('sgreen','end').
	'</div>';


	// Print the downloadable sql separately so we can generate a download
	print "<br />\n";
	print '<form method="post" action="'.$roster_conf['roster_dir'].'/admin/update.php" name="post">'."\n";
	print '<input type="hidden" name="data" value="'.htmlspecialchars($sqlstringout).'" />'."\n";
	print '<input type="hidden" name="send_file" value="sql" />'."\n";
	print '<input type="submit" name="download" value="Save SQL Log" />'."\n";
	print '</form>';
}
include('roster_footer.tpl');

function purge($dbname) {
	global $installer, $db_prefix;
	// Delete addon tables under dbname.	
	$query = 'SHOW TABLES LIKE `'.$db_prefix.'addons_'.$dbname.'_%';
	$tables = $wowdb->query($query) or die_quietly('Error while getting table names for '.$dbname.'. MySQL said: '.$wowdb->error(),'Roster Addon Installer');
	if ($wowdb->num_rows($tables)) {
		while ($row = $wowdb->fetch_assoc($tables)) {
			$query = 'DROP TABLE `'.$row[0].'`';
			$dropped = $wowdb->query($query) or die_quetly('Error while dropping '.$row[0].'. MySQL said: '.$wowdb->error(),'Roster Addon Installer');
			$wowdb->free_result($dropped);
		}
	}
	$wowdb->free_result($tables);
	
	// Delete menu entries
	$query = 'DELETE FROM `'.$db_prefix.'addon_menu` WHERE `addon_name` = "'.$dbname.'"';
	$wowdb->query($query) or die_quietly('Error while deleting menu entries for '.$dbname.'. MySQL said: '.$wowdb->error(),'Roster Addon Installer');
	
	// Delete addon table entry
	$query = 'DELETE FROM `'.$db_prefix.'addon` WHERE `dbname` = "'.$dbname.'"';
	$wowdb->query($query) or die_quietly('Error while deleting addon table entry for '.$dbname.'. MySQL said: '.$wowdb->error(),'Roster Addon Installer');
	
	return true;
}
?>