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

// Get pvp table/recipe sort mode
$sort = (isset($_GET['s']) ? $_GET['s'] : '');

// Include character class file
require_once ($addon['inc_dir'] . 'char.lib.php');

// Get Character Info
$char = new char($roster->data);

// Set <html><title> and <form action=""> and $char_url
$roster->output['title'] = sprintf($roster->locale->act['char_stats'],$char->get('name'));

$char_url = '&amp;a=c:' . $char->get('member_id');

$roster->tpl->assign_vars(array(
	'U_IMAGE_PATH' => $addon['tpl_image_path'],
	)
);

// Array of db fields to get ( 'globalsetting'=>'usersetting' )
$disp_array = array(
	'show_money',
	'show_played',
	'show_tab2',
	'show_tab3',
	'show_tab4',
	'show_tab5',
	'show_talents',
	'show_spellbook',
	'show_mail',
	'show_bags',
	'show_bank',
	'show_quests',
	'show_recipes',
	'show_item_bonuses'
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
$char_page = '<table border="0" cellpadding="0" cellspacing="0"><tr><td align="left">';
