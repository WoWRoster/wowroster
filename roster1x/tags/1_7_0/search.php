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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

require_once ROSTER_BASE.'lib'.DIR_SEP.'item.php';
require_once ROSTER_BASE.'lib'.DIR_SEP.'recipes.php';


//---[ Check for Guild Info ]------------
$guild_info = $wowdb->get_guild_info($roster_conf['server_name'],$roster_conf['guild_name']);
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];



include_once(ROSTER_BASE.'lib'.DIR_SEP.'menu.php');
print "<br />\n";

if (isset($_GET['s']))
{
	$inputbox_value = $_GET['s'];
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>">
  <?php print $wordings[$roster_conf['roster_lang']]['find'] ?>:<br />
  <input type="text" name="s" value="<?php print $inputbox_value; ?>" size="30" maxlength="30">
  <input type="submit" value="search">
</form>

<?php
if (isset($_GET['s']))
{
	// Set a ank for link to top of page
	echo '<a name="top">&nbsp;</a>
<p style="color:white;text-align;center">
  <a href="#items">'.$wordings[$roster_conf['roster_lang']]['items'].'</a>
  - <a href="#recipes">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a>
</p><br /><br />';

	$search = $_GET['s'];
	print border('rank','start','<a name="items"></a><a href="#top">'.$wordings[$roster_conf['roster_lang']]['items'].'</a>').
		'<table cellpadding="0" cellspacing="0" width="600" class="bodyline">
  <tr>
    <td>';
	$query="SELECT players.name,players.server,items.* FROM `".ROSTER_ITEMSTABLE."` items,`".ROSTER_PLAYERSTABLE."` players WHERE items.member_id = players.member_id AND items.item_name LIKE '%$search%' ORDER BY players.name ASC";
	$result = $wowdb->query( $query );

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$char_url = 'char.php?name='.$data['name'].'&amp;server='.$data['server'];

			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n<br />\n";
				}
				print '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td colspan="2" class="membersHeaderRight"><a href="'.$char_url.'">'.$data['name'].'</a></td>
  </tr>';
			}

			print '  <tr>
    <td width="45" class="membersRow1">';
			$item = new item($data);
			echo $item->out();
			print "</td>\n";
			print '    <td valign="middle" class="membersRowRight1" style="white-space:normal;">';

			$first_line = True;
			foreach (explode("\n", $data['item_tooltip']) as $line ) {
				if( $first_line )
				{
					$color = substr( $data['item_color'], 2, 6 );
					$first_line = False;
				}
				else
				{
					if( substr( $line, 0, 2 ) == '|c' )
					{
						$color = substr( $line, 4, 6 );
						$line = substr( $line, 10, -2 );
					}
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) === 0 )
						$color = 'ff0000; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) === 0 )
						$color = '00bbff; font-size: 10px;';
					elseif ( strpos( $line, '"' ) )
						$color = 'ffd517; font-size: 10px;';
					else
						$color='ffffff; font-size: 10px;';
				}
				$line = preg_replace('|\\>|','&gt;', $line );
				$line = preg_replace('|\\<|','&lt;', $line );
				if( $line == '' )
					$line = '&nbsp;';
				echo "<span style=\"color:#$color\">$line</span><br />";
			}
			print "</td>\n  </tr>\n";
			$cid = $data['member_id'];
		}

		if ( $cid != '' )
		{
			print "</table>\n";
		}
	}
	else
	{
		print '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$wordings[$roster_conf['roster_lang']]['items'].'</td>
  </tr>'."</table>\n";
	}


	print "</td></tr></table>".border('rank','end');

	print "<br />\n";

	print border('rank','start','<a name="recipes"></a><a href="#top">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a>').
		'<table cellpadding="0" cellspacing="0" width="600" class="bodyline">
<tr>
  <td>';
	//$query="SELECT players.name,players.server,recipes.* FROM recipes,players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' OR recipes.recipe_tooltip LIKE '%$search%' OR recipes.reagents LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$query="SELECT players.name,players.server,recipes.* FROM `".ROSTER_RECIPESTABLE."` recipes,`".ROSTER_PLAYERSTABLE."` players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$result = $wowdb->query( $query );

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
	//name | server | member_id | recipe_name | skill_name | difficulty | reagents | recipe_texture | recipe_tooltip
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$char_url = 'char.php?name='.$data['name'].'&amp;server='.$data['server'].'&amp;action=recipes';
			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n<br />\n";
				}
				print '<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td colspan="3" class="membersHeaderRight"><a href="'.$char_url.'">'.$data['name'].'</a></td>';
			}

			print '<tr><td width="45" valign="top" align="center" class="membersRow1">';

			$recipe = new recipe($data);
			echo $recipe->out();
				print '</td>'."\n";
			print '<td valign="top" class="membersRow1" style="white-space:normal;">';

			$first_line = True;
			foreach (explode("\n", $data['recipe_tooltip']) as $line )
			{
				if( $first_line )
				{
					$color = substr( $data['item_color'], 2, 6 );
					$first_line = False;
				}
				else
				{
					if( substr( $line, 0, 2 ) == '|c' )
					{
						$color = substr( $line, 4, 6 );
						$line = substr( $line, 10, -2 );
					}
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) === 0 )
						$color = 'ff0000; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) === 0 )
						$color = '00ff00; font-size: 10px;';
					else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) === 0 )
						$color = '00bbff; font-size: 10px;';
					elseif ( strpos( $line, '"' ) )
						$color = 'ffd517; font-size: 10px;';
					else
						$color='ffffff; font-size: 10px;';
				}
				$line = preg_replace('|\\>|','&gt;', $line );
				$line = preg_replace('|\\<|','&lt;', $line );
				if( $line == '' )
					$line = '&nbsp;';

				echo "<span style=\"color:#$color\">$line</span><br />";
			}
			print '</td>'."\n".'<td class="membersRowRight1" width="50%" valign="top">';
			echo "<span class=\"tooltipline\" style=\"color:#ffffff\">".$data['reagents']."</span><br /><br />";
			print "</td></tr>\n";
			$cid = $data['member_id'];
		}

		if ( $cid != '' )
		{
			print "</table>\n";
		}
	}
	else
	{
		print '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$wordings[$roster_conf['roster_lang']]['recipes'].'</td>
  </tr>'."</table>\n";
	}

	print "</td></tr></table>".border('rank','end');
}
?>