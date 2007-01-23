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

if( !defined('IN_UNIADMIN') )
{
	exit('Detected invalid access to this file!');
}

if( $uniadmin->config['UAVer'] >= UA_VER )
{
	message_die($user->lang['no_upgrade']);
}

// I require MySQL version 4.0.4 minimum.
$version = mysql_get_server_info();

if( !($version >= '4.0') )
{
	$message = 'MySQL server version is not sufficient for UniAdmin<br />UniAdmin requires MySQL version > 4.0 - you are running version '.$version;
	message_die($message);
}

class Upgrade
{
	var $db = null;
	var $versions = array('0.7.5');

	function upgrade()
	{
		global $db;

		$db->error_die(false);

		if( isset($_POST['upgrade']) )
		{
			// Find out what version we're upgrading from
			$version_from = $_POST['version'];
			foreach( $this->versions as $index => $version )
			{
				if( str_replace('.', '', $version) == $version_from )
				{
					$method = 'upgrade_' . $version_from;
					$this->$method($index,str_replace('.', '', $version));
				}
			}
		}
		else
		{
			$this->display_form();
		}
	}

	function finalize($index)
	{
		global $user;

		if( isset($this->versions[$index + 1]) )
		{
			$method = 'upgrade_' . str_replace('.', '', $this->versions[$index + 1]);
			$this->$method($index + 1);
		}
		else
		{
			message_die($user->lang['upgrade_complete'], $user->lang['success']);
		}
	}

	//--------------------------------------------------------------
	// Upgrade methods
	//--------------------------------------------------------------

	function upgrade_075($index,$version)
	{
		$this->standard_upgrader($version);

		$this->finalize($index);
	}

	/**
	 * The standard upgrader
	 * This parses the requested sql file for database upgrade
	 * Most upgrades will use this function
	 *
	 * @param string $ver
	 */
	function standard_upgrader($ver)
	{
		global $db, $uniadmin;

		$db_structure_file = UA_INCLUDEDIR . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'upgrade_'.$ver.'.sql';

		// Parse structure file and create database tables
		$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
		$sql = preg_replace('#uniadmin\_(\S+?)([\s\.,]|$)#', $config['table_prefix'] . '\\1\\2', $sql);

		$sql = remove_remarks($sql);
		$sql = parse_sql($sql, ';');

		$sql_count = count($sql);
		for ( $i = 0; $i < $sql_count; $i++ )
		{
			$db->query($sql[$i]);
		}
		unset($sql);

		return;
	}

	function display_form()
	{
		global $db, $uniadmin, $user, $tpl;

		foreach ( $this->versions as $version )
		{
			// This will never happen if common.php's been upgraded already; I'm a re-re!
			$selected = ( $version == UA_VER ) ? ' selected="selected"' : '';

			$tpl->assign_block_vars('version_row', array(
				'VALUE'    => str_replace('.', '', $version),
				'SELECTED' => $selected,
				'OPTION'   => 'UniAdmin ' . $version
				)
			);
		}

		$tpl->assign_vars(array(
			'L_UA_UPGRADE'     => $user->lang['ua_upgrade'],
			'L_SELECT_VERSION' => $user->lang['select_version'],
			'L_UPGRADE'        => $user->lang['upgrade']
			)
		);

		$uniadmin->set_vars(array(
			'page_title'    => $user->lang['ua_upgrade'],
			'template_file' => 'upgrade.html',
			'display'       => true
			)
		);
	}
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

$upgrade = new Upgrade();

// And the upgrade-o-matic 5000 takes care of the rest.
