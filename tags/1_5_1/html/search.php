<?php
require_once 'conf.php';
require_once 'lib/wowdb.php';
require_once 'lib/item.php';
require_once 'lib/recipes.php';

$link = mysql_connect($db_host, $db_user, $db_passwd) or die ("Could not connect to desired database.");
mysql_select_db($db_name) or die ("Could not select desired database");

$server_name_escape = $wowdb->escape($server_name);
$guild_name_escape = $wowdb->escape($guild_name);
$query = "SELECT guild_id, DATE_FORMAT(update_time, '".$timeformat[$roster_lang]."') FROM `".ROSTER_GUILDTABLE."` WHERE guild_name= '$guild_name_escape' and server='$server_name_escape'";
$result = mysql_query($query) or die(mysql_error());
if ($row = mysql_fetch_row($result)) {
	$guildId = $row[0];
	$updateTime = $row[1];
} else {
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");
}

include 'lib/menu.php';
print "<br>\n";
?>

<form action="<? echo $_SERVER['PHP_SELF']?>">
<? print $wordings[$roster_lang]['find'] ?>:<br>
<input type="text" name="s" size="30" maxlength="30">
<input type="submit" value="search">
</form>

<?
if (isset($_GET['s'])) {
	$search = $_GET['s'];
	print '<table border="0" cellpadding="0" cellspacing="0" width="80%">'."\n";
	print '<tr><td class="membersRow2"><center><b>'.$wordings[$roster_lang]['items'].'</b></center></td></tr><tr><td>'."\n";
	$query="SELECT players.name,players.server,items.* FROM `".ROSTER_ITEMSTABLE."` items,`".ROSTER_PLAYERSTABLE."` players WHERE items.member_id = players.member_id AND items.item_name LIKE '%$search%' ORDER BY players.name ASC";
	$result = $wowdb->query( $query );
	$cid = '';
	while ($data = $wowdb->getrow( $result )) {
		$bagtype = substr($data['item_parent'], 0, 3);
		if ( $bagtype == 'Ban' ) {
			$action = 'bank';
		} elseif (  $bagtype == 'Bag' ) {
			$action = 'bags';
		} else {
			$action = '';
		}

		$char_url = 'char.php?name='.$data['name'].'&amp;server='.$data['server'];

		if ( $cid != $data['member_id'] ) {
			if ( $cid != '' )  { print "</table>\n<br>\n"; }
			print '<table border="0" cellpadding="0" cellspacing="0" width="100%">'."\n";
			print '<tr><td colspan="2" class="membersRow2"><a href="'.$char_url.'">'.$data['name'].'</a></td></tr>'."\n";
		}

		print '<tr><td width="90" valign="top"><div class="bagSlot">';
		if ($action) { print '<a href="'.$char_url.'&amp;action='.$action.'">'; }
		$item = new item($data);
		$item->out();
		if ($action) { print '</a>'; }
		print '</div>';
		print '</td>'."\n";
		print '<td valign="top">';

		$first_line = True;
		foreach (explode("\n", $data['item_tooltip']) as $line ) { 
			$class='tooltipline'; 
			if( $first_line ) { 
				$color = substr( $data['item_color'], 2, 6 ); 
				$first_line = False; 
				$class='tooltipheader'; 
			} else { 
				if( substr( $line, 0, 2 ) == '|c' ) { 
					$color = substr( $line, 4, 6 ); 
					$line = substr( $line, 10, -2 ); 
				} else if ( substr( $line, 0, 4 ) == 'Use:' ) { 
					$color = '00ff00'; 
				} else { 
					$color='ffffff'; 
				}
			}
			$line = preg_replace('|\\>|','&gt;', $line ); 
			$line = preg_replace('|\\<|','&lt;', $line ); 
			if( $line == '' ) { 
				$line = '&nbsp;'; 
			}
			echo "<span class=\"$class\" style=\"color:#$color\">$line</span><br>";
		}
		print "</td></tr>\n";
		$cid = $data['member_id'];
	}
	if ( $cid != '' )  { print "</table>\n<br>\n"; }

	print '</td></tr><tr><td class="membersRow2"><center><b>'.$wordings[$roster_lang]['recipes'].'</b></center></td></tr><tr><td>'."\n";
	//$query="SELECT players.name,players.server,recipes.* FROM recipes,players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' OR recipes.recipe_tooltip LIKE '%$search%' OR recipes.reagents LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$query="SELECT players.name,players.server,recipes.* FROM `".ROSTER_RECIPESTABLE."` recipes,`".ROSTER_PLAYERSTABLE."` players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$result = $wowdb->query( $query );
	$cid = '';
//name | server | member_id | recipe_name | skill_name | difficulty | reagents | recipe_texture | recipe_tooltip
	while ($data = $wowdb->getrow( $result )) {
		$char_url = 'char.php?name='.$data['name'].'&amp;server='.$data['server'];
		if ( $cid != $data['member_id'] ) {
			if ( $cid != '' )  { print "</table>\n<br>\n"; }
			print '<table border="0" cellpadding="0" cellspacing="0" width="100%">'."\n";
			print '<tr><td colspan="3" class="membersRow2"><a href="'.$char_url.'">'.$data['name'].'</a></td>'."\n";
		}

		print '<tr><td valign="top" width="90"><div class="bagSlot">';
		print '<a href="'.$char_url.'&amp;action=recipes">';
		$recipe = new recipe($data);
		if( $recipe->data['difficulty'] == '4' ) {
			$difficultycolor = 'FF9900';
		} elseif( $recipe->data['difficulty'] == '3' ) {
			$difficultycolor = 'FFFF66';
		} elseif( $recipe->data['difficulty'] == '2' ) {
			$difficultycolor = '339900';
		} elseif( $recipe->data['difficulty'] == '1' ) {
			$difficultycolor = 'CCCCCC';
		} else { $difficultycolor = 'FFFF80'; }
		$recipe->out($difficultycolor);
		print '</div>';
		print '</a>';
		print '</td>'."\n";
		print '<td valign="top">';

		$first_line = True;
		foreach (explode("\n", $data['recipe_tooltip']) as $line ) { 
			$class='tooltipline'; 
			if( $first_line ) { 
				//$color = substr( $data['item_color'], 2, 6 ); 
				$color = 'ffffff';
				$first_line = False; 
				$class='tooltipheader'; 
			} else { 
				if( substr( $line, 0, 2 ) == '|c' ) { 
					$color = substr( $line, 4, 6 ); 
					$line = substr( $line, 10, -2 ); 
				} else if ( substr( $line, 0, 4 ) == 'Use:' ) { 
					$color = '00ff00'; 
				} else { 
					$color='ffffff'; 
				}
			}
			$line = preg_replace('|\\>|','&gt;', $line ); 
			$line = preg_replace('|\\<|','&lt;', $line ); 
			if( $line == '' ) { 
				$line = '&nbsp;'; 
			}
			echo "<span class=\"$class\" style=\"color:#$color\">$line</span><br>";
		}
		print '</td>'."\n".'<td class="reagentsRow1" width="50%" valign="top">';
		echo "<span class=\"tooltipline\" style=\"color:#ffffff\">".$data['reagents']."</span><br><br>";
		print "</td></tr>\n";
		$cid = $data['member_id'];
	}
	if ( $cid != '' )  { print "</table>\n<br>\n"; }
	print "</td></tr></table>\n";
}
?>