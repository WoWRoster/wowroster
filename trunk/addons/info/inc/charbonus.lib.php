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
		$this->_formatTooltip(); // call this to format bonus tooltip html
		$out .= $this->printBonus();
		return $out;
	}


	// prints out all status based on array of catagories
	function printBonus( )
	{
		global $roster, $tooltips;

		$row = 0;
		$out = '<div id="overDiv2" style="position:absolute;visibility:hidden;z-index:1000;"></div>';
		$tabs = array();

		foreach( $roster->locale->act['item_bonuses_tabs'] as $catkey => $catval )
		{
			// check to see if the catagory has data don't display if none
			if( isset($this->bonus[$catkey]) )
			{
				$cat = $this->bonus[$catkey];
				$out .= '<div class="tab3" id="' . $catkey . '"><div class="container">';
				$tabs += array($catkey => $catval);

				foreach( $cat as $key => $value )
				{
					$value = explode(':', $value);
					$idx = count($tooltips)+1;
					setTooltip( $idx, $this->bonus_tooltip[$catkey][$key]['html'] );
					setTooltip( 'cap_' . $idx, str_replace(array( 'XX', 'YY' ), $value, $key) );

					$out .= '<div class="membersRowRight' . (($row%2)+1) . '" style="white-space:normal;cursor:pointer;"'
						  . ' onmouseover="return overlib(overlib_' . $idx . ',CAPTION,overlib_cap_' . $idx . ',WIDTH,325,HAUTO);" onmouseout="return nd();"'
						  . ' onclick="return overlib(overlib_' . $idx . ',CAPTION,overlib_cap_' . $idx . ',WIDTH,325,STICKY,OFFSETX,-5,OFFSETY,-5,HAUTO);">'
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
//		echo "bonus HTML:";
//		aprint($this->bonus_tooltip);

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
	 * Recursing Method:
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
		// Replace Keys
		if( preg_match_all($roster->locale->wordings[$this->item_locale]['item_bonuses_preg_main'], $bonus, $matches) )
		{
			switch( count($matches[0]) )
			{
				default:
				case 0:
					break;
				case 1:
					$modifier = $matches[0][0];
					$bonus = $this->replaceOne($modifier, 'XX', $bonus);
					$this->setBonus( $modifier, $bonus, $catagory );
					return;
				case 2:
					$modifier = $matches[0][0] . ':' . $matches[0][1];
					$bonus = $this->replaceOne($matches[0][0], 'XX', $bonus);
					$bonus = $this->replaceOne($matches[0][1], 'YY', $bonus);
					$this->setBonus( $modifier, $bonus, $catagory );
					return;
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
	 * @param bool $is_standardized | true to bypass bonus standardizing
	 */
	function setBonus( $modifier, $string, $catagory, $is_standardized=false )
	{
		if( !$is_standardized && $catagory != 'Use' && $catagory != 'ChanceToProc' && $catagory != 'Set' )
		{
			// pass modifier and $catagory in case two or more bonuses come of the function
			$string = $this->standardizeBonus($string, $modifier, $catagory);
		}

		$this->_makeTooltip($string, $modifier, $catagory);

	}

	function _makeTooltip( $string, $modifier, $catagory )
	{
		global $roster;

		if( $catagory == 'Set' )
		{
			$html = '<span style="color:#ffd517;font-size:13px;font-weight:bold;">'
				  . $this->item->attributes['Set']['ArmorSet']['Name'] . '</span>';

			$this->bonus['Totals'][$string] = $modifier;
			$this->bonus[$catagory][$string] = $modifier;
			$setName = addslashes($this->item->attributes['Set']['ArmorSet']['Name']);
			if( empty($this->bonus_tooltip['Totals'][$string][$setName]) )
			{
				$this->bonus_tooltip['Totals'][$string][$setName] = $html . '<br /><br />' . $this->_setNewSetBonusHTML();
				$this->bonus_tooltip[$catagory][$string][$setName] = $html . '<br /><br />' . $this->_setNewSetBonusHTML();
			}
			else
			{
				$this->bonus_tooltip['Totals'][$string][$setName] = $this->bonus_tooltip['Totals'][$string][$setName] . '<br />' . $this->_setNewSetBonusHTML();
				$this->bonus_tooltip[$catagory][$string][$setName] = $this->bonus_tooltip[$catagory][$string][$setName] . '<br />' . $this->_setNewSetBonusHTML();
			}
			return;
		}

		//
		// if set this is an existing bonus
		if( isset($this->bonus['Totals'][$string]) )
		{
			$this->bonus['Totals'][$string] = $this->_add($this->bonus['Totals'][$string], $modifier);
			if( isset($this->bonus[$catagory][$string]) )
			{
				$this->bonus[$catagory][$string] = $this->_add($this->bonus[$catagory][$string], $modifier);
			}
			else
			{
				$this->bonus[$catagory][$string] = $modifier;
			}
		}
		else // new bonus
		{
			$this->bonus['Totals'][$string] = $modifier;
			$this->bonus[$catagory][$string] = $modifier;
		}

		if( isset($this->bonus_tooltip['Totals'][$string][$this->item->name]) )
		{
			$this->bonus_tooltip['Totals'][$string][$this->item->name] = $this->bonus_tooltip['Totals'][$string][$this->item->name] . ',&nbsp;' . $modifier;
		}
		else
		{
			$this->bonus_tooltip['Totals'][$string][$this->item->name] = $this->_setNewBonusHTML($modifier);
		}

		if( isset($this->bonus_tooltip[$catagory][$string][$this->item->name]) )
		{
			$this->bonus_tooltip[$catagory][$string][$this->item->name] = $this->bonus_tooltip[$catagory][$string][$this->item->name] . ',&nbsp;' . $modifier;
		}
		else
		{
			$this->bonus_tooltip[$catagory][$string][$this->item->name] = $this->_setNewBonusHTML($modifier);
		}

	}

	/**
	 * Search and find $this->item->name in the global $tooltips.
	 * Make subtooltip with the existing javascript variable
	 *
	 * Returns html string
	 *
	 * @return string
	 */
	function _setNewBonusHTML( $modifier )
	{
		global $roster, $tooltips;

		foreach( $tooltips as $key => $value )
		{
			if( strpos($value, addslashes($this->item->name)) )
			{
				return 	'<a onmouseover="return overlib2(overlib_' . $key . ',WIDTH,325,HAUTO);" onmouseout="return nd2();">'
				  	   	. '<img width="24px" height="24px" src="' . $roster->config['interface_url'] . 'Interface/Icons/'
				  	   	. $this->item->icon . '.' . $roster->config['img_suffix'] . '"/><span style="color:#' . $this->item->color
				  		. ';font-size:12px;">&nbsp;&nbsp;' . $this->item->name . '</span></a>&nbsp;:&nbsp;' . $modifier;
			}
		}
	}

	function _setNewSetBonusHTML( )
	{
		global $roster, $tooltips;

		foreach( $tooltips as $key => $value )
		{
			if( strpos(substr($value, 0, 256), addslashes($this->item->name)) ) //search the first 256 characters only
			{
				return 	'<a onmouseover="return overlib2(overlib_' . $key . ',WIDTH,325,HAUTO);" onmouseout="return nd2();">'
				  	   	. '<img width="24px" height="24px" src="' . $roster->config['interface_url'] . 'Interface/Icons/'
				  	   	. $this->item->icon . '.' . $roster->config['img_suffix'] . '"/><span style="color:#' . $this->item->color
				  		. ';font-size:12px;">&nbsp;&nbsp;' . $this->item->name . '</span></a>';
			}
		}
	}

	function _formatTooltip( )
	{
		foreach( $this->bonus_tooltip as $catagory => $catagory_value )
		{
			if( !is_array($this->bonus_tooltip[$catagory]) )
			{
				continue;
			}
			foreach( $this->bonus_tooltip[$catagory] as $bonus => $bonus_value )
			{
				if( !is_array($this->bonus_tooltip[$catagory][$bonus]) )
				{
					continue;
				}
				foreach( $this->bonus_tooltip[$catagory][$bonus] as $val )
				{
					if( isset($this->bonus_tooltip[$catagory][$bonus]['html']) )
					{
						$this->bonus_tooltip[$catagory][$bonus]['html'] = $this->bonus_tooltip[$catagory][$bonus]['html'] . $val . '<br />';
					}
					else
					{
						$this->bonus_tooltip[$catagory][$bonus]['html'] = $val . '<br />';
					}
				}
			}
		}
	}

	/**
	 * Helper function that will add values paired together with a :
	 * will add single pair if a ':' is not found
	 * @param mixed $value1	| 10:10
	 * @param mixed $value2	| 20:5
	 * @return string	| 30:15
	 */
	function _add( $value1, $value2 )
	{
		if( strpos($value1, ':') !== false )
		{
			$value1 = explode(':', $value1);
			$value2 = explode(':', $value2);

			(string)$return = ($value1[0] + $value2[0]) . ':' . ($value1[1] + $value2[1]);
		}
		else
		{
			(string)$return = ($value1 + $value2);
		}
		return $return;
	}

	/**
	 * Runs $bonus through a set of localized patterns to standerize the bonus
	 * Recursing if resulting bonus is split into multiple bonus strings
	 *
	 * @param string $bonus
	 * @param int $modifier
	 * @param string $catagory
	 * @return string 
	 */
	function standardizeBonus( $bonus, $modifier, $catagory )
	{
		global $roster;
		//
		// use preg matches to replace variations on bonus text
		$bonus = preg_replace($roster->locale->wordings[$this->item_locale]['item_bonuses_preg_patterns'], $roster->locale->wordings[$this->item_locale]['item_bonuses_preg_replacements'], ucwords($bonus));

		if( strpos($bonus, ':') )
		{
			$return = explode(':', $bonus);
			//
			// loop through the array recursing into setBonus()
			// until 0 index then return out
			for( $idx=(count($return)-1);;$idx-- )
			{
				if( $idx != 0 )
				{
					$this->setBonus($modifier, $return[$idx], $catagory, true);
				}
				else
				{
					return $return[0];
				}
			}
		}
		return $bonus;
	}

	// by: Dmitry Fedotov box@neting.ru
	// edited by ds
	function replaceOne( $in, $out, $content )
	{
		$pos = strpos($content, $in);

		if( $pos !== false )
		{
			return substr($content, 0, $pos) . $out . substr($content, $pos+strlen($in));
		}
		else
		{
			return $content;
		}
	}
} // end class CharBonus


