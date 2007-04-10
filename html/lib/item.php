<?php
$versions['versionDate']['item'] = '$Date: 2006/02/03 23:38:52 $'; 
$versions['versionRev']['item'] = '$Revision: 1.14 $'; 
$versions['versionAuthor']['item'] = '$Author: zanix $';

require_once 'wowdb.php';

class item
{
  var $data;

	function item( $data )
	{
		$this->data = $data;
	}

	function out()
	{
		global $img_url;
		global $img_suffix;
		global $roster_lang;
		global $wordings;

		$path = $img_url.preg_replace('|\\\\|','/', $this->data['item_texture']).'.'.$img_suffix;

		$first_line = True;
		foreach (explode("\n", $this->data['item_tooltip']) as $line )
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
				else if ( strpos( $line, $wordings[$roster_lang]['tooltip_set'] ) === 0 )
					$color = '00ff00; font-size: 10px;';
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

		$output = '<div class="item" onMouseover="return overlib(\''.$tooltip.'\');" onMouseout="return nd();">';
		extract($GLOBALS);
		if ($this->data['item_slot'] == 'Ammo')
		{
			$output .= '<a href="'.$itemlink[$roster_lang].urlencode(utf8_decode($this->data['item_name'])).'" target="_itemlink">'."\n".
			'<img src="'.$path.'" class="iconsmall"'." alt=\"\" /></a>\n";
		}
		else
		{
			$output .= '<a href="'.$itemlink[$roster_lang].urlencode(utf8_decode($this->data['item_name'])).'" target="_itemlink">'."\n".
			'<img src="'.$path.'" class="icon"'." alt=\"\" /></a>\n";
		}
		if( ($this->data['item_quantity'] > 1) && ($this->data['item_parent'] != 'bags') )
			$output .= '<span class="quant">'.$this->data['item_quantity'].'</span>';
		$output .= '</div>';

		return $output;
	}
}

function item_get_one( $member_id, $slot )
{
	global $wowdb;

	$slot = $wowdb->escape( $slot );
	$query = "SELECT * FROM `".ROSTER_ITEMSTABLE."` where member_id = $member_id and item_slot = '$slot'";
	if ($wowdb->sqldebug)
		print "<!-- $query --> \n";

	$result = $wowdb->query( $query );
	$data = $wowdb->getrow( $result );
	if( $data )
		return new item( $data );
	else
		return Null;

}

function item_get_many( $member_id, $parent )
{
	global $wowdb;

	if (isset($name)) $name = $wowdb->escape( $name );
	if (isset($server)) $server = $wowdb->escape( $server );
	$parent = $wowdb->escape( $parent );
	$query= "SELECT * FROM `".ROSTER_ITEMSTABLE."` where member_id = $member_id and item_parent = '$parent'";

	if ($wowdb->sqldebug)
		print "<!-- $query --> \n";

	$result = $wowdb->query( $query );

	$items = array();
	while( $data = $wowdb->getrow( $result ) )
	{
		$item = new item( $data );
		$items[$data['item_slot']] = $item;
	}
	return $items;
}
?>