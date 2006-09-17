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

require_once(ROSTER_LIB.'recipes.php');


$server_name_escape = $wowdb->escape($roster_conf['server_name']);


$qry_prof  = "SELECT DISTINCT( `skill_name` ) proff FROM ".ROSTER_RECIPESTABLE." WHERE `skill_name` != '".$wordings[$roster_conf['roster_lang']]['First Aid']."' AND `skill_name` != '".$wordings[$roster_conf['roster_lang']]['poisons']."' AND `skill_name` != '".$wordings[$roster_conf['roster_lang']]['Mining']."' ORDER BY `skill_name`;";

$result_prof = $wowdb->query($qry_prof) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_prof);
if ($roster_conf['sqldebug'])
{
	$content .= ("<!--$query -->\n");
}



$choiceForm = '<form action="'.$script_filename.'" method="GET" name="myform">
	<input type="hidden" name="dbname" value="'.$addon['dbname'].'">
'.border('sgray','start','').'
	<table cellspacing="0" cellpadding="0" class="bodyline">
		<tr class="membersRowColor1">
		<td class="membersRowCell">'.$wordings[$roster_conf['roster_lang']]['professionfilter'].'
			<select name="proffilter">';

while($row_prof = $wowdb->fetch_array($result_prof))
{
	if ($_REQUEST['proffilter']==$row_prof['proff'])
		$choiceForm .= '<option value="'.$row_prof['proff'].'" selected="selected">'.$row_prof['proff'];
	else
		$choiceForm .= '<option vaue="'.$row_prof['proff'].'">'.$row_prof['proff'];
}


$wowdb->free_result($result_prof);


$choiceForm .= '</select></td>
		<td class="membersRowCell">'.$wordings[$roster_conf['roster_lang']]['search'].'
			<input type="text" name="filterbox"';
if (isset($_REQUEST['filterbox']))
{
	$choiceForm .= ' value="'.$_REQUEST['filterbox'].'"';
}

$choiceForm .= '></td>
		<td class="membersRowRightCell"><input type="submit" value="'.$wordings[$roster_conf['roster_lang']]['applybutton'].'" /></td>
	</tr>
</table>
</form>'.border('sgray','end').'<br />';

$content .=  $choiceForm;




if (isset($_REQUEST['proffilter']))
{
	$recipes = recipe_get_all( $_REQUEST['proffilter'],($_REQUEST['filterbox']?$_REQUEST['filterbox']:''), ($_REQUEST['sort']?$_REQUEST['sort']:'type') );

	if( isset( $recipes[0] ) )
	{
		$rc = 1;

		$recipe_type = '';
		$first_table = true;
		$content .=  "<table><tr><td valign='middle'><a id='top_menu'></a> - \n";
		$qry_recipe_type = 'SELECT DISTINCT( `r`.`recipe_type` ) FROM '.ROSTER_RECIPESTABLE.' r WHERE `r`.`skill_name` = "'. $_REQUEST['proffilter'].'" ORDER BY `r`.`recipe_type` ASC';
		$content .= ("<!--$qry_recipe_type -->\n");
		$result_recipe_type = $wowdb->query($qry_recipe_type) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_recipe_type);
		if ($roster_conf['sqldebug'])
		{
			$content .= ("<!--$qry_recipe_type -->\n");
		}
		while($row_recipe_type = $wowdb->fetch_array($result_recipe_type))
		{
			$content .=  '<a href="#'.$row_recipe_type['recipe_type'].'">'.$row_recipe_type['recipe_type'].'</a> - '."\n";
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

				$content .= border('syellow','start','<a href="#top_menu" id="'.$recipe_type.'">'.$recipe_type.'</a>').
					'<table class="bodyline" cellspacing="0">'."\n";

				$content .= '<tr>'."\n";

				if ($addon_conf['recipe']['display_icon'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['item'].'&nbsp;</th>'."\n";
				}
				if ($addon_conf['recipe']['display_name'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['name'].'&nbsp;</th>'."\n";
				}
				if ($addon_conf['recipe']['display_level'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['level'].'&nbsp;</th>'."\n";
				}
				if ($addon_conf['recipe']['display_tooltip'])
				{
					$content .=  '<th width="220" class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['itemdescription'].'&nbsp;</th>'."\n";
				}
				if ($addon_conf['recipe']['display_type'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['type'].'&nbsp;</th>'."\n";
				}
				if ($addon_conf['recipe']['display_reagents'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['reagents'].'&nbsp;</th>'."\n";
				}
				if ($addon_conf['recipe']['display_makers'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$wordings[$roster_conf['roster_lang']]['whocanmakeit'].'&nbsp;</th>'."\n";
				}

				$content .=  '</tr>';
			}
			// while($row_main = $wowdb->fetch_array($result_main)){
			$qry_users = "SELECT `m`.`name`, `m`.`member_id`,`r`.`difficulty`, `s`.`skill_level` ".
				"FROM `".ROSTER_MEMBERSTABLE."` m, `".ROSTER_RECIPESTABLE."` r, `".ROSTER_SKILLSTABLE."` s ".
				"WHERE `r`.`member_id` = `m`.`member_id` AND `r`.`member_id` = `s`.`member_id` AND `r`.`skill_name` = `s`.`skill_name` ".
				"AND `recipe_name` = '".addslashes($recipe->data['recipe_name'])."' ORDER BY `m`.`name`;";

			$result_users = $wowdb->query($qry_users) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_users);
			$users = '';
			$break_counter = 0;
			while($row_users = $wowdb->fetch_array($result_users))
			{
				if ($break_counter == $addon_conf['recipe']['display_makers_count'])
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
					$users .= '<a '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_'.$row_users['difficulty'].'" href="./char.php?member='.$row_users['member_id'].'&amp;action=recipes">'.$row_users['name'].'</a>'."\n";
				}
				else
				{
					$users .= '<a '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_1" href="./char.php?member='.$row_users['member_id'].'&amp;action=recipes">'.$row_users['name'].'</a>'."\n";
				}
				$break_counter++;
			}
			$wowdb->free_result($result_users);

			$users = rtrim($users,', ');


			// Increment counter so rows are colored alternately
			++$rc;

			$table_cell_start = '<td class="membersRowCell" align="center" valign="middle">';


			$thottURL='<a href="http://www.thottbot.com/index.cgi?i='.
			str_replace(' ', '+',$recipe->data['recipe_name']).'" target="_thottbot">'.
			$recipe->data['recipe_name'].'</a>';

			if ($addon_conf['recipe']['display_tooltip'])
			{
				$tooltip = '';
				$first_line = true;
				$recipe->data['item_tooltip'] = stripslashes($recipe->data['recipe_tooltip']);
				foreach (explode("\n", $recipe->data['recipe_tooltip']) as $line )
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
							if( $recipe->data['item_color'] == '' )
								$recipe->data['item_color'] = '9d9d9d';

							if( strlen($recipe->data['item_color']) > 6 )
								$color = substr( $recipe->data['item_color'], 2, 6 );
							else
								$color = $recipe->data['item_color'];

							$color .= ';font-size:12px;font-weight:bold';
							$first_line = false;
						}
						else
						{
							if ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_use'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_requires'],$line) )
								$color = 'ff0000';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_reinforced'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_equip'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_chance'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_enchant'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_soulbound'],$line) )
								$color = '00bbff';
							elseif ( ereg('^'.$wordings[$roster_conf['roster_lang']]['tooltip_set'],$line) )
								$color = '00ff00';
							elseif ( preg_match('|\([a-f0-9]\).'.$wordings[$roster_conf['roster_lang']]['tooltip_set'].'|',$line) )
								$color = '666666';
							elseif ( ereg('^\\"',$line) )
								$color = 'ffd517';
						}

						// Convert tabs to a formated table
						if( strpos($line,"\t") )
						{
							$line = str_replace("\t",'</td><td align="right" class="overlib_maintext">', $line);
							$line = '<table width="100%" cellspacing="0" cellpadding="0"><tr><td class="overlib_maintext">'.$line.'</td></tr></table>';
							$tooltip .= $line;
						}
						elseif( !empty($color) )
						{
							$tooltip .= '<span style="color:#'.$color.';">'.$line.'</span><br />';
						}
						else
						{
							$tooltip .= "$line<br />";
						}
					}
					else
					{
						$tooltip .= '<br />';
					}
				}
				$users = rtrim($users,'<br>');
			}

			$content .=  '<tr class="membersRowColor'.(($rc%2)+1).'">'."\n";
			if ($addon_conf['recipe']['display_icon'])
			{
				$content .=  $table_cell_start.'<div class="equip">';
				$content .=  $recipe->out();
				$content .=  '</div></td>';
			}
			if ($addon_conf['recipe']['display_name'])
			{
				$content .=  $table_cell_start.'&nbsp;<span style="color:#'.substr( $recipe->data['item_color'], 2, 6 ).';">'.$recipe->data['recipe_name'].'</span>&nbsp;</td>';
			}
			if ($addon_conf['recipe']['display_level'])
			{
				$content .=  $table_cell_start.'&nbsp;'.$recipe->data['level'].'&nbsp;</td>';
			}
			if ($addon_conf['recipe']['display_tooltip'])
			{
				$content .=  $table_cell_start.'<table style="width:220;white-space:normal;"><tr><td>'.$tooltip.'</td></tr></table></td>';
			}
			if ($addon_conf['recipe']['display_type'])
			{
				$content .=  $table_cell_start.'&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>';
			}
			if ($addon_conf['recipe']['display_reagents'])
			{
				$content .=  $table_cell_start.'&nbsp;'.str_replace('<br>','&nbsp;<br />&nbsp;',$recipe->data['reagents']).'</td>';
			}
			if ($addon_conf['recipe']['display_makers'])
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