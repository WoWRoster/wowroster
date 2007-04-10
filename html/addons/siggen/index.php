<?php
$versions['versionDate']['sigconfig'] = '$Date: 2006/02/04 05:08:08 $'; 
$versions['versionRev']['sigconfig'] = '$Revision: 1.13 $'; 
$versions['versionAuthor']['sigconfig'] = '$Author: zanix $';

// Bad monkey! You can view this directly. And you are stupid for trying. HA HA, take that!
if( eregi('index.php',$_SERVER['PHP_SELF']) )
{
	exit("You can't access this file directly!");
}


// ----[ Set the Title Text ]-------------------------------
$header_title = 'SigGen Config';


// ----[ Disable the roster menu ]--------------------------
$roster_show_menu = false;

// ----[ Include a new roster menu ]------------------------
ob_start();
include_once( 'rostermenu.php' );
$roster_menu = ob_get_contents();
ob_end_clean();



// ----[ Define the sig_config table ]----------------------
if( !defined('ROSTER_SIGCONFIGTABLE') )
{
	define('ROSTER_SIGCONFIGTABLE',$db_prefix.'addon_siggen');
}


// ----[ Get the filename for this...file ]-----------------
$script_filename = basename($_SERVER['PHP_SELF']).'?roster_addon_name=siggen';


// ----[ Clear file status cache ]--------------------------
clearstatcache();


// ----[ SigGen directory ]---------------------------------
$sigconfig_dir = 'addons/siggen/';


// ----[ Connect to the database ]--------------------------
$wowdb->connect($db_host,$db_user,$db_passwd,$db_name);


// ----[ Check for required files ]-------------------------
define('SIGGEN_CONF_FILE',$sigconfig_dir.'conf.php');
define('SIGGEN_FUNCTIONS_FILE',$sigconfig_dir.'inc/functions.inc');

if( file_exists(SIGGEN_CONF_FILE) )
{
	require_once( SIGGEN_CONF_FILE );
}
else
{
	print errorMode("<span class=\"sc_errorText\">Fatal Error:</span> SigGen Config &quot;Configuration&quot; file not found\n[<span class=\"sc_sqlText\">".SIGGEN_CONF_FILE."</span>]");
	return;
}


if( file_exists(SIGGEN_FUNCTIONS_FILE) )
{
	require_once( SIGGEN_FUNCTIONS_FILE );
}
else
{
	print errorMode("<span class=\"sc_errorText\">Fatal Error:</span> SigGen Config &quot;Functions Class&quot; file not found\n[<span class=\"sc_sqlText\">".SIGGEN_FUNCTIONS_FILE."</span>]");
	return;
}
// ----[ End Check for required files ]---------------------




// ----[ Get the post/cookie variables ]--------------------
if( isset( $_POST['config_name'] ) )
{
	setcookie( 'roster_siggen_configname',$_POST['config_name'] );
	$config_name = $_POST['config_name'];
}
elseif( isset( $_COOKIE['roster_siggen_configname'] ) )
{
	$config_name = $_COOKIE['roster_siggen_configname'];
}
else
{
	$config_name = 'signature';
}


// Name to test siggen with
if( isset( $_POST['name_test'] ) )
{
	setcookie( 'roster_siggen_nametest',$_POST['name_test'] );
	$name_test = $_POST['name_test'];
}
else
{
	$name_test = $_COOKIE['roster_siggen_nametest'];
}
// ----[ End of the post/cookie variables ]-----------------



// ----[ Check for password in roster conf ]----------------
if( empty($sc_pass) )
{
	print errorMode("<span class=\"sc_errorText\">No password defined or empty password</span>

No...you can't have an empty password
What's the point of having an empty password?

I'm sorry, but you need some kind of a password set in &quot;conf.php&quot; to get in");
return;
}



// ----[ Run install/upgrade mode ]-------------------------
if( $_REQUEST['install'] == 'install' )
{
	print errorMode($functions->installDb( 'install' ));
	return;
}

if( $_REQUEST['install'] == 'upgrade' )
{
	print errorMode($functions->installDb( 'upgrade' ));
	return;
}



// ----[ Check if the SigConfig table exists ]--------------
if( !$functions->checkDb( (ROSTER_SIGCONFIGTABLE) ) )
{
	print errorMode("SigGen Config database table not found [<span class=\"sc_sqlText\">".ROSTER_SIGCONFIGTABLE."</span>]

SigGen Config Database Table AutoInstall

Choose your MySQL version
<a href=\"$script_filename&amp;install=install&amp;engine=ENGINE\">MySQL 4.1 or higher</a> - (db type set: <span class=\"sc_errorText\">ENGINE</span>)
<a href=\"$script_filename&amp;install=install&amp;engine=TYPE\">MySQL 4 or less</a> - (db type set: <span class=\"sc_errorText\">TYPE</span>)");
	return;
}



// ----[ Get the Guild ID ]---------------------------------
$guild_id = $functions->getDbData( (ROSTER_GUILDTABLE),'`guild_id`',"`guild_name` = '".$wowdb->escape($guild_name)."' AND `server` = '".$wowdb->escape($server_name)."'" );
$guild_id = $guild_id['guild_id'];



// ----[ Check for the required fields ]--------------------
// Check all the folders, save mode, and db version
$checkData = $functions->getDbData( (ROSTER_SIGCONFIGTABLE) , '*' , "`config_id` = '$config_name'" );



// ----[ Check db version for upgrade ]---------------------
if( $checkData['db_ver'] != $sc_db_ver )
{
	print errorMode("SigGen Config Database Upgrade Mode
<div align=\"right\">
Upgrade version [<span class=\"sc_sqlText\">$sc_db_ver</span>]
Your version [<span class=\"sc_errorText\">".$checkData['db_ver']."</span>]
</div>
<a href=\"$script_filename&amp;install=upgrade&amp;reset=reset\">Upgrade with reset</a> <span class=\"sc_sqlText\">(BEST option!)</span>
<a href=\"$script_filename&amp;install=upgrade\">Upgrade without reset</a> <span class=\"sc_errorText\">(not supported)</span>");
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
		$functions->setMessage("Saved Signatures folder COULD NOT be created\nCreate it manually");
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
		$functions->setMessage("Saved Signatures folder COULD NOT be chmod'ed\nManually set write access");
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
		$functions->setMessage("Custom Images folder COULD NOT be created\nCreate it manually");
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
		$functions->setMessage("Custom Images folder COULD NOT be chmod'ed\nManually set write access");
	}
}

// ----[ End Run folder maker ]---------------------------------



// ----[ Check the directories ]----------------------------
// Check for the Main Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'] ) )
{
	print errorMode("<span class=\"sc_errorText\">Fatal Error:</span> Cannot find Main Images folder [<span class=\"sc_sqlText\">".$sigconfig_dir.$checkData['image_dir']."</span>]");
	return;
}

// Check for the Character Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['char_dir'] ) )
{
	$functions->setMessage("Cannot find Character Images folder\nMake sure it is set correctly [<span class=\"sc_sqlText\">".$sigconfig_dir.$checkData['image_dir'].$checkData['char_dir'].'</span>]');
}

// Check for the Class Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['class_dir'] ) )
{
	$functions->setMessage("Cannot find Class Images folder\nMake sure it is set correctly [<span class=\"sc_sqlText\">".$sigconfig_dir.$checkData['image_dir'].$checkData['class_dir'].'</span>]');
}

// Check for the Background Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['backg_dir'] ) )
{
	$functions->setMessage("Cannot find Background Images folder\nMake sure it is set correctly\n[<span class=\"sc_sqlText\">".$sigconfig_dir.$checkData['image_dir'].$checkData['backg_dir'].'</span>]');
}

// Check for the PvP Logo Images directory
if( !$functions->checkDir( $sigconfig_dir.$checkData['image_dir'].$checkData['pvplogo_dir'] ) )
{
	$functions->setMessage("Cannot find PvP Logo Images folder\nMake sure it is set correctly\n[<span class=\"sc_sqlText\">".$sigconfig_dir.$checkData['image_dir'].$checkData['pvplogo_dir'].'</span>]');
}


// Function to check for the custom images directory
function checkMemberDir( $dir )
{
	global $functions, $script_filename;

	if( !$functions->checkDir( $dir ) )
	{
		$functions->setMessage("Custom Images folder doesn't exist\nIt is required if you want to upload custom user images\nClick <a href=\"$script_filename&amp;make_dir=user\">HERE</a> to try to create [<span class=\"sc_sqlText\">".$dir."</span>]\nCustom Image uploading is temporarily disabled");
		return false;
	}
	elseif( !$functions->checkDir( $dir,1 ) )
	{
		$functions->setMessage("Custom Images folder isn't writable\nClick <a href=\"$script_filename&amp;make_dir=chmoduser\">HERE</a> to try to chmod [<span class=\"sc_sqlText\">".$dir."</span>]\nCustom Image uploading is temporarily disabled");
		return false;
	}
	else
	{
		return true;
	}
}


// Function to check for the saved images directory
function checkSavedDir( $dir )
{
	global $functions, $script_filename;

	if( !$functions->checkDir( $dir ) )
	{
		$functions->setMessage("Saved Signatures Folder doesn't exist\nIt is required when &quot;Save Image Mode&quot; is turned on\nClick <a href=\"$script_filename&amp;make_dir=save\">HERE</a> to try to create [<span class=\"sc_sqlText\">".$dir."</span>]\nSave Image functions are temporarily disabled");
		return false;
	}
	elseif( !$functions->checkDir( $dir,1 ) )
	{
		$functions->setMessage("Saved Signatures Folder isn't writable\nWrite access is required when &quot;Save Image Mode&quot; is turned on\nClick <a href=\"$script_filename&amp;make_dir=chmodsave\">HERE</a> to try to chmod [<span class=\"sc_sqlText\">".$dir."</span>]\nSave Image functions are temporarily disabled");
		return false;
	}
	else
	{
		return true;
	}
}

// ----[ End Check for required directories ]---------------


// ----[ Check for PHP Safe Mode ]--------------------------
if( ini_get('safe_mode') )
{
	$functions->setMessage("PHP <span class=\"sc_errorText\">safe_mode=on</span> detected\nImage upload/delete functions may not operate properly");
}


// ----[ Check the cookies ]--------------------------------
if( isset( $_REQUEST['logout'] ) )
{
	setcookie( 'roster_siggen_pw','',time()-86400 );
	setcookie( 'roster_siggen_nametest','',time()-86400 );
	setcookie( 'roster_siggen_configname','',time()-86400 );
}
else
{
	if( !isset($_COOKIE['roster_siggen_pw']) )
	{
		if( isset($_POST['pass_word']) )
		{
			if( md5($_POST['pass_word']) == md5($sc_pass) )
			{
				setcookie( 'roster_siggen_pw',md5($sc_pass) );
				$password_message = '<span class="sc_errorText">Logged in:</span><span style="font-size:10px;color:#FFFFFF"> SigGen Config Admin [<a href="'.$script_filename.'&amp;logout=logout">Logout</a>]</span><br />';
				$allow_login = 1;
			}
			else
			{
				$password_message = '<span class="sc_errorText">Wrong password</span><br />';
			}
		}
	}
	else
	{
		$BigCookie = $_COOKIE['roster_siggen_pw'];

		if( $BigCookie == md5($sc_pass) )
		{
			$password_message = '<span class="sc_errorText">Logged in:</span><span style="font-size:10px;color:#FFFFFF"> SigGen Config Admin [<a href="'.$script_filename.'&amp;logout=logout">Logout</a>]</span><br />';
			$allow_login = 1;
		}
		else
		{
			setcookie( 'roster_siggen_pw','',time()-86400 );
		}
	}
}
// ----[ End Check the cookies ]----------------------------




// ----[ Decide what to do next ]---------------------------
if( $allow_login )
{
	// Get filename for image delete or upload
	if( isset( $_POST['image_name'] ) )
	{
		$filename = $_POST['image_name'];
	}

	switch ( $_POST['op'] )
	{
		case 'delete_image':
			$functions->deleteImage( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'],$filename );
			break;

		case 'reset_defaults':
			$functions->resetDefaults( $_POST['confirm_reset'],$config_name );
			break;

		case 'upload_image':
			$functions->uploadImage( $sigconfig_dir.$checkData['image_dir'].$checkData['user_dir'],$filename );
			break;

		case 'process':
			$functions->processData( $_POST,$config_name );
			break;

		default:
			break;
	}
}

// Check for the custom images directory
$allow_upload = checkMemberDir($sigconfig_dir.$checkData['image_dir'].$checkData['user_dir']);
// Check for the saved images directory
if( $checkData['save_images'] )
{
	$allow_save = checkSavedDir($sigconfig_dir.$checkData['save_images_dir']);
}
else
{
	$allow_save = false;
}


// ----[ Include the password box ]-------------------------
ob_start();
include_once( 'templates/sc_passbox.tpl' );
$passbox = ob_get_contents();
ob_end_clean();


// ----[ Include the body ]---------------------------------
ob_start();
include_once( 'templates/sc_body.tpl' );
$body = ob_get_contents();
ob_end_clean();


// ----[ Include the config select box ]--------------------
ob_start();
include_once( 'templates/sc_configselect.tpl' );
$conf_sel = ob_get_contents();
ob_end_clean();


// ----[ Include the java ]---------------------------------
ob_start();
include_once( 'templates/sc_java.tpl' );
$java = ob_get_contents();
ob_end_clean();


// ----[ Include delete images box ]------------------------
if( $allow_upload )
{
	ob_start();
	include_once( 'templates/sc_deleteimg.tpl' );
	$delete = ob_get_contents();
	ob_end_clean();
}


// ----[ Include upload images box ]------------------------
if( $allow_upload )
{
	ob_start();
	include_once( 'templates/sc_uploadimg.tpl' );
	$upload = ob_get_contents();
	ob_end_clean();
}


// ----[ Include reset settings box ]-----------------------
ob_start();
include_once( 'templates/sc_resetdb.tpl' );
$reset = ob_get_contents();
ob_end_clean();


// ----[ Include the name test box ]------------------------
ob_start();
include_once( 'templates/sc_nametest.tpl' );
$preview = ob_get_contents();
ob_end_clean();


// ----[ Include the menu ]---------------------------------
ob_start();
include_once( 'templates/sc_menu.tpl' );
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
if( $allow_login )
{
	$layout = $java.
$roster_menu.
'<br />
'.$password_message.'<br />
'.$messages.'
'.$sqldebug.'
<table width="100%" class="bodyline">
  <tr> 
    <td width="140" rowspan="2" valign="top" align="left">
      '.$conf_sel.'
      '.$menu.'
    </td>
    <td valign="top" align="center">
      '.$preview.'
    </td>
    <td width="200" rowspan="2" valign="top" align="right">
      '.$upload.'
      '.$delete.'
      '.$reset.'
    </td>
  </tr>
  <tr> 
    <td valign="top" align="center">
      '.$body.'
    </td>
  </tr>
</table>';
}
else
{
	$layout = $roster_menu.
	'<br />'.
	$password_message.
	$passbox;
}

// ----[ Output to addon.php ]------------------------------

echo $layout;


	function errorMode($message)
	{
		global $functions,$roster_menu;

		if( isset($functions) )
		{
			$sql = $functions->getSqlDebug();
		}

		if( !empty($message) )
		{
			// Replace newline feeds with <br />, then newline
			$message = nl2br( $message );

			$message = '
<table class="sc_table" cellspacing="1" cellpadding="2">
  <tr>
    <th class="membersHeaderRight">Messages</th>
  </tr>
  <tr class="sc_row1">
    <td>'.$message.'</td>
  </tr>
</table>
';
			ob_start();
			include_once( 'templates/sc_java.tpl' );
			$java = ob_get_contents();
			ob_end_clean();

			return $java.$roster_menu.'<br />'.$message.'<br />'.$sql;
		}
		else
		{
			return '';
		}
	}

?>