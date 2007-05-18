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

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// Check for start for pvp log data
$start = (isset($_GET['start']) ? $_GET['start'] : 0);

// Get char page mode
$action = (isset($roster->pages[2]) ? $roster->pages[2] : '' );

// Get pvp table/recipe sort mode
$sort = (isset($_GET['s']) ? $_GET['s'] : '');

// Include character class file
require_once ($addon['dir'] . 'inc/char.lib.php');


// Get Character Info
$char = new char($roster->data);

// Set <html><title> and <form action=""> and $char_url
$roster->output['title'] = sprintf($roster->locale->act['char_stats'],$char->get('name'));
$char_url = '&amp;member=' . $char->get('member_id');
$char_url_old = '&amp;member=' . $char->get('name') . '@' . $char->get('server');


// Array of db fields to get ( 'globalsetting'=>'usersetting' )
$disp_array = array(
	'show_money',
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


$char->data['char_icon'] = $roster->config['img_url'] . 'char/portrait/' . strtolower($char->data['raceEn']) . '-' . ($char->data['sexid'] == '0' ? 'male' : 'female');


$char_menu = '<div class="char_menubar">

	<a href="' . makelink('char-info' . $char_url) . '" onmouseover="overlib(\'' . $roster->locale->act['character'] . '\',WRAP);" onmouseout="return nd();">
		<img class="char_image" src="'.$char->data['char_icon'].'.gif" alt="" /></a>';

if( $addon['config']['show_talents'] )
	$char_menu .= '	<a href="'.makelink('char-info-talents'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['talents'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_talents.jpg" alt="" /></a>';

if( $addon['config']['show_spellbook'] )
	$char_menu .= '	<a href="'.makelink('char-info-spellbook'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['spellbook'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_spellbook.jpg" alt="" /></a>';

if( $addon['config']['show_mail'] )
	$char_menu .= '	<a href="'.makelink('char-info-mailbox'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['mailbox'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_mail.jpg" alt="" /></a>';

if( $addon['config']['show_bags'] )
	$char_menu .= '	<a href="'.makelink('char-info-bags'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['bags'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_bags.jpg" alt="" /></a>';

if( $addon['config']['show_bank'] )
	$char_menu .= '	<a href="'.makelink('char-info-bank'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['bank'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_bank.jpg" alt="" /></a>';

if( $addon['config']['show_quests'] )
	$char_menu .= '	<a href="'.makelink('char-info-quests'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['quests'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_questlog.jpg" alt="" /></a>';

if( $addon['config']['show_recipes'] )
	$char_menu .= '	<a href="'.makelink('char-info-recipes'.$char_url).'" onmouseover="overlib(\''.$roster->locale->act['recipes'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster->config['img_url'].'char/menubar/menu_recipes.jpg" alt="" /></a>';

$char_menu .= '
</div>

<div class="char_title">'.$char->get('name').' @ '.$char->get('server').(!empty($action) ? ' &gt; '.ucfirst($action) : '').'
	<div class="lastupdated">'.$roster->locale->act['lastupdate'].': '.$char->data['update_format'].'</div>
</div>';

$char_menu .= '<br />'.messagebox(
	makelink('char-info'.(empty($action)?'':'-'.$action).$char_url,true).'<br />'.
	makelink('char-info'.(empty($action)?'':'-'.$action).$char_url_old,true)
	,'','sgreen');


$char_page = '<div align="' . $addon['config']['char_bodyalign'] . "\">\n";

$char_page .= '
<br />
<table border="0" cellpadding="0" cellspacing="0"><tr><td align="left">';
