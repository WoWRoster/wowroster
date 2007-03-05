<?php
/******************************
 * WoWRoster.net  UniAdmin
 * Copyright 2002-2007
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

if( !defined('IN_UNIADMIN') )
{
    exit('Detected invalid access to this file!');
}

if( $user->data['level'] == UA_ID_ANON )
{
	ua_die($user->lang['access_denied']);
}

include(UA_INCLUDEDIR.'addon_lib.php');



session_start();




/**
 * WoWAce Addon Page Functions
 */

if( isset($_POST[UA_URI_OP]) )
{
	process_wowace_addons();
}

// Assign template vars
$tpl->assign_vars(array(
	'L_WOWACE_ADDONS'  => $user->lang['get_wowace_addons'],
	'L_NOLIST'         => $user->lang['error_no_wowace_addons'],
	'L_NAME'           => $user->lang['name'],
	'L_DOWNLOAD'       => $user->lang['download'],
	'L_GO'             => $user->lang['go'],
	'L_NOTES'          => $user->lang['notes']
	)
);

$ace_error = false;
$ace_file = UA_CACHEDIR.'descript.ion';

if( !file_exists($ace_file) )
{
	$filelist = $uniadmin->get_remote_contents('http://files.wowace.com/descript.ion');
	$uniadmin->message($user->lang['new_wowace_list']);

	$uniadmin->write_file($ace_file,$filelist);
}
else
{
	clearstatcache();
	$file_info = stat($ace_file);
	if( ($file_info['9'] + (60 * 60 * $uniadmin->config['remote_timeout'])) <= time() )
	{
		// Download List
		$filelist = $uniadmin->get_remote_contents('http://files.wowace.com/descript.ion');
		$uniadmin->message($user->lang['new_wowace_list']);

		$uniadmin->write_file($ace_file,$filelist);
		clearstatcache();
		$file_info = stat($ace_file);
		$tpl->assign_var('WOWACE_UPDATED',date($user->lang['time_format'],$file_info['9']) );
	}
	else
	{
		// Keep the old file
		$filelist = file_get_contents($ace_file);
		$tpl->assign_var('WOWACE_UPDATED',date($user->lang['time_format'],$file_info['9']) );
	}
}

if( !empty($filelist) )
{
	$tpl->assign_var('S_ACELIST', true);
	$tpl->assign_var('ONLOAD'," onload=\"initARC('ua_wowace','radioOn', 'radioOff','checkboxOn', 'checkboxOff');\"");

	preg_match_all("/(.*?)\t(.*)/", $filelist, $results);

	$count = count($results[0]);

	for($i = 0; $i < $count; $i++)
	{
		$waaddons[$results[1][$i]] = $results[2][$i];
	}

	uksort($waaddons, 'strnatcasecmp');

	$checkboxes = '';

	$id = 0;
	foreach( $waaddons as $addon => $description )
	{
		$description = preg_replace('/\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r/i','<span style="color:#$1;">$2</span>',htmlentities($description));
		// Assign template vars
		$tpl->assign_block_vars('addons_row', array(
			'ROW_CLASS'   => $uniadmin->switch_row_class(),
			'ID'          => 'addon_'.$id,
			'NAME'        => $addon,
			'DESC'        => $description
			)
		);
		$_SESSION['addon_'.$id] = $addon;
		$id++;
	}
}
else
{
	$tpl->assign_var('S_ACELIST', false);
}

$uniadmin->set_vars(array(
	'page_title'    => $user->lang['title_wowace'],
	'template_file' => 'wowace.html',
	'display'       => true
	)
);


function process_wowace_addons( )
{
	global $uniadmin, $user;

	foreach( $_POST as $addon => $dl )
	{
		if( $dl == 'on' )
		{
			$download[] = $addon;
		}
	}

	foreach( $download as $key => $addon )
	{
		$addon = $_SESSION[$addon];

		$addoncon = $uniadmin->get_remote_contents("http://files.wowace.com/$addon/$addon.zip");
		$filename = UA_BASEDIR.$uniadmin->config['addon_folder'].DIR_SEP."$addon.zip";

		$write_temp_file = $uniadmin->write_file($filename,$addoncon,'w+');

		if( $write_temp_file === false )
		{
			$uniadmin->error(sprintf($user->lang['error_write_file'],str_replace('\\','/',$filename)));
		}
		else
		{
			$toPass = array();
			$toPass['name'] = $addon.'.zip';
			$toPass['type'] = 'application/zip';
			$toPass['tmp_name'] = $filename;
			$toPass['file_name'] = 'http://files.wowace.com/'.$addon.'/'.$addon.'.zip';

			if( is_readable($toPass['tmp_name']) )
			{
				$toPass['error'] = 0;
			}
			else
			{
				$toPass['error'] = 1;
			}
			$toPass['size'] = filesize($toPass['tmp_name']);
			process_addon($toPass);
		}
	}
}
