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
 * @version    SVN: $Id: recipes.php 867 2007-04-29 07:41:43Z Zanix $
 * @link       http://www.wowroster.net
 * @package    Character Info
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

include( $addon['dir'] . 'inc/header.php' );

if( $addon['config']['show_recipes'] == 1 )
{
	$char_page .= $char->show_recipes();
}

include( $addon['dir'] . 'inc/footer.php' );
