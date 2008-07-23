<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /admin/index.php
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id: index.php 409 2008-07-02 01:58:28Z Zanix $
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

// Bad monkey! You can view this directly. And you are stupid for trying. HA HA, take that!
if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Set the Title Text ]-------------------------------
$roster->output['title'] .= $roster->locale->act['menu_siggen_config'];


// ----[ Include the Color Palet js ]-----------------------
$roster->output['html_head'] .= "<script type=\"text/javascript\" src=\"" .  ROSTER_PATH  . "js/color_functions.php?path=" . $roster->config['theme_path'] . "\"></script>\n";


// ----[ Clear file status cache ]--------------------------
clearstatcache();


// ----[ Check for required files ]-------------------------
if( !defined('SIGCONFIG_CONF') )
{
	print errorMode($roster->locale->act['config_notfound'],$roster->locale->act['fatal_error']);
	return;
}

$siggen_functions_file = SIGGEN_DIR . 'inc' . DIR_SEP . 'functions.inc';
if( file_exists($siggen_functions_file) )
{
	require_once( $siggen_functions_file );
	$functions = new SigConfigClass;
}
else
{
	print errorMode(sprintf($roster->locale->act['functions_notfound'],str_replace(DIR_SEP,'/',$siggen_functions_file)),$roster->locale->act['fatal_error']);
	return;
}
// ----[ End Check for required files ]---------------------



// ----[ Get the post/cookie variables ]--------------------
if( isset($_POST['config_mode']) && $_POST['config_mode'] == 'switch' )
{
	$config_name = $_POST['config_name'];
	setcookie($addon['basename'] . '_configname',$config_name,0,'/');
}
elseif( isset($_COOKIE[$addon['basename'] . '_configname']) )
{
	$config_name = $_COOKIE[$addon['basename'] . '_configname'];
}
else
{
	$config_name = 'signature';
	setcookie($addon['basename'] . '_configname',$config_name,0,'/');
}

// Delete/Create configs
if( isset($_POST['config_mode']) )
{
	switch( $_POST['config_mode'] )
	{
		case 'new':
			$functions->new_config($_POST['config_name']);
			break;

		case 'delete':
			$functions->delete_config($_POST['config_name']);
			$config_name = 'signature';
			setcookie($addon['basename'] . '_configname',$config_name,0,'/');
			break;
	}
}

// Name to test siggen with
if( isset($_POST['name_test']) )
{
	if( $_POST['name_test'] == '' )
	{
		$name_test = '';
		setcookie( $addon['basename'] . '_nametest',$name_test,0,'/' );
	}
	else
	{
		$name_test = stripslashes($_POST['name_test']);
		setcookie( $addon['basename'] . '_nametest',$name_test,0,'/' );
	}
}
elseif( isset($_COOKIE[$addon['basename'] . '_nametest']) )
{
	$name_test = $_COOKIE[$addon['basename'] . '_nametest'];
}
else
{
	$name_test = '';
}
// ----[ End of the post/cookie variables ]-----------------



// ----[ Check for the required fields ]--------------------
// Get the current configuration
$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );



// ----[ Check db version for upgrade ]---------------------
if( $checkData['db_ver'] != $sc_db_ver )
{
	print errorMode($roster->locale->act['upgrade_siggen']);
	return;
}



// ----[ Decide what to do next ]---------------------------
if( isset($_POST['sc_op']) && $_POST['sc_op'] != '' )
{
	switch ( $_POST['sc_op'] )
	{
		case 'delete_image':
			$functions->deleteImage( SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir'],$_POST['image_name'] );
			break;

		case 'reset_defaults':
			$functions->resetDefaults( $_POST['confirm_reset'],$config_name );
			// Re-get the configuration, since we just changed it
			$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );
			break;

		case 'import':
			$functions->importSettings( $checkData,$config_name );
			// Re-get the configuration, since we just changed it
			$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );
			break;

		case 'export':
			$functions->exportSettings( $checkData,$config_name );
			break;

		case 'upload_image':
			$functions->uploadImage( SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir'],$_POST['image_name'] );
			break;

		case 'process':
			$functions->processData( $_POST,$config_name,$checkData );
			// Re-get the configuration, since we just changed it
			$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );
			break;

		default:
			break;
	}
}
// ----[ End Decide what to do next ]-----------------------



// ----[ Fix Saved Images Directory Since it is Special ]---
$siggen_saved_find = array('/', '%r', '%s');
$siggen_saved_rep  = array(DIR_SEP, ROSTER_BASE, SIGGEN_DIR);

$checkData['save_images_dir'] = str_replace( $siggen_saved_find,$siggen_saved_rep,$checkData['save_images_dir'] );



// ----[ Run folder maker ]---------------------------------
if( isset($_GET['make_dir']) && $_GET['make_dir'] == 'save' )
{
	if( $functions->makeDir( $checkData['save_images_dir'] ) )
	{
		$functions->setMessage($roster->locale->act['saved_folder_created']);
	}
	else
	{
		$functions->setMessage($roster->locale->act['saved_folder_not_created_manual']);
	}
}

if( isset($_GET['make_dir']) && $_GET['make_dir'] == 'chmodsave' )
{
	if( $functions->checkDir( $checkData['save_images_dir'],1,1 ) )
	{
		$functions->setMessage($roster->locale->act['saved_folder_chmoded']);
	}
	else
	{
		$functions->setMessage($roster->locale->act['saved_folder_not_chmoded_manual']);
	}
}


if( isset($_GET['make_dir']) && $_GET['make_dir'] == 'user' )
{
	if( $functions->makeDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir'] ) )
	{
		$functions->setMessage($roster->locale->act['custom_folder_created']);
	}
	else
	{
		$functions->setMessage($roster->locale->act['custom_folder_not_created_manual']);
	}
}

if( isset($_GET['make_dir']) && $_GET['make_dir'] == 'chmoduser' )
{
	if( $functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir'],1,1 ) )
	{
		$functions->setMessage($roster->locale->act['custom_folder_chmoded']);
	}
	else
	{
		$functions->setMessage($roster->locale->act['custom_folder_not_chmoded_manual']);
	}
}

// ----[ End Run folder maker ]-----------------------------



// ----[ Check the directories ]--------------------------------
// Check for the Main Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_main_images'],SIGGEN_DIR . $checkData['image_dir']));
}

// Check for the Character Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['char_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_char_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['char_dir']));
}

// Check for the fonts directory
if( !$functions->checkDir( ROSTER_BASE . $checkData['font_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_font_folder'],ROSTER_BASE . $checkData['font_dir']));
}

// Check for the Class Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['class_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_class_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['class_dir']));
}

// Check for the Background Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['backg_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_backg_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['backg_dir']));
}

// Check for the PvP Logo Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['pvplogo_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_pvp_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['pvplogo_dir']));
}

// Check for the Frame Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['frame_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_frame_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['frame_dir']));
}

// Check for the Level Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['level_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_level_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['level_dir']));
}

// Check for the Border Images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['border_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_border_images'],SIGGEN_DIR . $checkData['image_dir'] . $checkData['border_dir']));
}

// Check for the custom images directory
if( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir'] ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_find_custom_folder'],makelink('&amp;make_dir=user'),SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir']));
	$allow_upload = false;
}
elseif( !$functions->checkDir( SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir'],1 ) )
{
	$functions->setMessage(sprintf($roster->locale->act['cannot_writeto_custom_folder'],makelink('&amp;make_dir=chmoduser'),SIGGEN_DIR . $checkData['image_dir'] . $checkData['user_dir']));
	$allow_upload = false;
}
else
{
	$allow_upload = true;
}

// Check for the saved images directory
if( $checkData['save_images'] )
{
	if( !$functions->checkDir( $checkData['save_images_dir'] ) )
	{
		$functions->setMessage(sprintf($roster->locale->act['cannot_find_save_folder'],makelink('&amp;make_dir=save'),$checkData['save_images_dir']));
		$allow_save = false;
	}
	elseif( !$functions->checkDir( $checkData['save_images_dir'],1 ) )
	{
		$functions->setMessage(sprintf($roster->locale->act['cannot_writeto_save_folder'],makelink('&amp;make_dir=chmodsave'),$checkData['save_images_dir']));
		$allow_save = false;
	}
	else
	{
		$allow_save = true;
	}
}
else
{
	$allow_save = false;
}
// ----[ End Check for required directories ]---------------



// ----[ Check for PHP Safe Mode ]--------------------------
if( ini_get('safe_mode') )
{
	$functions->setMessage($roster->locale->act['safemode_on']);
}


// ----[ Check for can ini_set ]----------------------------
if( !CAN_INI_SET )
{
	$functions->setMessage($roster->locale->act['iniset_off']);
}


// ----[ Initial set up and data grabbing ]-----------------

// Get the current settings
$configData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );

// Get the mode
$config_list = $functions->getDbList( (ROSTER_SIGCONFIGTABLE) , 'config_id' );

// Get the current memberlist
$member_list = $functions->getDbData( ($roster->db->table('members')),'`member_id`, `name`, CONCAT(`region`, "-", `server`) AS server', '`guild_id` != "0"', '`name`' );

// Get the background files
$backgFilesArr = $functions->listFiles( SIGGEN_DIR . $configData['image_dir'] . $configData['backg_dir'],array('png','gif','jpeg','jpg') );

// Get the font files
$fontFilesArr = $functions->listFiles( ROSTER_BASE . $configData['font_dir'],'ttf' );

// Get regular image files
$frameFilesArr = $functions->listFiles( SIGGEN_DIR . $configData['image_dir'] . $configData['frame_dir'],array('png','gif','jpeg','jpg') );
$levelFilesArr = $functions->listFiles( SIGGEN_DIR . $configData['image_dir'] . $configData['level_dir'],array('png','gif','jpeg','jpg') );
$borderFilesArr = $functions->listFiles( SIGGEN_DIR . $configData['image_dir'] . $configData['border_dir'],array('png','gif','jpeg','jpg') );

// Get the img folders
$backImgDirScan  = $functions->listDir( SIGGEN_DIR . $configData['image_dir'] . 'background' . DIR_SEP );
$charImgDirScan  = $functions->listDir( SIGGEN_DIR . $configData['image_dir'] . 'character' . DIR_SEP );
$classImgDirScan = $functions->listDir( SIGGEN_DIR . $configData['image_dir'] . 'class' . DIR_SEP );
$specImgDirScan  = $functions->listDir( SIGGEN_DIR . $configData['image_dir'] . 'spec' . DIR_SEP );
$pvpImgDirScan   = $functions->listDir( SIGGEN_DIR . $configData['image_dir'] . 'pvp' . DIR_SEP );

$charDirList = $functions->listFiles( SIGGEN_DIR . $configData['image_dir'] . $configData['char_dir'],array('png','gif','jpeg','jpg') );

$backImgDirList = $charImgDirList = $classImgDirList = $specImgDirList = $pvpImgDirList = array();

foreach( $backImgDirScan as $dir_check )
{
	$backImgDirList += array( $dir_check => 'background/' . $dir_check . '/' );
}

foreach( $charImgDirScan as $dir_check )
{
	$charImgDirList += array( $dir_check => 'character/' . $dir_check . '/' );
}

foreach( $classImgDirScan as $dir_check )
{
	$classImgDirList += array( $dir_check => 'class/' . $dir_check . '/' );
}

foreach( $specImgDirScan as $dir_check )
{
	$specImgDirList += array( $dir_check => 'spec/' . $dir_check . '/' );
}

foreach( $pvpImgDirScan as $dir_check )
{
	$pvpImgDirList += array( $dir_check => 'pvp/' . $dir_check . '/' );
}

$linklist = array(
	'Default' => 'default',
	'Force SEO' => 'forceseo',
	'Saved Directory' => 'saved',
	'Short' => 'short',
);

// Get list of columns for background selection
$table_name = ( $configData['backg_data_table'] == 'members' ? ($roster->db->table('members')) : ($roster->db->table('players')) );
$backgColumsArr = $functions->getDbColumns( $table_name );

// Make alignment array
$alignArr = array('Left' => 'left','Center' => 'center','Right' => 'right');
// Make image mode array
$imgTypeArr = array('png' => 'png','jpeg' => 'jpg','gif' => 'gif');








// ----[ Include the name test box ]------------------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_nametest.tpl' );
$body = ob_get_contents();
ob_end_clean();


// ----[ Include the body ]---------------------------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_body.tpl' );
$body .= '<br />' . ob_get_contents();
ob_end_clean();


// ----[ Include upload/delete images box ]-----------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_custimg.tpl' );
$body .= ob_get_contents();
ob_end_clean();


// ----[ Include export settings box ]-----------------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_export.tpl' );
$body .= '<br />' . ob_get_contents();
ob_end_clean();


// ----[ Include the java ]---------------------------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_java.tpl' );
$body .= ob_get_contents();
ob_end_clean();


// ----[ Include the config select box ]--------------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_configselect.tpl' );
$menu = ob_get_contents();
ob_end_clean();


// ----[ Include the menu ]---------------------------------
ob_start();
include_once( SIGGEN_DIR . 'templates/sc_menu.tpl' );
$menu .= '<br />' . ob_get_contents();
ob_end_clean();



// ----[ Get the messages ]---------------------------------
$messages = $functions->getMessage();
if( !empty($messages) )
{
	$messages .= '<br />';
}



// ----[ Render the entire page ]---------------------------
$header .= '<span class="title_text">' . sprintf($roster->locale->act['title_siggen_config'],$addon['version']) . '</span><br />'
		.  $messages;

$all_body = ",'radioOn','radioOff','checkboxOn','checkboxOff'";
$roster->output['body_attr'] = "onload=\"initARC('reset_settings'$all_body);initARC('images_upload'$all_body);\"";

// ----[ Output to addon.php ]------------------------------


/**
 * Enter description here...
 *
 * @param string $message
 * @return string
 */
function errorMode($message,$text=null)
{
	global $functions;

	if( !empty($message) )
	{
		// Replace newline feeds with <br />, then newline
		$message = nl2br( $message );

		$message = messagebox($message,$text,'sred');

		ob_start();
		include_once( SIGGEN_DIR . 'templates/sc_java.tpl' );
		$body .= ob_get_contents();
		ob_end_clean();

		return '<br />' . $message;
	}
	else
	{
		return '';
	}
}
