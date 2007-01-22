<?php
/******************************
 * WoWRoster.net  UniAdmin
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
 * Set up environment
 */
define('IN_UNIADMIN', true);
error_reporting(E_ALL);

// Be paranoid with passed vars
// Destroy GET/POST/Cookie variables from the global scope
if( intval(ini_get('register_globals')) != 0 )
{
	foreach( $_REQUEST AS $key => $val )
	{
		if (isset($$key))
			unset($$key);
	}
}

set_magic_quotes_runtime(0);
if( !get_magic_quotes_gpc() )
{
	$_GET = slash_global_data($_GET);
	$_POST = slash_global_data($_POST);
}

if( !defined('DIR_SEP') )
{
	define('DIR_SEP',DIRECTORY_SEPARATOR);
}

// Start a script timer
$mc_split = split(' ', microtime());
$timer_start = $mc_split[0] + $mc_split[1];
unset($mc_split);


$ua_root_path = dirname(__FILE__).DIR_SEP;
define( 'UA_BASEDIR' , $ua_root_path );
define( 'UA_THEMEDIR' , UA_BASEDIR . 'styles' . DIR_SEP );
define( 'UA_CACHEDIR' , UA_BASEDIR . 'cache' . DIR_SEP );
define( 'UA_LANGDIR'  , UA_BASEDIR . 'language' . DIR_SEP );
define( 'UA_ADDONZIP_DIR' , UA_BASEDIR . 'addon_zips' . DIR_SEP );
define( 'UA_ADDONTMP_DIR' , UA_BASEDIR . 'addon_temp' . DIR_SEP );
define( 'UA_LOGO_DIR' , UA_BASEDIR . 'logos' . DIR_SEP );
define( 'UA_DEBUG', 2 );

// Get the config file
if( file_exists($ua_root_path . 'config.php') )
{
	include_once($ua_root_path . 'config.php');
}

// ---------------------------------------------------------
// Template Wrap class
// ---------------------------------------------------------
if( !include_once($ua_root_path . 'include' . DIR_SEP . 'template.php') )
{
	die('Could not include include/template.php - check to make sure that the file exists!');
}


class Template_Wrap extends Template
{
	var $error_message   = array();           // Array of errors      @var $error_message
	var $install_message = array();           // Array of messages    @var $install_message
	var $header_inc      = false;             // Printed header?      @var $header_inc
	var $tail_inc        = false;             // Printed footer?      @var $tail_inc
	var $template_file   = '';                // Template filename    @var $template_file

	function template_wrap($template_file)
	{
		$this->template_file = $template_file;

		$this->set_template('install');

		$this->assign_vars(array(
			'MSG_TITLE' => '',
			'MSG_TEXT'  => ''
			)
		);

		$this->set_filenames(array(
			'body' => $this->template_file
			)
		);
	}

	function message_die($text = '', $title = '')
	{
		$this->set_filenames(array(
			'body' => 'install_error.html'
			)
		);

		$this->assign_vars(array(
			'MSG_TITLE' => ( $title != '' ) ? $title : '&nbsp;',
			'MSG_TEXT'  => ( $text  != '' ) ? $text  : '&nbsp;'
			)
		);

		if ( !$this->header_inc )
		{
			$this->page_header();
		}

		$this->page_tail();
	}

	function message_append($message)
	{
		$this->install_message[ sizeof($this->install_message) + 1 ] = $message;
	}

	function message_out($die = false)
	{
		sort($this->install_message);
		reset($this->install_message);

		$install_message = implode('<br /><br />', $this->install_message);

		if( $die )
		{
			$this->message_die($install_message, 'Installation ' . (( sizeof($this->install_message) == 1 ) ? 'Note' : 'Notes'));
		}
		else
		{
			$this->assign_vars(array(
				'MSG_TITLE' => 'Installation ' . (( sizeof($this->install_message) == 1 ) ? 'Note' : 'Notes'),
				'MSG_TEXT'  => $install_message
				)
			);
		}
	}

	function error_append($error)
	{
		$this->error_message[ (sizeof($this->error_message) + 1) ] = $error;
	}

	function error_out($die = false)
	{
		sort($this->error_message);
		reset($this->error_message);

		$error_message = implode('<br /><br />', $this->error_message);

		if( $die )
		{
			$this->message_die($error_message, 'Installation ' . (( sizeof($this->error_message) == 1 ) ? 'Error' : 'Errors'));
		}
		else
		{
			$this->assign_vars(array(
				'MSG_TITLE' => 'Installation ' . (( sizeof($this->error_message) == 1 ) ? 'Error' : 'Errors'),
				'MSG_TEXT'  => $error_message
				)
			);
		}
	}

	function page_header()
	{
		global $STEP;

		$this->header_inc = true;

		$this->assign_vars(array(
			'INSTALL_STEP' => $STEP,
			'TEMPLATE_PATH' => 'styles/install'
			)
		);
	}

	function page_tail()
	{
		global $DEFAULTS, $db, $timer_start;

		$this->assign_var('S_SHOW_BUTTON', true);

		if( sizeof($this->install_message) > 0 )
		{
			$this->message_out(false);
		}

		if( sizeof($this->error_message) > 0 )
		{
			$this->assign_var('S_SHOW_BUTTON', false);
			$this->error_message[0] = '<span style="font-weight: bold; font-size: 14px;" class="negative">NOTICE</span>';
			$this->error_out(false);
		}

		$mc_split = split(' ', microtime());
		$timer_end = $mc_split[0] + $mc_split[1];
		unset($mc_split);

		if ( UA_DEBUG )
		{
			$this->assign_vars(array(
				'S_SHOW_DEBUG'   => true,
				'U_RENDERTIME'   => substr($timer_end - $timer_start, 0, 5),
				'U_QUERYCOUNT'   => is_object($db) ? $db->query_count : 0
				)
			);
		}
		else
		{
			$this->assign_var('S_SHOW_DEBUG',false);
		}

		$this->assign_var('UA_VER', $DEFAULTS['version']);

		if( is_object($db) )
		{
			$db->close_db();
		}

		$this->display('body');
		$this->destroy();

		exit;
	}
}

$STEP = ( isset($_POST['install_step']) ) ? $_POST['install_step'] : '1';

// If EQdkp is already installed, don't let them install it again
if( defined('UA_INSTALLED') )
{
	$tpl = new Template_Wrap('install_error.html');
	$tpl->message_die('UniAdmin is already installed - please remove the <strong>install.php</strong> file in this directory.', 'Installation Error');
	exit();
}

// View phpinfo() if requested
if( (isset($_GET['mode'])) && ($_GET['mode'] == 'phpinfo') )
{
	phpinfo();
	exit;
}

// System defaults / available database abstraction layers
$DEFAULTS = array(
	'version'       => '0.7.5',
	'default_lang'  => 'english',
	'default_style' => '1',
	'table_prefix'  => 'uniadmin_',
	'dbal'          => 'mysql'
);

$DBALS = array(
	'mysql' => array(
		'label'       => 'MySQL 4.x',
		'structure'   => 'mysql',
		'comments'    => 'remove_remarks',
		'delim'       => ';',
		'delim_basic' => ';'
	)
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
	global $ua_root_path, $DEFAULTS;

	$tpl = new Template_Wrap('install_step1.html');

	/**
	 * Check to make sure config.php exists and is readable / writeable
	 */
	$config_file = $ua_root_path . 'config.php';
	if( !file_exists($config_file) )
	{
		if( !@touch($config_file) )
		{
			$tpl->error_append('The <strong>config.php</strong> file does not exist and could not be created in UniAdmin\'s root folder.<br />You must create an empty config.php file on your server before proceeding. And give it write access');
		}
		else
		{
			$tpl->message_append('The <strong>config.php</strong> file has been created in UniAdmin\'s root folder<br />Deleting this file will interfere with the operation of your UniAdmin installation.');
		}
	}
	else
	{
		if( (!is_writeable($config_file)) || (!is_readable($config_file)) )
		{
			if( !@chmod($config_file, 0666) )
			{
				$tpl->error_append('The file <strong>config.php</strong> is not set to be readable/writeable and could not be changed automatically.<br />Please change the permissions to 0666 manually by executing <strong>chmod 0666 config.php</strong> on your server.');
			}
			else
			{
				$tpl->message_append('<strong>config.php</strong> has been set to be readable/writeable in order to let this installer write your configuration file automatically.');
			}
		}
		// config file exists and is writeable, we're good to go
	}
	clearstatcache();

	/**
	 * Check to make sure cache exists and is writeable
	 */
	$cache_write = 'green';
	$cache_write_t = 'Write access confirmed';
	if( !file_exists(UA_CACHEDIR) )
	{
		if( !@mkdir(UA_CACHEDIR, 0777) )
		{
			$tpl->error_append('The template cache directory could not be created, create &quot;cache&quot;one manually in the root directory');
			$cache_write = 'red';
			$cache_write_t = 'Write access denied, read the info above';
		}
		else
		{
			$tpl->message_append('A template cache directory was created');
		}
	}
	else
	{
		if( !is_writeable(UA_CACHEDIR) )
		{
			if( !@chmod(UA_CACHEDIR, 0777) )
			{
				$tpl->error_append('The template cache directory exists, but is not set to be writeable and could not be changed automatically.<br />Please change the permissions to 0777 manually by executing <strong>chmod 0777 cache</strong> on your server.');
				$cache_write = 'red';
				$cache_write_t = 'Write access denied, read the info above';
			}
			else
			{
				$tpl->message_append('The template cache directory has been set to be writeable in order to let the Templating engine to function');
			}
		}
		// Cache directory exists and is writeable, we're good to go
	}
	clearstatcache();

	/**
	 * Check to make sure addon_temp exists and is writeable
	 */
	$addontmp_write = 'green';
	$addontmp_write_t = 'Write access confirmed';
	if( !file_exists(UA_ADDONTMP_DIR) )
	{
		if( !@mkdir(UA_ADDONTMP_DIR, 0777) )
		{
			$tpl->error_append('The addon temp directory could not be created, create the directory &quot;addon_temp&quot;manually');
			$addontmp_write = 'red';
			$addontmp_write_t = 'Write access denied, read the info above';
		}
		else
		{
			$tpl->message_append('The addon temp directory was created');
		}
	}
	else
	{
		if( !is_writeable(UA_ADDONTMP_DIR) )
		{
			if( !@chmod(UA_ADDONTMP_DIR, 0777) )
			{
				$tpl->error_append('The addon temp directory exists, but is not set to be writeable and could not be changed automatically.<br />Please change the permissions to 0777 manually by executing <strong>chmod 0777 addon_temp</strong> on your server.');
				$addontmp_write = 'red';
				$addontmp_write_t = 'Write access denied, read the info above';
			}
			else
			{
				$tpl->message_append('The addon temp directory has been set to be writeable so the addon upload process functions properly');
			}
		}
		// addon temp directory exists and is writeable, we're good to go
	}
	clearstatcache();

	/**
	 * Check to make sure addon_zips exists and is writeable
	 */
	$addonzip_write = 'green';
	$addonzip_write_t = 'Write access confirmed';
	if( !file_exists(UA_ADDONZIP_DIR) )
	{
		if( !@mkdir(UA_ADDONZIP_DIR, 0777) )
		{
			$tpl->error_append('The addon zip directory could not be created, create the directory &quot;addon_zips&quot; manually');
			$addonzip_write = 'red';
			$addonzip_write_t = 'Write access denied, read the info above';
		}
		else
		{
			$tpl->message_append('The addon temp directory was created');
		}
	}
	else
	{
		if( !is_writeable(UA_ADDONZIP_DIR) )
		{
			if( !@chmod(UA_ADDONZIP_DIR, 0777) )
			{
				$tpl->error_append('The addon zip directory exists, but is not set to be writeable and could not be changed automatically.<br />Please change the permissions to 0777 manually by executing <strong>chmod 0777 addon_zips</strong> on your server.');
				$addonzip_write = 'red';
				$addonzip_write_t = 'Write access denied, read the info above';
			}
			else
			{
				$tpl->message_append('The addon zip directory has been set to be writeable so the addon upload process functions properly');
			}
		}
		// addon zip directory exists and is writeable, we're good to go
	}
	clearstatcache();

	/**
	 * Check to make sure addon_zips exists and is writeable
	 */
	$logo_write = 'green';
	$logo_write_t = 'Write access confirmed';
	if( !file_exists(UA_LOGO_DIR) )
	{
		if( !@mkdir(UA_LOGO_DIR, 0777) )
		{
			$tpl->error_append('The logo directory could not be created, create the directory &quot;logos&quot; manually');
			$logo_write = 'red';
			$logo_write_t = 'Write access denied, read the info above';
		}
		else
		{
			$tpl->message_append('The logo directory was created');
		}
	}
	else
	{
		if( !is_writeable(UA_LOGO_DIR) )
		{
			if( !@chmod(UA_LOGO_DIR, 0777) )
			{
				$tpl->error_append('The logo directory exists, but is not set to be writeable and could not be changed automatically.<br />Please change the permissions to 0777 manually by executing <strong>chmod 0777 logos</strong> on your server');
				$logo_write = 'red';
				$logo_write_t = 'Write access denied, read the info above';
			}
			else
			{
				$tpl->message_append('The logo directory has been set to be writeable so the addon upload process functions properly');
			}
		}
		// addon zip directory exists and is writeable, we're good to go
	}
	clearstatcache();

	/**
	 * UniAdmin versions
	 */
	$our_ua_version   = $DEFAULTS['version'];
	$their_ua_version = 'Unknown';
	$sh = @fsockopen('wowroster.net', 80, $errno, $error, 5);
	if ( !$sh )
	{
		$their_ua_version = 'Connection to wowroster.com failed.';
	}
	else
	{
		@fputs($sh, "GET /ua_version.txt HTTP/1.1\r\nHost: wowroster.net\r\nConnection: close\r\n\r\n");
		while ( !@feof($sh) )
		{
			$content = @fgets($sh, 512);
			if ( preg_match('#<version>(.+)</version>#i', $content, $version) )
			{
				$their_ua_version = $version[1];
				break;
			}
		}
	}
	@fclose($sh);

	// UA Versions
	$our_ua_version   = (( $our_ua_version >= $their_ua_version ) ? '<span class="positive">' : '<span class="negative">') . $our_ua_version . '</span>';

	// PHP Versions
	$our_php_version   = (( phpversion() >= '4.3.0' ) ? '<span class="positive">' : '<span class="negative">') . phpversion() . '</span>';
	$their_php_version = '4.3 or higher';

	// Modules
	$our_mysql   = ( extension_loaded('mysql') ) ? '<span class="positive">Yes</span>' : '<span class="negative">No</span>';
	$their_mysql = '4.x or higher';

	if ( (phpversion() < '4.3.0') || (!extension_loaded('mysql')) )
	{
		$tpl->error_append('<span style="font-weight: bold; font-size: 14px;">Sorry, your server does not meet the minimum requirements for UniAdmin</span>');
	}
	else
	{
		$tpl->message_append('UniAdmin has scanned your server and determined that it meets the minimum requirements.');
	}

	//
	// Output the page
	//
	$tpl->assign_vars(array(
		'OUR_UA_VERSION'    => $our_ua_version,
		'THEIR_UA_VERSION'  => $their_ua_version,
		'OUR_PHP_VERSION'   => $our_php_version,
		'THEIR_PHP_VERSION' => $their_php_version,
		'OUR_MYSQL'         => $our_mysql,
		'THEIR_MYSQL'       => $their_mysql,
		'CACHE_WRITE'       => $cache_write,
		'ADDON_TMP_WRITE'   => $addontmp_write,
		'ADDON_WRITE'       => $addonzip_write,
		'LOGO_WRITE'        => $logo_write,
		'CACHE_TIP'         => $cache_write_t,
		'ADDONTEMP_TIP'     => $addontmp_write_t,
		'ADDONZIP_TIP'      => $addonzip_write_t,
		'LOGO_TIP'          => $logo_write_t
		)
	);

	$tpl->page_header();
	$tpl->page_tail();
}

function process_step2()
{
	global $ua_root_path, $DEFAULTS, $DBALS;

	$tpl = new Template_Wrap('install_step2.html');

	/**
	 * Build the default language drop-down
	 */
	if( $handle = @opendir(UA_LANGDIR) )
	{
		while( false !== ($file = readdir($handle)) )
		{
			if( $file != '.' && $file != '..' && $file != '.svn' && !is_dir(UA_LANGDIR.$file) )
			{
				$tpl->assign_block_vars('language_row', array(
					'VALUE'  => substr($file,0,-4),
					'OPTION' => ucfirst(strtolower(substr($file,0,-4)))
					)
				);
			}
		}
	}
	else
	{
		$tpl->message_die('Cannot read the directory ['.UA_LANGDIR.']', 'Installation Error');
	}

	/**
	 * Build the database drop-down
	 */
	foreach ( $DBALS as $db_type => $db_options )
	{
		$tpl->assign_block_vars('dbal_row', array(
			'VALUE'  => $db_type,
			'OPTION' => $db_options['label']
			)
		);
	}

	$tpl->message_append('Before proceeding, please verify that the database name you provided is already created<br />and that the user you provided has permission to create tables in that database');

	/**
	 * Output the page
	 */
	$tpl->assign_vars(array(
		'DB_HOST'      => 'localhost',
		'TABLE_PREFIX' => $DEFAULTS['table_prefix']
		)
	);

	$tpl->page_header();
	$tpl->page_tail();
}

function process_step3()
{
	global $ua_root_path, $DEFAULTS, $DBALS;

	$tpl = new Template_Wrap('install_step3.html');

	/**
	 * Get our posted data
	 */
	$default_lang = post_or_db('default_lang', $DEFAULTS);
	$config['dbtype']       = post_or_db('dbtype');
	$config['host']         = post_or_db('dbhost');
	$config['database']     = post_or_db('dbname');
	$config['username']     = post_or_db('dbuser');
	$config['password']     = post_or_db('dbpass');
	$config['table_prefix'] = post_or_db('table_prefix', $DEFAULTS);

	define('CONFIG_TABLE', $config['table_prefix'] . 'config');
	define('USERS_TABLE',  $config['table_prefix'] . 'users');
	define('UA_DB_DIR',  $ua_root_path . 'include' . DIR_SEP . 'dbal' . DIR_SEP);

	$dbal_file = UA_DB_DIR . $config['dbtype'] . '.php';
	if ( !file_exists($dbal_file) )
	{
		$tpl->message_die('Unable to find the database abstraction layer for <strong>' . $config['dbtype'] . '</strong>, check to make sure ' . $dbal_file . ' exists.');
	}

	//
	// Database population
	//
	include_once($dbal_file);
	$db = new SQL_DB($config['host'], $config['database'], $config['username'], $config['password'], false);

	// Check to make sure a connection was made
	if ( !is_resource($db->link_id) )
	{
		$tpl->message_die('Failed to connect to database <strong>' . $config['database'] . '</strong> as <strong>' . $config['username'] . '@' . $config['host'] . '</strong><br /><br /><a href="install.php">Restart Installation</a>');
	}

	$db_structure_file = UA_DB_DIR . 'structure' . DIR_SEP . $config['dbtype'] . '_structure.sql';
	$db_data_file      = UA_DB_DIR . 'structure' . DIR_SEP . $config['dbtype'] . '_data.sql';

	$remove_remarks_function = $DBALS[$config['dbtype']]['comments'];

	// I require MySQL version 4.0.4 minimum.
	$server_version = mysql_get_server_info();
	$client_version = mysql_get_client_info();

	if ( (isset($server_version) && isset($client_version)) )
	{
		$tpl->message_append('MySQL client <strong>and</strong> server version 4.0.4 or higher is required for UniAdmin
			<ul>
				<li><strong>Your server version: ' . $server_version . '</strong></li>
				<li><strong>Your client version: ' . $client_version . '</strong></li>
			</ul>
			MySQL versions less than 4.0.4 may experience data corruption and are not supported<br />
			We will not provide support for these types of installations');
	}
	else
	{
		$tpl->message_die('Failed to get version information for database <strong>' . $config['database'] . '</strong> as <strong>' . $config['username'] . '@' . $config['host'] . '</strong><br /><br /><a href="install.php">Restart Installation</a>');
	}

	// Parse structure file and create database tables
	$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
	$sql = preg_replace('#uniadmin\_(\S+?)([\s\.,]|$)#', $config['table_prefix'] . '\\1\\2', $sql);

	$sql = $remove_remarks_function($sql);
	$sql = parse_sql($sql, $DBALS[$config['dbtype']]['delim']);

	$sql_count = count($sql);
	$i = 0;

	while( $i < $sql_count )
	{
		if( isset($sql[$i]) && $sql[$i] != '' )
		{
			if( !($db->query($sql[$i])) )
			{
				$tpl->message_die('Failed to connect to database <strong>' . $config['database'] . '</strong> as <strong>' . $config['username'] . '@' . $config['host'] . '</strong>
				<br /><br /><a href="install.php">Restart Installation</a>');
			}
		}
		$i++;
	}
	unset($sql);

	// Parse the data file and populate the database tables
	$sql = @fread(@fopen($db_data_file, 'r'), @filesize($db_data_file));
	$sql = preg_replace('#uniadmin\_(\S+?)([\s\.,]|$)#', $config['table_prefix'] . '\\1\\2', $sql);

	$sql = $remove_remarks_function($sql);
	$sql = parse_sql($sql, $DBALS[$config['dbtype']]['delim']);

	$sql_count = count($sql);
	$i = 0;

	while ( $i < $sql_count )
	{
		if( isset($sql[$i]) && $sql[$i] != '' )
		{
			if( !($db->query($sql[$i])) )
			{
				$tpl->message_die('Failed to connect to database <strong>' . $config['database'] . '</strong> as <strong>' . $config['username'] . '@' . $config['host'] . '</strong>
				<br /><br /><a href="install.php">Restart Installation</a>');
			}
		}
		$i++;
	}

	unset($sql);

	/**
	 * Update some config settings
	 */
	$db->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value` = '".$default_lang."' WHERE `config_name` = 'default_lang';");

	/**
	 * Write the config file
	 */
	$config_file  = '';
	$config_file .= "<?php\n\n";
	$config_file .= "\$config['host']         = '" . $config['host']         . "';\n";
	$config_file .= "\$config['username']     = '" . $config['username']     . "';\n";
	$config_file .= "\$config['password']     = '" . $config['password']     . "';\n";
	$config_file .= "\$config['database']     = '" . $config['database']     . "';\n";
	$config_file .= "\$config['table_prefix'] = '" . $config['table_prefix'] . "';\n";
	$config_file .= "\$config['dbtype']       = '" . $config['dbtype']       . "';\n";

	// Set our permissions to execute-only
	@umask(0111);

	if ( !$fp = @fopen('config.php', 'w') )
	{
		$error_message  = 'The <strong>config.php</strong> file couldn\'t be opened for writing.  Paste the following in to config.php and save the file to continue:<br /><pre>' . $config_file . '</pre>';
		$tpl->error_append($error_message);
	}
	else
	{
		@fputs($fp, $config_file);
		@fclose($fp);

		$tpl->message_append('Your configuration file has been written with the initial values<br />But installation will not be complete until you create an administrator account in this step');
	}

	/**
	 * Output the page
	 */
	$tpl->page_header();
	$tpl->page_tail();
}

function process_step4()
{
	global $ua_root_path, $DEFAULTS;

	$tpl = new Template_Wrap('install_step4.html');

	/**
	 * Get our posted data
	 */
	$username       = post_or_db('username');
	$user_password1 = post_or_db('user_password1');
	$user_password2 = post_or_db('user_password2');

	/**
	 * Update admin account
	 */
	include($ua_root_path . 'config.php');
	define('CONFIG_TABLE', $config['table_prefix'] . 'config');
	define('USERS_TABLE',  $config['table_prefix'] . 'users');
	define('UA_DB_DIR',  $ua_root_path . 'include' . DIR_SEP . 'dbal' . DIR_SEP);

	define('DEBUG', 2);
	switch ( $config['dbtype'] )
	{
		case 'mysql':
			include_once(UA_DB_DIR . 'mysql.php');
			break;
		default:
			include_once(UA_DB_DIR . 'mysql.php');
			break;
	}

	$db = new SQL_DB($config['host'], $config['database'], $config['username'], $config['password'], false);

	$sql = 'SELECT `config_value` FROM ' . CONFIG_TABLE . " WHERE `config_name` = 'default_lang';";
	$default_lang = $db->query_first($sql);

	$sql = 'SELECT `config_value` FROM ' . CONFIG_TABLE . " WHERE `config_name` = 'default_style';";
	$default_style = $db->query_first($sql);

	$query = $db->build_query('UPDATE', array(
		'name'       => $username,
		'password'   => ( $user_password1 == $user_password2 ) ? md5($user_password1) : md5('changeme'),
		'language'   => $default_lang,
		'user_style' => $default_style
		)
	);

	$db->query('UPDATE `' . USERS_TABLE . '` SET ' . $query . " WHERE `id` = '1'");


	/**
	 * Rewrite the config file to its final form
	 */
	$config_file = file($ua_root_path . 'config.php');
	$config_file[] = 'define(\'UA_INSTALLED\', true);';
	$config_file = implode('', $config_file);

	// Set our permissions to execute-only
	@umask(0111);

	if ( !$fp = @fopen('config.php', 'w') )
	{
		$error_message  = 'The <strong>config.php</strong> file couldn\'t be opened for writing.<br />Paste the following in to config.php and save the file to continue:<br /><pre>' . htmlspecialchars($config_file) . '</pre>';
		$tpl->error_append($error_message);
	}
	else
	{
		@fwrite($fp, $config_file, strlen($config_file));
		@fclose($fp);
	}

	/**
	 * Print out the login form
	 */
	if ( $user_password1 != $user_password2 )
	{
		$tpl->message_append('<span style="font-weight:bold;font-size:14px;" class="negative">NOTICE</span><br /><br />Your passwords did not match, so it has been reset to <strong>changeme</strong><br />You can change it by logging in and going to your account settings.');
	}

	$tpl->message_append('Your administrator account has been created, log in above to be taken to the UniAdmin configuration page.');

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
