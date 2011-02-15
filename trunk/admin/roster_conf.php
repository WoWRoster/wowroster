<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Main Roster configuration
 *
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

$roster->output['title'] .= $roster->locale->act['pagebar_rosterconf'];

// ----[ Set the tablename and create the config class ]----
include(ROSTER_LIB . 'config.lib.php');
$config = new roster_config( $roster->db->table('config') );

// ----[ Include special functions file ]-------------------
include(ROSTER_ADMIN . 'roster_config_functions.php');

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Process data if available ]------------------------
$save_message = $config->processData( $roster->config );

// ----[ Build the page items using lib functions ]---------
$menu = $config->buildConfigMenu();

$config->buildConfigPage();

$body .= $config->form_start
	   . $save_message
	   . $config->submit_button
	   . $config->formpages
	   . $config->form_end
	   . $config->nonformpages;
$footer .= $config->jscript;
