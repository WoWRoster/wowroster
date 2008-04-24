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

define('ROSTER_VERSION','1.9.9.1758');

/**
 * Roster Conf File
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
define('ROSTER_UPDATECHECK', 'http://www.wowroster.net/roster_beta/version.txt');
define('ROSTER_ADDONUPDATEURL', 'http://www.wowroster.net/rss/downloads.php?id=%1$s');

/**
 * Some static and semi-static game data.
 */
define('ROSTER_MAXCHARLEVEL','70');
define('ROSTER_MAXSKILLLEVEL','375');
define('ROSTER_MAXQUESTS','25');

define('ROSTER_CLASS_1', 'Warrior');
define('ROSTER_CLASS_2', 'Paladin');
define('ROSTER_CLASS_3', 'Hunter');
define('ROSTER_CLASS_4', 'Rogue');
define('ROSTER_CLASS_5', 'Priest');
define('ROSTER_CLASS_6', '');
define('ROSTER_CLASS_7', 'Shaman');
define('ROSTER_CLASS_8', 'Mage');
define('ROSTER_CLASS_9', 'Warlock');
define('ROSTER_CLASS_10','');
define('ROSTER_CLASS_11','Druid');

define('ROSTER_RACE_1', 'Human');
define('ROSTER_RACE_2', 'Orc');
define('ROSTER_RACE_3', 'Dwarf');
define('ROSTER_RACE_4', 'NightElf');
define('ROSTER_RACE_5', 'Scourge');
define('ROSTER_RACE_6', 'Tauren');
define('ROSTER_RACE_7', 'Gnome');
define('ROSTER_RACE_8', 'Troll');
define('ROSTER_RACE_9', '');
define('ROSTER_RACE_10','BloodElf');
define('ROSTER_RACE_11','Draenei');

define('ROSTER_SEX_0','Male');
define('ROSTER_SEX_1','Female');
