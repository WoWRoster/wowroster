<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Upgrader
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
*/

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
 * Copyright 2002-2005
 * Licensed under the GNU GPL.  See COPYING for full terms.
 * ------------------
 * upgrade.php
 * Began: Tue July 1 2003
 *
 * $Id$
 *
 ******************************/
error_reporting(E_ALL);

// Be paranoid with passed vars
// Destroy GET/POST/Cookie variables from the global scope
if( intval(ini_get('register_globals')) != 0 )
{
	foreach( $_REQUEST AS $key => $val )
	{
		if( isset($$key) )
			unset($$key);
	}
}

set_magic_quotes_runtime(0);
if( !get_magic_quotes_gpc() )
{
	$_GET = slash_global_data($_GET);
	$_POST = slash_global_data($_POST);
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

define('DIR_SEP',DIRECTORY_SEPARATOR);
define('ROSTER_BASE', dirname(__FILE__) . DIR_SEP);
define('ROSTER_LIB', ROSTER_BASE . 'lib' . DIR_SEP);


include_once(ROSTER_BASE . 'conf.php');
include_once(ROSTER_LIB . 'constants.php');
include_once(ROSTER_LIB . 'dbal' . DIR_SEP . 'mysql.php');


// ---------------------------------------------------------
// Template Wrap class
// ---------------------------------------------------------
if( !include_once(ROSTER_BASE . 'install' . DIR_SEP . 'template.php') )
{
	die('Could not include ' . ROSTER_BASE . 'install' . DIR_SEP . 'template.php - check to make sure that the file exists!');
}


/**
 * Establish our connection and select our database
 */
$db = new roster_db($db_host, $db_name, $db_user, $db_passwd, $db_prefix);
if( !$db )
{
	$tpl = new Template_Wrap('upgrade_message.html','upgrade_header.html','upgrade_tail.html');
	$tpl->message_die('Could not connect to database "' . $db_name . '"<br />MySQL said:<br />' . $db->error(), 'Database Error');
	exit();
}


/**
 * Get the current version value
 */

// Detect $version variable from Roster 1.6.0
if( !isset($version) )
{
	if( !defined('ROSTER_CONFIGTABLE') )
	{
		define('ROSTER_CONFIGTABLE', $db_prefix . 'config');
	}

	if( !defined('ACCOUNT_TABLE') )
	{
		define('ACCOUNT_TABLE', $db_prefix . 'account');
	}

	$sql = "SELECT `config_value` FROM `" . ROSTER_CONFIGTABLE . "` WHERE `config_name` = 'version';";
	$results = $db->query($sql);

	if( !$results )
	{
		$tpl = new Template_Wrap('upgrade_message.html','upgrade_header.html','upgrade_tail.html');
		$tpl->message_die('Cannot get roster configuration from database "' . $db_name . '"<br />MySQL said:<br />' . $db->error(), 'Database Error');
		exit();
	}
	else
	{
		$row = $db->fetch($results);
		$version = $row['config_value'];
	}
}
define('ROSTER_OLDVERSION',$version);



if( ROSTER_OLDVERSION >= ROSTER_VERSION )
{
	$tpl = new Template_Wrap('upgrade_message.html','upgrade_header.html','upgrade_tail.html');
	$tpl->message_die('You have already upgraded Roster<br />Or you have a newer version than this upgrader', 'Upgrade Error');
	exit();
}





class Upgrade
{
	var $versions = array('1.6.0','1.7.0','1.7.1','1.7.2','1.7.3');
	var $messages;
	var $sql_errors = array();


	function upgrade()
	{
		if( isset($_POST['upgrade']) )
		{
			// Find out what version we're upgrading from
			$version_from = $_POST['version'];
			foreach( $this->versions as $index => $version )
			{
				if( str_replace('.', '', $version) == $version_from )
				{
					$method = 'upgrade_' . $version_from;
					$this->$method($index);
				}
			}
		}
		else
		{
			$this->display_form();
		}
	}

	function finalize( $index )
	{
		global $tpl, $db;

		if( isset($this->versions[$index + 1]) )
		{
			$method = 'upgrade_' . str_replace('.', '', $this->versions[$index + 1]);
			$this->$method($index + 1);
		}
		else
		{
			$tpl = new Template_Wrap('upgrade_message.html','upgrade_header.html','upgrade_tail.html');

			if( !empty($this->messages) )
			{
				$tpl->message_append($this->messages);
			}

			$this->sql_output($tpl, $db);
			$tpl->message_append('Your Roster installation has been successfully upgraded.<br /><br /><b class="negative">For extra security, remove this file!</b>', 'Success');
			$tpl->page_header();
			$tpl->page_tail();
		}
	}

	//--------------------------------------------------------------
	// Upgrade methods
	//--------------------------------------------------------------

	function upgrade_173( $index )
	{
		global $db;

		// Change player update time to datetime
		$query_string = "ALTER TABLE `" . ROSTER_PLAYERSTABLE . "` CHANGE `dateupdatedutc` `dateupdatedutc` VARCHAR( 19 ) NULL DEFAULT NULL;";
		$result = $db->query($query_string);

		$query_string = "UPDATE `" . ROSTER_PLAYERSTABLE . "` SET dateupdatedutc = CONCAT('20', MID(`dateupdatedutc`, 7, 2), '-', MID(`dateupdatedutc`, 1, 2), '-', MID(`dateupdatedutc`, 4, 2), ' ', MID(`dateupdatedutc`, 10, 8));";
		$result = $db->query($query_string);

		$query_string = "ALTER TABLE `" . ROSTER_PLAYERSTABLE . "` CHANGE `dateupdatedutc` `dateupdatedutc` DATETIME NULL DEFAULT NULL;";
		$result = $db->query($query_string);

		// Change mail update time to datetime
		$query_string = "ALTER TABLE `" . ROSTER_PLAYERSTABLE . "` CHANGE `maildateutc` `maildateutc` VARCHAR( 19 ) NULL DEFAULT NULL;";
		$result = $db->query($query_string);

		$query_string = "UPDATE `" . ROSTER_PLAYERSTABLE . "` SET maildateutc = CONCAT('20', MID(`maildateutc`, 7, 2), '-', MID(`maildateutc`, 1, 2), '-', MID(`maildateutc`, 4, 2), ' ', MID(`maildateutc`, 10, 8));";
		$result = $db->query($query_string);

		$query_string = "ALTER TABLE `" . ROSTER_PLAYERSTABLE . "` CHANGE `maildateutc` `maildateutc` DATETIME NULL DEFAULT NULL;";
		$result = $db->query($query_string);

		// Change guild update time to datetime
		$query_string = "ALTER TABLE `" . ROSTER_GUILDTABLE . "` CHANGE `guild_dateupdatedutc` `guild_dateupdatedutc` VARCHAR( 19 ) NULL DEFAULT NULL;";
		$result = $db->query($query_string);

		$query_string = "UPDATE `" . ROSTER_GUILDTABLE . "` SET `guild_dateupdatedutc` = CONCAT('20', MID(`guild_dateupdatedutc`, 7, 2), '-', MID(`guild_dateupdatedutc`, 1, 2), '-', MID(`guild_dateupdatedutc`, 4, 2), ' ', MID(`guild_dateupdatedutc`, 10, 8));";
		$result = $db->query($query_string);

		$query_string = "ALTER TABLE `" . ROSTER_GUILDTABLE . "` CHANGE `guild_dateupdatedutc` `guild_dateupdatedutc` DATETIME NULL DEFAULT NULL;";
		$result = $db->query($query_string);


		$this->standard_upgrader('173');
		$this->finalize($index);
	}

	function upgrade_172( $index )
	{
		$this->standard_upgrader('172');
		$this->finalize($index);
	}

	function upgrade_171( $index )
	{
		$this->standard_upgrader('171');
		$this->finalize($index);
	}

	function upgrade_170( $index )
	{
		$this->standard_upgrader('170');
		$this->finalize($index);
	}


	/**
	 * The upgrader for Roster 1.6.0
	 * This has to do alot, whew
	 *
	 * @param unknown_type $index
	 */
	function upgrade_160( $index )
	{
		global $db, $db_host, $db_name, $db_user, $db_passwd, $db_prefix,
			$roster_lang, $roster_upd_pw, $guild_name, $server_name;

		//
		// Lets get some roster 160 db values before we upgrade the db
		//
		$query_string = "SELECT `member_id`,`stat_int`,`stat_agl`,`stat_sta`,`stat_str`,`stat_spr`,`armor`,`defense`,".
			"`res_frost`,`res_arcane`,`res_fire`,`res_shadow`,`res_nature`".
			" FROM `" . ROSTER_PLAYERSTABLE . "`";
		$result = $db->query($query_string);

		//
		// Now loop through this so we can get each players stats to upgrade
		//
		$playerData = array();
		while( $player_row = $db->fetch($result) )
		{
			$playerData[] = $player_row;
		}


		$db_structure_file = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'upgrade_160.sql';
		$db_data_file      = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'mysql_data.sql';


		// Parse structure file and create database tables
		$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
		$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $db_prefix . '\\1\\2', $sql);

		$sql = remove_remarks($sql);
		$sql = parse_sql($sql, ';');

		$sql_count = count($sql);
		for( $i = 0; $i < $sql_count; $i++ )
		{
			// Added failure checks to the database transactions
			if( !empty($sql[$i]) && !($db->query($sql[$i]) ) )
			{
				$this->sql_errors[] = array(
					'query'=>$sql[$i],
					'error'=>$db->error()
				);
			}
		}
		unset($sql);


		// Parse the data file and populate the database tables
		$sql = @fread(@fopen($db_data_file, 'r'), @filesize($db_data_file));
		$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $db_prefix . '\\1\\2', $sql);

		$sql = remove_remarks($sql);
		$sql = parse_sql($sql, ';');

		$sql_count = count($sql);
		for( $i = 0; $i < $sql_count; $i++ )
		{
			// Added failure checks to the database transactions
			if( !empty($sql[$i]) && !($db->query($sql[$i]) ) )
			{
				$this->sql_errors[] = array(
					'query'=>$sql[$i],
					'error'=>$db->error()
				);
			}
		}
		unset($sql);

	    //
	    // Determine server settings
	    //
	    if( !empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']) )
		{
			$website_address = 'http://' . ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
		}
		elseif( !empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']) )
		{
			$website_address = 'http://' . ((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
		}
		else
		{
			$website_address = '';
		}
		$server_path = str_replace('/upgrade.php', '', $_SERVER['PHP_SELF']);

		//
		// Update some config settings
		//
		$db->query("UPDATE `" . ROSTER_CONFIGTABLE . "` SET `config_value`='$roster_lang' WHERE `config_name`='locale'");
		$db->query("UPDATE `" . ROSTER_CONFIGTABLE . "` SET `config_value`='$website_address' WHERE `config_name`='website_address'");
		$db->query("UPDATE `" . ROSTER_CONFIGTABLE . "` SET `config_value`='" . md5($roster_upd_pw) . "' WHERE `config_name`='roster_upd_pw';");
		$db->query("UPDATE `" . ROSTER_CONFIGTABLE . "` SET `config_value`='$guild_name' WHERE `config_name`='guild_name'");
		$db->query("UPDATE `" . ROSTER_CONFIGTABLE . "` SET `config_value`='$server_name' WHERE `config_name`='server_name';");

		$db->query("INSERT INTO `" . ACCOUNT_TABLE . "` (`account_id`, `name`) VALUES (1, 'Guild'), (2, 'Officer'), (3, 'Admin');");
		$db->query("UPDATE `" . ACCOUNT_TABLE . "` SET `hash` = '" . md5($roster_upd_pw) . "';");

		//
		// Now loop through this again and insert it into the db
		//
		foreach( $playerData as $value )
		{
			$query = array();
			if( !empty($value['stat_int']) )
			{
				$stats = explode(':',$value['stat_int']);
				$query[] = array( 'stat_int' => $stats[0] );
				$query[] = array( 'stat_int_c' => $stats[1] );
				$query[] = array( 'stat_int_b' => $stats[2] );
				$query[] = array( 'stat_int_d' => $stats[3] );
			}
			if( !empty($value['stat_agl']) )
			{
				$stats = explode(':',$value['stat_agl']);
				$query[] = array( 'stat_agl' => $stats[0] );
				$query[] = array( 'stat_agl_c' => $stats[1] );
				$query[] = array( 'stat_agl_b' => $stats[2] );
				$query[] = array( 'stat_agl_d' => $stats[3] );
			}
			if( !empty($value['stat_sta']) )
			{
				$stats = explode(':',$value['stat_sta']);
				$query[] = array( 'stat_sta' => $stats[0] );
				$query[] = array( 'stat_sta_c' => $stats[1] );
				$query[] = array( 'stat_sta_b' => $stats[2] );
				$query[] = array( 'stat_sta_d' => $stats[3] );
			}
			if( !empty($value['stat_str']) )
			{
				$stats = explode(':',$value['stat_str']);
				$query[] = array( 'stat_str' => $stats[0] );
				$query[] = array( 'stat_str_c' => $stats[1] );
				$query[] = array( 'stat_str_b' => $stats[2] );
				$query[] = array( 'stat_str_d' => $stats[3] );
			}
			if( !empty($value['stat_spr']) )
			{
				$stats = explode(':',$value['stat_spr']);
				$query[] = array( 'stat_spr' => $stats[0] );
				$query[] = array( 'stat_spr_c' => $stats[1] );
				$query[] = array( 'stat_spr_b' => $stats[2] );
				$query[] = array( 'stat_spr_d' => $stats[3] );
			}
			if( !empty($value['armor']) )
			{
				$stats = explode(':',$value['armor']);
				$query[] = array( 'stat_armor' => $stats[0] );
				$query[] = array( 'stat_armor_c' => $stats[1] );
				$query[] = array( 'stat_armor_b' => $stats[2] );
				$query[] = array( 'stat_armor_d' => $stats[3] );
			}
			if( !empty($value['defense']) )
				$query[] = array( 'stat_def_c' => $value['defense'] );

			if( !empty($value['res_frost']) )
				$query[] = array( 'res_frost_c' => $value['res_frost'] );
			if( !empty($value['res_arcane']) )
				$query[] = array( 'res_arcane_c' => $value['res_arcane'] );
			if( !empty($value['res_fire']) )
				$query[] = array( 'res_fire_c' => $value['res_fire'] );
			if( !empty($value['res_shadow']) )
				$query[] = array( 'res_shadow_c' => $value['res_shadow'] );
			if( !empty($value['res_nature']) )
				$query[] = array( 'res_nature_c' => $value['res_nature'] );

			unset($stats);

			$querystr = "UPDATE `" . ROSTER_PLAYERSTABLE . "` SET " . $db->build_query('UPDATE',$query) . " WHERE `member_id` = '" . $value['member_id'] . "';";
			$result = $db->query($querystr);
		}


		//
		// Write the config file
		//
		$config_file  = '<?php' . "\n";
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

		// Set our permissions to execute-only
		@umask(0111);

		if ( !$fp = @fopen('conf.php', 'w') )
		{
			$this->messages .=
'<form method="post" action="upgrade.php" name="post">
	<input type="hidden" name="config_data" value="' . htmlspecialchars($config_file) . '" />
	<input type="hidden" name="send_file" value="1" />
	<table cellspacing="1" cellpadding="2">
		<tr>
			<th align="left" colspan="2">Download conf.php</th>
		</tr>
		<tr>
			<td class="row1" align="center">&nbsp;<br />Download conf.php and upload it to your server into the directory that contains your roster files (eg. index.php, char.php, etc.)<br /><span style="font-weight: bold;" class="negative">BEFORE</span> you continue<br />&nbsp;</td>
		</tr>
		<tr>
			<td class="row2" align="center"><input type="submit" name="download" value="Download" class="mainoption" /></td>
		</tr>
	</table>
	<br />
</form>';
		}
		else
		{
			@fputs($fp, $config_file);
			@fclose($fp);

			$this->messages .= 'Your configuration file has been written, but upgrade will not be complete until you configure Roster';
		}
		$this->finalize($index);
	}

	/**
	 * The standard upgrader
	 * This parses the requested sql file for database upgrade
	 * Most upgrades will use this function
	 *
	 * @param string $ver
	 */
	function standard_upgrader( $ver )
	{
		global $db, $db_prefix;

		$db_structure_file = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'upgrade_' . $ver . '.sql';

		// Parse structure file and create database tables
		$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
		$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $db_prefix . '\\1\\2', $sql);

		$sql = remove_remarks($sql);
		$sql = parse_sql($sql, ';');

		$sql_count = count($sql);
		for( $i = 0; $i < $sql_count; $i++ )
		{
			// Added failure checks to the database transactions
			if( !empty($sql[$i]) && !($db->query($sql[$i]) ) )
			{
				$this->sql_errors[] = array(
					'query'=>$sql[$i],
					'error'=>$db->error()
				);
			}
		}
		unset($sql);

		return;
	}

	function display_form()
	{
		$tpl = new Template_Wrap('upgrade.html','upgrade_header.html','upgrade_tail.html');

		foreach( $this->versions as $version )
		{
			$selected = ( $version == ROSTER_OLDVERSION ) ? ' selected="selected"' : '';

			$tpl->assign_block_vars('version_row', array(
				'VALUE'    => str_replace('.', '', $version),
				'SELECTED' => $selected,
				'OPTION'   => 'Roster ' . $version,
				)
			);
		}

		$tpl->assign_vars(array(
			'ROSTER_UPGRADE'  => 'Roster Upgrade',
			'SELECT_VERSION' => 'Select the version you\'re upgrading from',
			'UPGRADE'        => 'Upgrade',
			)
		);

		$tpl->page_header();
		$tpl->page_tail();
	}

	function sql_output()
	{
		global $tpl, $db;

		foreach( explode("\n",$db->getQueries()) as $string )
		{
			$tpl->assign_block_vars('sql_rows', array(
				'TEXT' => $string
				)
			);
		}
		foreach( $this->sql_errors as $array )
		{
			$tpl->assign_block_vars('sql_errors', $array);
		}
	}
}

$upgrade = new Upgrade();

// And the upgrade-o-matic 5000 takes care of the rest.



/**
* Applies addslashes() to the provided data
*
* @param    mixed   $data   Array of data or a single string
* @return   mixed           Array or string of data
*/
function slash_global_data( &$data )
{
    if( is_array($data) )
    {
        foreach( $data as $k => $v )
        {
            $data[$k] = ( is_array($v) ) ? slash_global_data($v) : addslashes($v);
        }
    }
    return $data;
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
	$lines  = explode("\n", $sql);
	unset($sql);

	foreach( $lines as $line )
	{
		// Only parse this line if there's something on it, and we're not on the last line
		if( strlen($line) > 0 )
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
	if( $sql == '' )
	{
		die('Could not obtain SQL structure/data');
	}

	$retval     = array();
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
