<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Contants and defines file for Roster
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.0
 * @package    WoWRoster
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

define('ROSTER_VERSION', '2.2.0');

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
 * Base, absolute roster api directory
 */
define('ROSTER_API', ROSTER_LIB . 'api' . DIR_SEP);

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
define('ROSTER_PLUGINS', ROSTER_BASE . 'plugins' . DIR_SEP);

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
define('ROSTER_SVNREMOTE', 'http://www.wowroster.net/updater/roster_beta/version_match.php');
define('ROSTER_UPDATECHECK', 'http://www.wowroster.net/updater/roster_beta/version.txt');
define('ROSTER_ADDONUPDATEURL', 'http://www.wowroster.net/downloads.php?id=%1$s');

/**
 * Some static and semi-static game data.
 */
define('ROSTER_MAXCHARLEVEL', '85');
define('ROSTER_MAXSKILLLEVEL', '525');
define('ROSTER_MAXQUESTS', '25');
define('ROSTER_TALENT_ROWS', '7');
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
define('ROSTER_CLASS_MONK', 10);
define('ROSTER_CLASS_DRUID', 11);

define('ROSTER_RACE_HUMAN', 1);
define('ROSTER_RACE_ORC', 2);
define('ROSTER_RACE_DWARF', 3);
define('ROSTER_RACE_NIGHTELF', 4);
define('ROSTER_RACE_SCOURGE', 5);
define('ROSTER_RACE_TAUREN', 6);
define('ROSTER_RACE_GNOME', 7);
define('ROSTER_RACE_TROLL', 8);
define('ROSTER_RACE_GOBLIN', 9);
define('ROSTER_RACE_BLOODELF', 10);
define('ROSTER_RACE_DRAENEI', 11);
define('ROSTER_RACE_WORGEN', 22);
//Pandaren ALLIANCE/HORD RESPECTIVLY
define('ROSTER_RACE_PANDAREN_A', 25);
define('ROSTER_RACE_PANDAREN_H', 26);

define('ROSTER_SEX_MALE', 0);
define('ROSTER_SEX_FEMALE', 1);
