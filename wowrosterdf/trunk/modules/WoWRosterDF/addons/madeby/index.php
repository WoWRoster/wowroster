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
//---------------------------------------------------------
//$header_title = $wordings[$roster_conf['roster_lang']]['MadeBy'];
//require_once ($addonDir.'RecipeList.php');
//echo $content;
//---------------------------------------------------------

if (array_key_exists('action',$_GET))
$_GET['action'] = strtolower($_GET['action']);
else
$_GET['action'] = 'view';

if ($_GET['action'] == 'install')
{
	$dbversion = '0.0.0';
	$action = 'install';
}
elseif (($_GET['action'] == 'upgrade') && (version_compare($dbversion,$fileversion,"<")))
{
	$action = 'install';
}
elseif ($_GET['action'] == 'upgrade') // but we are already up to date
{
	$action = 'cant_upgrade_message';
}
elseif (version_compare($dbversion,$fileversion,"<"))  // If the database version is older than the file version we need to produce notice we need to update
{
	if ($dbversion == '0.0.0')
	$action = 'install_message';
	else
//	$action = 'upgrade_message';
	$action = 'install'; // not supporting upgrades at this time. 
}
else
{
	$action = $_GET['action'];
}

if (($action == 'install') || ($action == 'upgrade') || ($action == 'update') || ($action == 'config'))
{
	include($addonDir.'/login.php');
}

// -[ Begin switch for what we are going to do ]-
switch ($action) {
	case 'install':
//		include($addonDir.'/install.php');
		include('./modules/'.$module_name.'/addons/madeby/install.php');
		break;
	case 'update':
		end();
		include($addonDir.'/update_wrap.php');
		break;
	case 'config':
		include($addonDir.'/config.php');
		break;
	case 'view':
		$header_title = $wordings[$roster_conf['roster_lang']]['MadeBy'];
		require_once ($addonDir.'RecipeList.php');
		echo $content;
		break;
	case 'debug':
		end();
		include($addonDir.'/altlist_wrap.php');
		print_r($addon_conf);
		break;
	case 'install_message':
		message_die('&nbsp;&nbsp;'.$wordings[$roster_conf['roster_lang']]['MadeBy_install_msg'].'&nbsp;&nbsp;'.'<br /><br /><div style="text-align:center;"><span style="border:1px outset white; padding:2px 6px 2px 6px;"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=install').'">Install</a></span></div><br />',
		$wordings[$roster_conf['roster_lang']]['MadeBy_install_header'], 'sblue');
		break;
	case 'upgrade_message':
		message_die('&nbsp;&nbsp;'.$wordings[$roster_conf['roster_lang']]['MadeBy_upgrade_msg'].'&nbsp;&nbsp;<br /><br />'.
		'<table><tr><td align="center"><div style="text-align:center; border:1px outset white; padding:2px 6px 2px 6px;"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=upgrade').'">Update</a></div>'.
		'<td align="center"><div style="text-align:center; border:1px outset white; padding:2px 6px 2px 6px;"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=install').'">install</a></div></table>',
		$wordings[$roster_conf['roster_lang']]['MadeBy_install_header']);
		break;
	case 'cant_upgrade_message':
		message_die('&nbsp;&nbsp;'.$wordings[$roster_conf['roster_lang']]['MadeBy_no_upgrade_msg'].'&nbsp;&nbsp;<br /><br />'.
		'<div style="text-align:center;"><span style="border:1px outset white; padding:2px 6px 2px 6px;"><a href="'.getlink($module_name.'&amp;file=addon&amp;roster_addon_name='.$_GET['roster_addon_name'].'&amp;action=install').'">Reinstall</a></span></div><br />',
		$wordings[$roster_conf['roster_lang']]['MadeBy_install_header']);
		break;
	default:
		message_die($wordings[$roster_conf['roster_lang']]['MadeBy_NoAction_msg'],$wordings[$roster_conf['roster_lang']]['MadeBy_install_header']);
}


?>
