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

$header_title = $act_words['madeby'];

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
		<tr>
			<td class="membersRow1">'.$act_words['professionfilter'].'
				<select name="proffilter">';

while($row_prof = $wowdb->fetch_array($result_prof))
{
	if ($prof_filter==$row_prof['proff'])
		$choiceForm .= '					<option value="'.$row_prof['proff'].'" selected="selected">'.$row_prof['proff'].'</option>';
	else
		$choiceForm .= '					<option value="'.$row_prof['proff'].'">'.$row_prof['proff'].'</option>';
}


$wowdb->free_result($result_prof);


$choiceForm .= '				</select></td>
			<td class="membersRow1">'.$act_words['search'].'
				<input type="text" name="filterbox"';
if (!empty($filter_box))
{
	$choiceForm .= ' value="'.$filter_box.'"';
}

$choiceForm .= ' /></td>
			<td class="membersRowRightCell"><input type="submit" value="'.$act_words['applybutton'].'" /></td>
		</tr>
	</table>
'.border('sgray','end').'
</form><br />';

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
			$content .=  '<a href="#'.str_replace(' ','_',$row_recipe_type['recipe_type']).'">'.$row_recipe_type['recipe_type'].'</a> - '."\n";
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

				$content .= border('syellow','start','<a href="#top_menu" id="'.str_replace(' ','_',$recipe_type).'">'.$recipe_type.'</a>').
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
					$content .=  '<th class="membersHeader" style="width:220px;">&nbsp;'.$act_words['itemdescription'].'&nbsp;</th>'."\n";
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
					$users .= '<span '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_'.$row_users['difficulty'].'">'.( active_addon('char') ? '<a href="'.makelink('char-recipes&amp;member='.$row_users['member_id']).'">'.$row_users['name'].'</a>' : $row_users['name'] ).'</span>'."\n";
				}
				else
				{
					$users .= '<span '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_1">'.( active_addon('char') ? '<a href="'.makelink('char-recipes&amp;member='.$row_users['member_id']).'">'.$row_users['name'].'</a>' : $row_users['name'] ).'</span>'."\n";
				}
				$break_counter++;
			}
			$wowdb->free_result($result_users);

			$users = rtrim($users,', ');


			// Increment counter so rows are colored alternately
			++$rc;

			$table_cell_start = '<td class="membersRow'.(($rc%2)+1).'" align="center" valign="middle">';


			$content .=  '<tr>'."\n";

			if ($addon['config']['display_icon'])
			{
				$content .=  $table_cell_start.'<div class="equip">';
				$content .=  $recipe->out();
				$content .=  '</div></td>';
			}
			if ($addon['config']['display_name'])
			{
				$content .=  $table_cell_start.'<span style="color:#'.$recipe->data['item_color'].';">'.$recipe->data['recipe_name'].'</span></td>';
			}
			if ($addon['config']['display_level'])
			{
				$content .=  $table_cell_start.$recipe->data['level'].'</td>';
			}
			if ($addon['config']['display_tooltip'])
			{
				$tooltip = colorTooltip(stripslashes($recipe->data['recipe_tooltip']),$recipe->data['item_color']);
				$content .=  $table_cell_start.'<div class="overlib_maintext">'.$tooltip.'</div></td>';
			}
			if ($addon['config']['display_type'])
			{
				$content .=  $table_cell_start.$recipe->data['recipe_type'].'</td>';
			}
			if ($addon['config']['display_reagents'])
			{
				$content .=  $table_cell_start.str_replace('<br>','<br />',$recipe->data['reagents']).'</td>';
			}
			if ($addon['config']['display_makers'])
			{
				$users = rtrim($users,'<br />');
				$content .=  $table_cell_start.$users.'</td>';
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

echo $content;
