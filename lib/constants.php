<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Contants and defines file for Roster
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
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

define('ROSTER_VERSION','1.9.9.1394');

/**
 * Roster Conf File
 *
 */
define('ROSTER_CONF_FILE',ROSTER_BASE . 'conf.php');

/**
 * Base, absolute roster admin directory
 */
define('ROSTER_ADMIN',ROSTER_BASE . 'admin' . DIR_SEP);

/**
 * Base, absolute roster ajax directory
 */
define('ROSTER_AJAX',ROSTER_BASE . 'ajax' . DIR_SEP);

/**
 * Cache directory
 */
define('ROSTER_CACHEDIR',ROSTER_BASE . 'cache' . DIR_SEP);

/**
 * Template directory
 */
define('ROSTER_TPLDIR',ROSTER_BASE . 'templates' . DIR_SEP);

/**
 * Base, absolute roster addons directory
 */
define('ROSTER_ADDONS',ROSTER_BASE . 'addons' . DIR_SEP);

/**
 * Base, absolute roster pages directory
 */
define('ROSTER_PAGES',ROSTER_BASE . 'pages' . DIR_SEP);

/**
 * Base, absolute roster localization directory
 */
define('ROSTER_LOCALE_DIR',ROSTER_BASE . 'localization' . DIR_SEP);

/**
 * Roster Remote File Validation
 * Please make a page on the web where you place the most rescent version of the files, including this file.
 * The webpage must be entered below without a trailing slash
 */
define('ROSTER_SVNREMOTE', 'http://www.wowroster.net/roster_beta/version_match.php');

/**
 * Some static and semi-static game data.
 */
define('ROSTER_MAXCHARLEVEL','70');
define('ROSTER_MAXSKILLLEVEL','375');
