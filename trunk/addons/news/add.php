<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: enUS.php 1126 2007-07-27 05:14:27Z Zanix $
 * @link       http://www.wowroster.net
 * @package    News
*/

include( $addon['dir'] . 'template' . DIR_SEP . 'template.php' );

$roster_login = new RosterLogin();

if( $roster_login->getAuthorized() < $addon['config']['news_add'] )
{
	print $roster_login->getMessage().
	$roster_login->getLoginForm($addon['config']['news_add']);

	return; //To the addon framework
}

include_template( 'add.tpl' );


