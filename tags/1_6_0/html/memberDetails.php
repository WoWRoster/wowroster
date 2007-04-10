<?php
$versions['versionDate']['memberDetails'] = '$Date: 2006/02/04 05:08:08 $'; 
$versions['versionRev']['memberDetails'] = '$Revision: 1.20 $'; 
$versions['versionAuthor']['memberDetails'] = '$Author: zanix $';

require_once 'conf.php';
require_once 'lib/char.php';
require_once 'lib/bag.php';
require_once 'lib/wowdb.php';

$wowdb->connect( $db_host, $db_user, $db_passwd, $db_name );


$name = $_REQUEST['name'];

if( isset($_REQUEST['server']) ) 
	$server = $_REQUEST['server']; 
else 
	$server = $server_name;
        
if ( $server_name_comp == 0 ) {
	$server_name_escape = $_REQUEST['server'];
}
elseif ( $server_name_comp == 1 ) {
    $server_name_escape = $wowdb->escape($server_name);
}

$start = $_REQUEST['start'] ? $_REQUEST['start'] : 0;

//$server_name_escape = $wowdb->escape($server);

if( isset($_GET['action']) )
	$action = $_REQUEST['action'];
else
	$action = '';

if (isset($_GET['s']))
	$sort = $_REQUEST['s'];
else
	$sort = '';

if (isset($_GET['sort']))
	$sort_recipe = $_REQUEST['sort'];
else
	$sort_recipe = '';


if( get_magic_quotes_gpc() )
{
	$name = stripslashes( $name );
	$server = stripslashes( $server_name_escape );
}


$url = '<a href="?name='.$name.'&amp;server='.$server;

$menu_cell = '      <td class="menubarHeader" align="center" valign="middle">';
?>
<div align="center">
  <table cellpadding="3" cellspacing="0" class="menubar">
	  <tr>
      <td colspan="11" class="rankbordertop"><span class="rankbordertopleft"></span><span class="rankbordertopright"></span></td>
    </tr>
    <tr>
      <td bgcolor="#000000" class="rankbordercenterleft"></td>
<?php

echo $menu_cell.'<a href="./index.php">'.$wordings[$roster_lang]['backlink'].'</a></td>'."\n";
echo $menu_cell.$url.'&amp;action=character">'.$wordings[$roster_lang]['character'].' Stats</a></td>'."\n";

if( $show_inventory )
	echo $menu_cell.$url.'&amp;action=bags">'.$wordings[$roster_lang]['bags'].'</a></td>'."\n";
if( $show_bank )
	echo $menu_cell.$url.'&amp;action=bank">'.$wordings[$roster_lang]['bank'].'</a></td>'."\n";
if( $show_quests )
	echo $menu_cell.$url.'&amp;action=quests">'.$wordings[$roster_lang]['quests'].'</a></td>'."\n";
if( $show_recipes )
	echo $menu_cell.$url.'&amp;action=recipes">'.$wordings[$roster_lang]['recipes'].'</a></td>'."\n";
if ( $show_bg )
     echo $menu_cell.$url.'&amp;action=bg">'.$wordings[$roster_lang]['bglog'].'</a></td>'."\n";
if( $show_pvp )
	echo $menu_cell.$url.'&amp;action=pvp&amp;start=0">'.$wordings[$roster_lang]['pvplog'].'</a></td>'."\n";
if( $show_duels )
	echo $menu_cell.$url.'&amp;action=duels&amp;start=0">'.$wordings[$roster_lang]['duellog'].'</a></td>'."\n";

?>
      <td bgcolor="#000000" class="rankbordercenterright"></td>
    </tr>
	  <tr>
      <td colspan="11" class="rankborderbot"><span class="rankborderbotleft"></span><span class="rankborderbotright"></span></td>
    </tr>
  </table>
</div>

<div align="<?php print $char_bodyalign; ?>">
<?php

$date_char_data_updated = DateCharDataUpdated($name);
print '<br />
<p class="lastupdated">'.$wordings[$roster_lang]['lastupdate'].': '.$date_char_data_updated."</p>\n";

if($show_signature)
	print "<span onMouseover=\"return overlib('Signature Access<br />Use: $roster_dir/addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."<br />To access this signature',RIGHT);\" onMouseout=\"return nd();\">".
				"<img src=\"./addons/siggen/sig.php?name=".urlencode(utf8_decode($name))."&amp;saveonly=0\" alt=\"Signature Image for $name\" /></span>&nbsp;\n";
if($show_avatar)
	print "<span onMouseover=\"return overlib('Avatar Access<br />Use: $roster_dir/addons/siggen/av.php?name=".urlencode(utf8_decode($name))."<br />To access this avatar',RIGHT);\" onMouseout=\"return nd();\">".
				"<img src=\"./addons/siggen/av.php?name=".urlencode(utf8_decode($name))."&amp;saveonly=0\" alt=\"Avatar Image for $name\" /></span>\n";

?>
<br /><br />
<table border="0" cellpadding="0" cellspacing="0" >
  <tr>	
    <td align="left">
<?php

$char = char_get_one( $name, $server );

if ($action == '' or $action == 'character') 
	$char->out();
elseif ($action == 'bags')
{
	if( $show_inventory == 1 )
	{
		$bag0 = bag_get( $char, 'Bag0' );
		if( isset( $bag0 ) )
			echo $bag0->out();

		$bag1 = bag_get( $char, 'Bag1' );
		if( isset( $bag1 ) )
			echo $bag1->out();

		$bag2 = bag_get( $char, 'Bag2' );
		if( isset( $bag2 ) )
			echo $bag2->out();

		$bag3 = bag_get( $char, 'Bag3' );
		if( isset( $bag3 ) )
			echo $bag3->out();

		$bag4 = bag_get( $char, 'Bag4' );
		if( isset( $bag4 ) )
			echo $bag4->out();

		echo "</td>\n  </tr>\n</table>";
	}
}
elseif ($action == 'bank')
{
	if( $show_bank == 1 )
	{
		$bag0 = bag_get( $char, 'Bank Contents' );
		if( isset( $bag0 ) )
			echo $bag0->out();

		$bag1 = bag_get( $char, 'Bank Bag1' );
		if( isset( $bag1 ) )
			echo $bag1->out();

		$bag2 = bag_get( $char, 'Bank Bag2' );
		if( isset( $bag2 ) )
			echo $bag2->out();

		$bag3 = bag_get( $char, 'Bank Bag3' );
		if( isset( $bag3 ) )
			echo $bag3->out();

		$bag4 = bag_get( $char, 'Bank Bag4' );
		if( isset( $bag4 ) )
			echo $bag4->out();

		$bag5 = bag_get( $char, 'Bank Bag5' );
		if( isset( $bag5 ) )
			echo $bag5->out();

		$bag6 = bag_get( $char, 'Bank Bag6' );
		if( isset( $bag6 ) )
			echo $bag6->out();

		echo "</td>\n  </tr>\n</table>";
	}
}
elseif ($action == 'quests')
{
	if( $show_quests == 1 )
		echo $char->show_quests();
}
elseif ($action == 'recipes')
{
	if( $show_recipes == 1 )
		print $char->show_recipes();
}
elseif ($action == 'bg')
{
    if ( $show_bg == 1 )
	{
	    $url = $url.'&amp;action=bg';
	    echo $char->show_pvp2('BG', $url, $sort, $start);
	}
}
elseif ($action == 'pvp')
{
	if( $show_pvp == 1 )
	{
		$url = $url.'&amp;action=pvp';
		echo $char->show_pvp2('PvP', $url, $sort, $start);
	}
}
elseif ($action == 'duels')
{
	if( $show_duels == 1 )
	{
		$url = $url.'&amp;action=duels';
		echo $char->show_pvp2('Duel', $url, $sort, $start);
	}
}

if ($show_advancedstats)
{
	if ($action == '' or $action == 'character')
	{
  		echo dumpBonuses($name, $server); 
	}
}
?>
