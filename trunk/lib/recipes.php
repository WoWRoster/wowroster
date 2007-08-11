<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Recipe class and functions
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

class recipe
{
	var $data;

	function recipe( $data )
	{
		$this->data = $data;
	}

	function out()
	{
		global $roster, $char, $tooltips;

		if( !is_object($char) )
		{
			$lang = $roster->config['locale'];
		}
		else
		{
			$lang = $char->data['clientLocale'];
		}

		$path = $roster->config['interface_url'].'Interface/Icons/'.$this->data['recipe_texture'].'.'.$roster->config['img_suffix'];

		// Item links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';
		foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
		{
			$linktip .= '<a href="'.$ilink.urlencode(utf8_decode($this->data['recipe_name'])).'" target="_blank">'.$key.'</a><br />';
		}
		setTooltip($num_of_tips,$linktip);
		setTooltip('itemlink',$roster->locale->wordings[$lang]['itemlink']);

		$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		$tooltip = makeOverlib($this->data['recipe_tooltip'],'',$this->data['item_color'],0,$lang);

		$returnstring = '<div class="item" '.$tooltip.$linktip.'>';

		$returnstring .= '<img src="'.$path.'" class="icon"'." alt=\"\" />\n";

		$returnstring .= '</div>';
		return $returnstring;
	}
}

function recipe_get_many( $member_id, $search, $sort )
{
	global $roster;

	$query= "SELECT * FROM `".$roster->db->table('recipes')."` where `member_id` = '$member_id'";

	switch ($sort)
	{
		case 'item':
			$query .= " ORDER BY `skill_name` ASC , `difficulty` DESC , `recipe_type` ASC , `recipe_name` ASC";
			break;

		case 'difficulty':
			$query .= " ORDER BY `skill_name` ASC , `difficulty` DESC , `recipe_type` ASC , `recipe_name` ASC";
			break;

		case 'name':
			$query .= " ORDER BY `skill_name` ASC , `recipe_name` ASC , `recipe_type` ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `skill_name` ASC , `level` ASC , `difficulty` DESC , `recipe_name` ASC";
			break;

		case 'type':
			$query .= " ORDER BY `skill_name` ASC , `recipe_type` ASC , `difficulty` DESC , `recipe_name` ASC";
			break;

		case 'reagents':
			$query .= " ORDER BY `skill_name` ASC , `reagents` ASC , `recipe_name` ASC , `recipe_type` ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		default:
			$query .= " ORDER BY `skill_name` ASC , `difficulty` DESC , `recipe_type` ASC , `recipe_name` ASC";
			break;
	}

	$result = $roster->db->query( $query );
	$recipes = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}


function recipe_get_all( $skill_name, $search, $sort )
{
	global $roster;

	$query= "SELECT distinct recipe_tooltip, recipe_name, recipe_type, item_color, skill_name, reagents, recipe_texture, level, 1 difficulty FROM `".$roster->db->table('recipes')."` WHERE `skill_name` = '$skill_name' ".($search==''?'':" AND (recipe_tooltip LIKE '%".$search."%' OR recipe_name LIKE '%".$search."%')")." GROUP BY recipe_name";

	switch ($sort)
	{
		case 'item':
			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
			break;

		case 'name':
			$query .= " ORDER BY  recipe_name ASC ,  recipe_type ASC , `level` DESC , `difficulty` DESC";
			break;

		case 'type':
			$query .= " ORDER BY  recipe_type ASC , `level` DESC , recipe_name ASC , `difficulty` DESC";
			break;

		case 'reagents':
			$query .= " ORDER BY  `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		case 'level':
			$query .= " ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
			break;

		default:
			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
			break;
	}

	$result = $roster->db->query( $query );
//	echo '--'.$query.'--';
	$recipes = array();
	while( $data = $roster->db->fetch( $result ) ) {
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}
