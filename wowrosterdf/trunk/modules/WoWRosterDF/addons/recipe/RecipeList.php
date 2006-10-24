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

require_once(ROSTER_BASE.'lib'.DIR_SEP.'recipes.php');


$server_name_escape = $wowdb->escape($roster_conf['server_name']);


$qry_prof  = "select distinct( skill_name) proff from ".ROSTER_RECIPESTABLE." where skill_name != '".$wordings[$roster_conf['roster_lang']]['First Aid']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['poisons']."' and skill_name != '".$wordings[$roster_conf['roster_lang']]['Mining']."' order by skill_name";

$result_prof = $wowdb->query($qry_prof) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_prof);
if ($roster_conf['sqldebug'])
{
	$content .= ("<!--$query -->\n");
}



$choiceForm = '<form action="'.getlink('&amp;file=addon&amp;roster_addon_name=recipe').'" method="post" name="myform">

	<table>
		<tr>
		<td class="copy">'.$wordings[$roster_conf['roster_lang']]['professionfilter'].'</td>
		<td class="copy"><select NAME="proffilter">';



while($row_prof = $wowdb->fetch_array($result_prof))
{
	if ($_REQUEST["proffilter"]==$row_prof["proff"])
		$choiceForm .= '<option VALUE="'.$row_prof["proff"].'" selected>'.$row_prof["proff"];
	else
		$choiceForm .= '<option VALUE="'.$row_prof["proff"].'">'.$row_prof["proff"];
}



$wowdb->free_result($result_prof);


$choiceForm .= '</select></td>
<td>'.$wordings[$roster_conf['roster_lang']]['search'].'</td><td><input type="text" name="filterbox"';
if (isset($_REQUEST['filterbox']))
{
	$choiceForm .= ' value="'.$_REQUEST['filterbox'].'"';
}

$choiceForm .= ' /></td>
		<td><input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['applybutton'].'" /></td>
	</tr>
</table>
</form><br />';

$content .=  $choiceForm;




if (isset($_REQUEST["proffilter"]))
{
	$recipes = recipe_get_all( $_REQUEST["proffilter"],($_REQUEST["filterbox"]?$_REQUEST["filterbox"]:''), ($_REQUEST["sort"]?$_REQUEST["sort"]:'type') );

	if( isset( $recipes[0] ) )
	{
		$rc = 0;

		$recipe_type = '';
		$first_table = true;
		$content .=  "<table><tr><td valign='middle'><a id='top_menu'></a> - \n";
		$qry_recipe_type = 'select distinct r.recipe_type from '.ROSTER_RECIPESTABLE.' r where r.skill_name = "'. $_REQUEST["proffilter"].'" order by r.recipe_type asc';
		$content .= ("<!--$qry_recipe_type -->\n");
		$result_recipe_type = $wowdb->query($qry_recipe_type) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_recipe_type);
		if ($roster_conf['sqldebug'])
		{
			$content .= ("<!--$qry_recipe_type -->\n");
		}
		while($row_recipe_type = $wowdb->fetch_array($result_recipe_type))
		{
			$content .=  '<a href="'.getlink('&amp;file=addon&amp;roster_addon_name=recipe#'.str_replace(' ','',$row_recipe_type['recipe_type'])).'">'.$row_recipe_type['recipe_type'].'</a> - '."\n";
		}
		$content .=  "</td></tr></table>\n<br /><br />\n";

		foreach ($recipes as $recipe)
		{
			if ($recipe_type != $recipe->data['recipe_type'])
			{
				$recipe_type = $recipe->data['recipe_type'];
				if(!$first_table)
				{
					$content .=  '</table>'.border('syellow','end').'<br />';
				}
				$first_table = false;

				$content .= border('syellow','start','<a href="'.getlink('&amp;file=addon&amp;roster_addon_name=recipe#top_menu').'" id="'.str_replace(' ','',$recipe_type).'">'.$recipe_type.'</a>').
					'<table class="wowroster" cellspacing="0">'."\n";

				//$content .= '<tr>'."\n";
				//$content .= '<td colspan="14" class="membersHeaderRight"><div align="center"></div></td>'."\n";
				//$content .= '</tr>';
				$content .= '<tr>'."\n";

				if ($display_recipe_icon)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['item'].'&nbsp;</td>'."\n";
				}
				if ($display_recipe_name)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['name'].'&nbsp;</td>'."\n";
				}
				if ($display_recipe_level)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['level'].'&nbsp;</td>'."\n";
				}
				if ($display_recipe_tooltip)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['itemdescription'].'&nbsp;</td>'."\n";
				}
				if ($display_recipe_type)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['type'].'&nbsp;</td>'."\n";
				}
				if ($display_recipe_reagents)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['reagents'].'&nbsp;</td>'."\n";
				}
				if ($display_recipe_makers)
				{
					$content .=  '<td class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['whocanmakeit'].'&nbsp;</td>'."\n";
				}

				$content .=  '</tr>';
			}
			// while($row_main = $wowdb->fetch_array($result_main)){
			$qry_users = "select m.name, r.difficulty, s.skill_level ".
				"from ".ROSTER_MEMBERSTABLE." m, ".ROSTER_RECIPESTABLE." r, ".ROSTER_SKILLSTABLE." s ".
				"where r.member_id = m.member_id and r.member_id = s.member_id and r.skill_name = s.skill_name ".
				"and recipe_name = '".addslashes($recipe->data['recipe_name'])."' order by m.name";

			$result_users = $wowdb->query($qry_users) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_users);
			$users = '';
			$break_counter = 0;
			while($row_users = $wowdb->fetch_array($result_users))
			{
				if ($break_counter == $display_recipe_makers_count)
				{
					$users .= '<br />&nbsp;';
					$break_counter = 0;
				}
				elseif( $users != '' )
				{
					$users .= ', ';
				}

				if (substr($row_users['skill_level'],0,strpos($row_users['skill_level'],':')) < 300)
				{
					$users .= '<a onmouseover="return overlib(\''.$row_users['skill_level'].'\',WRAP);" onmouseout="return nd();" class="difficulty_'.$row_users['difficulty'].'" href="'.getlink('&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes').'">'.$row_users['name'].'</a>'."\n";
				}
				else
				{
					$users .= '<a onmouseover="return overlib(\''.$row_users['skill_level'].'\',WRAP);" onmouseout="return nd();" class="difficulty_1" href="'.getlink('&amp;file=char&amp;cname='.$row_users['name'].'&amp;server='.$server_name_escape.'&amp;action=recipes').'">'.$row_users['name'].'</a>'."\n";
				}
				$break_counter++;
			}
			$wowdb->free_result($result_users);

			$users = rtrim($users,', ');


			// Increment counter so rows are colored alternately
			++$rc;

			$table_cell_start = '<td class="membersRow'.(($rc%2)+1).'" align="center" valign="middle">';


			$thottURL='<a href="http://www.thottbot.com/index.cgi?i='.
			str_replace(' ', '+',$recipe->data['recipe_name']).'" target="_thottbot">'.
			$recipe->data['recipe_name'].'</a>';

			if ($display_recipe_tooltip)
			{
				$tooltip = '';
				$first_line = true;
				foreach (explode("\n", $recipe->data['recipe_tooltip']) as $line )
				{
					if( $first_line )
					{
						$color = substr( $recipe->data['item_color'], 2, 6 ) . '; font-size: 12px; font-weight: bold';
						$first_line = False;
					}
					else
					{
						if( substr( $line, 0, 2 ) == '|c' )
						{
							$color = substr( $line, 4, 6 ).';';
							$line = substr( $line, 10, -2 );
						} else if ( substr( $line, 0, 4 ) == $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) {
							$color = '00ff00;';
						} else if ( substr( $line, 0, 8 ) == $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) {
							$color = 'ff0000;';
						} else if ( substr( $line, 0, 10 ) == $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) {
							$color = '00ff00;';
						} else if ( substr( $line, 0, 6 ) == $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) {
							$color = '00ff00;';
						} else if ( substr( $line, 0, 6 ) == $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) {
							$color = '00ff00;';
						} else if ( substr( $line, 0, 8 ) == $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) {
							$color = '00ff00;';
						} else if ( substr( $line, 0, 9 ) == $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) {
							$color = '00ffff;';
						} elseif ( strpos( $line, '"' ) ) {
							$color = 'ffd517;';
						} else {
							$color='ffffff;';
						}
					}
					$line = preg_replace('|\\>|','&#8250;', $line );
					$line = preg_replace('|\\<|','&#8249;', $line );
					if( strpos($line,"\t") )
					{
						$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
						$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>'."\n";
						$tooltip .= $line;
					}
					elseif( $line != '')
					{
						$tooltip .= "<span style=\"color:#$color\">$line</span><br />\n";
					}
				}
				$users = rtrim($users,'<br>');
			}

			$content .=  '<tr>'."\n";
			if ($display_recipe_icon)
			{
				$content .=  $table_cell_start.'<div class="equip">';
				$content .=  $recipe->out();
				$content .=  '</div></td>';
			}
			if ($display_recipe_name)
			{
				$content .=  $table_cell_start.'&nbsp;<span style="color:#'.substr( $recipe->data['item_color'], 2, 6 ).';">'.$recipe->data['recipe_name'].'</span>&nbsp;</td>';
			}
			if ($display_recipe_level)
			{
				$content .=  $table_cell_start.'&nbsp;'.$recipe->data['level'].'&nbsp;</td>';
			}
			if ($display_recipe_tooltip)
			{
				$content .=  $table_cell_start.'<table style="width:220;white-space:normal;"><tr><td>'.$tooltip.'</td></tr></table></td>';
			}
			if ($display_recipe_type)
			{
				$content .=  $table_cell_start.'&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>';
			}
			if ($display_recipe_reagents)
			{
				$content .=  $table_cell_start.'&nbsp;'.str_replace('<br>','&nbsp;<br />&nbsp;',$recipe->data['reagents']).'</td>';
			}
			if ($display_recipe_makers)
			{
				$content .=  $table_cell_start.'&nbsp;'.$users.'&nbsp;</td>';
			}
			$content .=  '</tr>';
		}
		$content .=  '</table>'.border('syellow','end');

	}
	else
	{
		$content .=  $wordings[$roster_conf['roster_lang']]['dnotpopulatelist'];
	}

}


?>