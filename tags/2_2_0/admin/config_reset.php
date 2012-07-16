<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Configuration reset
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage RosterCP
 */

if( !defined('IN_ROSTER') || !defined('IN_ROSTER_ADMIN') )
{
	exit('Detected invalid access to this file!');
}

$roster->output['title'] .= $roster->locale->act['pagebar_configreset'];


if( isset($_POST['doit']) && ($_POST['doit'] == 'doit') )
{
	$query = 'TRUNCATE `' . $roster->db->table('config') . '`;';
	$roster->db->query($query);

	$db_data_file = ROSTER_LIB . 'dbal' . DIR_SEP . 'structure' . DIR_SEP . 'mysql_data.sql';

	// Parse the data file and populate the database tables
	$sql = @fread(@fopen($db_data_file, 'r'), @filesize($db_data_file));
	$sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $roster->db->prefix . '\\1\\2', $sql);

	$sql = parse_sql($sql, ';');

	$sql_count = count($sql);
	for( $i = 0; $i < $sql_count; $i++ )
	{
		$roster->db->query($sql[$i]);
	}
	unset($sql);

	$roster->set_message($roster->locale->act['config_is_reset'], $roster->locale->act['roster_cp'], 'error');
}

$roster->tpl->set_filenames(array('body' => 'admin/config_reset.html'));
$body = $roster->tpl->fetch('body');

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
		die_quietly('Could not obtain SQL structure/data');
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

			if( (strpos($statements[$i], $roster->db->table('menu'))===false)
				&& (strpos($statements[$i], $roster->db->table('menu_button'))===false) )
			{
				$statements[$i] = str_replace("\r\n", '', $statements[$i]) . "\n";

				// Remove 2 or more spaces
				$statements[$i] = preg_replace('#\s{2,}#', ' ', $statements[$i]);

				$retval[] = trim($statements[$i]);
			}
		}
	}
	unset($statements);

	return $retval;
}
