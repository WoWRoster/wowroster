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
 * @subpackage Upgrader
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( version_compare($roster->config['version'], ROSTER_VERSION,'>=') )
{
	roster_die($roster->locale->act['no_upgrade'],$roster->locale->act['upgrade_wowroster']);
}


/**
 * WoWRoster Upgrader
 *
 * @package    WoWRoster
 * @subpackage Upgrader
 */
class Upgrade
{
	var $versions = array('1.9.9');
	var $index = null;

	function Upgrade()
	{
		global $roster;

		$roster->db->error_die(false);

		if( isset($_POST['upgrade']) )
		{
			// Find out what version we're upgrading from
			$version_from = $_POST['version'];
			foreach( $this->versions as $index => $version )
			{
				$this->index = $index;
				if( str_replace('.', '', $version) == $version_from )
				{
					$method = 'upgrade_' . $version_from;
					$this->$method();
				}
			}
		}
		else
		{
			$this->display_form();
		}
	}

	function finalize()
	{
		global $roster;

		$this->index++;

		if( isset($this->versions[$this->index]) )
		{
			$method = 'upgrade_' . str_replace('.', '', $this->versions[$this->index]);
			$this->$method();
		}
		else
		{
			roster_die($roster->locale->act['upgrade_complete'],$roster->locale->act['upgrade_wowroster'],'sgreen');
		}
	}

	//--------------------------------------------------------------
	// Upgrade methods
	//--------------------------------------------------------------

	function upgrade_199()
	{
		global $roster;

		if( version_compare($roster->config['version'],'1.9.9.1407','<') )
		{
			$roster->db->query("INSERT INTO `" . $roster->db->table('config') . "` VALUES (6, 'versioncache', '', 'hidden', 'master');");
			$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '168', `form_type` = 'select{Do Not check^0|Once a Day^24|Once a Week^168|Once a Month^720' WHERE `id` = 1150 LIMIT 1;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('addon') . "` ADD `wrnet_id` int(4) NOT NULL DEFAULT '0';");
		}

		if( version_compare($roster->config['version'],'1.9.9.1417','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('menu') . "` CHANGE `section` `section` varchar(64) NULL DEFAULT NULL;");
			$roster->db->query("ALTER TABLE `" . $roster->db->table('menu') . "` ADD UNIQUE `section` ( `section` ) ");
		}

		if( version_compare($roster->config['version'],'1.9.9.1438','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('mailbox') . "` DROP `item_icon`, DROP `item_name`, DROP `item_quantity`, DROP `item_tooltip`, DROP `item_color`;");
		}

		if( version_compare($roster->config['version'],'1.9.9.1439','<') )
		{
			$roster->db->query("ALTER TABLE `" . $roster->db->table('mailbox') . "` ADD `mailbox_icon` varchar(64) NOT NULL DEFAULT '';");
		}

		$this->beta_upgrade();

		$this->finalize();
	}

	function beta_upgrade()
	{
		global $roster;

		$roster->db->query("UPDATE `" . $roster->db->table('config') . "` SET `config_value` = '" . ROSTER_VERSION . "' WHERE `id` = '4' LIMIT 1;");
	}

	/**
	 * The standard upgrader
	 * This parses the requested sql file for database upgrade
	 * Most upgrades will use this function
	 */
	function standard_upgrader()
	{
		global $roster;

		$ver = str_replace('.','',$this->versions[$this->index]);

		$db_structure_file = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'upgrade_' . $ver . '.sql';

		if( file_exists($db_structure_file) )
		{
			// Parse structure file and create database tables
			$sql = @fread(@fopen($db_structure_file, 'r'), @filesize($db_structure_file));
			$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $roster->db->prefix . '\\1\\2', $sql);

			$sql = remove_remarks($sql);
			$sql = parse_sql($sql, ';');

			$sql_count = count($sql);
			for( $i = 0; $i < $sql_count; $i++ )
			{
				$roster->db->query($sql[$i]);
			}
			unset($sql);
		}
		else
		{
			roster_die('Could not obtain SQL structure/data',$roster->locale->act['upgrade_wowroster']);
		}

		return;
	}

	function display_form()
	{
		global $roster;

		foreach ( $this->versions as $version )
		{
			$selected = ( $version == $roster->config['version'] ) ? ' selected="selected"' : '';

			$roster->tpl->assign_block_vars('version_row', array(
				'VALUE'    => str_replace('.', '', $version),
				'SELECTED' => $selected,
				'OPTION'   => 'WoWRoster ' . $version
				)
			);
		}

		$roster->tpl->assign_vars(array(
			'L_UPGRADE'        => $roster->locale->act['upgrade_wowroster'],
			'L_SELECT_VERSION' => $roster->locale->act['select_version'],
			'L_UPGRADE'        => $roster->locale->act['upgrade'],

			'U_UPGRADE'        => makelink('upgrade'),
			)
		);

		$roster->output['title'] = $roster->locale->act['upgrade_wowroster'];

		include(ROSTER_BASE . 'header.php');

		$roster->tpl->set_filenames(array('body' => 'upgrade.html'));
		$roster->tpl->display('body');

		include(ROSTER_BASE . 'footer.php');
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
	global $roster;

	if ( $sql == '' )
	{
		roster_die('Could not obtain SQL structure/data',$roster->locale->act['upgrade_wowroster']);
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
	global $roster;

	if ( $sql == '' )
	{
		roster_die('Could not obtain SQL structure/data',$roster->locale->act['upgrade_wowroster']);
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
