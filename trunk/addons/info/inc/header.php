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

$roster->output['title'] = sprintf($roster->locale->act['char_stats'],$char->get('name'));

$char_url = '&amp;a=c:' . $char->get('member_id');

$roster->tpl->assign_vars(array(
	'U_IMAGE_PATH' => $addon['tpl_image_path'],
	)
);

$char_page = '<table border="0" cellpadding="0" cellspacing="0"><tr><td align="left">';
