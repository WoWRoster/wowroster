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

	//	function compair_types($a, $b)
	//	{
	//		$al = strtolower($a->data['recipe_type']);
	//		$bl = strtolower($b->data['recipe_type']);
	//		if ($al == $bl)
	//		{
	//			return 0;
	//		}
	//		return ($al > $bl) ? 1 : -1;
	//	}
	//
	//
	//	function compair_types_byname($a, $b)
	//	{
	//		$aenc = strtolower(utf8_encode($a->data['recipe_type'].$a->data['recipe_name']));
	//		$benc = strtolower(utf8_encode($b->data['recipe_type'].$b->data['recipe_name']));
	//		if ($aenc == $benc)
	//		{
	//			return 0;
	//		}
	//		return ($aenc > $benc) ? 1 : -1;
	//	}

	function compair_types_usersort($a, $b)
	{
		global $sortby, $sortby_d;

		$sortstr_a = (is_numeric($a->data[$sortby]) ? sprintf("%02d",$a->data[$sortby]) : $a->data[$sortby]);
		$sortstr_b = (is_numeric($b->data[$sortby]) ? sprintf("%02d",$b->data[$sortby]) : $b->data[$sortby]);
		$asort = $a->data['recipe_type'].$sortstr_a;
		$bsort = $b->data['recipe_type'].$sortstr_b;
		if ($asort == $bsort)
		{
			return 0;
		}
		if ($sortby_d == '0')
		{
			return ($asort > $bsort) ? 1 : -1;
		}
		else
		{
			return ($asort > $bsort) ? -1 : 1;
		}
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

function recipe_get_all( $skill_name, $search, $sort )
{
	global $wowdb, $wordings, $roster_conf, $sortby;

	if (isset($server))
	{
		$server = $wowdb->escape( $server );
	}

	//$query= "SELECT distinct recipe_name, recipe_type, skill_name, reagents, recipe_texture, level, min(difficulty) difficulty FROM `".ROSTER_RECIPESTABLE."` where `skill_name` = '$skill_name' GROUP BY recipe_name, recipe_type, skill_name, reagents, recipe_texture, level";
	$query= "SELECT distinct recipe_name, recipe_tooltip, recipe_type, item_color, skill_name, reagents, recipe_texture, level, 1 difficulty
				FROM `".ROSTER_RECIPESTABLE."` 
				WHERE `skill_name` = '$skill_name' ".($search==''?'':" 
				AND (recipe_tooltip LIKE '%".$search."%' OR recipe_name LIKE '%".$search."%')")." 
				GROUP BY recipe_name";

	//	switch ($sort)
	//	{
	//		case 'item':
	//			$sortby = array("-difficulty", "+recipe_type", "+recipe_name");
	//			break;
	//
	//		case 'name':
	//			$sortby = array("+recipe_type", "+recipe_name",  "-level", "-difficulty");
	//			break;
	//
	//		case 'type':
	//			$sortby = array("+recipe_type", "+recipe_name", "-level", "+recipe_name");
	//			break;
	//
	//		case 'reagents':
	//			$query .= " ORDER BY  `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	//			break;
	//
	//		case 'level':
	//			$query .= " ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	//			break;
	//
	//		default:
	//			$query .= " ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
	//			break;
	//	}
	switch ($sort)
	{
		case 'item':
			$sortby = 'recipe_item';
			break;

		case 'name':
			$sortby = 'recipe_name';
			break;

		case 'difficulty':
			$sortby = 'difficulty';
			break;

		case 'reagents':
			$sortby = 'reagents';
			break;

		case 'level':
			$sortby = 'level';
			break;

		case 'type':
		default:
			$sortby = 'recipe_name';
	}

	$result = $wowdb->query( $query );

	$recipes = array();

	while( $data = $wowdb->fetch_assoc( $result ) )
	{
		if($data['skill_name'] == $wordings[$roster_conf['roster_lang']]['Enchanting'])
		{
			$data['recipe_type_org'] = $data['recipe_type'];

			if(preg_match($wordings[$roster_conf['roster_lang']]['REGEX_WAND_ROD_OILS'], $data['recipe_name'], $val))
			{
				if ($roster_conf['roster_lang'] == 'enUS')
				{
					$data['recipe_type'] = $val[1].'s';
				}
				else if ($roster_conf['roster_lang'] == 'deDE')
				{
					if(strtolower($val[1]) == 'rute')
					{
						$data['recipe_type'] = 'Ruten';
					}
					else if (strtolower($val[1]) == utf8_encode('öl') OR strtolower($val[1]) == 'Ã¶l')
					{
						$data['recipe_type'] = utf8_encode('Öle');
					}
					else if (strtolower($val[1]) == 'zauberstab')
					{
						$data['recipe_type'] = utf8_encode('Zauberstäbe');
					}
					else
					{
						$data['recipe_type'] = ucfirst($val[1]);
					}
				}
				else if($roster_conf['roster_lang'] == 'frFR')
				{
					$data['recipe_type'] = ucwords($val[1]);  // do nothing special for FR?
				}
			}
			elseif (preg_match($wordings[$roster_conf['roster_lang']]['REGEX_ENCHANTMENTS'], $data['recipe_name'], $val))
			{
				$data['recipe_type'] = $val[1];
			}
			else
			{
				$data['recipe_type'] = $wordings[$roster_conf['roster_lang']]['items'];
			}
			$data['recipe_type'] = trim($data['recipe_type']);  // removes leading and trailing spaces
		}
		$recipes[] = new recipe( $data );
	}
	//	if ($sort_direction == 'd')
	//	{
	usort($recipes, array("recipe", "compair_types_usersort"));
	//	}
	//	usort($recipes, array("recipe", "compair_types_usersort"));
	return $recipes;
}
?>
