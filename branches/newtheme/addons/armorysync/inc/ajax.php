<?php
/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySync ajax functions list
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: ajax.php 269 2007-10-12 20:15:16Z poetter $
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.6.0
 * @package    ArmorySync
 * @subpackage Ajax
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

$ajaxfuncs['status_update'] = array(
	'file'=>$addon['dir'] . 'ajax/armorysync.php',
);
