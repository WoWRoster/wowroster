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
	var $data = array(); // raw data from database
	var $item_id, $name, $level, $icon;
	var $slot, $parent, $tooltip, $html_tooltip;
	var $quality_id, $quality, $locale, $quantity;
	var $attributes = array(); // holds all parsed item attributes
	var $effects = array(); // holds passive bonus effects of the item
	var $isSetPiece, $isSocketable, $isEnchant, $isArmor, $isWeapon, $isPoison = false; // item flags for parsing
	var $enchantment;
	var $parsed_item = array();  // fully parsed item array

	var $DEBUG = false;
	
	
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
		$this->locale = $data['clientLocale'];
		$this->quantity = $data['item_quantity'];
		$this->setQuality($this->color);
		//maybe make a method to decide how to parse, full parse, simple, webdb (wowhead, itemstats, etc) oldschool (ie colortooltip?)
		$this->doParseTooltip();
		$this->setBaseStats();
		$this->makeTooltipHTML();
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

	/**
	 * private function to validate property data
	 *
	 * @param string $attribute
	 * @return string | returns the value of passed $attribute. Will return null if attribute is empty
	 */
	function _val( $attribute )
	{
		if( !empty($attribute) )
		{
			return $attribute;
		}
		else
		{
			return null;
		}
	}

// -- makeTooltipHTML() tooltip methods start here //
	
	/**
	 * Private function to return item caption in formated HTML
	 *
	 * @return string $html 
	 */
	function _getCaption()
	{
		$html = '<span style="color:#' . $this->color . ';font-size:14px;font-weight:bold;">' . $this->name . '</span><br />';
		return $html;
	}

	function _getBindType()
	{
		global $roster;

		$bindtype = $this->attributes['BindType'];

		if( ereg('^' . $roster->locale->wordings[$this->locale]['tooltip_soulbound'], $bindtype) )
		{
			$color = '00bbff';
		}
		else
		{
			$color = 'ffffff';
		}

		$html = '<span style="color:#' . $color . ';">' . $bindtype . '</span><br />';

		return $html;
	}

	function _getUnique()
	{
		$html = '<span style="color:#ffffff;">' . $this->attributes['Unique'] . '</span><br />';
		return $html;
	}

	function _getArmor()
	{
		$html = '<div style="width:100%;"><span style="float:right;">'
			  . $this->_val($this->attributes['ArmorType']) . '</span>'
			  . $this->_val($this->attributes['ArmorSlot']) . '</div>';
		
		return $html;
	}

	function _getWeapon()
	{
		$html = '<div style="width:100%;"><span style="float:right;">'
			  . $this->_val($this->attributes['WeaponType']) . '</span>'
			  . $this->_val($this->attributes['WeaponSlot']) . '</div>';

		if( isset($this->attributes['WeaponDamage']) )
		{
			$html  .= '<div style="width:100%;"><span style="float:right;">'
					. $this->_val($this->attributes['WeaponSpeed']) . '</span>'
					. $this->_val($this->attributes['WeaponDamage']) . '</div>'
					. $this->_val($this->attributes['WeaponDPS']) . '<br />';
		}
		return $html;
	}

	function _getBag()
	{
		$html = $this->attributes['BagDesc'] . '<br />';
		return $html;
	}
	
	function _getArmorClass()
	{
		$html = $this->attributes['ArmorClass']['Line'] . '<br />';
		return $html;
	}

	function _getBaseStats()
	{
		$html = '';
		$stats = array();
		$stats = $this->attributes['BaseStats'];

		foreach( $stats as $stat )
		{
			$html .= '<span style="color:#fffff0;">' . $stat . '</span><br />';
		}
		return $html;
	}

	function _getEnchantment()
	{
		$html = '<span style="color:#00ff00;">' . $this->attributes['Enchantment'] . '</span><br />';
		return $html;
	}

	/**
	 * Helper function that returns the localized color in english
	 *
	 * @param string $socket_color
	 * @return string $color
	 */
	function _socketColorEn( $socket_color )
	{
		if( $this->locale == 'enUS' )
		{
			return strtolower($socket_color);
		}

		global $roster;

		$colorArr = array_flip($roster->locale->wordings[$this->locale]['gem_colors']);
		return (string)strtolower($colorArr[$socket_color]);
	}

	function _getSockets()
	{
		global $roster;

		$html = '';

		//first lets do empty sockets
		if( isset($this->attributes['Sockets']) )
		{
			$emptysockets = $this->attributes['Sockets'];
			foreach( $emptysockets as $socket_color => $socket_line )
			{
				$html .= '<img src="' . $roster->config['interface_url'] . 'Interface/ItemSocketingFrame/ui-emptysocket-'
					   . $this->_socketColorEn($socket_color) . '.' . $roster->config['img_suffix'] . '"/>&nbsp;&nbsp;' . $socket_line . '<br />';
			}
		}
		//now lets do sockets with gems
		if( isset($this->attributes['Gems']) )
		{
			$gems = $this->attributes['Gems'];
			foreach( $gems as $gem )
			{
				$html .= '<img width="16px" height="16px" src="' . $roster->config['interface_url'] . 'Interface/Icons/'
					   . $gem['data']['gem_texture'] . '.' . $roster->config['img_suffix'] . '"/>'
					   . '<span style="color:#00ff00;">&nbsp;&nbsp;' . $gem['data']['gem_bonus'] . '</span><br />';
			}
		}
		return $html;
	}

	function _getSocketBonus()
	{
		if( isset($this->attributes['SocketBonus']) )
		{
			//at some point I need to evaluate if this bonus is in effect or not.  color grey for the time being
			$html = '<span style="color:#9d9d9d;">' . $this->attributes['SocketBonus'] . '</span><br />';
			return $html;
		}
		return null;
	}

	function _getDurability()
	{
		// $this->attributes['Durability']['Min']; $this->attributes['Durability']['Max']; also avaible for use if we want to get fancy
		$html = $this->attributes['Durability']['Line'] . '<br />';
		return $html;
	}

	function _getRequiredClasses()
	{
		global $roster;
		
		$html = $this->attributes['Class_Text'] . '&nbsp;';
		$count = count($this->attributes['Class']);
		
		$i = 0;
		foreach( $this->attributes['Class'] as $class )
		{
			$i++;
			$html .= '<span style="color:#'. $roster->locale->act['class_colorArray'][$class] . ';">' . $class . '</span>';
			if( $count > $i )
			{
				$html .= ', ';
			}
		}
		$html .= '<br />';
		return $html;
	}
	
	function _getRequiredRaces()
	{
		global $roster;
		
		$html = $this->attributes['Race_Text'] . '&nbsp;';
		$count = count($this->attributes['Race']);

		$i = 0;
		foreach( $this->attributes['Race'] as $race )
		{
			$i++;
			$html .= $race;
			if( $count > $i )
			{
				$html .= ', ';
			}
		}
		$html .= '<br />';
		return $html;
	}
	
	function _getRequiredLevel()
	{
		global $roster;

		$requires = array();
		$requires = $this->attributes['Requires'];
		foreach( $requires as $val )
		{
			if( preg_match($roster->locale->wordings[$this->locale]['requires_level'], $val) )
			{
				$html = '<span style="color:#ff0000;">' . $val . '</span><br />';
				return $html;
			}
		}
	}

	function _getPassiveBonus()
	{
		$html = '';
		$effects = array();
		$effects = $this->effects;

		foreach( $effects as $type )
		{
			foreach( $type as $effect)
			{
				$html .= '<span style="color:#00ff00;">' . $effect . '</span><br />';
			}
		}
		return $html;
	}

	function _getSetPiece()
	{
		$html = 'insert code for _getSetPiece()<br />';
		return $html;
	}

	function _getInactiveSetBonus()
	{
		$html = 'insert code for _getInactiveSetBonus()<br />';
		return $html;
	}

	function _getCrafter()
	{
		$html = '<span style="color:#00ff00;font-weight:bold;">' . htmlentities($this->attributes['MadeBy']['Line']) . '</span><br />';
		return $html;
	}

	function _getItemNote()
	{
		$html = '<span style="color:#ffd517;">' . $this->attributes['ItemNote'] . '</span><br />';
		return $html;
	}

	/**
	 * Reconstructs item's tooltip from parsed information.
	 * All HTML Styling is done in the private _getXX() methods
	 *
	 */
	function makeTooltipHTML()
	{
		$html_tt = $this->_getCaption();
		if( isset($this->attributes['BindType']) )
		{
			$html_tt .= $this->_getBindType();
		}
		if( isset($this->attributes['Unique']) )
		{
			$html_tt .= $this->_getUnique();
		}
		if( $this->isArmor )
		{
			$html_tt .= $this->_getArmor();
		}
		elseif( $this->isWeapon )
		{
			$html_tt .= $this->_getWeapon();
		}
		elseif( $this->isBag )
		{
			// not an Weapon or Armor... Treat as Item
			$html_tt .= $this->_getBag();
		}
		if( isset($this->attributes['ArmorClass']) )
		{
			$html_tt .= $this->_getArmorClass();
		}
		if( isset($this->attributes['BaseStats']) )
		{
			$html_tt .= $this->_getBaseStats();
		}
		if( isset($this->attributes['Enchantment']) )
		{
			$html_tt .= $this->_getEnchantment();
		}
		if( $this->isSocketable )
		{
			$html_tt .= $this->_getSockets();
			$html_tt .= $this->_getSocketBonus();
		}
		if( isset($this->attributes['Class']) )
		{
			$html_tt .= $this->_getRequiredClasses();
		}
		if( isset($this->attributes['Race']) )
		{
			$html_tt .= $this->_getRequiredRaces();
		}
		if( isset($this->attributes['Durability']) )
		{
			$html_tt .= $this->_getDurability();
		}
		if( isset($this->attributes['Requires']) )
		{
			$html_tt .= $this->_getRequiredLevel();
		}
		if( isset($this->effects) )
		{
			$html_tt .= $this->_getPassiveBonus();
		}
		if( $this->isSetPiece )
		{
			$html_tt .= $this->_getSetPiece();
			$html_tt .= $this->_getInactiveSetBonus();
		}
		if( isset($this->attributes['MadeBy']['Line']) )
		{
			$html_tt .= $this->_getCrafter();
		}
		if( isset($this->attributes['ItemNote']) )
		{
			$html_tt .= $this->_getItemNote();
		}

		if( $this->DEBUG )
		{
			echo '<table class="border_frame" cellpadding="0" cellspacing="1" width="350px"> <tr> <td>'
			. $html_tt
			. '<hr width="80%"> ' . str_replace("\n", '<br />', $this->tooltip)
			. '<hr width="80%"> ' . aprint($this->attributes)
			. '</td></tr></table><br />';
		}
		$this->html_tooltip = $html_tt;
	}

	/**
	 * set the BaseStats property of the item if it has any otherwise sets to false.
	 *
	 * @return void
	 */
	function setBaseStats()
	{
		if( isset($this->parsed_item['Attributes']['BaseStats']) )
		{
			$this->basestats = $this->parsed_item['Attributes']['BaseStats'];
		}
		else
		{
			$this->basestats = false;
		}
	}

	/**
	 * Sets the $quality and $quality_id property
	 * Takes the color of the item
	 *
	 * @param string $color | color of item
	 */
	function setQuality( $color )
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
			default:
				break;
		}
	}

	function doParseTooltip()
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
		if( preg_match('/\n(.+[VI].\(.+\))\n/i', $tooltip, $matches) )
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
			$tt['Attributes']['SocketBonus'] = $matches[0];
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
					$tt['Attributes']['Gems'][$i]['data'] = $this->getGem($gem);
					if( !isset ($tt['Attributes']['Gems'][$i]['data']['gem_bonus']) )
					{
						trigger_error('Unable to find gem_socketid: ' . $gem . ' locale: ' . $this->locale . ' in Gems table! [' . $this->item_id . ']' );
					}
					else
					{
						$tooltip = str_replace( $tt['Attributes']['Gems'][$i]['data']['gem_bonus'] . "\n", '', $tooltip);
					}
				}
				$i++;
			}
			$this->isSocketable = true;
		}

		//
		// if itemid shows an enchant on the item parse for it.
		// First find 'durability' and the line above that is the enchantment.
		// if the tooltip does not have 'durability' then the item must be a cloak, if thats true look for 'Requires Level'
		// string above that string it must be the enchant.  Remove the line from further parsing.
		if( $enchant )
		{
			$this->isEnchant = true;

			if( preg_match('/\n(.+)\n'.$roster->locale->wordings[$locale]['tooltip_durability'].'/i', $tooltip, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Attributes']['Enchantment'] = $matches[1];
			}
			elseif( preg_match( '/\n(.+)\n'.$roster->locale->wordings[$locale]['tooltip_reg_requires'].'/i', $tooltip, $matches) )
			{
				$tooltip = str_replace( $matches[1], '', $tooltip );
				$tt['Attributes']['Enchantment'] = $matches[1];
			}
			elseif( preg_match_all('/\+\d+.+/', $tooltip, $matches) )
			{
				//last chance.. lets grab the last + stat in the tooltip and call that the enchantment.
				//this should be ok, because the item most likely is a cloak 
				$tooltip = str_replace($matches[0][count($matches[0])-1], '', $tooltip);
				$tt['Attributes']['Enchantment'] = $matches[0][count($matches[0])-1];
			}
			else
			{
				//still failed to find the enchantment.  Report an error.
				trigger_error("Unable to parse the Enchantment! ($this->name) [ $this->item_id ]");
				$this->isEnchant = false;
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
			//
			// at this point any line prefixed with a + must be a White Stat (or base stat).
			if( preg_match('/^\+(\d+) (.+)/', $line, $matches) )
			{
				$tt['Attributes']['BaseStats'][$matches[2]] = $matches[0];
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_use'], $line) )
			{
				//Use:
				$tt['Effects']['Use'][] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_reg_requires'], $line) )
			{
				//Requires
				$tt['Attributes']['Requires'][] = $line;
			}
			elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_equip'], $line) )
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
			elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_durability'], $line, $matches) )
			{
				$tt['Attributes']['Durability']['Line']= $matches[0];
				$tt['Attributes']['Durability']['Current'] = $matches[1];
				$tt['Attributes']['Durability']['Max'] = $matches[2];
			}
			elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_classes'], $line, $matches) )
			{
				$tt['Attributes']['Class'] = explode(', ', $matches[2]);
				$tt['Attributes']['Class_Text'] = $matches[1];
			}
			elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_races'], $line, $matches) )
			{
				$tt['Attributes']['Race'] = explode(', ', $matches[2]);
				$tt['Attributes']['Race_Text'] = $matches[1];
			}
			elseif( preg_match($roster->locale->wordings[$locale]['tooltip_preg_emptysocket'], $line, $matches) )
			{
				// match[1] = color, match[0] full line  Red Socket
				$tt['Attributes']['Sockets'][$matches[1]] = $matches[0];
				$this->isSocketable = true;
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
				$tt['Attributes']['ArmorClass']['Line'] = $matches[0];
				$tt['Attributes']['ArmorClass']['Rating'] = $matches[1];
				// dont flag as armor here because weapons can have Armor too
			}
			elseif( strpos($line,"\t") )
			{
				$line = explode("\t",$line);

				if( ereg($roster->locale->wordings[$locale]['tooltip_armor_types'], $line[1] ) )
				{
					$tt['Attributes']['ArmorType'] = $line[1];
					$tt['Attributes']['ArmorSlot'] = $line[0];
					$this->isArmor = true;
				}
				elseif( ereg($roster->locale->wordings[$locale]['tooltip_weapon_types'], $line[1] ) )
				{
					$tt['Attributes']['WeaponType'] = $line[1];
					$tt['Attributes']['WeaponSlot'] = $line[0];
					$this->isWeapon = true;
				}
				elseif( ereg($roster->locale->wordings[$locale]['tooltip_speed'], $line[1]) )
				{
					$tt['Attributes']['WeaponSpeed'] = $line[1];
					$tt['Attributes']['WeaponDamage'] = $line[0];
					$this->isWeapon = true;
				}

			}
			elseif( ereg('^\(|^Adds ', $line))  // -- work on this LOCALIZE ME!
			{
				$tt['Attributes']['WeaponDPS'] = $line;
				$this->isWeapon = true;
			}
			elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_madeby'], $line, $matches ) )
			{
				$tt['Attributes']['MadeBy']['Name'] = $matches[1];
				$tt['Attributes']['MadeBy']['Line'] = $matches[0];
			}
			elseif( preg_match('/^(.+) \(\d+\/\d+\)$/', $line, $matches ) )
			{
				$tt['Attributes']['ArmorSet']['Name'] = $matches[1];
				$setpiece = 1;
			}
			elseif( $setpiece )
			{
				if( strlen($line) > 4)
				{
					$tt['Attributes']['ArmorSet'][$setpiece] = trim($line);
					$setpiece++;
				}
				else
				{
					$setpiece=false;
				}
			}
			else
			{
				//
				// pass 2
				// check for less common strings here. this will only get called if pass1 has failed to match anything on the current line
				if( $line !== '' && $line !== ' ' && !ereg( $roster->locale->wordings[$locale]['tooltip_garbage'], $line ) )
				{
					//
					// check to match more simpler items
					// also could have fell through the line split in pass1
					if( ereg( $roster->locale->wordings[$locale]['tooltip_weapon_types'], $line ) )
					{
						$tt['Attributes']['WeaponSlot'] = $line;
						$this->isWeapon = true;
					}
					//
					//check if its a bag
					elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_bags'], $line, $matches ) )
					{
						$tt['Attributes']['BagSize'] = $matches[1];
						$tt['Attributes']['BagDesc'] = $line;
						$this->isBag = true;
					}
					//
					//check if its a shield
					elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_block'], $line, $matches ) )
					{
						$tt['Attributes']['BaseStats'][$matches[2]] = $matches[0];
						$this->isArmor = true;
					}
					//
					//check if item has charges
					elseif( preg_match( $roster->locale->wordings[$locale]['tooltip_preg_charges'], $line ) )
					{
						$tt['Attributes']['Charges'] = $line;
					}
					//
					//check if item is a poison
					elseif( ereg( $roster->locale->wordings[$locale]['tooltip_poisoneffect'], $line ) )
					{
						$tt['Poison']['Effect'][] = $line;
						$this->isPoison = true;
					}
					elseif( ereg('^' . $roster->locale->wordings[$locale]['tooltip_misc_types'], $line) )
					{
						$tt['Attributes']['ArmorSlot'] = $line;
						$this->isArmor = true;
					}
					else
					{
						//
						//if all else fails its an unexpected line
						$unparsed[]=$line;
					}
				} // end pass2 if
			} // closed else pass1
		} // end foreach

		//echo '<hr><br />';
		//aprint($tt);
		//
		if( isset( $unparsed ) )
		{
			//global $idx;
			//$idx++;
			//echo "($idx)".'Failed to Parse: ('.$this->name.') ['.$this->item_id.']<br />';
			//aprint($unparsed);
			//aprint($tt);
			trigger_error( "Failed to Parse \"$this->name\": [$this->item_id] ($this->locale)<br>". implode('<br>', $unparsed) );
		}
		else
		//{
		//echo 'Fully Parsed! <br />';
		//}
		$this->parsedItem = $tt;
		$this->attributes = ( isset($tt['Attributes']) ? $tt['Attributes'] : null );
		$this->effects = ( isset($tt['Effects']) ? $tt['Effects'] : null );
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

		if( isset($gem_cache[$gem_id][$locale]) )
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

function item_get_one( $member_id, $slot )
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

	$query  = "SELECT `i`.*, `p`.`clientLocale` "
			. "FROM "
			. "`".$roster->db->table('items')."` AS i, "
			. "`".$roster->db->table('players')."` AS p "
			. "WHERE `i`.`member_id` = '$member_id' "
			. "AND `p`.`member_id` = '$member_id' "
			. "AND `item_parent` = '$parent' ";

	$result = $roster->db->query( $query );
	//echo "Number of items parsed: ". $roster->db->num_rows( $result ) . "!! <br /><br />";
	$items = array();
	while( $data = $roster->db->fetch( $result ) )
	{
		$item = new item( $data );

		$items[$data['item_slot']] = $item;
	}
	return $items;
}

//
// [ Debugging function dumps arrays/object formatted
// not sure who the author is or where I got the function... can remove later

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

