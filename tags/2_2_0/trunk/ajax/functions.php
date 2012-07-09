<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster ajax functions list
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage Ajax
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$ajaxfuncs['menu_button_add'] = array(
	'file'=>ROSTER_AJAX . 'menu.php',
);
$ajaxfuncs['menu_button_del'] = array(
	'file'=>ROSTER_AJAX . 'menu.php',
);
