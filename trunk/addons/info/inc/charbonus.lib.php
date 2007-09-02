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

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}



/**
 * Code originally from cybrey's 'Bonuses/Advanced Stats' addon
 * output formatting originally by dehoskins
 * tabs formatting added by Zanix
 *
 * Modified by the roster dev team
 * Rewritten by ds to use new item class
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


	// prints out all status based on array of catagories
	function printBonus( )
	{
		global $roster;

		$row = 0;
		$out = '';
		$tabs = array();

		foreach( $roster->locale->act['item_bonuses_tabs'] as $catkey => $catval )
		{
			// check to see if the catagory has data don't display if none
			if( isset($this->bonus[$catkey]) )
			{
				$cat = $this->bonus[$catkey];
				$out .= '<div class="tab3" id="' . $catkey . '"><div class="container">';
				$tabs += array($catkey => $catval);
				//$tt = '';

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
		$out .= '		<!-- </div>	</div> -->
	<div class="tab_navagation" style="margin:428px 0 0 17px;">
		<ul id="bonus_navagation">';
		$first_tab = true;

		foreach( $tabs as $tab_id => $tab_txt )
		{
			if( $first_tab )
			{
				$out .= '<li class="selected"><a rel="' . $tab_id . '" class="text">' . $tab_txt . '</a></li>';
				$first_tab = false;
			}
			else
			{
				$out .= '<li><a rel="' . $tab_id . '" class="text">' . $tab_txt . '</a></li>';
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
		global $roster;

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
				$this->addBonus($bonus, 'Effects', false, $roster->locale->wordings[$this->item_locale]['tooltip_equip']);
			}
		}
		if( isset($this->item->attributes['Set']['SetBonus']) )
		{
			foreach( $this->item->attributes['Set']['SetBonus'] as $bonus )
			{
				$this->addBonus($bonus, 'Set', false, $roster->locale->wordings[$this->item_locale]['tooltip_set']);
			}
		}
		if( isset($this->item->effects['Use']) )
		{
			foreach( $this->item->effects['Use'] as $bonus )
			{
				$this->addBonus($bonus, 'Use', false, $roster->locale->wordings[$this->item_locale]['tooltip_use']);
			}
		}
		if( isset($this->item->effects['ChanceToProc']) )
		{
			foreach( $this->item->effects['ChanceToProc'] as $bonus )
			{
				$this->addBonus($bonus, 'ChanceToProc', false, $roster->locale->wordings[$this->item_locale]['tooltip_equip']);
			}
		}
		if( isset($this->item->attributes['TempEnchantment']) )
		{
			foreach( $this->item->attributes['TempEnchantment'] as $bonus )
			{
				$this->addBonus($bonus, 'TempEnchantment', false);
			}
		}
	}

	/**
	 * Calculate the passed $bonus string. split the bonus line if $split_bonus is true
	 * strip out passed $strip_string from $bonus
	 *
	 * @param string $bonus
	 * @param string $catagory
	 * @param bool $split_bonus
	 * @param string $strip_string
	 */
	function addBonus( $bonus, $catagory, $split_bonus=false, $strip_string=false )
	{
		global $roster;

		if( $strip_string )
		{
			$bonus = preg_replace('/' . $strip_string . '/i', '', $bonus);
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

		if( preg_match_all($roster->locale->wordings[$this->item_locale]['item_bonuses_preg_main'], $bonus, $matches) )
		{
			switch( count($matches[0]) )
			{
				case 1:
					$modifier = $matches[0][0];
					$bonus_string = $this->replaceOne($modifier, 'XX', $bonus);
					$this->setBonus( $modifier, $bonus_string, $catagory );
					return;
				case 2:
					$modifier = $matches[0][0] . ':' . $matches[0][1];
					$bonus_string = $this->replaceOne($matches[0][0], 'XX', $bonus);
					$bonus_string = $this->replaceOne($matches[0][1], 'YY', $bonus_string);
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
	 * standardize Bonus $string and calculate bonus and set tooltip
	 *
	 * @param int $modifier |   12
	 * @param string $string |  +XX Strength
	 * @param string $catagory | Catagory this bonus belongs
	 */
	function setBonus( $modifier, $string, $catagory )
	{
//		$orgStr = $string;
		$string = $this->standardizeBonus($string);
//		if( $orgStr !== $string )
//		{
//			echo $orgStr . '<br>converted to: <br>' . $string . '<br><br><br>';
//		}
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
				$this->bonus['Totals'][$string] = $this->_doubleAdd($this->bonus['Totals'][$string], $modifier);
				$this->bonus_tooltip['Totals'][$string] = $this->bonus_tooltip['Totals'][$string] . '<br />' . $html;
				if( isset($this->bonus[$catagory][$string]) )
				{
					$this->bonus[$catagory][$string] = $this->_doubleAdd($this->bonus[$catagory][$string], $modifier);
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
	 * Helper function that will add values paired together with a :
	 *
	 * @param string $value1	| 10:10
	 * @param string $value2	| 20:5
	 * @return string	| 30:15
	 */
	function _doubleAdd( $value1, $value2 )
	{
		$value1 = explode(':', $value1);
		$value2 = explode(':', $value2);

		(string)$return = $value1[0] + $value2[0] . ':' . $value1[1] + $value2[1];
		return $return;
	}

	/**
	 * if $bonus is found in $lang['item_bonuses_remap'] return standardized string
	 * otherwise return unmodified $bonus string
	 *
	 * @param string $bonus
	 * @return string
	 */
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

	// by: Dmitry Fedotov box at neting dot ru
	function replaceOne( $in, $out, $content )
	{
		if( $pos = strpos($content, $in) )
		{
			return substr($content, 0, $pos) . $out . substr($content, $pos+strlen($in));
		}
		else
		{
			return $content;
		}
	}
} // end class CharBonus


