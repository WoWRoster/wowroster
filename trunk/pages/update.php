<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LUA update interface
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
 * @subpackage LuaUpdate
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Include update lib
require_once(ROSTER_LIB.'update.lib.php');
$update = new update;

// See if UU is requesting this page
if( eregi('uniuploader',$_SERVER['HTTP_USER_AGENT']) )
{
	$update->textmode = true;
}

// Set template vars
$roster->tpl->assign_vars(array(
	'S_DATA'           => false,
	'S_RESPONSE'       => false,
	'S_RESPONSE_ERROR' => false,
	'S_PASS'           => true,

	'L_SAVE_ERROR_LOG' => $roster->locale->act['save_error_log'],
	'L_SAVE_LOG'       => $roster->locale->act['save_update_log'],

	'L_UPDATE_ERRORS'  => $roster->locale->act['update_errors'],
	'L_UPDATE_LOG'     => $roster->locale->act['update_log'],

	'L_UPDATE_PAGE'    => $roster->locale->act['update_page'],
	'L_UPDATE'         => $roster->locale->act['upload'],
	'L_PASSWORD'       => $roster->locale->act['password'],
	'L_PASSWORD_REQ'   => $roster->locale->act['roster_upd_pwLabel'],
	'L_PASSWORD_HELP'  => $roster->locale->act['roster_upd_pw_help'],

	'MESSAGES' => ''
	)
);

// Fetch addon data
$update->fetchAddonData();


// Has data been uploaded?
if( (isset($_POST['process']) && $_POST['process'] == 'process') || $update->textmode )
{
	$messages  = $update->loadBlinds();
	$messages .= $update->parseFiles();
	$messages .= $update->processFiles();

	$errors = $update->getErrors();

	// Normal upload results
	if( !$update->textmode )
	{
		$roster->tpl->assign_var('S_RESPONSE',true);

		// print the error messages
		if( !empty($errors) )
		{
			// We have errors
			$roster->tpl->assign_vars(array(
				'S_RESPONSE_ERROR'   => true,
				'RESPONSE_ERROR'     => $errors,
				'RESPONSE_ERROR_LOG' => htmlspecialchars(stripAllHtml($errors)),
				)
			);
		}

		$roster->tpl->assign_vars(array(
			'RESPONSE'      => $messages,
			'RESPONSE_POST' => htmlspecialchars(stripAllHtml($messages)),
			)
		);

		$roster->tpl->set_filenames(array('body' => 'update.html'));
		$roster->tpl->display('body');
	}
	else
	{
		// No-HTML result page for UU
		print stripAllHtml($messages);

		$roster->output['show_header'] = false;
		$roster->output['show_menu'] = false;
		$roster->output['show_footer'] = false;
	}
}
else
{
	// No data uploaded, so return upload form
	foreach( $update->files as $file )
	{
		$roster->tpl->assign_block_vars('file_fields',array(
			'TOOLTIP' => makeOverlib('<i>*WOWDIR*</i>\\\\WTF\\\\Account\\\\<i>*ACCOUNT_NAME*</i>\\\\SavedVariables\\\\' . $file . '.lua',$file . '.lua Location','',2,'',',WRAP'),
			'FILE' => $file
			)
		);
	}

	if( $roster->auth->getAuthorized($roster->config['gp_user_level']) ||
		$roster->auth->getAuthorized($roster->config['cp_user_level']) ||
		$roster->auth->getAuthorized($roster->config['lua_user_level']) )
	{
		$roster->tpl->assign_var('S_PASS',false);
	}

	$messages = $update->getErrors();
	$roster->tpl->assign_var('MESSAGES',$messages);

	$roster->tpl->set_filenames(array('body' => 'update.html'));
	$roster->tpl->display('body');
}
