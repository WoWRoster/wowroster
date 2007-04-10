<?php
$versions['versionDate']['search'] = '$Date: 2006/02/03 23:41:01 $'; 
$versions['versionRev']['search'] = '$Revision: 1.10 $'; 
$versions['versionAuthor']['search'] = '$Author: zanix $';

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
if ($row = mysql_fetch_row($result))
{
	$guildId = $row[0];
	$updateTime = $row[1];
} else
	die("Could not find guild:'$guild_name' for server '$server_name'. You need to load your guild first and make sure you finished configuration.");


include 'lib/menu.php';
print "<br />\n";

if (isset($_GET['s']))
	$inputbox_value = $_GET['s'];
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>">
  <?php print $wordings[$roster_lang]['find'] ?>:<br />
  <input type="text" name="s" value="<?php print $inputbox_value; ?>" size="30" maxlength="30">
  <input type="submit" value="search">
</form>

<?php
if (isset($_GET['s']))
{
	// Set a ank for link to top of page
	echo '<a name="top">&nbsp;</a>
<p align="center">
  <a href="#'.$wordings[$roster_lang]['items'].'">'.$wordings[$roster_lang]['items'].'</a>&nbsp;-&nbsp;
  <a href="#'.$wordings[$roster_lang]['recipes'].'">'.$wordings[$roster_lang]['recipes'].'</a>
</p><br /><br />
';

	$search = $_GET['s'];
	print '<table cellpadding="0" cellspacing="2" width="80%">
  <tr class="membersHeader">
    <td><a name="'.$wordings[$roster_lang]['items'].'"></a><div align="center"><strong>'.$wordings[$roster_lang]['items'].'</strong></div></td>
  </tr>
  <tr>
    <td>
';
	$query="SELECT players.name,players.server,items.* FROM `".ROSTER_ITEMSTABLE."` items,`".ROSTER_PLAYERSTABLE."` players WHERE items.member_id = players.member_id AND items.item_name LIKE '%$search%' ORDER BY players.name ASC";
	$result = $wowdb->query( $query );
	$cid = '';
	while ($data = $wowdb->getrow( $result ))
	{
		$char_url = 'char.php?name='.$data['name'].'&amp;server='.$data['server'];

		if ( $cid != $data['member_id'] )
		{
			if ( $cid != '' )
				print "</table>\n<br />\n";
			print '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td colspan="2" class="membersHeaderRight"><a href="'.$char_url.'">'.$data['name'].'</a></td>
  </tr>
';
		}

		print '  <tr>
    <td width="45" class="membersRow1">';
		$item = new item($data);
		echo $item->out();
		print "</td>\n";
		print '    <td valign="top" class="membersRowRight1">';

		$first_line = True;
		foreach (explode("\n", $data['item_tooltip']) as $line ) {
			$class='tooltipline';
			if( $first_line )
			{
				$color = substr( $data['item_color'], 2, 6 ); 
				$first_line = False; 
				$class='tooltipheader'; 
			}
			else
			{
				if( substr( $line, 0, 2 ) == '|c' )
				{
					$color = substr( $line, 4, 6 ); 
					$line = substr( $line, 10, -2 ); 
				}
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_use'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_requires'] ) === 0 )
					$color = 'ff0000; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_reinforced'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_equip'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_chance'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_enchant'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_soulbound'] ) === 0 )
					$color = '00ffff; font-size: 10px;';
				else
					$color='ffffff; font-size: 10px;';
			}
			$line = preg_replace('|\\>|','&gt;', $line );
			$line = preg_replace('|\\<|','&lt;', $line );
			if( $line == '' )
				$line = '&nbsp;'; 
			echo "<span class=\"$class\" style=\"color:#$color\">$line</span><br />";
		}
		print "</td>\n  </tr>\n";
		$cid = $data['member_id'];
	}
	if ( $cid != '' )
		print "</table>\n<br />\n";

	print '</td>
</tr>
<tr class="membersHeader">
  <td><a name="'.$wordings[$roster_lang]['recipes'].'"></a><div align="center"><strong>'.$wordings[$roster_lang]['recipes'].'</strong></div></td>
</tr>
<tr>
  <td>
';
	//$query="SELECT players.name,players.server,recipes.* FROM recipes,players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' OR recipes.recipe_tooltip LIKE '%$search%' OR recipes.reagents LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$query="SELECT players.name,players.server,recipes.* FROM `".ROSTER_RECIPESTABLE."` recipes,`".ROSTER_PLAYERSTABLE."` players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$result = $wowdb->query( $query );
	$cid = '';
//name | server | member_id | recipe_name | skill_name | difficulty | reagents | recipe_texture | recipe_tooltip
	while ($data = $wowdb->getrow( $result )) {
		$char_url = 'char.php?name='.$data['name'].'&amp;server='.$data['server'].'&amp;action=recipes';
		if ( $cid != $data['member_id'] ) {
			if ( $cid != '' )  { print "</table>\n<br />\n"; }
			print '<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td colspan="3" class="membersHeaderRight"><a href="'.$char_url.'">'.$data['name'].'</a></td>
';
		}

		print '<tr><td width="45" valign="top" align="center" class="membersRow1">';

		$recipe = new recipe($data);
		if( $recipe->data['difficulty'] == '4' )
			$difficultycolor = 'FF9900';
		elseif( $recipe->data['difficulty'] == '3' )
			$difficultycolor = 'FFFF66';
		elseif( $recipe->data['difficulty'] == '2' )
			$difficultycolor = '339900';
		elseif( $recipe->data['difficulty'] == '1' )
			$difficultycolor = 'CCCCCC';
		else
			$difficultycolor = 'FFFF80';
		echo $recipe->out();
			print '</td>'."\n";
		print '<td valign="top" class="membersRow1">';

		$first_line = True;
		foreach (explode("\n", $data['recipe_tooltip']) as $line )
		{
			$class='tooltipline'; 
			if( $first_line )
			{
				$color = 'ffffff';
				$first_line = False; 
				$class='tooltipheader'; 
			}
			else
			{
				if( substr( $line, 0, 2 ) == '|c' )
				{
					$color = substr( $line, 4, 6 ); 
					$line = substr( $line, 10, -2 ); 
				}
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_use'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_requires'] ) === 0 )
					$color = 'ff0000; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_reinforced'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_equip'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_chance'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_enchant'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_soulbound'] ) === 0 )
					$color = '00ffff; font-size: 10px;';
				else
					$color='ffffff; font-size: 10px;';
			}
			$line = preg_replace('|\\>|','&gt;', $line ); 
			$line = preg_replace('|\\<|','&lt;', $line ); 
			if( $line == '' )
				$line = '&nbsp;'; 

			echo "<span class=\"$class\" style=\"color:#$color\">$line</span><br />";
		}
		print '</td>'."\n".'<td class="membersRowRight1" width="50%" valign="top">';
		echo "<span class=\"tooltipline\" style=\"color:#ffffff\">".$data['reagents']."</span><br /><br />";
		print "</td></tr>\n";
		$cid = $data['member_id'];
	}
	if ( $cid != '' )  { print "</table>\n<br />\n"; }
	print "</td></tr></table>\n";
}
?>