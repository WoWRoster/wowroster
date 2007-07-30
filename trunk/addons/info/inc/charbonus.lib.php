<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character item bonus class
 * This is largely undocumented due to the fact that the WoWRoster.net dev team did not create this
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 * @subpackage ItemBonuses
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}



/**
 * Code originally from cybrey's 'Bonuses/Advanced Stats' addon
 * output formatting originally by dehoskins
 *
 * Modified by the roster dev team
 *
 * @package    CharacterInfo
 * @subpackage ItemBonuses
 */
class CharBonus 
{
	var $bonus = array();
	var $bonus_tooltip = array();
	var $locale;
	var $color;
	var $name;
	var $equip;
	var $item;

	/**
	 * Constructor
	 *
	 * @param object $char
	 * @return CharBonus
	 */
	function CharBonus( $char )
	{
		$this->equip = item::fetchManyItems($char->data['member_id'], 'equip', 'full');
	}

	function dumpBonus( )
	{
		global $roster, $addon;

		$bt = '<div class="char_panel" style="margin-left:20px;">
	<img src="' . $addon['image_path'] . 'icon_bonuses.gif" class="panel_icon" alt="" />
	<div class="panel_title">' . $roster->locale->act['item_bonuses_full'] . '</div>
	<div class="tab3">
		<div class="container">';

		/* @var $item item */
		foreach( $this->equip as $item )
		{
			// set basic item info
			$this->name = $item->name;
			$this->color = $item->color;
		//	$this->item = $item;
		//	$this->getBonus();
		}
		
//		$bt .= $this->printBonus();
//		$bt .= item::printTooltipIcon("Wristguards of Stability");
//		$bt .= 'Enchantments';
//		$row = 0;
//		foreach( $this->my_bonus as $key => $value )
//		{
//			$bt .= '		<div class="membersRowRight' . (($row%2)+1) . '" style="white-space:normal;" ' 
//				 . makeOverlib($this->my_tooltip[$key],str_replace('XX', $value, $key),'',2) . '>'
//				 . str_replace('XX', $value, $key) . "</div>\n";
//		$row++;
//		}
//aprint($this->my_bonus);
//aprint($this->my_tooltip);
//aprint($this->failed);
		$bt .= "		</div>\n	</div>\n</div>";

//		if( !empty($this->my_bonus) )
//		{
			return $bt;
//		}
//		else
//		{
//			return;
//		}
	}
	

//	}	function dumpBonus( )
//	{
//		global $roster, $addon;
//
//		foreach( $this->equip as $item )
//		{
//			$this->sortOutTooltip($item->data['item_tooltip'], $item->data['item_name'], $item->data['item_color'] );
//		}
//
//		$bt = '<div class="char_panel" style="margin-left:20px;">
//	<img src="' . $addon['image_path'] . 'icon_bonuses.gif" class="panel_icon" alt="" />
//	<div class="panel_title">' . $roster->locale->wordings[$this->lang]['item_bonuses_full'] . '</div>
//	<div class="tab3">
//		<div class="container">
//';
//		$row = 0;
//		foreach( $this->my_bonus as $key => $value )
//		{
//			$bt .= '		<div class="membersRowRight' . (($row%2)+1) . '" style="white-space:normal;" ' 
//				 . makeOverlib($this->my_tooltip[$key],str_replace('XX', $value, $key),'',2) . '>'
//				 . str_replace('XX', $value, $key) . "</div>\n";
//		$row++;
//		}
//
//		$bt .= "		</div>\n	</div>\n</div>";
//
//		if( !empty($this->my_bonus) )
//		{
//			return $bt;
//		}
//		else
//		{
//			return;
//		}
//	}

	function printBonus( )
	{
		$row = 0;
		$out = '';
		
		foreach( $this->my_bonus as $key => $value )
		{
			$out .= '		<div class="membersRowRight' . (($row%2)+1) . '" style="white-space:normal;" '
				  . makeOverlib($this->my_tooltip[$key],str_replace('XX', $value, $key),'',2) . '>'
				  . str_replace('XX', $value, $key) . "</div>\n";
			$row++;
		}

		return $out;
	}

	function getBonus( $item )
	{
		//todo make userconfig on types to display/process 
		$bonus_types = array(
							'Enchantments' => 'Items with Enchantments',
							'BaseStats' => 'White Stats from Armor and Weapons',
							'Gems' => 'Gem Bonus',
							'Effects' => 'Passive Bonus for Equiped Items',
							'Set' => 'Bonus from Armor or Weapon Sets',
							'Use' => 'On Use Bonus',
							'ChanceOnHit' => 'Chance on Hit Bonus'
							);
		

							  
//		$data = array();
//		
//		$data[] = ( isset($item->attributes['BaseStats']) ? $item->attributes['BaseStats'] : null);
//		$data[] = ( isset($item->attributes['Enchantment']) ? $item->attributes['Enchantment'] : null);
//		$data[] = ( isset($item->attributes['Gems'][1]['Bonus']) ? $item->attributes['Gems'][1]['Bonus'] : null );
//		$data[] = ( isset($item->attributes['Gems'][2]['Bonus']) ? $item->attributes['Gems'][2]['Bonus'] : null );
//		$data[] = ( isset($item->attributes['Gems'][3]['Bonus']) ? $item->attributes['Gems'][3]['Bonus'] : null );
//		$data[] = ( isset($item->attributes['Effects']['Equip']) ? $item->attributes['Effects']['Equip'] : null );
//		$data[] = ( isset($item->attributes['Effects']['Set']) ? $item->attributes['Effects']['Set'] : null );
		
		foreach( $data as $bonus )
		{
			$this->addBonus( $bonus, $item->name, $item->color );
		}
				
	}
		
	function addBonus( $bonus, $item, $color )
	{
		if( is_array($bonus) )
		{
			foreach( $bonus as $line )
			{
				if( strstr($line, " and ") )
				{
					$lines = explode(" and ", $line);
				
					foreach( $lines as $innerline )
					{
						$this->processBonus( trim($innerline), $item, $color );
						//aprint($innerline);
					}
				}
				else 
				{
					$this->processBonus( trim($line), $item, $color );
				}
			}
		}
		else 
		{
			if( strstr($bonus, " and ") )
			{
				$lines = explode(" and ", $bonus);
				
				foreach( $lines as $innerline )
				{
					$this->processBonus( trim($innerline), $item, $color );
				}
			}
			else 
			{
				$this->processBonus( trim($bonus), $item, $color );
			}
		}
	}		

	function processBonus( $bonus, $item, $color )
	{
		if( preg_match('/^\+(\d+) .+$/', $bonus, $matches) )
		{
			$modifier = $matches[1];
			$bonus_string = str_replace($modifier, 'XX', $bonus);
			$this->setBonus( $modifier, $bonus_string, $item, $color );
//			aprint($bonus_string);
		}
		else 
		{
			//aprint($bonus);
		}
	}
	
	/**
	 * setBonus sets up the tooltips
	 *
	 * @param int $modifier |   12
	 * @param string $string |  XX Strength
	 * @param string $item_name |   name
	 * @param string $item_color |   color
	 */
	function setBonus( $modifier, $string, $item_name, $item_color )
	{
		$html = '<span style="color:#' . $item_color . ';">' . $item_name . '</span> : ' . $modifier;

		if ( array_key_exists($string, $this->my_bonus['Totals']) )
		{
			$this->my_bonus['Totals'][$string] = $this->my_bonus['Totals'][$string] + $modifier;
			$this->my_tooltip['Totals'][$string] = $this->my_tooltip['Totals'][$string] . '<br />' . $html;
		}
		else
		{
			$this->my_bonus['Totals'][$string] = $modifier;
			$this->my_tooltip['Totals'][$string] = $html;
		}
	}
	
	function sortOutTooltip( $tooltip, $item_name, $item_color )
	{
		global $roster;

		$lines = explode(chr(10), $tooltip);

		foreach( $lines as $line )
		{
			if( (ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip'], $line)) && ( $this->hasNumeric($line)==FALSE) )
			{
				$this->setBonus( '', $line, $item_name,$item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip_restores'], $line) )
			{
				$this->setBonus( $this->getModifierMana($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip_when'], $line) )
			{
				$this->setBonus( $this->getModifierMana($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_equip'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_set'], $line) )
			{
				$this->setBonus( '', $line, $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_spell_damage'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $line, $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_healing_power'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $line, $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_chance_hit'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_reinforced_armor'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getModifierString( $line), $item_name, $item_color);
			}
			elseif( ereg('^'.$roster->locale->wordings[$this->lang]['tooltip_school_damage'], $line) )
			{
				$this->setBonus( $this->getModifier($line), $this->getString( $line), $item_name, $item_color);
			}
		}
	}


}