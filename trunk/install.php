<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Installer
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
 * @subpackage Install
 */

if( !defined('IN_ROSTER') )
{
	exit('Direct access to install.php is not allowed. Please go to index.php to install.');
}

// Get the config file
if( file_exists(ROSTER_BASE . 'conf.php') )
{
	include_once (ROSTER_BASE . 'conf.php');
}

/**
 * URL path to Roster's directory
 * This is needed in template.php
 * And since we don't want to include cmslink.lib.php, this will have to do
 * Blank should be fine
 */
define('ROSTER_PATH', '');

// ---------------------------------------------------------
// Template Wrap class
// ---------------------------------------------------------
if( !include_once (ROSTER_LIB . 'template.php') )
{
	die('Could not include lib/template.php - check to make sure that the file exists!');
}

/**
 * Template Parser
 * TemplateWrap: Special for the installer
 *
 * @package    WoWRoster
 * @subpackage Template
 */
class Template_Wrap extends RosterTemplate
{
	var $error_message   = array();  // Array of errors      @var $error_message
	var $install_message = array();  // Array of messages    @var $install_message
	var $header_inc      = false;    // Printed header?      @var $header_inc
	var $tail_inc        = false;    // Printed footer?      @var $tail_inc


	function Template_Wrap( )
	{
		if( !is_dir(ROSTER_TPLDIR . 'install') )
		{
			trigger_error("'install' theme does not exist", E_USER_ERROR);
		}

		$this->tpl = 'install';
		include_once (ROSTER_LIB . 'cache.php');
		$cache = new RosterCache();
		$cache->cleanCache();
		$this->assign_vars(array(
			'MSG_TITLE' => '',
			'MSG_TEXT'  => '',
			'S_SQL'     => false,
			'U_QUERYCOUNT' => 0
		));

		//$this->_tpldata['.'][0]['REQUEST_URI'] = str_replace('&', '&amp;', substr(request_uri(),strlen(ROSTER_PATH)));
		$this->root = ROSTER_TPLDIR . $this->tpl;
	}

	function message_die( $text = '' , $title = '' )
	{
		$this->set_handle('body', 'install_error.html');

		$this->install_message = array();

		$this->assign_vars(array(
			'MSG_TITLE' => ($title != '') ? $title : '&nbsp;',
			'MSG_TEXT'  => ($text != '') ? $text : '&nbsp;'
		));

		if( !$this->header_inc )
		{
			$this->page_header();
		}

		$this->page_tail();
	}

	function message_append( $message )
	{
		$this->install_message[sizeof($this->install_message) + 1] = $message;
	}

	function message_out( $die = false )
	{
		sort($this->install_message);
		reset($this->install_message);

		$install_message = implode('<br /><br />', $this->install_message);

		if( $die )
		{
			$this->install_message = '';
			$this->message_die($install_message, 'Installation ' . ((sizeof($this->install_message) == 1) ? 'Note' : 'Notes'));
		}
		else
		{
			$this->assign_vars(array(
				'MSG_TITLE' => 'Installation ' . ((sizeof($this->install_message) == 1) ? 'Note' : 'Notes'),
				'MSG_TEXT'  => $install_message
			));
		}
	}

	function error_append( $error )
	{
		$this->error_message[(sizeof($this->error_message) + 1)] = $error;
	}

	function error_out( $die = false )
	{
		sort($this->error_message);
		reset($this->error_message);

		$error_message = implode('<br /><br />', $this->error_message);

		if( $die )
		{
			$this->message_die($error_message, 'Installation ' . ((sizeof($this->error_message) == 1) ? 'Error' : 'Errors'));
		}
		else
		{
			$this->assign_vars(array(
				'MSG_TITLE' => 'Installation ' . ((sizeof($this->error_message) == 1) ? 'Error' : 'Errors'),
				'MSG_TEXT'  => $error_message
			));
		}
	}

	function page_header( )
	{
		global $STEP;

		$this->header_inc = true;

		$this->assign_vars(array(
			'INSTALL_STEP'  => $STEP,
			'TEMPLATE_PATH' => 'templates/install',
			'FORMACTION'    => 'index.php'
		));

		$this->set_handle('header', 'install_header.html');
	}

	function page_tail( )
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
			$this->error_message[0] = '<span style="font-weight:bold;font-size:14px;" class="negative">NOTICE</span>';
			$this->error_out(false);
		}

		$this->assign_vars(array(
			'S_SHOW_DEBUG' => true,
			'U_RENDERTIME' => substr(format_microtime() - ROSTER_STARTTIME, 0, 5),
			'ROSTER_VERSION' => $DEFAULTS['version']
		));

		$this->assign_var('NOTICE', '');

/*/ BETA ONLY, COMMENT THIS IN RC OR LATER!
		if( file_exists(ROSTER_BASE . 'valid.inc') )
		{
			$v_content = '';
			ob_start();
				require (ROSTER_BASE . 'valid.inc');
			$v_content = ob_get_clean();

			$this->assign_var('NOTICE', $v_content);
		}
// END BETA ONLY */

		if( is_object($db) )
		{
			$db->close_db();
		}

		$this->set_handle('footer', 'install_tail.html');

		$this->display('header');
		$this->display('body');
		$this->display('footer');

		exit();
	}

	function sql_output( $db = NULL )
	{
		$this->assign_vars(array(
			'U_QUERYCOUNT' => is_object($db) ? $db->query_count : 0,
			'S_SQL'        => is_object($db) ? true : false
		));

		if( is_object($db) )
		{
			foreach( $db->queries as $file => $queries )
			{
				foreach( $queries as $query )
				{
					$this->assign_block_vars('sql_row', array(
						'TIME' => $query['time'],
						'TEXT' => nl2br(htmlentities($query['query']))
					));
				}
			}

		}
	}
}

$STEP = (isset($_POST['install_step']) ? $_POST['install_step'] : '0');

// If Roster is already installed, don't let them install it again
if( defined('ROSTER_INSTALLED') )
{
	$tpl = new Template_Wrap();
	$tpl->set_handle('body', 'install_error.html');
	$tpl->message_die('WoWRoster is already installed - please remove the file <strong>install.php</strong>', 'Installation Error');
}

// View phpinfo() if requested
if( (isset($_GET['mode'])) && ($_GET['mode'] == 'phpinfo') )
{
	phpinfo();
	exit();
}

// System defaults / available database abstraction layers
$DEFAULTS = array(
	'version'      => ROSTER_VERSION,
	'default_lang' => 'enUS',
	'table_prefix' => 'roster_',
	'dbal'         => 'mysql'
);

$REQUIRE = array(
	'php_version'   => '5.2.0',
	'mysql_version' => '4.1.0'
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
		'label' => 'English',
		'type'  => 'enUS'
	),
	'German' => array(
		'label' => 'German',
		'type'  => 'deDE'
	),
	'French' => array(
		'label' => 'French',
		'type'  => 'frFR'
	),
	'Spanish' => array(
		'label' => 'Spanish',
		'type'  => 'esES'
	)
);

/**
 * Figure out what we're doing...
 */
switch( $STEP )
{
	case 0:
		process_step0();
		break;
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
function process_step0( )
{
	$tpl = new Template_Wrap();
	$tpl->set_handle('body', 'install_step0.html');

	if( file_exists(ROSTER_BASE . 'license.txt') )
	{
		if( function_exists('readgzfile') )
		{
			ob_start();
				readgzfile(ROSTER_BASE . 'license.txt');
			$content = ob_get_clean();
			$content = htmlentities($content);
			$content = nl2br($content);
			$tpl->assign_var('LICENSE', $content);
		}
		else
		{
			$tpl->assign_var('LICENSE', false);
		}
	}
	else
	{
		$tpl->set_handle('body', 'install_error.html');
		$tpl->message_die('You removed the license.txt file<br /><br />This file MUST be present to install WoWRoster!', 'Installation Error');
	}

	$tpl->page_header();
	$tpl->page_tail();
}

function process_step1( )
{
	global $DEFAULTS, $REQUIRE;

	$tpl = new Template_Wrap();
	$tpl->set_handle('body', 'install_step1.html');

	/**
	 * Check to make sure conf.php exists and is readable / writeable
	 */
	$config_file = ROSTER_BASE . 'conf.php';
	$conf_write = 'green';
	$conf_tip = 'Write access confirmed';
	if( !file_exists($config_file) )
	{
		if( !@touch($config_file) )
		{
			$conf_tip = 'The <strong>conf.php</strong> file does not exist and could not be created in WoWRoster\'s root folder.<br />'
				. 'You must create an empty conf.php file on your server before proceeding. And give it write access';
			$conf_write = 'red';
			$tpl->error_append($conf_tip);
		}
		else
		{
			$conf_tip = 'The <strong>conf.php</strong> file has been created in WoWRoster\'s root folder<br />'
				. 'Deleting this file will interfere with the operation of your WoWRoster installation.';
			$conf_write = 'green';
			$tpl->message_append($conf_tip);
		}
	}
	else
	{
		if( (!is_writeable($config_file)) || (!is_readable($config_file)) )
		{
			if( !@chmod($config_file, 0666) )
			{
				$conf_tip = 'The file <strong>conf.php</strong> is not set to be readable/writable and could not be changed automatically.<br />'
					. 'Please change the permissions to 0666 manually by executing <strong>chmod 0666 conf.php</strong> on your server.';
				$conf_write = 'red';
				$tpl->error_append($conf_tip);
			}
			else
			{
				$conf_tip = '<strong>conf.php</strong> has been set to be readable/writable in order to let this installer '
					. 'write your configuration file automatically.';
				$conf_write = 'green';
				$tpl->message_append($conf_tip);
			}
		}
		// config file exists and is writeable, we're good to go
	}

	$tpl->assign_block_vars('dir', array(
		'CAPTION'  => 'Config File',
		'NAME'     => 'conf.php',
		'TIP'      => addslashes($conf_tip),
		'WRITE'    => $conf_write
	));

	clearstatcache();

	/**
	 * Check to make sure cache exists and is writeable
	 */
	$cache_write = 'green';
	$cache_tip = 'Write access confirmed';
	if( !file_exists(ROSTER_CACHEDIR) )
	{
		if( !@mkdir(ROSTER_CACHEDIR, 0777) )
		{
			$cache_tip = 'The cache directory could not be created, create a directory named &quot;cache&quot; manually in the root directory.';
			$cache_write = 'red';
			$tpl->error_append($cache_tip);
		}
		else
		{
			$cache_tip = 'A cache directory was created';
			$cache_write = 'green';
			$tpl->message_append($cache_tip);
		}
	}
	else
	{
		if( !is_writeable(ROSTER_CACHEDIR) )
		{
			if( !@chmod(ROSTER_CACHEDIR, 0777) )
			{
				$cache_tip = 'The cache directory is not set to be writable and could not be changed automatically.<br />'
					. 'Please change the permissions to 0777 manually by executing <strong>chmod 0777 cache</strong> on your server.';
				$cache_write = 'red';
				$tpl->error_append($cache_tip);
			}
			else
			{
				$cache_tip = 'The cache directory has been set to be writable.';
				$cache_write = 'green';
				$tpl->message_append($cache_tip . '<br />Write access confirmed');
			}
		}
		// Cache directory exists and is writeable, we're good to go
	}

	$tpl->assign_block_vars('dir', array(
		'CAPTION' => 'Cache Directory',
		'NAME'    => 'cache',
		'TIP'     => addslashes($cache_tip),
		'WRITE'   => $cache_write
	));

	clearstatcache();

	/**
	 * WoWRoster versions
	 */
	$our_roster_version = $DEFAULTS['version'];
	$their_roster_version = 'Unknown';
	$their_roster_updated = 'Unknown';

	$location = str_replace('http://www.wowroster.net', '', ROSTER_UPDATECHECK);

	$sh = @fsockopen('wowroster.net', 80, $errno, $error, 5);
	if( !$sh )
	{
		$their_roster_version = 'Connection to wowroster.net failed.';
	}
	else
	{
		@fputs($sh, "GET $location HTTP/1.1\r\nHost: wowroster.net\r\nConnection: close\r\n\r\n");
		while( !@feof($sh) )
		{
			$content = @fgets($sh, 512);
			if( preg_match('#<version>(.+)</version>#i', $content, $version) )
			{
				$their_roster_version = $version[1];
				break;
			}
			if( preg_match('#<updated>(.+)</updated>#i', $content, $updated) )
			{
				$their_roster_updated = date('D M jS, g:ia', $updated[1]);
				break;
			}
		}
	}
	@fclose($sh);

	// WoWRoster Versions
	$our_roster_version = ((version_compare($our_roster_version, $their_roster_version, '>=')) ? '<span class="positive">' : '<span class="negative">') . $our_roster_version . '</span>';

	// PHP Versions
	$our_php_version = ((phpversion() >= $REQUIRE['php_version']) ? '<span class="positive">' : '<span class="negative">') . phpversion() . '</span>';
	$their_php_version = $REQUIRE['php_version'] . ' +';

	// Modules
	$our_mysql = (extension_loaded('mysql')) ? '<span class="positive">Yes</span>' : '<span class="negative">No</span>';
	// Required?
	$their_mysql = $REQUIRE['mysql_version'] . ' +';

	$our_gd = (function_exists('imageTTFBBox') && function_exists('imageTTFText') && function_exists('imagecreatetruecolor')) ? '<span class="positive">Yes</span>' : '<span class="negative">No</span>';
	// Required?
	$their_gd = 'Optional';

	//curl check
	$our_curl = in_array('curl', get_loaded_extensions()) ? '<span class="positive">Yes</span>' : '<span class="negative">No</span>';
	$their_curl = 'Curl is required for api usage for updating.';
	if( (phpversion() < $REQUIRE['php_version']) || (!extension_loaded('mysql')) )
	{
		$tpl->error_append('<span style="font-weight:bold;font-size:14px;">Sorry, your server does not meet the minimum requirements for WoWRoster</span>');
	}
	else
	{
		$tpl->message_append('WoWRoster has scanned your server and determined that it meets the minimum requirements.');
	}

	/**
	 * Output the page
	 */
	$tpl->assign_vars(array(
		'OUR_ROSTER_VERSION'   => $our_roster_version,
		'THEIR_ROSTER_VERSION' => $their_roster_version,
		'THEIR_ROSTER_UPDATED' => $their_roster_updated,
		'OUR_PHP_VERSION'      => $our_php_version,
		'THEIR_PHP_VERSION'    => $their_php_version,
		'OUR_MYSQL'            => $our_mysql,
		'THEIR_MYSQL'          => $their_mysql,
		'OUR_GD'               => $our_gd,
		'THEIR_GD'             => $their_gd,
		'OUR_CURL'               => $our_curl,
		'THEIR_CURL'             => $their_curl
	));

	$tpl->page_header();
	$tpl->page_tail();
}

function process_step2( )
{
	global $DEFAULTS, $DBALS, $LOCALES;

	$tpl = new Template_Wrap();
	$tpl->set_handle('body', 'install_step2.html');

	/**
	 * Build the default language drop-down
	 */
	foreach( $LOCALES as $locale_type => $locale_desc )
	{
		if( file_exists(ROSTER_BASE . 'localization' . DIR_SEP . $locale_desc['type'] . '.php') )
		{
			$tpl->assign_block_vars('locale_row', array(
				'VALUE'  => $locale_desc['type'],
				'OPTION' => $locale_type
			));
		}
	}

	/**
	 * Build the database drop-down
	 */
	foreach( $DBALS as $db_type => $db_options )
	{
		$tpl->assign_block_vars('dbal_row', array(
			'VALUE'  => $db_type,
			'OPTION' => $db_options['label']
		));
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
	));

	$tpl->page_header();
	$tpl->page_tail();
}

function process_step3( )
{
	global $DEFAULTS, $DBALS, $LOCALES, $REQUIRE;

	$tpl = new Template_Wrap();
	$tpl->set_handle('body', 'install_step3.html');

	/**
	 * Get our posted data
	 */
	$db_config['dbtype']       = post_or_db('dbtype');
	$db_config['host']         = post_or_db('dbhost');
	$db_config['database']     = post_or_db('dbname');
	$db_config['username']     = post_or_db('dbuser');
	$db_config['password']     = post_or_db('dbpass');
	$db_config['table_prefix'] = post_or_db('table_prefix', $DEFAULTS);
	$default_locale            = post_or_db('default_lang', $DEFAULTS);
	$server_name               = post_or_db('server_name');

	$create['username'] = post_or_db('dbuser_c');
	$create['password'] = post_or_db('dbpass_c');

	define('ROSTER_DB_DIR', ROSTER_LIB . 'dbal' . DIR_SEP);

	$dbal_file = ROSTER_DB_DIR . $db_config['dbtype'] . '.php';
	if( !file_exists($dbal_file) )
	{
		$tpl->message_die('Unable to find the database abstraction layer for <strong>' . $db_config['dbtype'] . '</strong>, check to make sure ' . $dbal_file . ' exists.');
	}

	/**
	 * Database population
	 */
	include_once ($dbal_file);

	// Hey, looks like we are making the database, YAY!
	if( $create['username'] != '' && $create['password'] != '' )
	{
		include_once ($dbal_file);
		$db = new roster_db($db_config['host'], '', $create['username'], $create['password']);
		$db->query("CREATE DATABASE IF NOT EXISTS `" . $db_config['database'] . "`;");
		unset($db, $create);
	}

	// Try to connect
	$db = new roster_db($db_config['host'], $db_config['database'], $db_config['username'], $db_config['password'], $db_config['table_prefix']);
	$db->log_level();
	$db->error_die();

	// Check to make sure a connection was made
	if( !is_resource($db->link_id) )
	{
		// Attempt to
		$tpl->message_die('Failed to connect to database <strong>' . $db_config['database'] . '</strong> as <strong>' . $db_config['username'] . '@' . $db_config['host'] . '</strong><br />' . $db->connect_error() . '<br /><br />' . '<form method="post" action="index.php" name="post"><input type="hidden" name="install_step" value="2" /><div align="center"><input type="submit" name="submit" value="Try Again" /></div></form>');
	}

	$db_structure_file = ROSTER_DB_DIR . 'structure' . DIR_SEP . $db_config['dbtype'] . '_structure.sql';
	$db_data_file = ROSTER_DB_DIR . 'structure' . DIR_SEP . $db_config['dbtype'] . '_data.sql';

	$remove_remarks_function = $DBALS[$db_config['dbtype']]['comments'];

	// I require MySQL version 4.1.0 minimum.
	$server_version = $db->server_info();
	$client_version = $db->client_info();

	if( (isset($server_version) && isset($client_version)) )
	{
		$tpl->message_append('MySQL server version ' . $REQUIRE['mysql_version'] . ' or higher is required for WoWRoster.<br /><br />
			<strong>You are running:</strong>
			<ul>
				<li><strong>Your server version: ' . $server_version . '</strong></li>
				<li><strong>Your client version: ' . $client_version . '</strong></li>
			</ul>
			Your server meets the MySQL requirements for WoWRoster.');

		if( version_compare($server_version, $REQUIRE['mysql_version'], '<') )
		{
			$tpl->message_die('MySQL server version ' . $REQUIRE['mysql_version'] . ' or higher is required for WoWRoster.<br /><br />
				<strong>You are running:</strong>
				<ul>
					<li><strong>Your server version: ' . $server_version . '</strong></li>
					<li><strong>Your client version: ' . $client_version . '</strong></li>
				</ul>
				We are sorry, your MySQL server version is not high enough to install WoWRoster, please upgrade MySQL.');
		}
	}
	else
	{
		$tpl->message_die('Failed to get version information for database <strong>' . $db_config['database'] . '</strong> as <strong>' . $db_config['username'] . '@' . $db_config['host'] . '</strong><br />'
			. $db->connect_error() . '<br /><br />'
			. '<form method="post" action="index.php" name="post"><input type="hidden" name="install_step" value="2" /><div align="center"><input type="submit" name="submit" value="Try Again" /></div></form>');
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
				$tpl->message_die('Error in SQL query<br />'
					. $sql[$i] . '<br />'
					. 'Error: ' . $db->error() . '<br />'
					. '<a href="index.php">Restart Installation</a>');
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

	while( $i < $sql_count )
	{
		if( isset($sql[$i]) && $sql[$i] != '' )
		{
			if( !($db->query($sql[$i])) )
			{
				$tpl->message_die('Error in SQL query<br />'
					. $sql[$i] . '<br />'
					. 'Error: ' . $db->error() . '<br />'
					. '<a href="index.php">Restart Installation</a>');
			}
		}
		$i++;
	}

	unset($sql);

	/**
	 * Update some config settings
	 */
	$db->query("UPDATE `" . $db->table('config') . "` SET `config_value` = '$default_locale' WHERE `config_name` = 'locale';");
	$db->query("UPDATE `" . $db->table('config') . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `config_name` = 'version';");
	$db->query("UPDATE `" . $db->table('config') . "` SET `config_value` = '$server_name' WHERE `config_name` = 'website_address';");

	/**
	 * Write the config file
	 */
	$config_file = "<?php\n";
	$config_file .= "/**\n * AUTO-GENERATED CONF FILE\n * DO NOT EDIT !!!\n */\n\n";
	$config_file .= "\$db_config['host']         = " . var_export($db_config['host'], true) . ";\n";
	$config_file .= "\$db_config['username']     = " . var_export($db_config['username'], true) . ";\n";
	$config_file .= "\$db_config['password']     = " . var_export($db_config['password'], true) . ";\n";
	$config_file .= "\$db_config['database']     = " . var_export($db_config['database'], true) . ";\n";
	$config_file .= "\$db_config['table_prefix'] = " . var_export($db_config['table_prefix'], true) . ";\n";
	$config_file .= "\$db_config['dbtype']       = " . var_export($db_config['dbtype'], true) . ";\n";

	// Set our permissions to execute-only
	@umask(0111);

	if( !$fp = @fopen('conf.php', 'w') )
	{
		$error_message = 'The <strong>conf.php</strong> file couldn\'t be opened for writing.  Paste the following in to conf.php and save the file to continue:<br /><pre>' . htmlspecialchars($config_file) . '</pre>';
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
	$tpl->sql_output($db);
	$tpl->page_header();
	$tpl->page_tail();
}

function process_step4( )
{
	global $DEFAULTS;

	$tpl = new Template_Wrap();
	$tpl->set_handle('body', 'install_step4.html');

	/**
	 * Get our posted data
	 */
	$user_password1 = post_or_db('user_password1');
	$user_password2 = post_or_db('user_password2');

	/**
	 * Update admin account
	 */
	include (ROSTER_BASE . 'conf.php');
	define('ROSTER_DB_DIR', ROSTER_LIB . 'dbal' . DIR_SEP);

	switch( $db_config['dbtype'] )
	{
		case 'mysql':
			include_once (ROSTER_DB_DIR . 'mysql.php');
			break;

		default:
			include_once (ROSTER_DB_DIR . 'mysql.php');
			break;
	}

	$db = new roster_db($db_config['host'], $db_config['database'], $db_config['username'], $db_config['password'], $db_config['table_prefix']);
	$db->log_level();
	$db->error_die();

	if( !is_resource($db->link_id) )
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
	$db->query("INSERT INTO `" . $db->table('user_members') . "` (`usr`) VALUES	('Admin');");
	$db->query("UPDATE `" . $db->table('user_members') . "` SET `pass` = '" . $pass_word . "',`access` = '11:0',`active`='1' WHERE `usr` = 'Admin';");

	$tpl->message_append('The WoWRoster Admin account has created<br />Please do not forget your password');

	/**
	 * Rewrite the config file to its final form
	 */
	$config_file = file(ROSTER_BASE . 'conf.php');
	$config_file[] = "\ndefine('ROSTER_INSTALLED', true);";
	$config_file = implode('', $config_file);

	// Set our permissions to execute-only
	@umask(0111);

	if( !$fp = @fopen('conf.php', 'w') )
	{
		$error_message = 'The <strong>conf.php</strong> file couldn\'t be opened for writing.<br />Paste the following in to conf.php and save the file to continue:<br /><pre>' . htmlspecialchars($config_file) . '</pre>';
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

	$tpl->message_append('Your administrator account has been created, log in to be taken to the WoWRoster configuration page.');

	$tpl->sql_output($db);
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
function post_or_db( $post_field , $db_row = array() , $db_field = '' )
{
	if( @sizeof($db_row) > 0 )
	{
		if( $db_field == '' )
		{
			$db_field = $post_field;
		}

		$db_value = $db_row[$db_field];
	}
	else
	{
		$db_value = '';
	}
	return (isset($_POST[$post_field]) || !empty($_POST[$post_field])) ? $_POST[$post_field] : $db_value;
}

/**
 * Removes comments from a SQL data file
 *
 * @param    string  $sql    SQL file contents
 * @return   string
 */
function remove_remarks( $sql )
{
	if( $sql == '' )
	{
		die('Could not obtain SQL structure/data');
	}

	$retval = '';
	$lines = explode("\n", $sql);
	unset($sql);

	foreach( $lines as $line )
	{
		// Only parse this line if there's something on it, and we're not on the last line
		if( strlen($line) > 0 )
		{
			// If '#' is the first character, strip the line
			$retval .= (substr($line, 0, 1) != '#') ? $line . "\n" : "\n";
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
function parse_sql( $sql , $delim )
{
	if( $sql == '' )
	{
		die('Could not obtain SQL structure/data');
	}

	$retval = array();
	$statements = explode($delim, $sql);
	unset($sql);

	$linecount = count($statements);
	for( $i = 0; $i < $linecount; $i++ )
	{
		if( ($i != $linecount - 1) || (strlen($statements[$i]) > 0) )
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
