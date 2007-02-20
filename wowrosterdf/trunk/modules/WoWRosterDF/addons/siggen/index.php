<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /index.php
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
 * @version $Id$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

// Bad monkey! You can view this directly. And you are stupid for trying. HA HA, take that!
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}
if( isset($_GET['member']) )
{
        require( ROSTER_BASE.'addons'.DIR_SEP.'siggen'.DIR_SEP.'siggen.php' );
        exit();
}


// ----[ Set the Title Text ]-------------------------------
$header_title = $siggen_locale[$roster_conf['roster_lang']]['menu_siggen_config'];


// ----[ Get the filename for this...file ]-----------------
//$script_filename = basename($_SERVER['PHP_SELF']).'?roster_addon_name=siggen';
// NUKED $script_filename = $roster_moddir.'&amp;op=addon&amp;roster_addon_name=siggen';
//wowrosterdf
$script_filename = 'index.php?name='.$module_name.'&amp;file=addon&amp;roster_addon_name=siggen';


// ----[ Clear file status cache ]--------------------------
clearstatcache();



// ----[ Check for GD Functions ]---------------------------
if( !function_exists('imageTTFBBox') || !function_exists('imageTTFText') || !function_exists('imagecreatetruecolor') || !function_exists('imagecreate') )
{
	print errorMode($siggen_locale[$roster_conf['roster_lang']]['no_gd_error'],$siggen_locale[$roster_conf['roster_lang']]['fatal_error']);
	return;
}


// ----[ Check for required files ]-------------------------
if( !defined('SIGCONFIG_CONF') )
{
	print errorMode($siggen_locale[$roster_conf['roster_lang']]['config_notfound'],$siggen_locale[$roster_conf['roster_lang']]['fatal_error']);
	return;
}

$siggen_functions_file = SIGGEN_DIR.'inc'.DIR_SEP.'functions.inc';
if( file_exists($siggen_functions_file) )
{
	require_once( $siggen_functions_file );
	$functions = new SigConfigClass;
}
else
{
	print errorMode(sprintf($siggen_locale[$roster_conf['roster_lang']]['functions_notfound'],str_replace(DIR_SEP,'/',$siggen_functions_file)),$siggen_locale[$roster_conf['roster_lang']]['fatal_error']);
	return;
}
// ----[ End Check for required files ]---------------------



// ----[ Check for password in roster conf ]----------------
if( empty($roster_conf['roster_upd_pw']) )
{
	print errorMode($siggen_locale[$roster_conf['roster_lang']]['no_pass_error'],$siggen_locale[$roster_conf['roster_lang']]['no_pass_error_t']);
	return;
}
// ----[ End Check for password in roster conf ]------------



// ----[ Check log-in ]-------------------------------------
require_once(ROSTER_LIB.'login.php');
$roster_login = new RosterLogin($script_filename);

if( !$roster_login->getAuthorized() )
{
	print
	'<br />'.
	'<span class="title_text">'.sprintf($siggen_locale[$roster_conf['roster_lang']]['title_siggen_config'],$sc_file_ver).'</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	return;
}
// ----[ End Check log-in ]---------------------------------


// ----[ Run install/upgrade mode ]-------------------------
if( $_REQUEST['install'] == 'install' )
{
	if( !$functions->checkDb( (ROSTER_SIGCONFIGTABLE) ) )
	{
		print errorMode($functions->installDb( 'install' ));
		return;
	}
	else
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['no_reinstall_error']);
	}
}

if( $_REQUEST['install'] == 'upgrade' )
{
	print errorMode($functions->installDb( 'upgrade' ));
	return;
}
// ----[ End Run install/upgrade mode ]---------------------



// ----[ Get the post/cookie variables ]--------------------
if( isset( $_POST['config_name'] ) )
{
	$config_name = $_POST['config_name'];
	setcookie( 'siggen_configname',$config_name,0,'/' );
}
elseif( isset( $_COOKIE['siggen_configname'] ) )
{
	$config_name = $_COOKIE['siggen_configname'];
}
else
{
	$config_name = 'signature';
	setcookie( 'siggen_configname',$config_name,0,'/' );
}

// Name to test siggen with
if( isset( $_POST['name_test'] ) )
{
	$name_test = $_POST['name_test'];
	setcookie( 'siggen_nametest',$name_test,0,'/' );
}
elseif( isset($_COOKIE['siggen_nametest']) )
{
	$name_test = $_COOKIE['siggen_nametest'];
}
else
{
	$name_test = '';
}
// ----[ End of the post/cookie variables ]-----------------



// ----[ Check if the SigConfig table exists ]--------------
if( !$functions->checkDb( (ROSTER_SIGCONFIGTABLE) ) )
{
	print errorMode(sprintf($siggen_locale[$roster_conf['roster_lang']]['install'],ROSTER_SIGCONFIGTABLE,$script_filename.'&amp;install=install'),$siggen_locale[$roster_conf['roster_lang']]['install_t']);
	return;
}
// ----[ End Check if the SigConfig table exists ]----------



// ----[ Check for the required fields ]--------------------
// Get the current configuration
$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );



// ----[ Check db version for upgrade ]---------------------
if( $checkData['db_ver'] != $sc_db_ver )
{
	print errorMode(sprintf($siggen_locale[$roster_conf['roster_lang']]['upgrade'],$sc_db_ver,$checkData['db_ver'],$script_filename.'&amp;install=upgrade&amp;reset=1&amp;ver='.$checkData['db_ver'],$script_filename.'&amp;install=upgrade&amp;reset=0&amp;ver='.$checkData['db_ver']),$siggen_locale[$roster_conf['roster_lang']]['upgrade_t']);
	return;
}



// ----[ Decide what to do next ]---------------------------
if( isset($_POST['sc_op']) && $_POST['sc_op'] != '' )
{
	switch ( $_POST['sc_op'] )
	{
		case 'delete_image':
			$functions->deleteImage( SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir'],$_POST['image_name'] );
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
			$functions->uploadImage( SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir'],$_POST['image_name'] );
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



// ----[ Run folder maker ]---------------------------------
if( $_REQUEST['make_dir'] == 'save' )
{
	if( $functions->makeDir( SIGGEN_DIR.$checkData['save_images_dir'] ) )
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['saved_folder_created']);
	}
	else
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['saved_folder_not_created_manual']);
	}
}

if( $_REQUEST['make_dir'] == 'chmodsave' )
{
	if( $functions->checkDir( SIGGEN_DIR.$checkData['save_images_dir'],1,1 ) )
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['saved_folder_chmoded']);
	}
	else
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['saved_folder_not_chmoded_manual']);
	}
}


if( $_REQUEST['make_dir'] == 'user' )
{
	if( $functions->makeDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir'] ) )
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['custom_folder_created']);
	}
	else
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['custom_folder_not_created_manual']);
	}
}

if( $_REQUEST['make_dir'] == 'chmoduser' )
{
	if( $functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir'],1,1 ) )
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['custom_folder_chmoded']);
	}
	else
	{
		$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['custom_folder_not_chmoded_manual']);
	}
}

// ----[ End Run folder maker ]-----------------------------



// ----[ Check the directories ]--------------------------------
// Check for the Main Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_main_images'],SIGGEN_DIR.$checkData['image_dir']));
}

// Check for the Character Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['char_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_char_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['char_dir']));
}

// Check for the fonts directory
if( !$functions->checkDir( ROSTER_BASE.$checkData['font_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_font_folder'],ROSTER_BASE.$checkData['font_dir']));
}

// Check for the Class Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['class_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_class_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['class_dir']));
}

// Check for the Background Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['backg_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_backg_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['backg_dir']));
}

// Check for the PvP Logo Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['pvplogo_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_pvp_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['pvplogo_dir']));
}

// Check for the Frame Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['frame_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_frame_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['frame_dir']));
}

// Check for the Level Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['level_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_level_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['level_dir']));
}

// Check for the Border Images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['border_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_border_images'],SIGGEN_DIR.$checkData['image_dir'].$checkData['border_dir']));
}

// Check for the custom images directory
if( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir'] ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_custom_folder'],$script_filename.'&amp;make_dir=user',SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir']));
	$allow_upload = false;
}
elseif( !$functions->checkDir( SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir'],1 ) )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_writeto_custom_folder'],$script_filename.'&amp;make_dir=chmoduser',SIGGEN_DIR.$checkData['image_dir'].$checkData['user_dir']));
	$allow_upload = false;
}
else
{
	$allow_upload = true;
}

// Check for the saved images directory
if( $checkData['save_images'] )
{
	if( !$functions->checkDir( SIGGEN_DIR.$checkData['save_images_dir'] ) )
	{
		$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_find_save_folder'],$script_filename.'&amp;make_dir=save',SIGGEN_DIR.$checkData['save_images_dir']));
		$allow_save = false;
	}
	elseif( !$functions->checkDir( SIGGEN_DIR.$checkData['save_images_dir'],1 ) )
	{
		$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['cannot_writeto_save_folder'],$script_filename.'&amp;make_dir=chmodsave',SIGGEN_DIR.$checkData['save_images_dir']));
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
	$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['safemode_on']);
}


// ----[ Check for can ini_set ]----------------------------
if( ereg('ini_set', ini_get('disable_functions')) )
{
	$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['iniset_off']);
}


// ----[ Check for latest SigGen Version ]------------------
$sc_file_ver_latest = '';

// Check xent.homeip.net for versioning
$sh = @fsockopen('xent.homeip.net', 80, $errno, $error, 5);
if ( $sh )
{
	@fputs($sh, "GET /files/siggen/version.txt HTTP/1.1\r\nHost: xent.homeip.net\r\nConnection: close\r\n\r\n");
	while ( !@feof($sh) )
	{
		$content = @fgets($sh, 512);
		if ( preg_match('#<version>(.+)</version>#i',$content,$version) )
		{
			$sc_file_ver_latest = $version[1];
			break;
		}
	}
}
@fclose($sh);

if( $sc_file_ver_latest == '' )
{
	$functions->setMessage($siggen_locale[$roster_conf['roster_lang']]['cannot_check_version']);
}
elseif( $sc_file_ver_latest > $sc_file_ver )
{
	$functions->setMessage(sprintf($siggen_locale[$roster_conf['roster_lang']]['new_siggen_available'],$sc_file_ver_latest));
}




// ----[ Get the Guild ID ]---------------------------------
$guild_id = $guild_info['guild_id'];







// ----[ Include the body ]---------------------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_body.tpl' );
$body = ob_get_contents();
ob_end_clean();


// ----[ Include the config select box ]--------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_configselect.tpl' );
$conf_sel = ob_get_contents();
ob_end_clean();


// ----[ Include the java ]---------------------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_java.tpl' );
$java = ob_get_contents();
ob_end_clean();


// ----[ Include delete images box ]------------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_deleteimg.tpl' );
$delete = ob_get_contents();
ob_end_clean();


// ----[ Include upload images box ]------------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_uploadimg.tpl' );
$upload = ob_get_contents();
ob_end_clean();


// ----[ Include export settings box ]-----------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_export.tpl' );
$export = ob_get_contents();
ob_end_clean();


// ----[ Include reset settings box ]-----------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_resetdb.tpl' );
$reset = ob_get_contents();
ob_end_clean();


// ----[ Include the name test box ]------------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_nametest.tpl' );
$preview = ob_get_contents();
ob_end_clean();


// ----[ Include the menu ]---------------------------------
ob_start();
include_once( SIGGEN_DIR.'templates/sc_menu.tpl' );
$menu = ob_get_contents();
ob_end_clean();



// ----[ Get the messages/SQL debug ]-----------------------
if( $sc_show_sql_win )
{
	$sqldebug = $functions->getSqlDebug().'<br />';
}
else
{
	$sqldebug = '';
}

$messages = $functions->getMessage();
if( !empty($messages) )
{
	$messages .= '<br />';
}



// ----[ Render the entire page ]---------------------------
print
'<br />'.
'<span class="title_text">'.sprintf($siggen_locale[$roster_conf['roster_lang']]['title_siggen_config'],$sc_file_ver).'</span><br />'.
$roster_login->getMessage().'<br />'.
$messages.'
<table width="100%" class="bodyline">
  <tr>
    <td width="140" rowspan="2" valign="top" align="left">
      '.$conf_sel.'<br />
      '.$menu.'
    </td>
    <td valign="top" align="center">
      '.$preview.'
    </td>
    <td width="200" rowspan="2" valign="top" align="right">
      '.$upload.'<br />
      '.$delete.'<br />
      '.$export.'<br />
      '.$reset.'
    </td>
  </tr>
  <tr>
    <td valign="top" align="center">
      '.$body.'
    </td>
  </tr>
</table><br />'.$sqldebug.$java;

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

	if( isset($functions) )
	{
		$sql = $functions->getSqlDebug();
	}

	if( !empty($message) )
	{
		// Replace newline feeds with <br />, then newline
		$message = nl2br( $message );

		$message = messagebox($message,$text,'sred');

		ob_start();
		include_once( SIGGEN_DIR.'templates/sc_java.tpl' );
		$java = ob_get_contents();
		ob_end_clean();

		return '<br />'.$message.'<br />'.$sql.$java;
	}
	else
	{
		return '';
	}
}
