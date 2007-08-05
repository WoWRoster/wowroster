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
	var $locale; // roster language
	var $item_locale; // working item locale
	var $color;
	var $name;
	var $equip;
	/**
	 * item object for current bonus
	 *
	 * @var item
	 */
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
		//build header of bonus box
		$out = '<div class="char_panel" style="margin-left:20px;">
	<img src="' . $addon['image_path'] . 'icon_bonuses.gif" class="panel_icon" alt="" />
	<div class="panel_title">' . $roster->locale->act['item_bonuses_full'] . '</div>';
		//start content here..move to foreach dynamic building of tabs move this to the printBonus() method

		/* @var $item item */
		foreach( $this->equip as $item )
		{
			// set basic item info
			$this->name = $item->name;
			$this->color = $item->color;
			$this->item_locale = $item->locale;
			$this->item = $item;
			$this->getBonus();
		}
		
		$out .= $this->printBonus();		
		return $out;
	}

//		$bt .= '
//
//	<div class="tab3" id="tab2b">
//		<div class="container">BaseStats
//		</div>
//	</div>
//	<div class="tab3" id="tab3b">
//		<div class="container">Enchantment
//		</div>
//	</div>
//	<div class="tab3" id="tab4b">
//		<div class="container">Effects
//		</div>
//	</div>
//	<div class="tab3" id="tab5b">
//		<div class="container">Gems
//		</div>
//	</div>
//	<div class="tab3" id="tab6b">
//		<div class="container">Set
//		</div>
//	</div>
//	<div class="tab3" id="tab7b">
//		<div class="container">Use';
//		$bt .= '		</div>	</div>
//	<div class="tab_navagation" style="margin:428px 0 0 17px;">
//		<ul id="bonus_navagation">
//			<li class="selected"><a rel="tab1b" class="text">Totals</a></li>
//			<li><a rel="tab2b" class="text">BaseStats</a></li>
//			<li><a rel="tab3b" class="text">Enchantment</a></li>
//			<li><a rel="tab4b" class="text">Effects</a></li>
//			<li><a rel="tab5b" class="text">Gems</a></li>
//			<li><a rel="tab6b" class="text">Set</a></li>
//			<li><a rel="tab7b" class="text">Use</a></li>
//		</ul>
//	</div></div>
//<script type="text/javascript">
//	initializetabcontent(\'bonus_navagation\')
//</script>';

	

	// prints out all status based on array of catagories
	function printBonus( )
	{
		//todo make userconfig on types to display/process 
		// can add Temp Enchants, Perm Enchants?
		// make array vals into tooltips for tabs?
		// localize this.
		// first key will be the default tab on inital load up
		$catagory = array('Totals' => 'Totals',
						  'Enchantment' => 'Enchantments',
						  'BaseStats' => 'White Stats from Armor and Weapons',
						  'Gems' => 'Gem Bonus',
						  'Effects' => 'Passive Bonus for Equiped Items',
						  'Set' => 'Bonus from Armor or Weapon Sets',
						  'Use' => 'On Use Bonus',
						  'ChanceOnHit' => 'Chance on Hit Bonus',
						  );
		
		$row = 0;
		$out = '';
		
		foreach( $catagory as $catkey => $catval )
		{
			// check to see if the catagory has data don't display is none
			if( isset($this->bonus[$catkey]) )
			{
				//$out .= $catval;
				$cat = $this->bonus[$catkey];
				$out .= '<div class="tab3" id="' . $catkey . '"><div class="container">';
				$tabs[] = $catkey;
				
				foreach( $cat as $key => $value )
				{
					$value = explode(':', $value);
					$out .= '<div class="membersRowRight' . (($row%2)+1) . '" style="white-space:normal;" '
						  . makeOverlib($this->bonus_tooltip[$catkey][$key], str_replace(array( 'XX', 'YY' ), $value, $key), '', 2) . '>'
						  . str_replace(array( 'XX', 'YY' ), $value, $key) . "</div>\n";
					$row++;
				}
			$out .= '</div> </div>';
			}
		}
		// build tabs
		$out .= '		</div>	</div>
	<div class="tab_navagation" style="margin:-6px 0 0 32px;">
		<ul id="bonus_navagation">';
		$first_tab = true;

		foreach( $tabs as $tab )
		{
			if( $first_tab )
			{
				$out .= '<li class="selected"><a rel="' . $tab . '" class="text">' . $tab . '</a></li>';
				$first_tab = false;
			}
			else 
			{
				$out .= '<li><a rel="' . $tab . '" class="text">' . $tab . '</a></li>';
			}
		}

		$out .= '		</ul>
	</div></div>
<script type="text/javascript">
	initializetabcontent(\'bonus_navagation\')
</script>';
		
//		echo "bonus";
//		aprint($this->bonus);

		return $out;
	}

	// gets all the bonus from the item and sets the tooltips
	function getBonus()
	{
		if( isset($this->item->attributes['BaseStats']) )
		{
			if( is_array($this->item->attributes['BaseStats']) )
			{
				foreach( $this->item->attributes['BaseStats'] as $bonus )
				{
					$this->addBonus($bonus, 'BaseStats', false);
				}
			}
			else 
			{
				$this->addBonus($this->item->attributes['BaseStats'], 'BaseStats', false);
			}
		}
		if( isset($this->item->attributes['Enchantment']) )
		{
			if( is_array($this->item->attributes['Enchantment']) )
			{
				foreach( $this->item->attributes['Enchantment'] as $bonus )
				{
					$this->addBonus($bonus, 'Enchantment', true);
				}
			}
			else 
			{
				$this->addBonus($this->item->attributes['Enchantment'], 'Enchantment', true);
			}
		}
		if( isset($this->item->attributes['Gems']) )
		{
			$gems[] = ( isset($this->item->attributes['Gems'][1]['Bonus']) ? $this->item->attributes['Gems'][1]['Bonus'] : 0 );
			$gems[] = ( isset($this->item->attributes['Gems'][2]['Bonus']) ? $this->item->attributes['Gems'][2]['Bonus'] : 0 );
			$gems[] = ( isset($this->item->attributes['Gems'][3]['Bonus']) ? $this->item->attributes['Gems'][3]['Bonus'] : 0 );
			foreach( $gems as $bonus )
			{
				if( $bonus )
				{
					$this->addBonus($bonus, 'Gems', true);
				}
			}
		}
		if( isset($this->item->effects['Equip']) )
		{
			foreach( $this->item->effects['Equip'] as $bonus )
			{
				$this->addBonus($bonus, 'Effects', false, 'Equip:');
			}
		}
		if( isset($this->item->attributes['Set']['SetBonus']) )
		{
			foreach( $this->item->attributes['Set']['SetBonus'] as $bonus )
			{
				$this->addBonus($bonus, 'Set', false, 'Set:');
			}
		}
		if( isset($this->item->effects['Use']) )
		{
			foreach( $this->item->effects['Use'] as $bonus )
			{
				$this->addBonus($bonus, 'Use', false, 'Use:');
			}
		}
	}
	
	//starts processing the passed in string
	//if $split_bonus is true will look for localized break string
	//and process them as two or more buffs
	//ZG enchants can have 3 bonus
	// if $strip_string is set remove string from bonus string
	
	function addBonus( $bonus, $catagory, $split_bonus=false, $strip_string=false )
	{
		global $roster;
		
		if( $strip_string )
		{
			$bonus = str_replace($strip_string, '', $bonus);
		}
		
		$bonus = trim($bonus);
		//
		// Warning: do not set $split_bonus true inside this if
		if( $split_bonus )
		{
			if( preg_match($roster->locale->wordings[$this->item_locale]['item_bonuses_preg_linesplits'], $bonus, $matches) )
			{
				$lines = explode($matches[1], $bonus);
				foreach( $lines as $line )
				{
					$this->addBonus( trim($line), $catagory );
				}
				return;
			}
			else 
			{
				$this->addBonus( $bonus, $catagory );
			}
			return;
		}
		//
		//
		
		if( preg_match_all('/(?!\d*\s(sec|min))(-{0,1}\d*\.{0,1}\d+)/', $bonus, $matches) )
		{		
			switch( count($matches[0]) )
			{
				case 1:
					$modifier = $matches[0][0];
					$bonus_string = str_replace($modifier, 'XX', $bonus);
					$this->setBonus( $modifier, $bonus_string, $catagory );
					return;					
				case 2:
					$modifier = $matches[0][0] . ':' . $matches[0][1];
					$bonus_string = str_replace($matches[0][0], 'XX', $bonus);
					$bonus_string = str_replace($matches[0][1], 'YY', $bonus_string);
					$this->setBonus( $modifier, $bonus_string, $catagory );
					return;
					
				default:
				case 3:
				case 4:
					$this->setBonus( '', $bonus, $catagory );
					return;
			}
		}
		else
		{
			$this->setBonus( '', $bonus, $catagory );
		}
		return;
	}
	
	/**
	 * setBonus sets up the tooltips
	 *
	 * @param int $modifier |   12
	 * @param string $string |  XX Strength
	 * @param string $catagory | Catagory this bonus belongs
	 */
	function setBonus( $modifier, $string, $catagory)
	{
		$orgStr = $string;
		$string = $this->standardizeBonus($string);
		if( $orgStr !== $string )
		{
			echo $orgStr . '<br>converted to: <br>' . $string . '<br><br><br>';
		}
		if( $catagory == 'Set' )
		{
			$html = '<span style="color:#ffd517;font-size:11px;font-weight:bold;">' 
				  . $this->item->attributes['Set']['ArmorSet']['Name'] . '</span>';
			
			$this->bonus['Totals'][$string] = $modifier;
			$this->bonus_tooltip['Totals'][$string] = $html;
			$this->bonus[$catagory][$string] = $modifier;
			$this->bonus_tooltip[$catagory][$string] = $html;
			
			return;
		}

		$html = '<span style="color:#' . $this->item->color . ';">' . $this->item->name . '</span> : ' . $modifier;
		
		if( strchr($modifier, ':') === true )
		{
			if( isset($this->bonus['Totals'][$string]) )
			{
				$this->bonus['Totals'][$string] = $this->doubleAdd($this->bonus['Totals'][$string], $modifier);
				$this->bonus_tooltip['Totals'][$string] = $this->bonus_tooltip['Totals'][$string] . '<br />' . $html;
				if( isset($this->bonus[$catagory][$string]) )
				{
					$this->bonus[$catagory][$string] = $this->doubleAdd($this->bonus[$catagory][$string], $modifier);
					$this->bonus_tooltip[$catagory][$string] = $this->bonus_tooltip[$catagory][$string] . '<br />' . $html;
				}
				else
				{
					$this->bonus[$catagory][$string] = $modifier;
					$this->bonus_tooltip[$catagory][$string] = $html;
				}
			}
			else
			{
				$this->bonus['Totals'][$string] = $modifier;
				$this->bonus_tooltip['Totals'][$string] = $html;
				$this->bonus[$catagory][$string] = $modifier;
				$this->bonus_tooltip[$catagory][$string] = $html;
			}
			return;
		}

		if( isset($this->bonus['Totals'][$string]) )
		{
			$this->bonus['Totals'][$string] = $this->bonus['Totals'][$string] + $modifier;
			$this->bonus_tooltip['Totals'][$string] = $this->bonus_tooltip['Totals'][$string] . '<br />' . $html;
			if( isset($this->bonus[$catagory][$string]) )
			{
				$this->bonus[$catagory][$string] = $this->bonus[$catagory][$string] + $modifier;
				$this->bonus_tooltip[$catagory][$string] = $this->bonus_tooltip[$catagory][$string] . '<br />' . $html;
			}
			else 
			{
				$this->bonus[$catagory][$string] = $modifier;
				$this->bonus_tooltip[$catagory][$string] = $html;
			}
		}
		else
		{
			$this->bonus['Totals'][$string] = $modifier;
			$this->bonus_tooltip['Totals'][$string] = $html;
			$this->bonus[$catagory][$string] = $modifier;
			$this->bonus_tooltip[$catagory][$string] = $html;
		}
	}

	/**
	 * Helper function that will add values paired together with a colen.
	 * 200:100 + 200:100 = 400:200
	 *
	 * @param string $value1
	 * @param string $value2
	 * @return string
	 */
	function doubleAdd( $value1, $value2 )
	{
		$value1 = explode(':', $value1);
		$value2 = explode(':', $value2);
		
		(string)$return = $value1[0] + $value2[0] . ':' . $value1[1] + $value2[1];
		return $return;
	}
	

	function standardizeBonus( $bonus )
	{
		global $roster;

		$bonus_map = $roster->locale->wordings[$this->item->locale]['item_bonuses_remap'];

		if( isset($bonus_map[strtolower($bonus)]) )
		{
			return $bonus_map[strtolower($bonus)];
		}
		else
		{
			return $bonus;
		}
	}

} // end class CharBonus


