<?php
require_once 'wowdb.php';

class recipe {
	var $data;

	function recipe( $data ) {
		$this->data = $data;
	}

	function out($difficultycolor) {
		global $img_url;
		global $img_suffix;

		$path = $img_url.preg_replace('|\\\\|','/', $this->data['recipe_texture']).'.'.$img_suffix;

		$first_line = True;
		foreach (explode("\n", $this->data['recipe_tooltip']) as $line ) {
			$class='tooltipline';
			if( $first_line ) {
				$color = $difficultycolor . ";font-weight: bold";
				$first_line = False;
				$class='tooltipheader';
			} else {
				if( substr( $line, 0, 2 ) == '|c' ) {
					$color = substr( $line, 4, 6 ).'; font-size: 10px;';
					$line = substr( $line, 10, -2 );
				} else if ( substr( $line, 0, 4 ) == 'Use:' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 8 ) == 'Requires' ) {
					$color = 'ff0000; font-size: 10px;';
				} else if ( substr( $line, 0, 10 ) == 'Reinforced' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 6 ) == 'Equip:' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 6 ) == 'Chance' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 8 ) == 'Enchant:' ) {
					$color = '00ff00; font-size: 10px;';
				} else if ( substr( $line, 0, 9 ) == 'Soulbound' ) {
					$color = '00ffff; font-size: 10px;';
				} else {
					$color='ffffff; font-size: 10px;';
				}
			}
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );
			if( $line != '') {
				$tooltip = $tooltip."<span class=\"$class\" style=\"color:#$color;\">$line</span><br>";
			}
		}
		$tooltip = str_replace("'", "\'", $tooltip);
		$tooltip = str_replace('"','&quot;', $tooltip);
		echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$tooltip.'\');" onMouseout="return nd();">';

		echo '<div class="item">';
		extract($GLOBALS);
		echo '<a href="'.$itemlink.urlencode($this->data['recipe_name']).'" target="_thottbot">
		<img src="'.$path.'" class="icon"'." /></a>\n";
		echo '</div></span>';
	}
}
//end modification

function recipe_get_many( $member_id, $search, $sort ) {
	global $wowdb;
	if (isset($char)) { $char = $wowdb->escape( $char ); }
	if (isset($server)) { $server = $wowdb->escape( $server ); }
	$query= "SELECT * FROM `".ROSTER_RECIPESTABLE."` where `member_id` = '$member_id'";

	if ($sort == 'item') {
		$query=$query." ORDER BY `skill_name` ASC , `difficulty` DESC , recipe_type ASC , recipe_name ASC";
	} else if ($sort == 'name') {
		$query=$query." ORDER BY `skill_name` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	} else if ($sort == 'type') {
		$query=$query." ORDER BY `skill_name` ASC , recipe_type ASC , recipe_name ASC , `difficulty` DESC";
	} else if ($sort == 'reagents') {
		$query=$query." ORDER BY `skill_name` ASC , `reagents` ASC , recipe_name ASC , recipe_type ASC , `difficulty` DESC";
	} else {
		$query=$query." ORDER BY `skill_name` ASC , `difficulty` DESC , recipe_type ASC , recipe_name ASC";
	}

	$result = $wowdb->query( $query );
	$recipes = array();
	while( $data = $wowdb->getrow( $result ) ) {
		$recipe = new recipe( $data );
		$recipes[] = $recipe;
	}
	return $recipes;
}
?>