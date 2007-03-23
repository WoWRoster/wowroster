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

class item
{
  var $data;

	function item( $data )
	{
		$this->data = $data;
	}

	function out()
	{
		global $roster_conf, $wordings, $itemlink;

		$item_texture = preg_replace('|\\\\|','/', $this->data['item_texture']);
		$item_texture = preg_replace('|//|','/', $item_texture);

		$path = $roster_conf['interface_url'].$item_texture.'.'.$roster_conf['img_suffix'];

		$first_line = True;
		foreach (explode("\n", $this->data['item_tooltip']) as $line )
		{
			if( $first_line )
			{
				$color = substr( $this->data['item_color'], 2, 6 ) . '; font-size: 12px; font-weight: bold';
				$first_line = False;
			}
			else
			{
				if( substr( $line, 0, 2 ) == '|c' )
				{
					$color = substr( $line, 4, 6 ).';';
					$line = substr( $line, 10, -2 );
				}
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_use'] ) === 0 )
					$color = '00ff00;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_requires'] ) === 0 )
					$color = 'ff0000;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_reinforced'] ) === 0 )
					$color = '00ff00;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_equip'] ) === 0 )
					$color = '00ff00;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_chance'] ) === 0 )
					$color = '00ff00;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_enchant'] ) === 0 )
					$color = '00ff00;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_soulbound'] ) === 0 )
					$color = '00bbff;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_set'] ) === 0 )
					$color = '00ff00;';
				else if ( strpos( $line, $wordings[$roster_conf['roster_lang']]['tooltip_set'] ) === 4 )
					$color = 'd9b200;';
				elseif ( strpos( $line, '"' ) )
					$color = 'ffd517';
				else
					$color='ffffff;';
			}
			$line = preg_replace('|\\>|','&#8250;', $line );
			$line = preg_replace('|\\<|','&#8249;', $line );

			if( strpos($line,"\t") )
			{
				$line = str_replace("\t",'</td><td align="right" style="font-size:10px;color:white;">', $line);
				$line = '<table width="220" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;color:white;">'.$line.'</td></tr></table>';
				$tooltip .= $line;
			}
			elseif( $line != '')
				$tooltip .= "<span style=\"color:#$color\">$line</span><br />";
		}

		$tooltip = str_replace("'", "\'", $tooltip);
		$tooltip = str_replace('"','&quot;', $tooltip);
		$tooltip = str_replace('<','&lt;', $tooltip);
		$tooltip = str_replace('>','&gt;', $tooltip);


		$output = '<div class="item" onmouseover="overlib(\''.$tooltip.'\');" onmouseout="return nd();">';

		if ($this->data['item_slot'] == 'Ammo')
		{
			$output .= '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($this->data['item_name'])).'" target="_itemlink">'."\n".
			'<img src="'.$path.'" class="iconsmall"'." alt=\"\" /></a>\n";
		}
		else
		{
			$output .= '<a href="'.$itemlink[$roster_conf['roster_lang']].urlencode(utf8_decode($this->data['item_name'])).'" target="_itemlink">'."\n".
			'<img src="'.$path.'" class="icon"'." alt=\"\" /></a>\n";
		}
		if( ($this->data['item_quantity'] > 1) )
		{
			$output .= '<span class="quant_shadow">'.$this->data['item_quantity'].'</span>';
			$output .= '<span class="quant">'.$this->data['item_quantity'].'</span>';
		}
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
	$data = $wowdb->fetch_assoc( $result );
	if( $data )
		return new item( $data );
	else
		return null;

}

function item_get_many( $member_id, $parent )
{
	global $wowdb;

	$parent = $wowdb->escape( $parent );
	$query= "SELECT * FROM `".ROSTER_ITEMSTABLE."` where member_id = $member_id and item_parent = '$parent'";

	if ($wowdb->sqldebug)
		print "<!-- $query --> \n";

	$result = $wowdb->query( $query );

	$items = array();
	while( $data = $wowdb->fetch_assoc( $result ) )
	{
		$item = new item( $data );
		$items[$data['item_slot']] = $item;
	}
	return $items;
}
?>