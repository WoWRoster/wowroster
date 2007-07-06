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
 * @version    SVN: $Id: header.php 965 2007-06-10 22:58:50Z Zanix $
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

include_once($addon['dir'] . 'inc' . DIR_SEP . 'pvp.lib.php');

// Check for start for pvp log data
$start = (isset($_GET['start']) ? ( $_GET['start'] > 0 ? $_GET['start'] : 0 ) : 0);

// Get pvp table/recipe sort mode
$sort = (isset($_GET['s']) ? $_GET['s'] : '');

// Set <html><title> and <form action=""> and $char_url
$char_url = '&amp;member=' . $roster->data['member_id'];
$char_url_old = '&amp;member=' . $roster->data['name'] . '@' . $roster->data['region'] . '-' . $roster->data['server'];


// Array of db fields to get ( 'globalsetting'=>'usersetting' )
$disp_array = array(
	'show_pvp',
	'show_bg',
	'show_duel'
);

// Loop through this array and set display accordingly
foreach( $disp_array as $global_setting )
{
	if( $addon['config'][$global_setting] == '2' )
	{
		switch ($char->get($global_setting))
		{
			case '1': // Private setting
				$addon['config'][$global_setting] = 0;
				break;
			case '2': // Guild setting
				$addon['config'][$global_setting] = 0;
				break;
			case '3': // Public Setting
				$addon['config'][$global_setting] = 1;
				break;
		}
	}
}


$char_menu = '
<div class="char_title">' . $roster->data['name'] . ' @ '.$roster->data['region'] . '-'.$roster->data['server'] . (!empty($action) ? ' &gt; '.ucfirst($action) : '') . '
	<div class="lastupdated">' . $roster->locale->act['lastupdate'] . ': ' . $char->data['update_format'] . '</div>
</div>';

$char_menu .= '<br />'.messagebox(
			makelink(ROSTER_PAGE_NAME . $char_url,true) . '<br />'
			. makelink(ROSTER_PAGE_NAME . $char_url_old,true)
			,'','sgreen').'<br />';
