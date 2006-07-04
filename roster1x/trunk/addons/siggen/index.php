<?php
/*******************************
 * $Id$
 *******************************/

// Bad monkey! You can view this directly. And you are stupid for trying. HA HA, take that!
if( eregi(basename(__FILE__),$_SERVER['PHP_SELF']) )
{
	die("You can't access this file directly!");
}


// ----[ Set the Title Text ]-------------------------------
$header_title = 'SigGen Config';


// ----[ Get the filename for this...file ]-----------------
$script_filename = basename($_SERVER['PHP_SELF']).'?roster_addon_name=siggen';
// NUKED $script_filename = $roster_moddir.'&amp;op=addon&amp;roster_addon_name=siggen';


// ----[ Clear file status cache ]--------------------------
clearstatcache();



// ----[ Check for GD Functions ]---------------------------
if( !function_exists('imageTTFBBox') || !function_exists('imageTTFText') || !function_exists('imagecreatetruecolor') )
{
	print errorMode('GD Functions are not available<br />SigGen REQUIRES GD with FreeType support<br /><br /><a href="rosterdiag.php" target="_new">More Info</a>','<span class="red">Fatal Error</span>');
	return;
}


// ----[ Check for required files ]-------------------------
if( !defined('SIGCONFIG_CONF') )
{
	print errorMode('SigGen &quot;Configuration&quot; file not included','<span class="red">Fatal Error</span>');
	return;
}

if( file_exists($sigconfig_dir.'inc/functions.inc') )
{
	require_once( $sigconfig_dir.'inc/functions.inc' );
	$functions = new SigConfigClass;
}
else
{
	print errorMode("SigGen Config &quot;Functions Class&quot; file not found\n[<span class=\"green\">".$sigconfig_dir."inc/functions.inc</span>]",'<span class="red">Fatal Error</span>');
	return;
}
// ----[ End Check for required files ]---------------------



// ----[ Check for password in roster conf ]----------------
if( empty($roster_conf['roster_upd_pw']) )
{
	print errorMode("No...you can't have an empty password
What's the point of having an empty password?

I'm sorry, but you need some kind of a password set in Roster Config to get in",'<span class="red">Password not defined or empty</span>');
	return;
}
// ----[ End Check for password in roster conf ]------------



// ----[ Check log-in ]-------------------------------------
require_once(ROSTER_BASE.'lib'.DIR_SEP.'login.php');
$roster_login = new RosterLogin($script_filename);

if( !$roster_login->getAuthorized() )
{
	print
	'<br />'.
	'<span class="title_text">SigGen Config</span><br />'.
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
		$functions->setMessage('You cannot re-install SigGen after it has been installed');
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
	print errorMode("SigGen Config database table not found [<span class=\"green\">".ROSTER_SIGCONFIGTABLE."</span>]

<center><span style=\"border:1px outset white; padding:2px 6px 2px 6px;\"><a href=\"$script_filename&amp;install=install\">Install</a></span></center><br />",
'SigGen Config Table AutoInstall');
	return;
}
// ----[ End Check if the SigConfig table exists ]----------



// ----[ Check for the required fields ]--------------------
// Get the current configuration
$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );



// ----[ Decide what to do next ]---------------------------
if( isset($_POST['sc_op']) && $_POST['sc_op'] != '' )
{
	switch ( $_POST['sc_op'] )
	{
		case 'delete_image':
			$functions->deleteImage( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'],$_POST['image_name'] );
			break;

		case 'reset_defaults':
			$functions->resetDefaults( $_POST['confirm_reset'],$config_name );
			break;

		case 'upload_image':
			$functions->uploadImage( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'],$_POST['image_name'] );
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



// ----[ Check db version for upgrade ]---------------------
if( $checkData['db_ver'] != $sc_db_ver )
{
	print errorMode("<div align=\"right\">
Upgrade version [<span class=\"green\">$sc_db_ver</span>]
Your version [<span class=\"red\">".$checkData['db_ver']."</span>]
</div>
<a href=\"$script_filename&amp;install=upgrade&amp;reset=reset\">Upgrade with reset</a> <span class=\"green\">(BEST option!)</span>
<a href=\"$script_filename&amp;install=upgrade\">Upgrade without reset</a> <span class=\"red\">(not supported)</span>",
'SigGen Config Upgrade Mode');
	return;
}



// ----[ Run folder maker ]---------------------------------
if( $_REQUEST['make_dir'] == 'save' )
{
	if( $functions->makeDir( $sigconfig_dir.$checkData['save_images_dir'] ) )
	{
		$functions->setMessage("Saved Signatures folder created");
	}
	else
	{
		$functions->setMessage("Saved Signatures folder COULD NOT be created<br />Create it manually");
	}
}

if( $_REQUEST['make_dir'] == 'chmodsave' )
{
	if( $functions->checkDir( $sigconfig_dir.$checkData['save_images_dir'],1,1 ) )
	{
		$functions->setMessage("Saved Signatures folder is now writable");
	}
	else
	{
		$functions->setMessage("Saved Signatures folder COULD NOT be chmod'ed<br />Manually set write access");
	}
}


if( $_REQUEST['make_dir'] == 'user' )
{
	if( $functions->makeDir( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'] ) )
	{
		$functions->setMessage("Custom Images folder created");
	}
	else
	{
		$functions->setMessage("Custom Images folder COULD NOT be created<br />Create it manually");
	}
}

if( $_REQUEST['make_dir'] == 'chmoduser' )
{
	if( $functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'],1,1 ) )
	{
		$functions->setMessage("Custom Images folder is now writable");
	}
	else
	{
		$functions->setMessage("Custom Images folder COULD NOT be chmod'ed<br />Manually set write access");
	}
}

// ----[ End Run folder maker ]-----------------------------



// ----[ Check the directories ]--------------------------------
// Check for the Main Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'] ) )
{
	$functions->setMessage("<span class=\"red\">Fatal Error:</span> Cannot find Main Images folder [<span class=\"green\">".$sigconfig_dir.$checkData['image_dir']."</span>]<br /><br />This <span class=\"red\"><u>MUST</u></span> be fixed before you do <span class=\"red\"><u>ANYTHING</u></span> else");
}

// Check for the Character Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['char_dir'] ) )
{
	$functions->setMessage("Cannot find Character Images folder<br />Make sure it is set correctly [<span class=\"green\">".$sigconfig_dir.$checkData['image_dir'].$checkData['char_dir'].'</span>]');
}

// Check for the fonts directory
if( !$functions->checkDir( ROSTER_BASE.$checkData['font_dir'] ) )
{
	$functions->setMessage("Cannot find Fonts folder<br />Make sure it is set correctly [<span class=\"green\">".ROSTER_BASE.$checkData['font_dir'].'</span>]');
}

// Check for the Class Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['class_dir'] ) )
{
	$functions->setMessage("Cannot find Class Images folder<br />Make sure it is set correctly [<span class=\"green\">".$sigconfig_dir.$checkData['image_dir'].$checkData['class_dir'].'</span>]');
}

// Check for the Background Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['backg_dir'] ) )
{
	$functions->setMessage("Cannot find Background Images folder<br />Make sure it is set correctly<br />[<span class=\"green\">".$sigconfig_dir.$checkData['image_dir'].$checkData['backg_dir'].'</span>]');
}

// Check for the PvP Logo Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['pvplogo_dir'] ) )
{
	$functions->setMessage("Cannot find PvP Logo Images folder<br />Make sure it is set correctly<br />[<span class=\"green\">".$sigconfig_dir.$checkData['image_dir'].$checkData['pvplogo_dir'].'</span>]');
}

// Check for the custom images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'] ) )
{
	$functions->setMessage("Custom Images folder doesn't exist<br />It is required if you want to upload custom user images<br />Click <a href=\"$script_filename&amp;make_dir=user\">HERE</a> to try to create [<span class=\"green\">".$sigconfig_dir.$checkData['image_dir'].$checkData['user_dir']."</span>]<br />Custom Image uploading is temporarily disabled");
	$allow_upload = false;
}
elseif( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'],1 ) )
{
	$functions->setMessage("Custom Images folder isn't writable<br />Click <a href=\"$script_filename&amp;make_dir=chmoduser\">HERE</a> to try to chmod [<span class=\"green\">".$sigconfig_dir.$checkData['image_dir'].$checkData['user_dir']."</span>]<br />Custom Image uploading is temporarily disabled");
	$allow_upload = false;
}
else
{
	$allow_upload = true;
}

// Check for the saved images directory
if( $checkData['save_images'] )
{
	if( !$functions->checkDir( $sigconfig_dir.$checkData['save_images_dir'] ) )
	{
		$functions->setMessage("Saved Signatures Folder doesn't exist<br />It is required when &quot;Save Image Mode&quot; is turned on<br />Click <a href=\"$script_filename&amp;make_dir=save\">HERE</a> to try to create [<span class=\"green\">".$sigconfig_dir.$checkData['save_images_dir']."</span>]<br />Save Image functions are temporarily disabled");
		$allow_save = false;
	}
	elseif( !$functions->checkDir( $sigconfig_dir.$checkData['save_images_dir'],1 ) )
	{
		$functions->setMessage("Saved Signatures Folder isn't writable<br />Write access is required when &quot;Save Image Mode&quot; is turned on<br />Click <a href=\"$script_filename&amp;make_dir=chmodsave\">HERE</a> to try to chmod [<span class=\"green\">".$sigconfig_dir.$checkData['save_images_dir']."</span>]<br />Save Image functions are temporarily disabled");
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
	$functions->setMessage('PHP <span class="red">safe_mode</span> is turned <span class="green">on</span><br />Image upload/delete functions <u>might</u> not operate properly');
}



// ----[ Get the Guild ID ]---------------------------------
$guild_id = $functions->getDbData( (ROSTER_GUILDTABLE),'`guild_id`',"`guild_name` = '".$wowdb->escape($roster_conf['guild_name'])."' AND `server` = '".$wowdb->escape($roster_conf['server_name'])."'" );
$guild_id = $guild_id['guild_id'];







// ----[ Include the body ]---------------------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_body.tpl' );
$body = ob_get_contents();
ob_end_clean();


// ----[ Include the config select box ]--------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_configselect.tpl' );
$conf_sel = ob_get_contents();
ob_end_clean();


// ----[ Include the java ]---------------------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_java.tpl' );
$java = ob_get_contents();
ob_end_clean();


// ----[ Include delete images box ]------------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_deleteimg.tpl' );
$delete = ob_get_contents();
ob_end_clean();


// ----[ Include upload images box ]------------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_uploadimg.tpl' );
$upload = ob_get_contents();
ob_end_clean();


// ----[ Include reset settings box ]-----------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_resetdb.tpl' );
$reset = ob_get_contents();
ob_end_clean();


// ----[ Include the name test box ]------------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_nametest.tpl' );
$preview = ob_get_contents();
ob_end_clean();


// ----[ Include the menu ]---------------------------------
ob_start();
include_once( $sigconfig_dir.'templates/sc_menu.tpl' );
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
print $java.
'<br />'.
'<span class="title_text">SigGen Config</span><br />'.
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
      '.$reset.'
    </td>
  </tr>
  <tr>
    <td valign="top" align="center">
      '.$body.'
    </td>
  </tr>
</table><br />'.$sqldebug;

// ----[ Output to addon.php ]------------------------------


/**
 * Enter description here...
 *
 * @param string $message
 * @return string
 */
function errorMode($message,$text=null)
{
	global $functions,$sigconfig_dir;

	if( isset($functions) )
	{
		$sql = $functions->getSqlDebug();
	}

	if( !empty($message) )
	{
		// Replace newline feeds with <br />, then newline
		$message = nl2br( $message );

		$message = border('sred','start',$text).'
<table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
  <tr>
    <td class="sc_row_right1">'.$message.'</td>
  </tr>
</table>'.border('sred','end');

		ob_start();
		include_once( $sigconfig_dir.'templates/sc_java.tpl' );
		$java = ob_get_contents();
		ob_end_clean();

		return $java.'<br />'.$message.'<br />'.$sql;
	}
	else
	{
		return '';
	}
}

?>