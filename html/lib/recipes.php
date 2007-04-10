<?php
$versions['versionDate']['recipes'] = '$Date: 2006/02/03 23:38:52 $'; 
$versions['versionRev']['recipes'] = '$Revision: 1.13 $'; 
$versions['versionAuthor']['recipes'] = '$Author: zanix $';

require_once 'wowdb.php';

class recipe
{
	var $data;

	function recipe( $data )
	{
		$this->data = $data;
	}

	function out()
	{
		global $img_url;
		global $img_suffix;
		global $roster_lang;
		global $wordings;

		$path = $img_url.preg_replace('|\\\\|','/', $this->data['recipe_texture']).'.'.$img_suffix;

		$first_line = True;
		foreach (explode("\n", $this->data['recipe_tooltip']) as $line )
		{
			$line = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$line);
			$class='tooltipline';

			if( $first_line )
			{
				$color = substr( $this->data['item_color'], 2, 6 ) . '; font-size: 10px; font-weight: bold';
				$first_line = False;
				$class='tooltipheader';
			}
			else
			{
				if( substr( $line, 0, 2 ) == '|c' )
				{
					$color = substr( $line, 4, 6 ).'; font-size: 10px;';
					$line = substr( $line, 10, -2 );
				}
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_use'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_requires'] ) === 0 )
					$color = 'ff0000; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_reinforced'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_equip'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_chance'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_enchant'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_soulbound'] ) === 0 )
					$color = '00ffff; font-size: 10px;';
				else
					$color='ffffff; font-size: 10px;';
			}
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );

			if( $line != '')
				$tooltip = $tooltip."<span class=\"$class\" style=\"color:#$color\">$line</span><br />";
		}
		$tooltip = str_replace("'", "\'", $tooltip);
		$tooltip = str_replace('"','&quot;', $tooltip);

		$returnstring = '<div class="item" onMouseover="return overlib(\''.$tooltip.'\',WIDTH,250);" onMouseout="return nd();">';
		extract($GLOBALS);
		$returnstring .= '<a href="'.$itemlink[$roster_lang].urlencode(utf8_decode($this->data['recipe_name'])).'" target="_itemlink">'.
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

	if ($sort == 'item')
		$query=$query." ORDER BY `skill_name` ASC , `difficulty` DESC , recipe_type ASC , recipe_name ASC";
	else if ($sort == 'name')
		$query=$query." ORDER BY `skill_name` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	else if ($sort == 'type')
		$query=$query." ORDER BY `skill_name` ASC , recipe_type ASC , recipe_name ASC , `difficulty` DESC";
	else if ($sort == 'reagents')
		$query=$query." ORDER BY `skill_name` ASC , `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	else
		$query=$query." ORDER BY `skill_name` ASC , `difficulty` DESC , recipe_type ASC , recipe_name ASC";


	$result = $wowdb->query( $query );
	$recipes = array();
	while( $data = $wowdb->getrow( $result ) )
	{
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}
function recipe_get_all( $skill_name, $search, $sort ) {
	global $wowdb;
	if (isset($server)) { $server = $wowdb->escape( $server ); }
//	$query= "SELECT distinct recipe_name, recipe_type, skill_name, reagents, recipe_texture, level, min(difficulty) difficulty FROM `".ROSTER_RECIPESTABLE."` where `skill_name` = '$skill_name' GROUP BY recipe_name, recipe_type, skill_name, reagents, recipe_texture, level";

	$query= "SELECT distinct recipe_tooltip, recipe_name, recipe_type, skill_name, reagents, recipe_texture, level, 1 difficulty FROM `".ROSTER_RECIPESTABLE."` where `skill_name` = '$skill_name' ".($search==''?'':" and (recipe_tooltip like '%".$search."%' or recipe_name like '%".$search."%')");

	if ($sort == 'item') {
		$query=$query." ORDER BY  `difficulty` DESC , recipe_type ASC , `level` DESC , recipe_name ASC";
	} else if ($sort == 'name') {
		$query=$query." ORDER BY  recipe_name ASC ,  recipe_type ASC , `level` DESC , `difficulty` DESC";
	} else if ($sort == 'type') {
		$query=$query." ORDER BY  recipe_type ASC , `level` DESC , recipe_name ASC , `difficulty` DESC";
	} else if ($sort == 'reagents') {
		$query=$query." ORDER BY  `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	} else if ($sort == 'level') {
		$query=$query." ORDER BY `level` DESC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	} else {
		$query=$query." ORDER BY `difficulty` DESC , recipe_type ASC , recipe_name ASC";
	}

	$result = $wowdb->query( $query );
//	echo '--'.$query.'--';
	$recipes = array();
	while( $data = $wowdb->getrow( $result ) ) {
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}
?>