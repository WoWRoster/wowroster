<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$roster->output['title'] = $roster->locale->act['madeby'];

require_once(ROSTER_LIB.'recipes.php');

$prof_filter = ( isset($_REQUEST['proffilter']) ? $_REQUEST['proffilter'] : '');
$filter_box = ( isset($_REQUEST['filterbox']) ? $_REQUEST['filterbox'] : '');
$prof_sort = ( isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '');

$anchor_link = ( isset($_GET['proffilter']) ? '&amp;proffilter=' . $_GET['proffilter'] : '' )
			 . ( isset($_GET['filterbox']) ? '&amp;filterbox=' . $_GET['filterbox'] : '' )
			 . ( isset($_GET['sort']) ? '&amp;sort=' . $_GET['sort'] : '' );

$qry_prof  = "SELECT DISTINCT( `skill_name` ) proff
	FROM ".$roster->db->table('recipes')."
	WHERE `skill_name` != '".$roster->locale->act['First Aid']."'
		AND `skill_name` != '".$roster->locale->act['Poisons']."'
		AND `skill_name` != '".$roster->locale->act['Mining']."'
	ORDER BY `skill_name`;";

$result_prof = $roster->db->query($qry_prof) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qry_prof);


$choiceForm = '<form action="'.makelink().'" method="get" name="myform">
	'.linkform().'
'.border('sgray','start').'
	<table cellspacing="0" cellpadding="2" class="bodyline">
		<tr>
			<td class="membersRow1">'.$roster->locale->act['professionfilter'].'
				<select name="proffilter">';

while($row_prof = $roster->db->fetch($result_prof))
{
	if ($prof_filter==$row_prof['proff'])
		$choiceForm .= '					<option value="'.$row_prof['proff'].'" selected="selected">'.$row_prof['proff'].'</option>';
	else
		$choiceForm .= '					<option value="'.$row_prof['proff'].'">'.$row_prof['proff'].'</option>';
}


$roster->db->free_result($result_prof);


$choiceForm .= '				</select></td>
			<td class="membersRow1">'.$roster->locale->act['search'].'
				<input type="text" name="filterbox"';
if (!empty($filter_box))
{
	$choiceForm .= ' value="'.$filter_box.'"';
}

$choiceForm .= ' /></td>
			<td class="membersRowRightCell"><input type="submit" value="'.$roster->locale->act['applybutton'].'" /></td>
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
			FROM '.$roster->db->table('recipes').' r
			WHERE `r`.`skill_name` = "'. $prof_filter.'"
			ORDER BY `r`.`recipe_type` ASC';

		$content .= ("<!--$qry_recipe_type -->\n");
		$result_recipe_type = $roster->db->query($qry_recipe_type) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qry_recipe_type);
		if ($roster->config['sqldebug'])
		{
			$content .= ("<!--$qry_recipe_type -->\n");
		}
		while($row_recipe_type = $roster->db->fetch($result_recipe_type))
		{
			$content .=  '<a href="' . makelink($anchor_link . '#'.str_replace(' ','_',$row_recipe_type['recipe_type'])) . '">'.$row_recipe_type['recipe_type'].'</a> - '."\n";
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

				$content .= border('syellow','start','<a href="' . makelink($anchor_link . '#top_menu') . '" id="'.str_replace(' ','_',$recipe_type).'">'.$recipe_type.'</a>').
					'<table class="bodyline" cellspacing="0">'."\n";

				$content .= '<tr>'."\n";

				if ($addon['config']['display_icon'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$roster->locale->act['item'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_name'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$roster->locale->act['name'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_level'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$roster->locale->act['level'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_tooltip'])
				{
					$content .=  '<th class="membersHeader" style="width:220px;">&nbsp;'.$roster->locale->act['itemdescription'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_type'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$roster->locale->act['type'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_reagents'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$roster->locale->act['reagents'].'&nbsp;</th>'."\n";
				}
				if ($addon['config']['display_makers'])
				{
					$content .=  '<th class="membersHeader">&nbsp;'.$roster->locale->act['whocanmakeit'].'&nbsp;</th>'."\n";
				}

				$content .=  '</tr>';
			}
			// while($row_main = $roster->db->fetch($result_main)){
			$qry_users = "SELECT `c`.`name`, `c`.`member_id`,`r`.`difficulty`, `s`.`skill_level` ".
				"FROM `".$roster->db->table('players')."` c ".
				"INNER JOIN `".$roster->db->table('recipes')."` r ON `r`.`member_id` = `c`.`member_id` ".
				"INNER JOIN `".$roster->db->table('skills')."` s ON `r`.`member_id` = `s`.`member_id` AND `r`.`skill_name` = `s`.`skill_name` ".
				"WHERE `recipe_name` = '".addslashes($recipe->data['recipe_name'])."' ORDER BY `c`.`name`;";

			$result_users = $roster->db->query($qry_users) or die_quietly($roster->db->error(),'Database Error',__FILE__,__LINE__,$qry_users);
			$users = '';
			$break_counter = 0;
			while($row_users = $roster->db->fetch($result_users))
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
					$users .= '<span '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_'.$row_users['difficulty'].'">'.( active_addon('info') ? '<a href="'.makelink('char-info-recipes&amp;member='.$row_users['member_id']).'">'.$row_users['name'].'</a>' : $row_users['name'] ).'</span>'."\n";
				}
				else
				{
					$users .= '<span '.makeOverlib($row_users['skill_level'],'','',2,'',',WRAP').' class="difficulty_1">'.( active_addon('info') ? '<a href="'.makelink('char-info-recipes&amp;member='.$row_users['member_id']).'">'.$row_users['name'].'</a>' : $row_users['name'] ).'</span>'."\n";
				}
				$break_counter++;
			}
			$roster->db->free_result($result_users);

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
		$content .=  $roster->locale->act['dnotpopulatelist'];
	}

}

echo $content;
