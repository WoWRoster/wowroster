<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Contants and defines file for Roster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
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

define('ROSTER_VERSION', '2.0.9.2159');

/**
 * Roster Conf File
 */
define('ROSTER_CONF_FILE', ROSTER_BASE . 'conf.php');

/**
 * Base, absolute roster admin directory
 */
define('ROSTER_ADMIN', ROSTER_BASE . 'admin' . DIR_SEP);

/**
 * Base, absolute roster ajax directory
 */
define('ROSTER_AJAX', ROSTER_BASE . 'ajax' . DIR_SEP);

/**
 * Cache directory
 */
define('ROSTER_CACHEDIR', ROSTER_BASE . 'cache' . DIR_SEP);

/**
 * Template directory
 */
define('ROSTER_TPLDIR', ROSTER_BASE . 'templates' . DIR_SEP);

/**
 * Base, absolute roster addons directory
 */
define('ROSTER_ADDONS', ROSTER_BASE . 'addons' . DIR_SEP);

/**
 * Base, absolute roster pages directory
 */
define('ROSTER_PAGES', ROSTER_BASE . 'pages' . DIR_SEP);

/**
 * Base, absolute roster localization directory
 */
define('ROSTER_LOCALE_DIR', ROSTER_BASE . 'localization' . DIR_SEP);

/**
 * Roster Remote File Validation
 * Please make a page on the web where you place the most rescent version of the files, including this file.
 * The webpage must be entered below without a trailing slash
 */
define('ROSTER_SVNREMOTE', 'http://www.wowroster.net/roster_beta/version_match.php');
define('ROSTER_UPDATECHECK', 'http://www.wowroster.net/roster_beta/version.txt');
define('ROSTER_ADDONUPDATEURL', 'http://www.wowroster.net/rss/downloads.php?id=%1$s');

/**
 * Some static and semi-static game data.
 */
define('ROSTER_MAXCHARLEVEL', '80');
define('ROSTER_MAXSKILLLEVEL', '450');
define('ROSTER_MAXQUESTS', '25');
define('ROSTER_TALENT_ROWS', '11');
define('ROSTER_TALENT_COLS', '4');

define('ROSTER_CLASS_WARRIOR', 1);
define('ROSTER_CLASS_PALADIN', 2);
define('ROSTER_CLASS_HUNTER', 3);
define('ROSTER_CLASS_ROGUE', 4);
define('ROSTER_CLASS_PRIEST', 5);
define('ROSTER_CLASS_DEATHKNIGHT', 6);
define('ROSTER_CLASS_SHAMAN', 7);
define('ROSTER_CLASS_MAGE', 8);
define('ROSTER_CLASS_WARLOCK', 9);
//define('ROSTER_CLASS_', 10);
define('ROSTER_CLASS_DRUID', 11);

define('ROSTER_RACE_HUMAN', 1);
define('ROSTER_RACE_ORC', 2);
define('ROSTER_RACE_DWARF', 3);
define('ROSTER_RACE_NIGHTELF', 4);
define('ROSTER_RACE_SCOURGE', 5);
define('ROSTER_RACE_TAUREN', 6);
define('ROSTER_RACE_GNOME', 7);
define('ROSTER_RACE_TROLL', 8);
//define('ROSTER_RACE_', 9);
define('ROSTER_RACE_BLOODELF', 10);
define('ROSTER_RACE_DRAENEI', 11);

define('ROSTER_SEX_MALE', 0);
define('ROSTER_SEX_FEMALE', 1);
