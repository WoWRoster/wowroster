<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Installer
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
 * @subpackage Install
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

// Get the config file
if( file_exists(ROSTER_BASE . 'conf.php') )
{
	include_once(ROSTER_BASE . 'conf.php');
}

// ---------------------------------------------------------
// Template Wrap class
// ---------------------------------------------------------
if( !include_once(ROSTER_LIB . 'template.php') )
{
	die('Could not include lib/template.php - check to make sure that the file exists!');
}

class Template_Wrap extends Template
{
	var $error_message   = array();           // Array of errors      @var $error_message
	var $install_message = array();           // Array of messages    @var $install_message
	var $header_inc      = false;             // Printed header?      @var $header_inc
	var $tail_inc        = false;             // Printed footer?      @var $tail_inc

	function Template_Wrap()
	{
		if( !is_dir(ROSTER_TPLDIR . 'install') )
		{
			trigger_error("'install' theme does not exist", E_USER_ERROR);
		}

		$this->tpl = 'install';

		$this->assign_vars(array(
			'MSG_TITLE'    => '',
			'MSG_TEXT'     => '',
			'S_SQL'        => false,
			'U_QUERYCOUNT' => 0
			)
		);

		//$this->_tpldata['.'][0]['REQUEST_URI'] = str_replace('&', '&amp;', substr(request_uri(),strlen(ROSTER_PATH)));
		$this->root = 'templates/' . $this->tpl;
	}

	function message_die( $text = '' , $title = '' )
	{
		$this->set_filenames(array('body' => 'install_error.html'));

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
			'INSTALL_STEP'  => $STEP,
			'TEMPLATE_PATH' => 'templates/install',
			'FORMACTION'    => ''
			)
		);

		$this->set_filenames(array('header' => 'install_header.html'));
	}

	function page_tail()
	{
		global $DEFAULTS, $db;

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

		$this->assign_vars(array(
			'S_SHOW_DEBUG'   => true,
			'U_RENDERTIME'   => substr(format_microtime() - ROSTER_STARTTIME, 0, 5),
			'ROSTER_VERSION' => $DEFAULTS['version'],
			)
		);

		if( is_object($db) )
		{
			$db->close_db();
		}

		$this->set_filenames(array('footer' => 'install_tail.html'));

		$this->display('header');

		if( file_exists(ROSTER_BASE . 'valid.inc') )
		{
			include(ROSTER_BASE . 'valid.inc');
		}

		$this->display('body');
		$this->display('footer');

		exit;
	}
}

$STEP = ( isset($_POST['install_step']) ? $_POST['install_step'] : '1' );

// If Roster is already installed, don't let them install it again
if( defined('ROSTER_INSTALLED') )
{
	$tpl = new Template_Wrap();
	$tpl->set_filenames(array('body' => 'install_error.html'));
	$tpl->message_die('WoWRoster is already installed - please remove the files <strong>install.php</strong> and <strong>upgrade.php</strong>', 'Installation Error');
}

// View phpinfo() if requested
if( (isset($_GET['mode'])) && ($_GET['mode'] == 'phpinfo') )
{
	phpinfo();
	exit;
}

// System defaults / available database abstraction layers
$DEFAULTS = array(
	'version'        => ROSTER_VERSION,
	'default_locale' => 'enUS',
	'table_prefix'   => 'roster_',
	'dbal'           => 'mysql'
);

// Database settings
$DBALS = array(
	'mysql' => array(
		'label'       => 'MySQL 4.1.x / 5',
		'structure'   => 'mysql',
		'comments'    => 'remove_remarks',
		'delim'       => ';',
		'delim_basic' => ';'
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
	'Spanish'  => array(
		'label' => 'Spanish',
		'type'	=> 'esES'
		)
	);

/**
 * Figure out what we're doing...
 */
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

/**
 * And do it
 */
function process_step1()
{
	global $DEFAULTS;

	$tpl = new Template_Wrap();
	$tpl->set_filenames(array('body' => 'install_step1.html'));

	/**
	 * Check to make sure conf.php exists and is readable / writeable
	 */
	$config_file = ROSTER_BASE . 'conf.php';
	if( !file_exists($config_file) )
	{
		if( !@touch($config_file) )
		{
			$tpl->error_append('The <strong>conf.php</strong> file does not exist and could not be created in Roster\'s root folder.<br />You must create an empty conf.php file on your server before proceeding. And give it write access');
		}
		else
		{
			$tpl->message_append('The <strong>conf.php</strong> file has been created in Roster\'s root folder<br />Deleting this file will interfere with the operation of your Roster installation.');
		}
	}
	else
	{
		if( (!is_writeable($config_file)) || (!is_readable($config_file)) )
		{
			if( !@chmod($config_file, 0666) )
			{
				$tpl->error_append('The file <strong>conf.php</strong> is not set to be readable/writeable and could not be changed automatically.<br />Please change the permissions to 0666 manually by executing <strong>chmod 0666 conf.php</strong> on your server.');
			}
			else
			{
				$tpl->message_append('<strong>conf.php</strong> has been set to be readable/writeable in order to let this installer write your configuration file automatically.');
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
	if( !file_exists(ROSTER_CACHEDIR) )
	{
		if( !@mkdir(ROSTER_CACHEDIR, 0777) )
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
		if( !is_writeable(ROSTER_CACHEDIR) )
		{
			if( !@chmod(ROSTER_CACHEDIR, 0777) )
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
	 * Roster versions
	 */
	$our_roster_version   = $DEFAULTS['version'];
	$their_roster_version = 'Unknown';
	$sh = @fsockopen('wowroster.net', 80, $errno, $error, 5);
	if ( !$sh )
	{
		$their_roster_version = 'Connection to wowroster.net failed.';
	}
	else
	{
		@fputs($sh, "GET /roster_updater/version.txt HTTP/1.1\r\nHost: wowroster.net\r\nConnection: close\r\n\r\n");
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

	// Roster Versions
	$our_roster_version   = (( version_compare($our_roster_version, $their_roster_version, '>=') ) ? '<span class="positive">' : '<span class="negative">') . $our_roster_version . '</span>';

	// PHP Versions
	$our_php_version   = (( phpversion() >= '4.3.0' ) ? '<span class="positive">' : '<span class="negative">') . phpversion() . '</span>';
	$their_php_version = '4.3 +';

	// Modules
	$our_mysql   = ( extension_loaded('mysql') ) ? '<span class="positive">Yes</span>' : '<span class="negative">No</span>';
	// Required?
    $their_mysql = '4.1 +';

    $our_gd    = ( function_exists('imageTTFBBox') && function_exists('imageTTFText') && function_exists('imagecreatetruecolor') )  ? '<span class="positive">Yes</span>' : '<span class="negative">No</span> - <a href="gd_info.php" target="_blank">More Info</a>';
    // Required?
    $their_gd  = 'Optional';

	if ( (phpversion() < '4.3.0') || (!extension_loaded('mysql')) )
	{
		$tpl->error_append('<span style="font-weight: bold; font-size: 14px;">Sorry, your server does not meet the minimum requirements for Roster</span>');
	}
	else
	{
		$tpl->message_append('Roster has scanned your server and determined that it meets the minimum requirements.');
	}

	/**
	 * Output the page
	 */
	$tpl->assign_vars(array(
		'OUR_ROSTER_VERSION'   => $our_roster_version,
		'THEIR_ROSTER_VERSION' => $their_roster_version,
		'OUR_PHP_VERSION'      => $our_php_version,
		'THEIR_PHP_VERSION'    => $their_php_version,
		'OUR_MYSQL'            => $our_mysql,
		'THEIR_MYSQL'          => $their_mysql,
		'CACHE_WRITE'          => $cache_write,
		'CACHE_TIP'            => $cache_write_t,
		'OUR_GD'               => $our_gd,
		'THEIR_GD'             => $their_gd
		)
	);

	sql_output($tpl);
	$tpl->page_header();
	$tpl->page_tail();
}

function process_step2()
{
	global $DEFAULTS, $DBALS, $LOCALES;

	$tpl = new Template_Wrap();
	$tpl->set_filenames(array('body' => 'install_step2.html'));

	/**
	 * Build the default language drop-down
	 */
    foreach( $LOCALES as $locale_type => $locale_desc )
    {
    	if( file_exists(ROSTER_BASE . 'localization' . DIR_SEP . $locale_desc['type'] . '.php') )
    	{
	        $tpl->assign_block_vars('locale_row', array(
	            'VALUE'  => $locale_desc['type'],
	            'OPTION' => $locale_type,
	            )
	        );
    	}
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

    /**
     * Determine server settings
     */
	if( !empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']) )
	{
		$server_name = 'http://' . ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
	}
	elseif( !empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']) )
	{
		$server_name = 'http://' . ((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
	}
	else
	{
		$server_name = '';
	}

	$tpl->message_append('Before proceeding, please verify that the database name you provided is already created<br />and that the user you provided has permission to create tables in that database');

	/**
	 * Output the page
	 */
	$tpl->assign_vars(array(
		'DB_HOST'      => 'localhost',
		'TABLE_PREFIX' => $DEFAULTS['table_prefix'],
        'SERVER_NAME'  => $server_name
		)
	);

	sql_output($tpl);
	$tpl->page_header();
	$tpl->page_tail();
}

function process_step3()
{
	global $DEFAULTS, $DBALS, $LOCALES;

	$tpl = new Template_Wrap();
	$tpl->set_filenames(array('body' => 'install_step3.html'));

	/**
	 * Get our posted data
	 */
	$db_config['dbtype']       = post_or_db('dbtype');
	$db_config['host']         = post_or_db('dbhost');
	$db_config['database']     = post_or_db('dbname');
	$db_config['username']     = post_or_db('dbuser');
	$db_config['password']     = post_or_db('dbpass');
	$db_config['table_prefix'] = post_or_db('table_prefix', $DEFAULTS);
    $default_locale            = post_or_db('default_locale', $DEFAULTS);
    $server_name               = post_or_db('server_name');

	define('CONFIG_TABLE', $db_config['table_prefix'] . 'config');
	define('ROSTER_DB_DIR',  ROSTER_LIB . 'dbal' . DIR_SEP);

	$dbal_file = ROSTER_DB_DIR . $db_config['dbtype'] . '.php';
	if ( !file_exists($dbal_file) )
	{
		$tpl->message_die('Unable to find the database abstraction layer for <strong>' . $db_config['dbtype'] . '</strong>, check to make sure ' . $dbal_file . ' exists.');
	}

	/**
	 * Database population
	 */
	include_once($dbal_file);
	$db = new roster_db($db_config['host'], $db_config['database'], $db_config['username'], $db_config['password'], false);

	// Check to make sure a connection was made
	if ( !is_resource($db->link_id) )
	{
		$tpl->message_die('Failed to connect to database <strong>' . $db_config['database'] . '</strong> as <strong>' . $db_config['username'] . '@' . $db_config['host'] . '</strong><br /><br /><a href="index.php">Restart Installation</a>');
	}

	$db_structure_file = ROSTER_DB_DIR . 'structure' . DIR_SEP . $db_config['dbtype'] . '_structure.sql';
	$db_data_file      = ROSTER_DB_DIR . 'structure' . DIR_SEP . $db_config['dbtype'] . '_data.sql';

	$remove_remarks_function = $DBALS[$db_config['dbtype']]['comments'];

	// I require MySQL version 4.0.4 minimum.
	$server_version = mysql_get_server_info();
	$client_version = mysql_get_client_info();

	if ( (isset($server_version) && isset($client_version)) )
	{
		$tpl->message_append('MySQL client <strong>and</strong> server version 4.1.0 or higher is required for Roster.<br /><br />
			<strong>You are running:</strong>
			<ul>
				<li><strong>Your server version: ' . $server_version . '</strong></li>
				<li><strong>Your client version: ' . $client_version . '</strong></li>
			</ul>
			MySQL versions less than 4.1.0 may experience data corruption and are not supported.<br />
			We will not provide support for these types of installations.');

		if( version_compare($server_version,'4.1','<') )
		{
			$tpl->message_die('MySQL client <strong>and</strong> server version 4.1.0 or higher is required for Roster.<br /><br />
				<strong>You are running:</strong>
				<ul>
					<li><strong>Your server version: ' . $server_version . '</strong></li>
					<li><strong>Your client version: ' . $client_version . '</strong></li>
				</ul>
				We are sorry, your MySQL server version is not high enough to install Roster, please upgrade MySQL.');
		}
	}
	else
	{
		$tpl->message_die('Failed to get version information for database <strong>' . $db_config['database'] . '</strong> as <strong>' . $db_config['username'] . '@' . $db_config['host'] . '</strong><br /><br /><a href="index.php">Restart Installation</a>');
	}

	// Parse structure file and create database tables
	$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
	$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $db_config['table_prefix'] . '\\1\\2', $sql);

	$sql = $remove_remarks_function($sql);
	$sql = parse_sql($sql, $DBALS[$db_config['dbtype']]['delim']);

	$sql_count = count($sql);
	$i = 0;

	while( $i < $sql_count )
	{
		if( isset($sql[$i]) && $sql[$i] != '' )
		{
			if( !($db->query($sql[$i])) )
			{
				$tpl->message_die('Error in SQL query<br />
					' . $sql[$i] . '<br />
					Error: ' . $db->error() . '<br />
					<a href="index.php">Restart Installation</a>');
			}

		}
		$i++;
	}
	unset($sql);

	// Parse the data file and populate the database tables
	$sql = @fread(@fopen($db_data_file, 'r'), @filesize($db_data_file));
	$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $db_config['table_prefix'] . '\\1\\2', $sql);

	$sql = $remove_remarks_function($sql);
	$sql = parse_sql($sql, $DBALS[$db_config['dbtype']]['delim']);

	$sql_count = count($sql);
	$i = 0;

	while ( $i < $sql_count )
	{
		if( isset($sql[$i]) && $sql[$i] != '' )
		{
			if( !($db->query($sql[$i])) )
			{
				$tpl->message_die('Error in SQL query<br />
					' . $sql[$i] . '<br />
					Error: ' . $db->error() . '<br />
					<a href="index.php">Restart Installation</a>');
			}
		}
		$i++;
	}

	unset($sql);

	/**
	 * Update some config settings
	 */
    $db->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value` = '$default_locale' WHERE `config_name` = 'locale';");
    $db->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `config_name` = 'version';");
    $db->query("UPDATE `" . CONFIG_TABLE . "` SET `config_value` = '$server_name' WHERE `config_name` = 'website_address';");

	/**
	 * Write the config file
	 */
	$config_file  = "<?php\n";
	$config_file .= "/**\n * AUTO-GENERATED CONF FILE\n * DO NOT EDIT !!!\n */\n\n";
	$config_file .= "\$db_config['host']         = '" . $db_config['host']         . "';\n";
	$config_file .= "\$db_config['username']     = '" . $db_config['username']     . "';\n";
	$config_file .= "\$db_config['password']     = '" . $db_config['password']     . "';\n";
	$config_file .= "\$db_config['database']     = '" . $db_config['database']     . "';\n";
	$config_file .= "\$db_config['table_prefix'] = '" . $db_config['table_prefix'] . "';\n";
	$config_file .= "\$db_config['dbtype']       = '" . $db_config['dbtype']       . "';\n";

	// Set our permissions to execute-only
	@umask(0111);

	if ( !$fp = @fopen('conf.php', 'w') )
	{
		$error_message  = 'The <strong>conf.php</strong> file couldn\'t be opened for writing.  Paste the following in to conf.php and save the file to continue:<br /><pre>' . htmlspecialchars($config_file) . '</pre>';
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
	sql_output($tpl,$db);
	$tpl->page_header();
	$tpl->page_tail();
}

function process_step4()
{
	global $DEFAULTS;

	$tpl = new Template_Wrap();
	$tpl->set_filenames(array('body' => 'install_step4.html'));

	/**
	 * Get our posted data
	 */
	$user_password1 = post_or_db('user_password1');
	$user_password2 = post_or_db('user_password2');

	/**
	 * Update admin account
	 */
	include(ROSTER_BASE . 'conf.php');
	define('CONFIG_TABLE', $db_config['table_prefix'] . 'config');
	define('ACCOUNT_TABLE',  $db_config['table_prefix'] . 'account');
	define('ROSTER_DB_DIR',  ROSTER_LIB . 'dbal' . DIR_SEP);

	switch ( $db_config['dbtype'] )
	{
		case 'mysql':
			include_once(ROSTER_DB_DIR . 'mysql.php');
			break;

		default:
			include_once(ROSTER_DB_DIR . 'mysql.php');
			break;
	}

	$db = new roster_db($db_config['host'], $db_config['database'], $db_config['username'], $db_config['password'], false);

	if ( !is_resource($db->link_id) )
	{
		$tpl->message_die('Failed to connect to database <strong>' . $db_config['database'] . '</strong> as <strong>' . $db_config['username'] . '@' . $db_config['host'] . '</strong><br /><br /><a href="index.php">Restart Installation</a>');
	}

	/**
	 * Insert account data. This isn't in the data sql file because we don't
	 * want to include it in a settings reset
	 */
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
	$db->query("INSERT INTO `".ACCOUNT_TABLE."` (`account_id`, `name`) VALUES
		(1, 'Guild'),
		(2, 'Officer'),
		(3, 'Admin');");

	$db->query("UPDATE `".ACCOUNT_TABLE."` SET `hash` = '".$pass_word."';");

	/**
	 * Rewrite the config file to its final form
	 */
	$config_file = file(ROSTER_BASE . 'conf.php');
	$config_file[] = "\ndefine('ROSTER_INSTALLED', true);";
	$config_file = implode('', $config_file);

	// Set our permissions to execute-only
	@umask(0111);

	if ( !$fp = @fopen('conf.php', 'w') )
	{
		$error_message  = 'The <strong>conf.php</strong> file couldn\'t be opened for writing.<br />Paste the following in to conf.php and save the file to continue:<br /><pre>' . htmlspecialchars($config_file) . '</pre>';
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
	if( ($user_password1 != $user_password2) || $user_password1 == '' || $user_password2 == '' )
	{
		$tpl->message_append('<span style="font-weight:bold;font-size:14px;" class="negative">NOTICE</span><br /><br />Your passwords did not match, so it has been reset to <strong>admin</strong><br />You can change it by logging in and going to your account settings.');
	}

	$tpl->message_append('Your administrator account has been created, log in above to be taken to the Roster configuration page.');

	sql_output($tpl,$db);
	$tpl->page_header();
	$tpl->page_tail();
}


// ---------------------------------------------------------
// Functions!
// ---------------------------------------------------------


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

function sql_output(&$tpl, $db=NULL)
{
	$tpl->assign_vars(array(
		'U_QUERYCOUNT'   => is_object($db) ? $db->query_count : 0,
		'S_SQL'          => is_object($db) ? true : false
		)
	);

	if( is_object($db) )
	{
		foreach( $db->queries as $file => $queries )
		{
			foreach( $queries as $query )
			{
				$tpl->assign_block_vars('sql_row', array(
					'TIME' => $query['time'],
					'TEXT' => $query['query']
					)
				);
			}
		}
	}
}
