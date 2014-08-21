<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character information
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

include( $addon['inc_dir'] . 'header.php' );

if( $roster->auth->getAuthorized($addon['config']['show_talents']) )
{
	$char_page .= $char->show_talents();
}

include( $addon['inc_dir'] . 'footer.php' );
