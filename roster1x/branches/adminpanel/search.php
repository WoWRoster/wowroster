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

$header_title = $wordings[$roster_conf['roster_lang']]['search'];
include_once (ROSTER_BASE.'roster_header.tpl');

require_once ROSTER_LIB.'item.php';
require_once ROSTER_LIB.'recipes.php';


//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];



echo $roster_menu->makeMenu('main');
print "<br />\n";

if (isset($_GET['s']))
{
	$inputbox_value = $_GET['s'];
}
else
{
	$inputbox_value = '';
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
<div style="color:white;text-align;center">
  <a href="#items">'.$wordings[$roster_conf['roster_lang']]['items'].'</a>
  - <a href="#recipes">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a>
</div><br /><br />';

	$search = $_GET['s'];
	print '<a name="items"></a><a href="#top">'.$wordings[$roster_conf['roster_lang']]['items'].'</a>';
	$query="SELECT `players`.`name`, `players`.`server`, `items`.* FROM `".ROSTER_ITEMSTABLE."` AS items INNER JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `items`.`member_id` = `players`.`member_id` WHERE `items`.`item_name` LIKE '%$search%' ORDER BY `players`.`name` ASC";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching items. MySQL said: <br/>'.$wowdb->error(),'Search',basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
		$rc = 0;
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$row_st = (($rc%2)+1);
			$char_url = 'char.php?member='.$data['member_id'];

			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n".border('sblue','end')."<br />\n";
				}
				print border('sblue','start','<a href="'.$char_url.'">'.$data['name'].'</a>').'<table cellpadding="0" cellspacing="0" width="600">';
			}

			print '  <tr>
    <td width="45" valign="top" class="membersRow'.$row_st.'">';
			$item = new item($data);
			echo $item->out();
			print "</td>\n";
			print '    <td valign="middle" class="membersRowRight'.$row_st.'" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['item_tooltip']);
			foreach (explode("\n", $data['item_tooltip']) as $line )
			{
				$color = '';

				if( !empty($line) )
				{
					$line = preg_replace('|\\>|','&#8250;', $line );
					$line = preg_replace('|\\<|','&#8249;', $line );
					$line = preg_replace('|\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r|','<span style="color:#$1;">$2</span>',$line);

					// Do this on the first line
					// This is performed when $caption_color is set
					if( $first_line )
					{
						if( $data['item_color'] == '' )
							$data['item_color'] = 'ffffff';

						if( strlen($data['item_color']) > 6 )
							$color = substr( $data['item_color'], 2, 6 ) . ';font-size:12px;font-weight:bold';
						else
							$color = $data['item_color'] . ';font-size:12px;font-weight:bold';

						$first_line = false;
					}
					else
					{
						if ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_use'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_requires'],$line) )
							$color = 'ff0000;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_reinforced'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_equip'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_chance'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_enchant'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_soulbound'],$line) )
							$color = '00bbff;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_set'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( preg_match('|\([a-f0-9]\).'.$wordings[$roster_conf['roster_lang']]['tooltip_set'].'|',$line) )
							$color = '666666;font-size:10px';
						elseif ( ereg('^\\"',$line) )
							$color = 'ffd517;font-size:10px';
					}

					// Convert tabs to a formated table
					if( strpos($line,"\t") )
					{
						$line = str_replace("\t",'</td><td align="right" class="overlib_maintext">', $line);
						$line = '<table width="100%" cellspacing="0" cellpadding="0"><tr><td class="overlib_maintext">'.$line.'</td></tr></table>';
						$tooltip_out .= $line;
					}
					elseif( !empty($color) )
					{
						$tooltip_out .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
					}
					else
					{
						$tooltip_out .= "$line<br />";
					}
				}
				else
				{
					$tooltip_out .= '<br />';
				}
			}
			print $tooltip_out;
			print "</td>\n  </tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if ( $cid != '' )
		{
			print "</table>\n".border('sblue','end')."<br />\n";
		}
	}
	else
	{
		print '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$wordings[$roster_conf['roster_lang']]['items'].'</td>
  </tr>'."</table>\n";
	}


	print "<br /><hr />\n";

	print '<a name="recipes"></a><a href="#top">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a>';

	//$query="SELECT players.name,players.server,recipes.* FROM recipes,players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' OR recipes.recipe_tooltip LIKE '%$search%' OR recipes.reagents LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$query="SELECT `players`.`name`, `players`.`server`, `recipes`.* FROM `".ROSTER_RECIPESTABLE."` AS recipes INNER JOIN `".ROSTER_PLAYERSTABLE."` AS players ON `recipes`.`member_id` = `players`.`member_id` WHERE `recipes`.`recipe_name` LIKE '%$search%' ORDER BY `players`.`name` ASC, `recipes`.`recipe_name` ASC";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching recipes. MySQL said: <br/>'.$wowdb->error(),'Search',basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
	//name | server | member_id | recipe_name | skill_name | difficulty | reagents | recipe_texture | recipe_tooltip
		$rc = 0;
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$row_st = (($rc%2)+1);

			$char_url = 'char.php?member='.$data['member_id'].'&amp;action=recipes';
			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n".border('syellow','end')."<br />\n";
				}
				print border('syellow','start','<a href="'.$char_url.'">'.$data['name'].'</a>').'<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <th colspan="2" class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['item'].'</th>
    <th class="membersHeaderRight">'.$wordings[$roster_conf['roster_lang']]['reagents'].'</th>';
			}

			print '<tr><td width="45" valign="top" align="center" class="membersRow'.$row_st.'">';

			$recipe = new recipe($data);
			echo $recipe->out();
				print '</td>'."\n";
			print '<td valign="top" class="membersRow'.$row_st.'" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['recipe_tooltip']);
			foreach (explode("\n", $data['recipe_tooltip']) as $line )
			{
				$color = '';

				if( !empty($line) )
				{
					$line = preg_replace('|\\>|','&#8250;', $line );
					$line = preg_replace('|\\<|','&#8249;', $line );
					$line = preg_replace('|\|c[a-f0-9]{2}([a-f0-9]{6})(.+?)\|r|','<span style="color:#$1;">$2</span>',$line);

					// Do this on the first line
					// This is performed when $caption_color is set
					if( $first_line )
					{
						if( $data['item_color'] == '' )
							$data['item_color'] = 'ffffff';

						if( strlen($data['item_color']) > 6 )
							$color = substr( $data['item_color'], 2, 6 ) . ';font-size:12px;font-weight:bold';
						else
							$color = $data['item_color'] . ';font-size:12px;font-weight:bold';

						$first_line = false;
					}
					else
					{
						if ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_use'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_requires'],$line) )
							$color = 'ff0000;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_reinforced'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_equip'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_chance'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_enchant'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_soulbound'],$line) )
							$color = '00bbff;font-size:10px';
						elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_set'],$line) )
							$color = '00ff00;font-size:10px';
						elseif ( preg_match('|\([a-f0-9]\).'.$wordings[$roster_conf['roster_lang']]['tooltip_set'].'|',$line) )
							$color = '666666;font-size:10px';
						elseif ( ereg('^\\"',$line) )
							$color = 'ffd517;font-size:10px';
					}

					// Convert tabs to a formated table
					if( strpos($line,"\t") )
					{
						$line = str_replace("\t",'</td><td align="right" class="overlib_maintext">', $line);
						$line = '<table width="100%" cellspacing="0" cellpadding="0"><tr><td class="overlib_maintext">'.$line.'</td></tr></table>';
						$tooltip_out .= $line;
					}
					elseif( !empty($color) )
					{
						$tooltip_out .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
					}
					else
					{
						$tooltip_out .= "$line<br />";
					}
				}
				else
				{
					$tooltip_out .= '<br />';
				}
			}
			print $tooltip_out;

			print '</td>'."\n".'<td class="membersRowRight'.$row_st.'" width="50%" valign="top">';
			echo "<span class=\"tooltipline\" style=\"color:#ffffff\">".$data['reagents']."</span><br /><br />";
			print "</td></tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if ( $cid != '' )
		{
			print "</table>\n".border('syellow','end')."<br />\n";
		}
	}
	else
	{
		print border('sblue','start').'<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$wordings[$roster_conf['roster_lang']]['recipes'].'</td>
  </tr>'."</table>\n".border('sblue','end');
	}

}


include_once (ROSTER_BASE.'roster_footer.tpl');
?>
