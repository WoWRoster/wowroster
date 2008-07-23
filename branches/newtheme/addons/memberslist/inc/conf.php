<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: conf.php 1791 2008-06-15 16:59:24Z Zanix $
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

if ( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/*
DATABASE: the altmonitor table should have an entry for every member.

roster_addon_altmonitor {
	member_id,
	main_id,
	alt_type,
}

member_id is the ID of the member this record is about. main_id is the ID of the corresponding
main. alt_type one of the following
*/

/* Using $roster->db->table('alts',$addon['basename']) in the files now
This solves the need to set $addon in the update hook
define('ROSTER_ALT_TABLE',$roster->db->table('alts',$addon['basename']));*/

define('ALTMONITOR_MAIN_ALTS',0);
define('ALTMONITOR_MAIN_NO_ALTS',1);
define('ALTMONITOR_ALT_WITH_MAIN',2);
define('ALTMONITOR_ALT_NO_MAIN',3);
define('ALTMONITOR_MAIN_MANUAL_WITH_ALTS',4);
define('ALTMONITOR_MAIN_MANUAL_NO_ALTS',5);
define('ALTMONITOR_ALT_MANUAL_WITH_MAIN',6);
define('ALTMONITOR_ALT_MANUAL_NO_MAIN',7);

/*
$manual = $alt_type & 0x4
$alt    = $alt_type & 0x2
$single = $alt_type & 0x1
*/
