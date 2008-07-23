<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character display configuration
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: config.func.php 1791 2008-06-15 16:59:24Z Zanix $
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
