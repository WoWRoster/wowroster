<?php
require_once 'wowdb.php';

class item {
  var $data;

	function item( $data ) {
		$this->data = $data;
	}

	function out( ) {
		global $img_url;
		global $img_suffix;

		$path = $img_url.preg_replace('|\\\\|','/', $this->data['item_texture']).'.'.$img_suffix;

		$first_line = True;
		foreach (explode("\n", $this->data['item_tooltip']) as $line ) {
			$class='tooltipline';
			if( $first_line ) {
				$color = substr( $this->data['item_color'], 2, 6 ) . '; font-weight: bold';
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
				$tooltip = $tooltip."<span class=\"$class\" style=\"color:#$color\">$line</span><br>";
			}
		}
		$tooltip = str_replace("'", "\'", $tooltip);
		$tooltip = str_replace('"','&quot;', $tooltip);
		echo '<span style="z-index: 1000;" onMouseover="return overlib(\''.$tooltip.'\');" onMouseout="return nd();">'."\n";

		echo '<div class="item">';
		extract($GLOBALS);
		if ($this->data['item_slot'] == 'Ammo') {
			echo '<a href="'.$itemlink.urlencode($this->data['item_name']).'" target="_thottbot">'."\n".
			'<img src="'.$path.'" class="iconsmall"'." /></a>\n";
		} else {
			echo '<a href="'.$itemlink.urlencode($this->data['item_name']).'" target="_thottbot">'."\n".
			'<img src="'.$path.'" class="icon"'." /></a>\n";
		}
		if( ($this->data['item_quantity'] > 1) && ($this->data['item_parent'] != 'bags') ) {
			echo '<span class="quant">'.$this->data['item_quantity'].'</span>';
		}
		echo '</div></span>';
	}
}

function item_get_one( $member_id, $slot ) {
	global $wowdb;

	$slot = $wowdb->escape( $slot );
	$query = "SELECT * FROM `".ROSTER_ITEMSTABLE."` where member_id = $member_id and item_slot = '$slot'";
	if ($wowdb->sqldebug) {
		print "<!-- $querystr --> \n";
	}
	$result = $wowdb->query( $query );
	$data = $wowdb->getrow( $result );
	if( $data ) {
		return new item( $data );
	} else {
		return Null;
	}
}

function item_get_many( $member_id, $parent ) {
	global $wowdb;

	if (isset($name)) { $name = $wowdb->escape( $name ); }
	if (isset($server)) { $server = $wowdb->escape( $server ); }
	$parent = $wowdb->escape( $parent );
	$query= "SELECT * FROM `".ROSTER_ITEMSTABLE."` where member_id = $member_id and item_parent = '$parent'";

	if ($wowdb->sqldebug) {
		print "<!-- $querystr --> \n";
	}
	$result = $wowdb->query( $query );

	$items = array();
	while( $data = $wowdb->getrow( $result ) ) {
		$item = new item( $data );
		$items[$data['item_slot']] = $item;
	}
	return $items;
}
?>