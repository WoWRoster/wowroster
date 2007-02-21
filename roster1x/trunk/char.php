<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

require_once( 'settings.php' );

if( isset($_GET['member']) && $_GET['member'] != '' )
{
	$member = explode('@',$_GET['member']);
	$name = ($member[0] != '' ? $member[0] : '');
	$server = ($member[1] != '' ? $member[1] : $roster_conf['server_name']);

	// Check for start for pvp log data
	$start = (isset($_GET['start']) ? $_GET['start'] : 0);

	// Get char page mode
	$action = (isset($_GET['action']) ? $_GET['action'] : '' );

	// Get pvp table/recipe sort mode
	$sort = (isset($_GET['s']) ? $_GET['s'] : '');
}
else
{
	message_die('Character was not specified','Character Error');
}

// Check for name
if( $name == '' )
{
	message_die('Character name was not specified','No character name');
}

// Include character class file
require_once (ROSTER_LIB.'char.php');


// Get Character Info
if( is_numeric($name) )
{
	$char = char_get_one_by_id($name);
	if( !is_object($char) )
		message_die('Sorry no character data for member_id &quot;'.$name.'&quot;','Character Not Found');

	$name = $char->get('name');
}
else
{
	$char = char_get_one( $name, $server );
	if( !is_object($char) )
		message_die('Sorry no character data for &quot;'.$name.'&quot; of &quot;'.$server.'&quot;','Character Not Found');
}


// Set <html><title> and <form action=""> and $url
$header_title = 'Character Stats for: '.$name.' @ '.$server;
$script_filename = 'char.php?member='.$char->get('member_id');
$script_filename_old = 'char.php?member='.$char->get('name').'@'.$char->get('server');
$url = '<a href="char.php?member='.$char->get('member_id');


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


$menu_cell = '      <td class="menubarHeader" align="center" valign="middle">';

$char_menu = '<div align="center">'."\n";

$char_menu .= border('sorange','start');

$char_menu .= '  <table cellpadding="3" cellspacing="0" class="menubar">'."\n<tr>\n";

$char_menu .= $menu_cell.$url.'">'.$wordings[$roster_conf['roster_lang']]['character'].' Stats</a></td>'."\n";

if( $roster_conf['show_spellbook'] )
	$char_menu .= $menu_cell.$url.'&amp;action=spellbook">'.$wordings[$roster_conf['roster_lang']]['spellbook'].'</a></td>'."\n";

if( $roster_conf['show_inventory'] )
	$char_menu .= $menu_cell.$url.'&amp;action=bags">'.$wordings[$roster_conf['roster_lang']]['bags'].'</a></td>'."\n";

if( $roster_conf['show_bank'] )
	$char_menu .= $menu_cell.$url.'&amp;action=bank">'.$wordings[$roster_conf['roster_lang']]['bank'].'</a></td>'."\n";

if( $roster_conf['show_mail'] )
	$char_menu .= $menu_cell.$url.'&amp;action=mailbox">'.$wordings[$roster_conf['roster_lang']]['mailbox'].'</a></td>'."\n";

if( $roster_conf['show_quests'] )
	$char_menu .= $menu_cell.$url.'&amp;action=quests">'.$wordings[$roster_conf['roster_lang']]['quests'].'</a></td>'."\n";

if( $roster_conf['show_recipes'] )
	$char_menu .= $menu_cell.$url.'&amp;action=recipes">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a></td>'."\n";

if( $roster_conf['show_bg'] )
	$char_menu .= $menu_cell.$url.'&amp;action=bg">'.$wordings[$roster_conf['roster_lang']]['bglog'].'</a></td>'."\n";

if( $roster_conf['show_pvp'] )
	$char_menu .= $menu_cell.$url.'&amp;action=pvp">'.$wordings[$roster_conf['roster_lang']]['pvplog'].'</a></td>'."\n";

if( $roster_conf['show_duels'] )
	$char_menu .= $menu_cell.$url.'&amp;action=duels">'.$wordings[$roster_conf['roster_lang']]['duellog'].'</a></td>'."\n";

$char_menu .= "  </tr>\n</table>\n";

$char_menu .= border('sorange','end');
$char_menu .="\n</div>\n";


$char_page = "<div align=\"".$roster_conf['char_bodyalign']."\" style=\"margin:10px;\">\n";

$char_page .= '<br />
<span class="lastupdated">'.$wordings[$roster_conf['roster_lang']]['lastupdate'].': '.$char->data['update_format']."</span><br />\n";

if($roster_conf['show_signature'])
	$char_page .= "<img onmouseover=\"return overlib('To access this signature use: ".$roster_conf['roster_dir']."/addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."',CAPTION,'Signature Access',RIGHT);\" onmouseout=\"return nd();\" ".
		"src=\"".$roster_conf['roster_dir']."/addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."&amp;saveonly=0\" alt=\"Signature Image for $name\" />&nbsp;\n";

if($roster_conf['show_avatar'])
	$char_page .= "<img onmouseover=\"return overlib('To access this avatar use: ".$roster_conf['roster_dir']."/addons/siggen/av.php?name=".urlencode(utf8_decode($name))."',CAPTION,'Avatar Access');\" onmouseout=\"return nd();\" ".
		"src=\"".$roster_conf['roster_dir']."/addons/siggen/av.php?name=".urlencode(utf8_decode($name))."&amp;saveonly=0\" alt=\"Avatar Image for $name\" />\n";


$char_page .= '
<br /><br />
<table border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td align="left">';


switch ($action)
{
	case 'bags':
		if( $roster_conf['show_inventory'] == 1 )
		{
			$bag0 = bag_get( $char, 'Bag0' );
			if( !is_null( $bag0 ) )
				$char_page .= $bag0->out();

			$bag1 = bag_get( $char, 'Bag1' );
			if( !is_null( $bag1 ) )
				$char_page .= $bag1->out();

			$bag2 = bag_get( $char, 'Bag2' );
			if( !is_null( $bag2 ) )
				$char_page .= $bag2->out();

			$bag3 = bag_get( $char, 'Bag3' );
			if( !is_null( $bag3 ) )
				$char_page .= $bag3->out();

			$bag4 = bag_get( $char, 'Bag4' );
			if( !is_null( $bag4 ) )
				$char_page .= $bag4->out();

			$bag5 = bag_get( $char, 'Bag5' );
			if( !is_null( $bag5 ) )
				$char_page .= $bag5->out();
		}
		break;

	case 'bank':
		if( $roster_conf['show_bank'] == 1 )
		{
			$bag0 = bag_get( $char, 'Bank Bag0' );
			if( !is_null( $bag0 ) )
				$char_page .= $bag0->out(true);

			$bag1 = bag_get( $char, 'Bank Bag1' );
			if( !is_null( $bag1 ) )
				$char_page .= $bag1->out();

			$bag2 = bag_get( $char, 'Bank Bag2' );
			if( !is_null( $bag2 ) )
				$char_page .= $bag2->out();

			$bag3 = bag_get( $char, 'Bank Bag3' );
			if( !is_null( $bag3 ) )
				$char_page .= $bag3->out();

			$bag4 = bag_get( $char, 'Bank Bag4' );
			if( !is_null( $bag4 ) )
				$char_page .= $bag4->out();

			$bag5 = bag_get( $char, 'Bank Bag5' );
			if( !is_null( $bag5 ) )
				$char_page .= $bag5->out();

			$bag6 = bag_get( $char, 'Bank Bag6' );
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
		    $url .= '&amp;action=bg';
		    $char_page .= $char->show_pvp2('BG', $url, $sort, $start);
		}
		break;

	case 'pvp':
		if( $roster_conf['show_pvp'] == 1 )
		{
			$url .= '&amp;action=pvp';
			$char_page .= $char->show_pvp2('PvP', $url, $sort, $start);
		}
		break;

	case 'duels':
		if( $roster_conf['show_duels'] == 1 )
		{
			$url .= '&amp;action=duels';
			$char_page .= $char->show_pvp2('Duel', $url, $sort, $start);
		}
		break;

	case 'spellbook':
		if( $roster_conf['show_spellbook'] == 1 )
		{
			$url .= '&amp;action=spellbook';
			$char_page .= $char->show_spellbook();
		}
		break;

	case 'mailbox':
		if( $roster_conf['show_mail'] == 1 )
		{
			$url .= '&amp;action=mail';
			$char_page .= $char->show_mailbox();
		}
		break;

	default:
		ob_start();
			$char->out();
			$char_page .= ob_get_contents();
		ob_end_clean();
		break;
}


$char_page .= "</td></tr></table>\n";

if( empty($action) && $roster_conf['show_item_bonuses'])
{
	$char_page .= dumpBonuses($char);
}

$char_page .= '<br />'.messagebox('<div style="text-align:left;font-size:10px;">'.
	ROSTER_URL.'/'.$script_filename.( !empty($action) ? '&amp;action='.$action : '' ).'<br />'.
	ROSTER_URL.'/char.php?member='.$script_filename_old.( !empty($action) ? '&amp;action='.$action : '' )
	,'Character Links','sgreen');

$char_page .= "</div>\n";


// Include Header
include_once (ROSTER_BASE.'roster_header.tpl');
include_once (ROSTER_LIB.'menu.php');

echo $char_menu;
echo $char_page;

include_once (ROSTER_BASE.'roster_footer.tpl');

?>