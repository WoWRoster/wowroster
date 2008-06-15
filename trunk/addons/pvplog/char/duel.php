<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character information
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    PvPLog
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

include_once($addon['inc_dir'] . 'pvp.lib.php');

$roster->output['title'] = sprintf($roster->locale->act['duellog'],$roster->data['name']);

// Check for start for pvp log data
$start = (isset($_GET['start']) ? ( $_GET['start'] > 0 ? $_GET['start'] : 0 ) : 0);

// Get pvp table/recipe sort mode
$sort = (isset($_GET['s']) ? $_GET['s'] : '');

// Set <html><title> and <form action=""> and $char_url
$char_url = '&amp;a=c:' . $roster->data['member_id'];

$char_page = show_pvp2('Duel', 'char-' . $addon['basename'] . '-duels' . $char_url, $sort, $start);

echo $char_page;

$roster->tpl->set_handle('body', $addon['basename'] . '/char.html');
$roster->tpl->display('body');
