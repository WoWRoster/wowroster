<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character information
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include_once($addon['dir'] . 'inc' . DIR_SEP . 'pvp.lib.php');

// Check for start for pvp log data
$start = (isset($_GET['start']) ? ( $_GET['start'] > 0 ? $_GET['start'] : 0 ) : 0);

// Get pvp table/recipe sort mode
$sort = (isset($_GET['s']) ? $_GET['s'] : '');

// Set <html><title> and <form action=""> and $char_url
$char_url = '&amp;a=c:' . $roster->data['member_id'];
