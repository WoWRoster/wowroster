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
 * @package    PvPLog
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = sprintf($roster->locale->act['bglog'],$roster->data['name']);

include( $addon['dir'] . 'inc/header.php' );

$char_page = show_pvp2('BG', 'char-' . $addon['basename'] . '-bg' . $char_url, $sort, $start);

include( $addon['dir'] . 'inc/footer.php' );
