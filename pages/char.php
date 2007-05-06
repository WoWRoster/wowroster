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

if( isset($_GET['member']) && $_GET['member'] != '' )
{
	$member = explode('@',$_GET['member']);
	$name = (isset($member[0]) && $member[0] != '' ? $member[0] : '');
	$server = (isset($member[1]) && $member[1] != '' ? $member[1] : $roster_conf['server_name']);

	// Check for start for pvp log data
	$start = (isset($_GET['start']) ? $_GET['start'] : 0);

	// Get char page mode
	$action = (isset($roster_pages[1]) ? $roster_pages[1] : '' );

	// Get pvp table/recipe sort mode
	$sort = (isset($_GET['s']) ? $_GET['s'] : '');
}
else
{
	roster_die($act_words['specify_char'],$act_words['char_error']);
}

// Check for name
if( $name == '' )
{
	roster_die($act_words['specify_char'],$act_words['char_error']);
}

// Include character class file
require_once (ROSTER_LIB.'char.php');


// Get Character Info
if( is_numeric($name) )
{
	$char = char_get_one_by_id($name);
	if( !is_object($char) )
		roster_die(sprintf($act_words['no_char_id'],$name),$act_words['char_error']);

	$name = $char->get('name');
}
else
{
	$char = char_get_one( $name, $server );
	if( !is_object($char) )
		roster_die(sprintf($act_words['no_char_name'],$name,$server),$act_words['char_error']);
}


// Set <html><title> and <form action=""> and $char_url
$roster->output['title'] = sprintf($act_words['char_stats'],$name,$server);
$char_url = '&amp;member='.$char->get('member_id');
$char_url_old = '&amp;member='.$char->get('name').'@'.$char->get('server');


// Array of db fields to get ( 'globalsetting'=>'usersetting'
$disp_array = array(
	'show_talents'=>'talents',
	'show_spellbook'=>'spellbook',
	'show_mail'=>'mail',
	'show_inventory'=>'inv',
	'show_money'=>'money',
	'show_bank'=>'bank',
	'show_recipes'=>'recipes',
	'show_quests'=>'quests',
	'show_bg'=>'bg',
	'show_pvp'=>'pvp',
	'show_duels'=>'duels',
	'show_item_bonuses'=>'item_bonuses',
);
// Loop through this array and set display accordingly
foreach( $disp_array as $global_setting => $user_setting )
{
	if( $roster_conf[$global_setting] == '2' )
	{
		switch ($char->get($user_setting))
		{
			case '1': // Private setting
				$roster_conf[$global_setting] = 0;
				break;
			case '2': // Guild setting
				$roster_conf[$global_setting] = 0;
				break;
			case '3': // Public Setting
				$roster_conf[$global_setting] = 1;
				break;
		}
	}
}


$char->data['char_icon'] = $roster_conf['img_url'].'char/portrait/'.strtolower($char->data['raceEn']).'-'.($char->data['sexid'] == '0' ? 'male' : 'female');


$char_menu = '<div class="char_menubar">

	<a href="'.makelink('char'.$char_url).'" onmouseover="overlib(\''.$act_words['character'].' Stats\',WRAP);" onmouseout="return nd();">
		<img class="char_image" src="'.$char->data['char_icon'].'.gif" alt="" /></a>';

if( $roster_conf['show_talents'] )
	$char_menu .= '	<a href="'.makelink('char-talents'.$char_url).'" onmouseover="overlib(\''.$act_words['talents'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_talents.jpg" alt="" /></a>';

if( $roster_conf['show_spellbook'] )
	$char_menu .= '	<a href="'.makelink('char-spellbook'.$char_url).'" onmouseover="overlib(\''.$act_words['spellbook'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_spellbook.jpg" alt="" /></a>';

if( $roster_conf['show_mail'] )
	$char_menu .= '	<a href="'.makelink('char-mailbox'.$char_url).'" onmouseover="overlib(\''.$act_words['mailbox'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_mail.jpg" alt="" /></a>';

if( $roster_conf['show_inventory'] )
	$char_menu .= '	<a href="'.makelink('char-bags'.$char_url).'" onmouseover="overlib(\''.$act_words['bags'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_bags.jpg" alt="" /></a>';

if( $roster_conf['show_bank'] )
	$char_menu .= '	<a href="'.makelink('char-bank'.$char_url).'" onmouseover="overlib(\''.$act_words['bank'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_bank.jpg" alt="" /></a>';

if( $roster_conf['show_quests'] )
	$char_menu .= '	<a href="'.makelink('char-quests'.$char_url).'" onmouseover="overlib(\''.$act_words['quests'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_questlog.jpg" alt="" /></a>';

if( $roster_conf['show_recipes'] )
	$char_menu .= '	<a href="'.makelink('char-recipes'.$char_url).'" onmouseover="overlib(\''.$act_words['recipes'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_recipes.jpg" alt="" /></a>';

if( $roster_conf['show_pvp'] )
	$char_menu .= '	<a href="'.makelink('char-pvp'.$char_url).'" onmouseover="overlib(\''.$act_words['pvplog'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_pvp.jpg" alt="" /></a>';

if( $roster_conf['show_bg'] )
	$char_menu .= '	<a href="'.makelink('char-bg'.$char_url).'" onmouseover="overlib(\''.$act_words['bglog'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_bg.jpg" alt="" /></a>';

if( $roster_conf['show_duels'] )
	$char_menu .= '	<a href="'.makelink('char-duels'.$char_url).'" onmouseover="overlib(\''.$act_words['duellog'].'\',WRAP);" onmouseout="return nd();">
		<img class="menu_icon" src="'.$roster_conf['img_url'].'char/menubar/menu_duel.jpg" alt="" /></a>';

$char_menu .= '
</div>

<div class="char_title">'.$name.' @ '.$server.(!empty($action) ? ' &gt; '.ucfirst($action) : '').'
	<div class="lastupdated">'.$act_words['lastupdate'].': '.$char->data['update_format'].'</div>
</div>';

$char_menu .= '<br />'.messagebox('<div style="text-align:left;font-size:10px;">'.
	makelink('char'.(empty($action)?'':'-'.$action).$char_url,true).'<br />'.
	makelink('char'.(empty($action)?'':'-'.$action).$char_url_old,true).
	'</div>','','sgreen');


$char_page = "<div align=\"".$roster_conf['char_bodyalign']."\">\n";


$char_page .= '
<br />
<table border="0" cellpadding="0" cellspacing="0"><tr><td align="left" width="100%">';


switch( $action )
{
	case 'bags':
		if( $roster_conf['show_inventory'] == 1 )
		{
			$bag0 = bag_get( $char->get('member_id'), 'Bag0' );
			if( !is_null( $bag0 ) )
				$char_page .= $bag0->out();

			$bag1 = bag_get( $char->get('member_id'), 'Bag1' );
			if( !is_null( $bag1 ) )
				$char_page .= $bag1->out();

			$bag2 = bag_get( $char->get('member_id'), 'Bag2' );
			if( !is_null( $bag2 ) )
				$char_page .= $bag2->out();

			$bag3 = bag_get( $char->get('member_id'), 'Bag3' );
			if( !is_null( $bag3 ) )
				$char_page .= $bag3->out();

			$bag4 = bag_get( $char->get('member_id'), 'Bag4' );
			if( !is_null( $bag4 ) )
				$char_page .= $bag4->out();

			$bag5 = bag_get( $char->get('member_id'), 'Bag5' );
			if( !is_null( $bag5 ) )
				$char_page .= $bag5->out();
		}
		break;

	case 'bank':
		if( $roster_conf['show_bank'] == 1 )
		{
			$bag0 = bag_get( $char->get('member_id'), 'Bank Bag0' );
			if( !is_null( $bag0 ) )
				$char_page .= $bag0->out();

			$bag1 = bag_get( $char->get('member_id'), 'Bank Bag1' );
			if( !is_null( $bag1 ) )
				$char_page .= $bag1->out();

			$bag2 = bag_get( $char->get('member_id'), 'Bank Bag2' );
			if( !is_null( $bag2 ) )
				$char_page .= $bag2->out();

			$bag3 = bag_get( $char->get('member_id'), 'Bank Bag3' );
			if( !is_null( $bag3 ) )
				$char_page .= $bag3->out();

			$bag4 = bag_get( $char->get('member_id'), 'Bank Bag4' );
			if( !is_null( $bag4 ) )
				$char_page .= $bag4->out();

			$bag5 = bag_get( $char->get('member_id'), 'Bank Bag5' );
			if( !is_null( $bag5 ) )
				$char_page .= $bag5->out();

			$bag6 = bag_get( $char->get('member_id'), 'Bank Bag6' );
			if( !is_null( $bag6 ) )
				$char_page .= $bag6->out();
		}
		break;

	case 'quests':
		if( $roster_conf['show_quests'] == 1 )
			$char_page .= $char->show_quests();
		break;

	case 'recipes':
		if( $roster_conf['show_recipes'] == 1 )
			$char_page .= $char->show_recipes();
		break;

	case 'bg':
	    if ( $roster_conf['show_bg'] == 1 )
		{
		    $char_page .= $char->show_pvp2('BG', 'char-bg'.$char_url, $sort, $start);
		}
		break;

	case 'pvp':
		if( $roster_conf['show_pvp'] == 1 )
		{
			$char_page .= $char->show_pvp2('PvP', 'char-pvp'.$char_url, $sort, $start);
		}
		break;

	case 'duels':
		if( $roster_conf['show_duels'] == 1 )
		{
			$char_page .= $char->show_pvp2('Duel', 'char-duels'.$char_url, $sort, $start);
		}
		break;

	case 'spellbook':
		if( $roster_conf['show_spellbook'] == 1 )
		{
			$char_page .= $char->show_spellbook();
		}
		break;

	case 'mailbox':
		if( $roster_conf['show_mail'] == 1 )
		{
			$char_page .= $char->show_mailbox();
		}
		break;

	case 'talents':
		if( $roster_conf['show_talents'] == 1 )
		{
			$char_page .= $char->printTalents();
		}
		break;

	default:
		ob_start();
			$char->out();
		$char_page .= ob_get_clean();
		break;
}


$char_page .= "</td></tr></table>\n<br clear=\"all\" />\n";

if( empty($action) && $roster_conf['show_item_bonuses'])
{
	require_once (ROSTER_LIB.'charbonus.lib.php');
	$char_bonus = new CharBonus($char);
	$char_page .= $char_bonus->dumpBonus();
	unset($char_bonus);
}


$char_page .= "</div>\n";


// Include Header
include_once (ROSTER_BASE.'roster_header.tpl');

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');

echo $char_menu;
echo $char_page;

include_once (ROSTER_BASE.'roster_footer.tpl');
