<?php
/**
 * Project: SigGen - Signature and Avatar Generator for WoWRoster
 * File: /config.php
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://www.wowroster.net
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Joshua Clark
 * @version $Id$
 * @copyright 2005-2007 Joshua Clark
 * @package SigGen
 * @filesource
 *
 */

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}



//------[ Show the SQL Queries Window? ]------------
	// This controls the display of the SQL Queries window in the SigGen config page
	$sc_show_sql_win = 1;




// ----[ SigGen directory ]---------------------------------
// This should be the path to the siggen addon directory
// Starting from where siggen config is accessed
define('SIGGEN_DIR', dirname(__FILE__).DIR_SEP);




// ----[ Define the sig_config table ]----------------------
if( !isset($db_prefix))
{
	global $db_prefix;
}

if( !defined('ROSTER_SIGCONFIGTABLE') )
{
	define('ROSTER_SIGCONFIGTABLE',$db_prefix.'addon_siggen');
}


//------[ END OF CONFIG ]-------------------------










// ----[ Database version DO NOT CHANGE!! ]-----------------
$sc_db_ver = '1.3';
$sc_file_ver = '0.2.2';




define('SIGCONFIG_CONF',true);
