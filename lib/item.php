<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Item class
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

if( !defined('ROSTER_INSTALLED') )
{
	exit('Detected invalid access to this file!');
}

class item
{
	var $data;

	var $item_id, $name, $level, $icon, $slot, $parent, $tooltip, $html_tooltip, $quality_id, $quality, $locale, $quantity;
	var $attributes;
	var $bonus;
	var $enchantments;
	var $info = array();  // parsed item info array

	function item( $data )
	{
		$this->data = $data;

		$this->item_id = $data['item_id'];
		$this->name = $data['item_name'];
		$this->level = $data['item_level'];
		$this->icon = $data['item_texture'];
		$this->slot = $data['item_slot'];
		$this->parent = $data['item_parent'];
		$this->tooltip = $data['item_tooltip'];
		$this->color = $data['item_color'];
//		$this->locale = $data['clientLocale'];
		$this->quantity = $data['item_quantity'];
		$this->set_Quality($this->color);
//		$this->parse_tooltip();  // disabled until i finish the support methods.
	}

	function out( )
	{
		global $roster, $tooltips;

		$lang = ( isset($this->data['clientLocale']) ? $this->data['clientLocale'] : $roster->config['locale']);

		$path = $roster->config['interface_url'].'Interface/Icons/'.$this->data['item_texture'].'.'.$roster->config['img_suffix'];

		$tooltip = makeOverlib($this->data['item_tooltip'],'',$this->data['item_color'],0,$lang, '');

		// Item links
		$num_of_tips = (count($tooltips)+1);
		$linktip = '';
		foreach( $roster->locale->wordings[$lang]['itemlinks'] as $key => $ilink )
		{
			$linktip .= '<a href="'.$ilink.urlencode(utf8_decode($this->data['item_name'])).'" target="_blank">'.$key.'</a><br />';
		}
		setTooltip($num_of_tips,$linktip);
		setTooltip('itemlink',$roster->locale->wordings[$lang]['itemlink']);

		$linktip = ' onclick="return overlib(overlib_'.$num_of_tips.',CAPTION,overlib_itemlink,STICKY,NOCLOSE,WRAP,OFFSETX,5,OFFSETY,5);"';

		$output = '<div class="item" '.$tooltip.$linktip.'>';

		if ($this->data['item_slot'] == 'Ammo')
		{
			$output .= '<img src="'.$path.'" class="iconsmall"'." alt=\"\" />\n";
		}
		else
		{
			$output .= '<img src="'.$path.'" class="icon"'." alt=\"\" />\n";
		}

		if( ($this->data['item_quantity'] > 1) )
		{
			$output .= '<b>'.$this->data['item_quantity'].'</b>';
			$output .= '<span>'.$this->data['item_quantity'].'</span>';
		}
		$output .= '</div>';

		return $output;
	}

	function set_Quality( $color )
	{
		switch( strtolower( $color ) )
		{
			case 'ff8800':
				$this->quality_id = '6';
				$this->quality = 'legendary';
				break;
			case 'a335ee':
				$this->quality_id = '5';
				$this->quality = 'epic';
				break;
			case '0070dd':
				$this->quality_id = '4';
				$this->quality = 'rare';
				break;
			case '1eff00':
				$this->quality_id = '3';
				$this->quality = 'uncommon';
				break;
			case 'ffffff':
				$this->quality_id = '2';
				$this->quality = 'common';
				break;
			case '9d9d9d':
				$this->quality_id = '1';
				$this->quality = 'poor';
				break;
		}
	}

	function parse_tooltip()
	{
		global $roster;

		//ini_set('default_charset', 'UTF-8');  // -- debugging only REMOVE ME!

		$locale = $this->locale;
		$tooltip = $this->tooltip;
		list($itemid, $enchant, $gem1, $gem2, $gem3) = explode(':', $this->item_id);

		$tt = array();
		$setpiece = null; //flag for parsing

		//cleanup tooltip for array conversion and parsing
		$tooltip = str_replace("\n\n", "\n", $tooltip);
		$tooltip = str_replace('<br>',"\n",$tooltip);
		$tooltip = str_replace('<br />',"\n",$tooltip);
		$tooltip = preg_replace( '/\|c[a-f0-9]{2}[a-f0-9]{6}(.+?)\|r/', '$1', $tooltip );

		//
		//need a better pattern for poisons! this sucks badly.  <--
		if( preg_match('/\n(.+[VI].\(.+\))/i', $tooltip, $matches) )
		{
			$tooltip = str_replace( $matches[0], '', $tooltip );
			$tt['Poisons'][] = $matches[1];
		}
		//
		// need a way to find out if this bonus is in effect or not
		// perhaps pull base stat info from a db.. check into itemstats mod?
		if( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_socketbonus'], $tooltip, $matches) )
		{
			$tooltip = str_replace( $matches[0], '', $tooltip );
			$tt['UNKNOWN'][] = $matches[0];
		}
		//
		// get gem infomation, put into tracking array, remove bonus string for further parsing
		if( $gem1 || $gem2 || $gem3 )
		{
			$gems = array($gem1,$gem2,$gem3);
			$i = 1;
			foreach( $gems as $gem )
			{
				if( $gem )
				{
					//might need to tighten the replacement of stats?
					$tt['Attributes']['Gem'.$i]['data'] = $this->getGem($gem);
					if( !isset ($tt['Attributes']['Gem'.$i]['data']['gem_bonus']) )
					{
						trigger_error('Unable to find gem_socketid: '.$gem.' locale: '.$this->locale. ' in Gems table! ['.$this->item_id.']' );
					}
					else 
					{
					$tooltip = str_replace( $tt['Attributes']['Gem'.$i]['data']['gem_bonus']."\n", '', $tooltip);
					}
				}
				$i++;
			}
		}

		//
		// if itemid shows an enchant on the item parse for it.
		// First find 'durability' and the line above that is the enchantment.
		// if the tooltip does not have 'durability' then the item must be a cloak, if thats true look for 'Requires Level'
		// string above that string it must be the enchant.  Remove the line from further parsing.
		if( $enchant )
		{
			if( preg_match('/\n(.+)\n'.$roster->locale->wordings[$locale]['tooltip_durability'].'/i', $tooltip, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Bonus']['Enchantment'] = $matches[1];
			}
			elseif( preg_match( '/\n(.+)\n'.$roster->locale->wordings[$locale]['tooltip_requires'].'/i', $tooltip, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Bonus']['Enchantment'] = $matches[1];
			}
		}

		$tooltip = explode("\n", $tooltip);

		$tt['General']['Name'] = array_shift($tooltip);
		$tt['General']['Item_ID'] = $this->item_id;
		$tt['General']['ItemColor'] = $this->color;
		$tt['General']['Icon'] = $this->icon;
		$tt['General']['Slot'] = $this->slot;
		$tt['General']['Parent'] = $this->parent;
		$tt['General']['tooltip'] = str_replace("\n", '<br>', $this->tooltip);
		$tt['General']['Locale']=$this->locale;
		$tt['Attributes']['Quality']['id']=$this->quality_id;
		$tt['Attributes']['Quality']['name']=$this->quality;
		$tt['Attributes']['Quantity']=$this->quantity;


		foreach( $tooltip as $line )
		{
			if( ereg('^' . $roster->locale->wordings[$locale]['tooltip_use'], $line ) )
			{
				//Use:
				$tt['Effects']['Use'][] = $line;
			}
			//
			// at this point any line prefixed with a + must be a White Stat (or base stat).
			// also look for shield block ratings for basestats
			elseif( preg_match('/^\+(\d+) (.+)/i', $line, $matches))
			{
				$tt['Attributes']['BaseStats'][$matches[2]] = $matches[0];
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_requires'], $line ) )
			{
				//Requires
				$tt['Attributes']['Requires'][] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_equip'], $line ) )
			{
				//Equip:
				$tt['Effects']['Equip'][] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_chance'],$line) )
			{
				//Chance
				$tt['Effects']['ChanceOnHit'][] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_bind_types'],$line) )
			{
				//soulbound, bop, quest item etc
				$tt['Attributes']['BindType'] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_set'],$line) )
			{
				//set piece bonus
				$tt['Effects']['SetBonus'][] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_misc_types'], $line ) )
			{
				$tt['Attributes']['ArmorSlot'] = $line;
			}
			elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_durability'], $line, $matches ) )
			{
				$tt['Attributes']['Durability']['Line']= $matches[0];
				$tt['Attributes']['Durability']['Current'] = $matches[1];
				$tt['Attributes']['Durability']['Max'] = $matches[2];
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_classes'], $line, $matches ) )
			{
				$tt['Attributes']['Class'] = explode(', ', $matches[1]);
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_races'], $line, $matches ) )
			{
				$tt['Attributes']['Race'] = explode(', ', $matches[1]);
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_charges'], $line ) )
			{
				$tt['Attributes']['Charges'] = $line;
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_block'], $line, $matches ) )
			{
				$tt['Attributes']['BaseStats'][$matches[2]] = $matches[0];
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_emptysocket'], $line, $matches ) )
			{
				$tt['Attributes']['Sockets'][] = $matches[1];
			}
			elseif( ereg( $roster->locale->wordings[$locale]['tooltip_poisoneffect'], $line ) )
			{
				$tt['Poison']['Effect'][] = $line;
			}
//			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_rank'], $line ) )
//			{
//				$tt['Attributes']['Rank'] = $line;
//			}
//			elseif(ereg('^' . $roster->locale->wordings[$locale]['tooltip_next_rank'],$line) )
//			{
//				$tt['Attributes']['NextRank'] = $line;
//			}
			elseif( preg_match('/\([a-f0-9]\).' . $roster->locale->wordings[$locale]['tooltip_set'].'/i',$line) )
			{
				$tt['Attributes']['InactiveSet'][] = $line;
			}
			elseif( ereg('^"',$line) )
			{
				// item comment or item writing note?
				$tt['Attributes']['ItemNote'] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_unique'], $line ) )
			{
				$tt['Attributes']['Unique'] = $line;
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_armour'], $line, $matches ) )
			{
				$tt['Attributes']['ArmorClass'] = $matches[1];
			}
			elseif( strpos($line,"\t") )
			{
				$line = explode("\t",$line);

				if( ereg($roster->locale->wordings[$locale]['tooltip_armour_types'], $line[1] ) )
				{
					$tt['Attributes']['ArmorType'] = $line[1];
					$tt['Attributes']['ArmorSlot'] = $line[0];
				}
				elseif( ereg($roster->locale->wordings[$locale]['tooltip_weapon_types'], $line[1] ) )
				{
					$tt['Attributes']['WeaponType'] = $line[1];
					$tt['Attributes']['WeaponSlot'] = $line[0];
				}
				elseif( ereg($roster->locale->wordings[$locale]['tooltip_speed'], $line[1]) )
				{
					$tt['Attributes']['WeaponSpeed'] = $line[1];
					$tt['Attributes']['WeaponDamage'] = $line[0];
				}

			}
			elseif( ereg('^\(|^Adds ', $line))  // -- work on this LOCALIZE ME!
			{
				$tt['Attributes']['WeaponDPS'] = $line;
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_madeby'], $line, $matches ) )
			{
				$tt['Attributes']['CraftedBy'] = $matches[1];
			}
			elseif( preg_match('/^(.+) \(\d+\/\d+\)$/', $line, $matches ) )
			{
				$tt['ArmorSet']['Name'] = $matches[1];
				$setpiece = 1;
			}
			elseif( $setpiece )
			{
				if( strlen($line) > 4)
				{
					$tt['ArmorSet'][$setpiece] = trim($line);
					$setpiece++;
				}
				else
				{
					$setpiece=false;
				}
			}
			//
			//check if its a bag
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_bags'], $line, $matches ) )
			{
				$tt['General']['BagSize'] = $matches[1];
				$tt['General']['BagDesc'] = $line;
			}
			else
			{
				//
				// pass 2
				if( $line !== '' && $line !== ' ' && !ereg( $roster->locale->wordings[$locale]['tooltip_garbage'], $line ) )
				{
					//
					// check to match more simpler items
					// can put less common searches in pass 2 like bags?
					if( ereg( $roster->locale->wordings[$locale]['tooltip_weapon_types'], $line ) )
					{
						$tt['Attributes']['WeaponSlot'] = $line;
					}
					else 
					{					
						//
						//if all else fails its an unexpected line
						$unparsed[]=$line;
					}
				}
			}

		}

//		echo '<hr><br />';
//		aprint($tt);
//
		if( isset( $unparsed ) )
		{
//			global $idx;
//			$idx++;
//			echo "($idx)".'Failed to Parse: ('.$this->name.') ['.$this->item_id.']<br />';
//			aprint($unparsed); 
//			aprint($tt);
			trigger_error( "Failed to Parse : [$this->item_id] ($this->locale)<br>". implode('<br>', $unparsed) );
		}
		else
		//{
		//echo 'Fully Parsed! <br />';
		//}
		$this->info = $tt;
	}

	function getGem($gem_id, $locale=false)
	{
		if( !$gem_id )
		{
			return false;
		}
		
		if( !$locale )
		{
			$locale = $this->locale;
		}

		global $roster, $gem_cache;

		if( isset( $gem_cache[$gem_id][$locale] ) )
		{
			return $gem_cache[$gem_id][$locale];
		}

		$sql = "SELECT * FROM `" . $roster->db->table('gems') . "` WHERE `gem_socketid` = '" . $gem_id . "' AND `locale` = '" . $locale . "'";
		$result = $roster->db->query($sql);
		$gem = $roster->db->fetch($result, SQL_ASSOC);
		$gem['gem_tooltip'] = str_replace("\n", '<br>', $gem['gem_tooltip']);  // -- REMOVE LATER DEBUGGING
		$gem_cache[$gem_id][$locale]=$gem;

		return $gem;
	}
} //end class item

function item_get_one( $member_id , $slot )
{
	global $roster;

	$slot = $roster->db->escape( $slot );
	$query = "SELECT `i`.*, `p`.`clientLocale` FROM `".$roster->db->table('items')."` AS i, `".$roster->db->table('players')."` AS p WHERE `i`.`member_id` = '$member_id' AND `item_slot` = '$slot';";

	$result = $roster->db->query( $query );
	$data = $roster->db->fetch( $result );
	if( $data )
	{
		return new item( $data );
	}
	else
	{
		return null;
	}

}

function item_get_many( $member_id , $parent )
{
	global $roster;

	$parent = $roster->db->escape( $parent );
	/*	$query= "SELECT `i`.*, `p`.`clientLocale`
	FROM
	`".$roster->db->table('items')."` AS i,
	`".$roster->db->table('players')."` AS p
	WHERE `i`.`member_id` = '$member_id'
	AND `p`.`member_id` = '$member_id' ";

	*/	//AND `i`.`item_id` = '25689:2792:2698:2698:2698:0:0:1979837656'";
	// -- REPLACE WITH COMMENTED DUMPS ALL ITEMS FROM CHAR

	$query= "SELECT `i`.*, `p`.`clientLocale`
				FROM
				`".$roster->db->table('items')."` AS i,
				`".$roster->db->table('players')."` AS p 
				WHERE `i`.`member_id` = '$member_id'
				AND `p`.`member_id` = '$member_id'
				AND `item_parent` = '$parent'";

	$result = $roster->db->query( $query );
//echo "Number of items parsed: ". $roster->db->num_rows( $result ) . "!! <br /><br />";
	$items = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$item = new item( $data );

		$items[$data['item_slot']] = $item;
		$items['Info'] = $item->info;
	}
	return $items;
}

//
// [ Debugging function dumps arrays/object formatted
// not sure who the author is or where i got the function... can remove later

function _aprint($arr, $tab = 1) // similar to print_r()
{
	if( !is_array($arr) ) return " <span style=\"color:#336699\">".ucfirst(gettype($arr))."</span> ".$arr;

	$space = str_repeat("\t", $tab);
	$out = " <span style=\"color:#336699\">array(</span>\n";
	end($arr); $end = key($arr);

	if( count($arr) == 0 )
	return "<span style=\"color:#336699\">array()</span>";

	foreach( $arr  as $key=>$val ){
		if( $key == $end ) $colon = ''; else $colon = ',';
		if( !is_numeric($key) ) $key = "<span style=\"color:#993366\">'".str_replace( array("\\","'"), array("\\\\","\'"), htmlspecialchars($key) )."'</span>";
		if(  is_array($val)   ) $val = _aprint($val, ($tab+1)); else
		if( !is_numeric($val) ) $val = "<span style=\"color:#993366\">'".str_replace( array("\\","'"), array("\\\\","\'"), htmlspecialchars($val) )."'</span>";
		$out .= "$space$key => $val$colon\n";
	}

	if( $tab == 1 )
	return "$out$space<span style=\"color:#336699\">)</span>;"; else
	return "$out$space<span style=\"color:#336699\">)</span>";
}


function aprint( $arr, $prefix=''){
	if( ltrim($prefix) != '' ) $prefix = '<span style="color:#336699">'.$prefix.'</span> =';
	echo "\n\n<table style=\"width:100%; margin:1px; background:#F0F2F4; border:1px solid #D8DDE6;\">
<tbody><tr><td><pre style=\"color:#000000;\">$prefix"._aprint($arr)."</pre></td></tr></tbody></table>\n\n";
}

