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

require_once(ROSTER_LIB.'recipes.php');

$prof_filter = ( isset($_REQUEST['proffilter']) ? $_REQUEST['proffilter'] : '');
$filter_box = ( isset($_REQUEST['filterbox']) ? $_REQUEST['filterbox'] : '');
$prof_sort = ( isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '');

$qry_prof  = "SELECT DISTINCT( `skill_name` ) proff
	FROM ".ROSTER_RECIPESTABLE."
	WHERE `skill_name` != '".$act_words['First Aid']."'
		AND `skill_name` != '".$act_words['Poisons']."'
		AND `skill_name` != '".$act_words['Mining']."'
	ORDER BY `skill_name`;";

$result_prof = $wowdb->query($qry_prof) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_prof);


$choiceForm = '<form action="'.makelink().'" method="get" name="myform">
	'.linkform().'
'.border('sgray','start').'
	<table cellspacing="0" cellpadding="2" class="bodyline">
		<tr class="membersRowColor1">
		<td class="membersRowCell">'.$act_words['professionfilter'].'
			<select name="proffilter">';

while($row_prof = $wowdb->fetch_array($result_prof))
{
	if ($prof_filter==$row_prof['proff'])
		$choiceForm .= '<option value="'.$row_prof['proff'].'" selected="selected">'.$row_prof['proff'];
	else
		$choiceForm .= '<option vaue="'.$row_prof['proff'].'">'.$row_prof['proff'];
}


$wowdb->free_result($result_prof);


$choiceForm .= '</select></td>
		<td class="membersRowCell">'.$act_words['search'].'
			<input type="text" name="filterbox"';
if (!empty($filter_box))
{
	$choiceForm .= ' value="'.$filter_box.'"';
}

$choiceForm .= '></td>
		<td class="membersRowRightCell"><input type="submit" value="'.$act_words['applybutton'].'" /></td>
	</tr>
</table>
</form>'.border('sgray','end').'<br />';

$content =  $choiceForm;




if (!empty($prof_filter))
{
	$recipes = recipe_get_all( $prof_filter,(!empty($filter_box) ? $filter_box : ''), (!empty($prof_sort) ? $prof_sort:'type') );

	if( isset( $recipes[0] ) )
	{
		$rc = 1;

		$recipe_type = '';
		$first_table = true;
		$content .=  "<table><tr><td valign='middle'><a id='top_menu'></a> - \n";

		$qry_recipe_type = 'SELECT DISTINCT( `r`.`recipe_type` )
			FROM '.ROSTER_RECIPESTABLE.' r
			WHERE `r`.`skill_name` = "'. $prof_filter.'"
			ORDER BY `r`.`recipe_type` ASC';

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

				if ($addon['config']['display_icon'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$act_words['item'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_name'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$act_words['name'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_level'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$act_words['level'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_tooltip'])
				{
					$content .=  '<th width="220" class="membersHeader">&nbsp;'.$act_words['itemdescription'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_type'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$act_words['type'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_reagents'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$act_words['reagents'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_makers'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$act_words['whocanmakeit'].'&nbsp;</th>'."\n";
				}

				$content .=  '</tr>';
			}
			// while($row_main = $wowdb->fetch_array($result_main)){
			$qry_users = "SELECT `c`.`name`, `c`.`member_id`,`r`.`difficulty`, `s`.`skill_level` ".
				"FROM `".ROSTER_PLAYERSTABLE."` c ".
				"INNER JOIN `".ROSTER_RECIPESTABLE."` r ON `r`.`member_id` = `c`.`member_id` ".
				"INNER JOIN `".ROSTER_SKILLSTABLE."` s ON `r`.`member_id` = `s`.`member_id` AND `r`.`skill_name` = `s`.`skill_name` ".
				"WHERE `recipe_name` = '".addslashes($recipe->data['recipe_name'])."' ORDER BY `c`.`name`;";

			$result_users = $wowdb->query($qry_users) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$qry_users);
			$users = '';
			$break_counter = 0;
			while($row_users = $wowdb->fetch_array($result_users))
			{
				if ($break_counter == $addon['config']['display_makers_count'])
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
					$users .= '<a '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_'.$row_users['difficulty'].'" href="'.makelink('char-recipes&amp;member='.$row_users['member_id']).'">'.$row_users['name'].'</a>'."\n";
				}
				else
				{
					$users .= '<a '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_1" href="'.makelink('char-recipes&amp;member='.$row_users['member_id']).'">'.$row_users['name'].'</a>'."\n";
				}
				$break_counter++;
			}
			$wowdb->free_result($result_users);

			$users = rtrim($users,', ');


			// Increment counter so rows are colored alternately
			++$rc;

			$table_cell_start = '<td class="membersRowCell" align="center" valign="middle">';


			if ($addon['config']['display_tooltip'])
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
							if ( ereg('^'.$act_words['tooltip_use'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$act_words['tooltip_requires'],$line) )
								$color = 'ff0000';
							elseif ( ereg('^'.$act_words['tooltip_reinforced'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$act_words['tooltip_equip'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$act_words['tooltip_chance'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$act_words['tooltip_enchant'],$line) )
								$color = '00ff00';
							elseif ( ereg('^'.$act_words['tooltip_soulbound'],$line) )
								$color = '00bbff';
							elseif ( ereg('^'.$act_words['tooltip_set'],$line) )
								$color = '00ff00';
							elseif ( preg_match('|\([a-f0-9]\).'.$act_words['tooltip_set'].'|',$line) )
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
			if ($addon['config']['display_icon'])
			{
				$content .=  $table_cell_start.'<div class="equip">';
				$content .=  $recipe->out();
				$content .=  '</div></td>';
			}
			if ($addon['config']['display_name'])
			{
				$content .=  $table_cell_start.'&nbsp;<span style="color:#'.substr( $recipe->data['item_color'], 2, 6 ).';">'.$recipe->data['recipe_name'].'</span>&nbsp;</td>';
			}
			if ($addon['config']['display_level'])
			{
				$content .=  $table_cell_start.'&nbsp;'.$recipe->data['level'].'&nbsp;</td>';
			}
			if ($addon['config']['display_tooltip'])
			{
				$content .=  $table_cell_start.'<table style="width:220;white-space:normal;"><tr><td>'.$tooltip.'</td></tr></table></td>';
			}
			if ($addon['config']['display_type'])
			{
				$content .=  $table_cell_start.'&nbsp;'.$recipe->data['recipe_type'].'&nbsp;</td>';
			}
			if ($addon['config']['display_reagents'])
			{
				$content .=  $table_cell_start.'&nbsp;'.str_replace('<br>','&nbsp;<br />&nbsp;',$recipe->data['reagents']).'</td>';
			}
			if ($addon['config']['display_makers'])
			{
				$content .=  $table_cell_start.'&nbsp;'.$users.'&nbsp;</td>';
			}
			$content .=  '</tr>';
		}
		$content .=  '</table>'.border('syellow','end');

	}
	else
	{
		$content .=  $act_words['dnotpopulatelist'];
	}

}


?>
