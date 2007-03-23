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

/**
* NOTICE: This file was not written by the WoWRoster Dev Team
* It was originally written for the EQdkp project and used here
*
* It has been modified and adapted to fit WoWRoster's needs.
*
* The EQdkp Group's original notice appears below
*/

/******************************
 * EQdkp
 * Copyright 2002-2003
 * Licensed under the GNU GPL.  See COPYING for full terms.
 * ------------------
 * install.php
 * Began: Sun June 22 2003
 *
 * $Id$
 *
 ******************************/

// ---------------------------------------------------------
// Set up environment
// ---------------------------------------------------------
error_reporting(E_ALL);

set_magic_quotes_runtime(0);
if ( !get_magic_quotes_gpc() )
{
	$_GET = slash_global_data($_GET);
	$_POST = slash_global_data($_POST);
}

$roster_root_path = './';



// ---------------------------------------------------------
// Template Wrap class
// ---------------------------------------------------------
if ( !include_once($roster_root_path . 'install/template.php') )
{
	die('Could not include ' . $roster_root_path . 'install/includes/class_template.php - check to make sure that the file exists!');
}


// ---------------------------------------------------------
// Config file Downloader
// ---------------------------------------------------------
if( isset($_POST['send_file']) && $_POST['send_file'] == 1 && !empty($_POST['config_data']) )
{
	header('Content-Type: text/x-delimtext; name="conf.php"');
	header('Content-disposition: attachment; filename="conf.php"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	// because we add slashes at the top if its off, and they are added automaticlly
	// if it is on.
	echo stripslashes($_POST['config_data']);

	exit;
}



$STEP = ( isset($_POST['install_step']) ) ? $_POST['install_step'] : 1;


// Get the config file
if ( file_exists($roster_root_path . 'conf.php') )
{
	include_once($roster_root_path . 'conf.php');
}


// If Roster is already installed, don't let them install it again
if ( defined('ROSTER_INSTALLED') )
{
	$tpl = new Template_Wrap('install_message.html','install_header.html','install_tail.html');
	$tpl->message_die('Roster is already installed - <span class="negative">remove</span> the file <span class="positive">install.php</span> and the folder <span class="positive">install/</span> in this directory.', 'Installation Error');
	exit();
}

// View phpinfo() if requested
if ( (isset($_GET['mode'])) && ($_GET['mode'] == 'phpinfo') )
{
	phpinfo();
	exit;
}

// System defaults / available database abstraction layers
$DEFAULTS = array(
	'version'        => '1.7.0',
	'default_locale' => 'enUS',
	'db_prefix'      => 'roster_',
	'dbal'           => 'mysql',
);

// Database settings
$DBALS    = array(
	'mysql' => array(
		'label'       => 'MySQL 3/4/5',
		'structure'   => 'mysql',
		'comments'    => 'remove_remarks',
		'delim'       => ';',
		'delim_basic' => ';',
	)
);

// Set locales
$LOCALES = array(
	'English' => array(
		'label'	=> 'English',
		'type'	=> 'enUS'
		),
	'German'  => array(
		'label' => 'German',
		'type'	=> 'deDE'
		),
	'French'  => array(
		'label' => 'French',
		'type'	=> 'frFR'
		),
	);

// ---------------------------------------------------------
// Figure out what we're doing...
// ---------------------------------------------------------
switch ( $STEP )
{
	case 1:
		process_step1();
		break;
	case 2:
		process_step2();
		break;
	case 3:
		process_step3();
		break;
	case 4:
		process_step4();
		break;
	default:
		process_step1();
		break;
}

// ---------------------------------------------------------
// And do it
// ---------------------------------------------------------
function process_step1()
{
	global $roster_root_path, $DEFAULTS;

	$tpl = new Template_Wrap('install_step1.html','install_header.html','install_tail.html');

/* DISABLED
    //
    // Check to make sure conf.php exists and is readable / writeable
    //
    $config_file = $roster_root_path . 'conf.php';
    if ( !file_exists($config_file) )
    {
        if ( !@touch($config_file) )
        {
            $tpl->error_append('The <b>conf.php</b> file does not exist and could not be created in Roster\'s root folder.<br />You must create this file and enable write access on your server before proceeding.');
        }
        else
        {
            $tpl->message_append('The <b>conf.php</b> file has been created in Roster\'s root folder<br />Deleting this file will interfere with the operation of your Roster installation.');
        }
    }
    else
    {
        if ( (!is_writeable($config_file)) || (!is_readable($config_file)) )
        {
            if ( !@chmod($config_file, 0666) )
            {
                $tpl->error_append('The file <b>conf.php</b> is not set to be readable/writeable and could not be changed automatically.<br />Please change the permissions to 0666 manually by executing <b>chmod 0666 conf.php</b> on your server.');
            }
            else
            {
                $tpl->message_append('<b>conf.php</b> has been set to be readable/writeable in order to let this installer write your configuration file automatically.');
            }
        }
        // config file exists and is writeable, we're good to go
    }
    clearstatcache();
*/

    //
    // Server settings
    //
    // Roster versions
    $our_roster_version   = $DEFAULTS['version'];
    $their_roster_version = 'Unknown';


/* DISABLED FOR NOW CAN WE USE THIS???
    // Check wowroster.net for versioning
    $sh = @fsockopen('wowroster.net', 80, $errno, $error, 5);
    if ( !$sh )
    {
        $their_roster_version = 'Connection failed.';
    }
    else
    {
        @fputs($sh, "GET /FILELOCATION HTTP/1.1\r\nHost: wowroster.net\r\nConnection: close\r\n\r\n");
        while ( !@feof($sh) )
        {
            $content = @fgets($sh, 512);
            if ( preg_match('#<version>(.+)</version>#i', $content, $version) )
            {
                $their_roster_version = $version[1];
                break;
            }
        }
    }
    @fclose($sh);
*/

    // Roster Versions
    $our_roster_version = (( $our_roster_version >= $their_roster_version || $their_roster_version == 'Unknown' ) ? '<span class="positive">' : '<span class="negative">') . $our_roster_version . '</span>';

    // PHP Versions
    $our_php_version   = (( phpversion() >= '4.3.0' ) ? '<span class="positive">' : '<span class="negative">') . phpversion() . '</span>';
    $their_php_version = '4.3.0+';

    // Modules
    $our_mysql   = ( extension_loaded('mysql') ) ? '<span class="positive">Yes</span>' : '<span class="negative">No</span>';
    // Required?
    $their_mysql = 'Yes';

    $our_gd    = ( function_exists('imageTTFBBox') && function_exists('imageTTFText') && function_exists('imagecreatetruecolor') )  ? '<span class="positive">Yes</span>' : '<span class="negative">No</span> - <a href="gd_info.php" target="_new">More Info</a>';
    // Required?
    $their_gd  = 'Optional';


    if ( (phpversion() < '4.3.0') || (!extension_loaded('mysql')) )
    {
        $tpl->error_append('<span style="font-weight: bold; font-size: 14px;">Sorry, your server does not meet the minimum requirements for Roster</span>');
    }
    else
    {
        $tpl->message_append('Roster Installer has scanned your server and determined that it meets the requirements');
    }

    //
    // Output the page
    //
	$tpl->assign_vars(array(
			'OUR_ROSTER_VERSION'   => $our_roster_version,
			'THEIR_ROSTER_VERSION' => $their_roster_version,
			'OUR_PHP_VERSION'      => $our_php_version,
			'THEIR_PHP_VERSION'    => $their_php_version,
			'OUR_MYSQL'            => $our_mysql,
			'THEIR_MYSQL'          => $their_mysql,
			'OUR_GD'               => $our_gd,
			'THEIR_GD'             => $their_gd
		)
	);

    $tpl->page_header();
    $tpl->page_tail();
}

function process_step2()
{
    global $roster_root_path, $DEFAULTS, $DBALS, $LOCALES;

    $tpl = new Template_Wrap('install_step2.html','install_header.html','install_tail.html');

    //
    // Build the database drop-down
    //
    foreach ( $DBALS as $db_type => $db_options )
    {
        $tpl->assign_block_vars('dbal_row', array(
            'VALUE'  => $db_type,
            'OPTION' => $db_options['label'])
        );
    }

    //
    // Build the default language drop-down
    //
    foreach ( $LOCALES as $locale_type => $locale_desc )
    {
        $tpl->assign_block_vars('locale_row', array(
            'VALUE'  => $locale_desc['type'],
            'OPTION' => $locale_type)
        );
    }

    //
    // Determine server settings
    //
	if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
	{
		$server_name = 'http://'.((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
	}
	else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
	{
		$server_name = 'http://'.((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
	}
	else
	{
		$server_name = '';
	}

    $server_path = str_replace('/install.php', '', $_SERVER['PHP_SELF']);

    $tpl->message_append('Before proceeding, please verify that the database name you provided is already created<br />And that the user you provided has permission to create tables in that database');

    //
    // Output the page
    //
    $tpl->assign_vars(array(
        'DB_HOST'      => 'localhost',
        'TABLE_PREFIX' => $DEFAULTS['db_prefix'],
        'SERVER_NAME'  => $server_name,
        'SERVER_PATH'  => $server_path
       )
    );

    $tpl->page_header();
    $tpl->page_tail();
}

function process_step3()
{
    global $roster_root_path, $DEFAULTS, $DBALS, $LOCALES;

    $tpl = new Template_Wrap('install_step3.html','install_header.html','install_tail.html');

    //
    // Get our posted data
    //
    $server_name    = post_or_db('server_name');
    $server_path    = post_or_db('server_path');
    $db_host        = post_or_db('db_host');
    $db_name        = post_or_db('db_name');
    $db_user        = post_or_db('db_user');
    $db_passwd      = post_or_db('db_passwd');
    $db_prefix      = post_or_db('db_prefix', $DEFAULTS);
    $default_locale = post_or_db('default_locale', $DEFAULTS);

    $dbal_file = $roster_root_path . 'lib/wowdb.php';
    if ( !file_exists($dbal_file) )
    {
        $tpl->message_die('Unable to find the database layer for Roster, check to make sure ' . $dbal_file . ' exists.', 'Database Error');
    }

    //
    // Database population
    //
    define('CONFIG_TABLE', $db_prefix . 'config');

    include_once($dbal_file);
    $wowdb->connect($db_host, $db_user, $db_passwd, $db_name);

    // Check to make sure a connection was made
    if ( !is_resource($wowdb->db) )
    {
        $tpl->message_die('Failed to connect to database <b>' . $db_name . '</b> as <b>' . $db_user . '@' . $db_host . '</b><br /><br /><a href="install.php">Restart Installation</a>', 'Database Error');
    }

    $db_structure_file = $roster_root_path . 'install/db/mysql_structure.sql';
    $db_data_file      = $roster_root_path . 'install/db/mysql_data.sql';

    $remove_remarks_function = $DBALS['mysql']['comments'];

    // I require MySQL version 4.0.4 minimum.
    $server_version = mysql_get_server_info();
    $client_version = mysql_get_client_info();

    if ( (isset($server_version) && isset($client_version)) )
    {
        $tpl->message_append('MySQL client <b>and</b> server versions 4.0.4 or higher and MyISAM table support are required for Roster.<br /><br />
          <b>You are running</b>
          <ul>
            <li>server version - <b>' . $server_version . '</b></li>
            <li>client version - <b>' . $client_version . '</b></li>
          </ul>
          MySQL versions less than 4.0.4 may not work and are not supported.<br />
          Versions less than 4.0.4 may have unexpected issues, and we <u>will not</u> provide support for these installations.<br /><br />');
    }
    else
    {
		$tpl->message_die('Failed to get version information for database <b>' . $db_name . '</b> as <b>' . $db_user . '@' . $db_host . '</b><br /><br /><a href="install.php">Restart Installation</a>', 'Database Error');
    }

    // Parse structure file and create database tables
    $sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
    $sql = preg_replace('#roster\_(\S+?)([\s\.,]|$)#', $db_prefix . '\\1\\2', $sql);

    $sql = $remove_remarks_function($sql);
    $sql = parse_sql($sql, $DBALS['mysql']['delim']);

    $sql_count = count($sql);
    for ( $i = 0; $i < $sql_count; $i++ )
    {
        $wowdb->query($sql[$i]);
		// Added failure checks to the database transactions
		/*if ( !($wowdb->query($sql[$i]) ) )
		{
			$tpl->message_die('Failed to connect to database <b>' . $db_name . '</b> as <b>' . $db_user . '@' . $db_host . '</b><br /><br /><a href="install.php">Restart Installation</a>', 'Database Error');
		}*/
    }
    unset($sql);

    // Parse the data file and populate the database tables
    $sql = @fread(@fopen($db_data_file, 'r'), @filesize($db_data_file));
    $sql = preg_replace('#INSERT INTO \`roster\_(\S+?)([\s\.,]|$)#', 'INSERT INTO `'.$db_prefix . '\\1\\2', $sql);

    $sql = $remove_remarks_function($sql);
    $sql = parse_sql($sql, $DBALS['mysql']['delim']);

    $sql_count = count($sql);
    for ( $i = 0; $i < $sql_count; $i++ )
    {
        $wowdb->query($sql[$i]);
		// Added failure checks to the database transactions
		/*if ( !($wowdb->query($sql[$i])) )
		{
			$tpl->message_die('Failed to connect to database <b>' . $db_name . '</b> as <b>' . $db_user . '@' . $db_host . '</b><br /><br /><a href="install.php">Restart Installation</a>', 'Database Error');
		}*/
    }
    unset($sql);

    //
    // Update some config settings
    //
    $wowdb->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value`='".$default_locale."' WHERE `config_name`='roster_lang'");
    $wowdb->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value`='".$server_path."' WHERE `config_name`='roster_dir'");
    $wowdb->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value`='".$server_name."' WHERE `config_name`='website_address'");

    //
    // Assign Variables
    //
    $tpl->assign_vars(array(
        'DB_HOST'   => $db_host,
        'DB_NAME'   => $db_name,
        'DB_USER'   => $db_user,
        'DB_PASSWD' => $db_passwd,
        'DB_PREFIX' => $db_prefix,
       )
    );

    //
    // Output the page
    //
    $tpl->page_header();
    $tpl->page_tail();
}

function process_step4()
{
    global $roster_root_path, $DEFAULTS;

    $tpl = new Template_Wrap('install_step4.html','install_header.html','install_tail.html');

    //
    // Get our posted data
    //
    $user_password1 = post_or_db('user_password1');
    $user_password2 = post_or_db('user_password2');
    $db_host        = post_or_db('db_host');
    $db_name        = post_or_db('db_name');
    $db_user        = post_or_db('db_user');
    $db_passwd      = post_or_db('db_passwd');
    $db_prefix      = post_or_db('db_prefix');

    //
    // Update admin account
    //
    define('CONFIG_TABLE', $db_prefix . 'config');

    include_once($roster_root_path . 'lib/wowdb.php');


    if( $user_password1 == '' || $user_password2 == '' )
    {
    	$pass_word = md5('admin');
    }
    elseif( $user_password1 == $user_password2 )
    {
    	$pass_word = md5($user_password1);
    }
    else
    {
    	$pass_word = md5('admin');
    }


    $wowdb->connect($db_host, $db_user, $db_passwd, $db_name);

    $wowdb->query("UPDATE " . CONFIG_TABLE . " SET `config_value`='".$pass_word."' WHERE `config_name`='roster_upd_pw';");

    //
    // Check password and notify uer
    //
    if ( $user_password1 != $user_password2 || $user_password1 == '' || $user_password2 == '' )
    {
        $tpl->message_append('<span style="font-weight: bold; font-size: 14px;" class="negative">NOTICE</span><br /><br />Your passwords did not match, so it has been reset to <b>admin</b>. You can change it by logging into Roster Config');
    }


	//
    // Write the config file
    //
    $config_file  = '';
    $config_file .= '<?php' . "\n";
	$config_file .= "/******************************\n";
	$config_file .= " * AUTO-GENERATED CONF FILE\n";
	$config_file .= " * DO NOT EDIT !!!\n";
	$config_file .= " ******************************/\n\n";

    $config_file .= '$db_host   = "' . $db_host    . '";' . "\n";
    $config_file .= '$db_name   = "' . $db_name    . '";' . "\n";
    $config_file .= '$db_user   = "' . $db_user    . '";' . "\n";
    $config_file .= '$db_passwd = "' . $db_passwd  . '";' . "\n";
    $config_file .= '$db_prefix = "' . $db_prefix  . '";' . "\n\n";

    $config_file .= 'define(\'ROSTER_INSTALLED\', true);' . "\n";

    $config_file .= '?>';

    // Set our permissions to execute-only
    @umask(0111);

    if ( !$fp = @fopen('conf.php', 'w') )
    {
	    $tpl->assign_vars(array(
	        'DOWNLOAD'   => true,
	        'CONF_FILE'   => htmlspecialchars($config_file),
	       )
	    );
    }
    else
    {
	    $tpl->assign_vars(array(
	        'DOWNLOAD'   => false,
	       )
	    );

        @fputs($fp, $config_file);
        @fclose($fp);

        $tpl->message_append('Your configuration file has been written, but installation will not be complete until you configure Roster');
    }

    $tpl->page_header();
    $tpl->page_tail();
}




// ---------------------------------------------------------
// Functions!
// ---------------------------------------------------------
/**
* Applies addslashes() to the provided data
*
* @param    mixed   $data   Array of data or a single string
* @return   mixed           Array or string of data
*/
function slash_global_data(&$data)
{
    if ( is_array($data) )
    {
        foreach ( $data as $k => $v )
        {
            $data[$k] = ( is_array($v) ) ? slash_global_data($v) : addslashes($v);
        }
    }
    return $data;
}

/**
* Checks if a POST field value exists;
* If it does, we use that one, otherwise we use the optional database field value,
* or return a null string if $db_row contains no data
*
* @param    string  $post_field POST field name
* @param    array   $db_row     Array of DB values
* @param    string  $db_field   DB field name
* @return   string
*/
function post_or_db($post_field, $db_row = array(), $db_field = '')
{
    if ( @sizeof($db_row) > 0 )
    {
        if ( $db_field == '' )
        {
            $db_field = $post_field;
        }

        $db_value = $db_row[$db_field];
    }
    else
    {
        $db_value = '';
    }

    return ( (isset($_POST[$post_field])) || (!empty($_POST[$post_field])) ) ? $_POST[$post_field] : $db_value;
}

/**
* Removes comments from a SQL data file
*
* @param    string  $sql    SQL file contents
* @return   string
*/
function remove_remarks($sql)
{
    if ( $sql == '' )
    {
        die('Could not obtain SQL structure/data');
    }

    $retval = '';
    $lines  = explode("\n", $sql);
    unset($sql);

    foreach ( $lines as $line )
    {
        // Only parse this line if there's something on it, and we're not on the last line
        if ( strlen($line) > 0 )
        {
            // If '#' is the first character, strip the line
            $retval .= ( substr($line, 0, 1) != '#' ) ? $line . "\n" : "\n";
        }
    }
    unset($lines, $line);

    return $retval;
}

/**
* Parse multi-line SQL statements into a single line
*
* @param    string  $sql    SQL file contents
* @param    char    $delim  End-of-statement SQL delimiter
* @return   array
*/
function parse_sql($sql, $delim)
{
    if ( $sql == '' )
    {
        die('Could not obtain SQL structure/data');
    }

    $retval     = array();
    $statements = explode($delim, $sql);
    unset($sql);

    $linecount = count($statements);
    for ( $i = 0; $i < $linecount; $i++ )
    {
        if ( ($i != $linecount - 1) || (strlen($statements[$i]) > 0) )
        {
            $statements[$i] = trim($statements[$i]);
            $statements[$i] = str_replace("\r\n", '', $statements[$i]) . "\n";

            // Remove 2 or more spaces
            $statements[$i] = preg_replace('#\s{2,}#', ' ', $statements[$i]);

            $retval[] = trim($statements[$i]);
        }
    }
    unset($statements);

    return $retval;
}
?>