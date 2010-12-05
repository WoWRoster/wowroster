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

// Include character class file
require_once ($addon['inc_dir'] . 'char.lib.php');

// Get Character Info
$char = new char($roster->data);

$roster->output['title'] = sprintf($roster->locale->act['char_stats'], $char->get('name'));

$roster->tpl->assign_var('U_IMAGE_PATH', $addon['tpl_image_path']);
