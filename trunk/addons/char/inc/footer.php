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
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$char_page .= "</td></tr></table>\n<br clear=\"all\" />\n";

if( empty($action) && $addon['config']['show_item_bonuses'])
{
	require_once (ROSTER_LIB.'charbonus.lib.php');
	$char_bonus = new CharBonus($char);
	$char_page .= $char_bonus->dumpBonus();
	unset($char_bonus);
}


$char_page .= "</div>\n";


echo $char_menu;
echo $char_page;
