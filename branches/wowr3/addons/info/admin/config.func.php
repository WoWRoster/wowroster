<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character display configuration
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

function infoAccess($values)
{
	global $roster;

	if( count($roster->auth->levels) == 0 )
	{
		$roster->auth->rosterAccess(array('name'=>'','value'=>''));
		$roster->auth->levels[-1] = 'Per-Char';
		ksort($roster->auth->levels);
	}

	return $roster->auth->rosterAccess($values);

}
