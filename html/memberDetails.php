<?php
require_once 'conf.php';
require_once 'lib/char.php';
require_once 'lib/bag.php';

$wowdb->connect( $db_host, $db_user, $db_passwd, $db_name );

$name = $_REQUEST['name'];
$server = $_REQUEST['server'];
//$start = $_REQUEST['start'];
$start = $_REQUEST['start']?$_REQUEST['start']:0;
if (isset($_GET['action'])) {
	$action = $_REQUEST['action'];
} else {
	$action = '';
}
if (isset($_GET['s'])) {
	$sort = $_REQUEST['s'];
} else {
	$sort = '';
}
if (isset($_GET['sort'])) {
	$sort_recipe = $_REQUEST['sort'];
} else {
	$sort_recipe = '';
}

if( get_magic_quotes_gpc() ) {
	$name = stripslashes( $name );
	$server = stripslashes( $server );
}

$url = '<a href="?name='.$name.'&server='.$server;
$menuCell = '<td class="membersHeader" align="center">';
?>

<table border="0" cellpadding="0" cellspacing="0">
	<th bgcolor="#000000" colspan="10" class="rankbordertop">
		<span class="rankbordertopleft"></span>
		<span class="rankbordertopright"></span>
	</th>
	<tr><td bgcolor="#000000" class="rankbordercenterleft"></td>

<?
echo $menuCell.'<a href="./index.php">'.$wordings[$roster_lang]['backlink'].'</a></td>'."\n";
echo $menuCell.$url.'&action=character">'.$wordings[$roster_lang]['character'].' Stats</a></td>'."\n";
if( $show_inventory ) { echo $menuCell.$url.'&action=bags">'.$wordings[$roster_lang]['bags'].'</a></td>'."\n"; }
if( $show_bank ) { echo $menuCell.$url.'&action=bank">'.$wordings[$roster_lang]['bank'].'</a></td>'."\n"; }
if( $show_quests ) { echo $menuCell.$url.'&action=quests">'.$wordings[$roster_lang]['quests'].'</a></td>'."\n"; }
if( $show_recipes ) { echo $menuCell.$url.'&action=recipes">'.$wordings[$roster_lang]['recipes'].'</a></td>'."\n"; }
if( $show_pvp ) { echo $menuCell.$url.'&action=pvp&start=0">'.$wordings[$roster_lang]['pvplog'].'</a></td>'."\n"; }
if( $show_duels ) { echo $menuCell.$url.'&action=duels&start=0">'.$wordings[$roster_lang]['duellog'].'</a></td>'."\n"; }
?>

		<td bgcolor="#000000" class="rankbordercenterright"></td>
	</tr>
	<th bgcolor="#000000" colspan="10" class="rankborderbot">
		<span class="rankborderbotleft"></span>
		<span class="rankborderbotright"></span>
	</th>
</table>

<?php
$date_char_data_updated = DateDataUpdated($name);
print '<br><p class="lastupdated">'.$wordings[$roster_lang]['lastupdate'].': '.$date_char_data_updated.'</p>';
if($show_signature) print '<br><img src="./sig.php?name='.$name.'" alt="Signature Image for '.$name.'" border="0" />'; ?>

<br><br>
</div><div align="<?php print $char_bodyalign; ?>">
<table border="0" cellpadding="0" cellspacing="0" >
<tr><td align="left">

<?php
$char = char_get_one( $name, $server );

if ($action == '' or $action == 'character') {
	$char->out();
} elseif ($action == 'bags') {
	if( $show_inventory == 1 ) {
		$bag0 = bag_get( $char, 'Bag0' );
		if( isset( $bag0 ) ) {
			$bag0->out();
		}
		$bag1 = bag_get( $char, 'Bag1' );
		if( isset( $bag1 ) ) {
			$bag1->out();
		}
		$bag2 = bag_get( $char, 'Bag2' );
		if( isset( $bag2 ) ) {
			$bag2->out();
		}
		$bag3 = bag_get( $char, 'Bag3' );
		if( isset( $bag3 ) ) {
			$bag3->out();
		}
		$bag4 = bag_get( $char, 'Bag4' );
		if( isset( $bag4 ) ) {
			$bag4->out();
		}
	}
} elseif ($action == 'bank') {
	if( $show_bank == 1 ) {
		$bag0 = bag_get( $char, 'Bank Contents' );
		if( isset( $bag0 ) ) {
			$bag0->out();
		}
		$bag1 = bag_get( $char, 'Bank Bag1' );
		if( isset( $bag1 ) ) {
			$bag1->out();
		}
		$bag2 = bag_get( $char, 'Bank Bag2' );
		if( isset( $bag2 ) ) {
			$bag2->out();
		}
		$bag3 = bag_get( $char, 'Bank Bag3' );
		if( isset( $bag3 ) ) {
			$bag3->out();
		}
		$bag4 = bag_get( $char, 'Bank Bag4' );
		if( isset( $bag4 ) ) {
			$bag4->out();
		}
		$bag5 = bag_get( $char, 'Bank Bag5' );
		if( isset( $bag5 ) ) {
			$bag5->out();
		}
		$bag6 = bag_get( $char, 'Bank Bag6' );
		if( isset( $bag6 ) ) {
			$bag6->out();
		}
	}
} elseif ($action == 'quests') {
	if( $show_quests == 1 ) {
		$char->show_quests();
	}
} elseif ($action == 'recipes') {
	if( $show_recipes == 1 ) {
		$char->show_recipes();
	}
} elseif ($action == 'pvp') {
	if( $show_pvp == 1 ) {
		$url = $url.'&action=pvp';
		$char->show_pvp2('PvP', $url, $sort, $start);
	}
} elseif ($action == 'duels') {
	if( $show_duels == 1 ) {
		$url = $url.'&action=duels';
		$char->show_pvp2('Duel', $url, $sort, $start);
	}
}
?>