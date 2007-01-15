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

//DF security
if (!defined('CPG_NUKE')) { exit; }
//Roster security
/*
if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}*/

// Check for name
if( $name == '' )
{
	message_die('Character name was not specified','No character name');
}

// Include character class file
require_once (ROSTER_LIB.'char.php');


// Check for server name
if ( $roster_conf['server_name_comp'] == 0 && isset($_GET['server']) )
{
	$server = stripslashes($_GET['server']);
}
else
{
	$server = $roster_conf['server_name'];
}

// Check for start for pvp log data
$start = (isset($_GET['start']) ? $_GET['start'] : 0);


// Get char page mode
$action = (isset($_GET['action']) ? $_GET['action'] : 'character' );


// Get pvp table/recipe sort mode
$sort = (isset($_GET['s']) ? $_GET['s'] : '');

// Get Character Info
$char = char_get_one( $name, $server );
if( !$char )
{
	message_die('Sorry no data in database for &quot;'.$_GET['cname'].'&quot; of &quot;'.$_GET['server'].'&quot;<br /><br /><a href="./index.php">'.$wordings[$roster_conf['roster_lang']]['backlink'].'</a>','Character Not Found');
}


// Get per character display control
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


$url = $module_name.'&amp;file=char&amp;cname='.urlencode($name).'&amp;server='.urlencode($server);
//wowrosterdf test url to make sure it is right
//$url = '<a href="'.getlink($module_name.'&amp;file=char&amp;cname='.urlencode($name).'&amp;server='.urlencode($server).'&amp;action=character').'">
$menu_cell = '      <td class="menubarHeader" align="center" valign="middle">';

print '<div align="center">'."\n";


print border('sorange','start');

print '  <table cellpadding="3" cellspacing="0" class="menubar">'."\n<tr>\n";

echo $menu_cell.'<a href="'.getlink($module_name).'">'.$wordings[$roster_conf['roster_lang']]['backlink'].'</a></td>'."\n";
echo $menu_cell.'<a href="'.getlink($url.'&amp;action=character').'">'.$wordings[$roster_conf['roster_lang']]['character'].' Stats</a></td>'."\n";

if( $roster_conf['show_spellbook'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=spellbook').'">'.$wordings[$roster_conf['roster_lang']]['spellbook'].'</a></td>'."\n";

if( $roster_conf['show_inventory'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=bags').'">'.$wordings[$roster_conf['roster_lang']]['bags'].'</a></td>'."\n";

if( $roster_conf['show_bank'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=bank').'">'.$wordings[$roster_conf['roster_lang']]['bank'].'</a></td>'."\n";

if( $roster_conf['show_mail'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=mail').'">'.$wordings[$roster_conf['roster_lang']]['mailbox'].'</a></td>'."\n";

if( $roster_conf['show_quests'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=quests').'">'.$wordings[$roster_conf['roster_lang']]['quests'].'</a></td>'."\n";

if( $roster_conf['show_recipes'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=recipes').'">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a></td>'."\n";

if( $roster_conf['show_bg'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=bg').'">'.$wordings[$roster_conf['roster_lang']]['bglog'].'</a></td>'."\n";

if( $roster_conf['show_pvp'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=pvp').'">'.$wordings[$roster_conf['roster_lang']]['pvplog'].'</a></td>'."\n";

if( $roster_conf['show_duels'] )
	echo $menu_cell.'<a href="'.getlink($url.'&amp;action=duels').'">'.$wordings[$roster_conf['roster_lang']]['duellog'].'</a></td>'."\n";

print "  </tr>\n</table>\n";

print border('sorange','end');



print "\n</div>\n<div align=\"".$roster_conf['char_bodyalign']."\" style=\"margin:10px;\">\n";

$date_char_data_updated = DateCharDataUpdated($name);

print '<br />
<span class="lastupdated">'.$wordings[$roster_conf['roster_lang']]['lastupdate'].': '.$date_char_data_updated."</span><br />\n";
/*roster version
if($roster_conf['show_signature'])
	print "<img onmouseover=\"return overlib('To access this signature use: ".$roster_conf['roster_dir']."/addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."',CAPTION,'Signature Access',RIGHT);\" onmouseout=\"return nd();\" ".
		"src=\"".$roster_conf['roster_dir']."/addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."&amp;saveonly=0\" alt=\"Signature Image for $name\" />&nbsp;\n";

if($roster_conf['show_avatar'])
	print "<img onmouseover=\"return overlib('To access this avatar use: ".$roster_conf['roster_dir']."/addons/siggen/av.php?name=".urlencode(utf8_decode($name))."',CAPTION,'Avatar Access');\" onmouseout=\"return nd();\" ".
		"src=\"".$roster_conf['roster_dir']."/addons/siggen/av.php?name=".urlencode(utf8_decode($name))."&amp;saveonly=0\" alt=\"Avatar Image for $name\" />\n";
*/
//wowrosterdf version
if($roster_conf['show_signature'])
	print "<img onmouseover=\"return overlib('To access this signature use: ".$roster_conf['roster_dir']."/addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."',CAPTION,'Signature Access',RIGHT);\" onmouseout=\"return nd();\" ".
		"src=\"".getlink($module_name.'&amp;file=addon&amp;roster_addon_name=siggen&amp;mode=signature&amp;member='.urlencode(utf8_decode($name)).'&amp;saveonly=0')."\" alt=\"Signature Image for $name\" />&nbsp;\n";

if($roster_conf['show_avatar'])
	print "<img onmouseover=\"return overlib('To access this avatar use: ".$roster_conf['roster_dir']."/addons/siggen/av.php?name=".urlencode(utf8_decode($name))."',CAPTION,'Avatar Access');\" onmouseout=\"return nd();\" ".
		"src=\"".getlink($module_name.'&amp;file=addon&amp;roster_addon_name=siggen&amp;mode=avatar&amp;member='.urlencode(utf8_decode($name)).'&amp;saveonly=0')."\" alt=\"Avatar Image for $name\" />\n";

?>
<br /><br />
<table border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td align="left">
<?php

switch ($action)
{
	case 'character':
		$char->out();
		break;

	case 'bags':
		if( $roster_conf['show_inventory'] == 1 )
		{
			$bag0 = bag_get( $char, 'Bag0' );
			if( !is_null( $bag0 ) )
				echo $bag0->out();

			$bag1 = bag_get( $char, 'Bag1' );
			if( !is_null( $bag1 ) )
				echo $bag1->out();

			$bag2 = bag_get( $char, 'Bag2' );
			if( !is_null( $bag2 ) )
				echo $bag2->out();

			$bag3 = bag_get( $char, 'Bag3' );
			if( !is_null( $bag3 ) )
				echo $bag3->out();

			$bag4 = bag_get( $char, 'Bag4' );
			if( !is_null( $bag4 ) )
				echo $bag4->out();

			$bag5 = bag_get( $char, 'Bag5' );
			if( !is_null( $bag5 ) )
				echo $bag5->out();
		}
		break;

	case 'bank':
		if( $roster_conf['show_bank'] == 1 )
		{
			$bag0 = bag_get( $char, 'Bank Bag0' );
			if( !is_null( $bag0 ) )
				echo $bag0->out();

			$bag1 = bag_get( $char, 'Bank Bag1' );
			if( !is_null( $bag1 ) )
				echo $bag1->out();

			$bag2 = bag_get( $char, 'Bank Bag2' );
			if( !is_null( $bag2 ) )
				echo $bag2->out();

			$bag3 = bag_get( $char, 'Bank Bag3' );
			if( !is_null( $bag3 ) )
				echo $bag3->out();

			$bag4 = bag_get( $char, 'Bank Bag4' );
			if( !is_null( $bag4 ) )
				echo $bag4->out();

			$bag5 = bag_get( $char, 'Bank Bag5' );
			if( !is_null( $bag5 ) )
				echo $bag5->out();

			$bag6 = bag_get( $char, 'Bank Bag6' );
			if( !is_null( $bag6 ) )
				echo $bag6->out();

			$bag7 = bag_get( $char, 'Bank Bag7' );
			if( !is_null( $bag7 ) )
				echo $bag7->out();
		}
		break;

	case 'quests':
		if( $roster_conf['show_quests'] == 1 )
		    $url .= '&amp;action=quests';
			echo $char->show_quests();
		break;

	case 'recipes':
		if( $roster_conf['show_recipes'] == 1 )
		    $url .= '&amp;action=recipes';
			print $char->show_recipes();
		break;

	case 'bg':
	    if ( $roster_conf['show_bg'] == 1 )
		{
		    $url .= '&amp;action=bg';
		    echo $char->show_pvp2('BG', $url, $sort, $start);
		}
		break;

	case 'pvp':
		if( $roster_conf['show_pvp'] == 1 )
		{
			$url .= '&amp;action=pvp';
			echo $char->show_pvp2('PvP', $url, $sort, $start);
		}
		break;

	case 'duels':
		if( $roster_conf['show_duels'] == 1 )
		{
			$url .= '&amp;action=duels';
			echo $char->show_pvp2('Duel', $url, $sort, $start);
		}
		break;

	case 'spellbook':
		if( $roster_conf['show_spellbook'] == 1 )
		{
	      $url .= '&amp;action=spellbook';
	      echo $char->show_spellbook();
		}
		break;

	case 'mail':
		if( $roster_conf['show_mail'] == 1 )
		{
	      $url .= '&amp;action=mail';
	      echo $char->show_mailbox();
		}
		break;

	default:
		$char->out();
		break;
}


print "</td></tr></table>\n";

if ($action == 'character' && $roster_conf['show_item_bonuses'])
{
	echo dumpBonuses($name, $server);
}

print "</div>\n";
