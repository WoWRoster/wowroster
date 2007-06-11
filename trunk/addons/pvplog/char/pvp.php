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
 * @version    SVN: $Id: pvp.php 867 2007-04-29 07:41:43Z Zanix $
 * @link       http://www.wowroster.net
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = sprintf($roster->locale->act['pvplog'],$roster->data['name']);

include( $addon['dir'] . 'inc/header.php' );

//if( $addon['config']['show_pvp'] == 1 )
{
	$char_page = show_pvp2('PvP', 'char-' . $addon['basename'] . '-pvp' . $char_url, $sort, $start);
}

include( $addon['dir'] . 'inc/footer.php' );
