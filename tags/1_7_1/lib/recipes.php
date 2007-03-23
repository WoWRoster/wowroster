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

class recipe
{
	var $data;

	function recipe( $data )
	{
		$this->data = $data;
	}

	function out()
	{
		global $roster_conf, $wordings, $itemlink, $char;

		if( !is_object($char) )
			$lang = $roster_conf['roster_lang'];
		else
			$lang = $char->data['clientLocale'];

		$path = $roster_conf['interface_url'].$this->data['recipe_texture'].'.'.$roster_conf['img_suffix'];

		$tooltip = makeOverlib($this->data['recipe_tooltip'],'',$this->data['item_color'],0,$lang);

		$returnstring = '<div class="item" '.$tooltip.'>';

		$returnstring .= '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($this->data['recipe_name'])).'" target="_blank">'.
		'<img src="'.$path.'" class="icon"'." alt=\"\" /></a>\n";
		$returnstring .= '</div>';
		return $returnstring;
	}
}

function recipe_get_many( $member_id, $search, $sort )
{
	global $wowdb;
	if (isset($char))
		$char = $wowdb->escape( $char );
	if (isset($server))
		$server = $wowdb->escape( $server );

	$query= "SELECT * FROM `".ROSTER_RECIPESTABLE."` where `member_id` = '$member_id'";

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

	$result = $wowdb->query( $query );
	$recipes = array();
	while( $data = $wowdb->fetch_assoc( $result ) )
	{
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}


function recipe_get_all( $skill_name, $search, $sort )
{
	global $wowdb;

	if (isset($server))
	{
		$server = $wowdb->escape( $server );
	}

	//$query= "SELECT distinct recipe_name, recipe_type, skill_name, reagents, recipe_texture, level, min(difficulty) difficulty FROM `".ROSTER_RECIPESTABLE."` where `skill_name` = '$skill_name' GROUP BY recipe_name, recipe_type, skill_name, reagents, recipe_texture, level";
	$query= "SELECT distinct recipe_tooltip, recipe_name, recipe_type, item_color, skill_name, reagents, recipe_texture, level, 1 difficulty FROM `".ROSTER_RECIPESTABLE."` WHERE `skill_name` = '$skill_name' ".($search==''?'':" AND (recipe_tooltip LIKE '%".$search."%' OR recipe_name LIKE '%".$search."%')");

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

	$result = $wowdb->query( $query );
//	echo '--'.$query.'--';
	$recipes = array();
	while( $data = $wowdb->fetch_assoc( $result ) ) {
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}
?>